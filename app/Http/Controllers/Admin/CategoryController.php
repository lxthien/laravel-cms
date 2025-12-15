<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Rules\UniqueSlugAcrossTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Kiểm tra permission
        if (!auth()->user()->can('category-list')) {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        // Lấy tất cả categories với parent relationship
        $categories = Category::with('childrenRecursive')->whereNull('parent_id')->orderBy('order')->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('category-create');

        // Lấy tất cả categories để làm parent
        $parentCategories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $this->authorize('category-create');

        // Auto-generate slug if empty
        if (!$request->filled('slug') && $request->filled('name')) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }

        // Validation
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => ['required', new UniqueSlugAcrossTables('categories')],
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'order' => 'nullable|integer',
            'status' => 'boolean',
        ]);

        // Upload image nếu có
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }

        // Tạo category
        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Danh mục đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $this->authorize('category-list');

        // Load posts trong category
        $category->load([
            'posts' => function ($query) {
                $query->latest()->take(10);
            }
        ]);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $this->authorize('category-edit');

        // Lấy parent categories (không bao gồm chính nó và con của nó)
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('category-edit');

        // Auto-generate slug if empty
        if (!$request->filled('slug') && $request->filled('name')) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }

        // Validation
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => ['required', new UniqueSlugAcrossTables('categories', $category->id)],
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'order' => 'nullable|integer',
            'status' => 'boolean',
        ]);

        // Upload image mới nếu có
        if ($request->hasFile('image')) {
            // Xóa image cũ
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
            }

            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }

        // Update category
        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize('category-delete');

        // Kiểm tra xem có posts trong category không
        if ($category->posts()->count() > 0) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Không thể xóa danh mục có bài viết!');
        }

        // Xóa image nếu có
        if ($category->image) {
            \Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Danh mục đã được xóa thành công!');
    }

    /**
     * Update category order via AJAX
     */
    public function updateOrder(Request $request, Category $category)
    {
        $validated = $request->validate([
            'order' => 'required|integer|min:0'
        ]);

        try {
            $category->order = $validated['order'];
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thứ tự thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update category status via AJAX
     */
    public function updateStatus(Request $request, Category $category)
    {
        $validated = $request->validate([
            'status' => 'required|boolean'
        ]);

        try {
            $category->status = $validated['status'];
            $category->save();

            return response()->json([
                'success' => true,
                'message' => $validated['status'] ? 'Đã kích hoạt danh mục!' : 'Đã ẩn danh mục!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
