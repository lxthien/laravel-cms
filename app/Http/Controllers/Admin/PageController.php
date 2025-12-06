<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of pages
     */
    public function index(Request $request)
    {
        $query = Page::with(['user', 'parent']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $pages = $query->ordered()->paginate(20);

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page
     */
    public function create()
    {
        $pages = Page::whereNull('parent_id')->ordered()->get();
        $templates = $this->getTemplates();
        
        return view('admin.pages.create', compact('pages', 'templates'));
    }

    /**
     * Store a newly created page
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|unique:pages,slug|max:255',
            'excerpt' => 'nullable',
            'content' => 'required',
            'featured_image' => 'nullable',
            'template' => 'required|in:default,full-width,sidebar-left,sidebar-right',
            'status' => 'required|in:draft,published',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable',
            'show_in_menu' => 'boolean',
            'is_homepage' => 'boolean',
            'parent_id' => 'nullable|exists:pages,id',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['user_id'] = Auth::id();
        $validated['show_in_menu'] = $request->has('show_in_menu');
        $validated['is_homepage'] = $request->has('is_homepage');

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Trang đã được tạo thành công!');
    }

    /**
     * Display the specified page
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified page
     */
    public function edit(Page $page)
    {
        $pages = Page::whereNull('parent_id')
            ->where('id', '!=', $page->id)
            ->ordered()
            ->get();
        $templates = $this->getTemplates();
        
        return view('admin.pages.edit', compact('page', 'pages', 'templates'));
    }

    /**
     * Update the specified page
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|unique:pages,slug,' . $page->id . '|max:255',
            'excerpt' => 'nullable',
            'content' => 'required',
            'featured_image' => 'nullable',
            'template' => 'required|in:default,full-width,sidebar-left,sidebar-right',
            'status' => 'required|in:draft,published',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable',
            'show_in_menu' => 'boolean',
            'is_homepage' => 'boolean',
            'parent_id' => 'nullable|exists:pages,id',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['show_in_menu'] = $request->has('show_in_menu');
        $validated['is_homepage'] = $request->has('is_homepage');

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Trang đã được cập nhật thành công!');
    }

    /**
     * Remove the specified page
     */
    public function destroy(Page $page)
    {
        // Kiểm tra xem có page con không
        if ($page->children()->count() > 0) {
            return back()->with('error', 'Không thể xóa trang có trang con!');
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Trang đã được xóa thành công!');
    }

    /**
     * Update order via AJAX
     */
    public function updateOrder(Request $request, Page $page)
    {
        $validated = $request->validate([
            'order' => 'required|integer|min:0'
        ]);

        $page->update(['order' => $validated['order']]);

        return response()->json([
            'success' => true,
            'message' => 'Thứ tự đã được cập nhật!'
        ]);
    }

    /**
     * Toggle status via AJAX
     */
    public function toggleStatus(Request $request, Page $page)
    {
        $newStatus = $page->status === 'published' ? 'draft' : 'published';
        $page->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => 'Trạng thái đã được cập nhật!'
        ]);
    }

    /**
     * Get available templates
     */
    private function getTemplates()
    {
        return [
            'default' => 'Mặc định',
            'full-width' => 'Full Width (Toàn màn hình)',
            'sidebar-left' => 'Sidebar trái',
            'sidebar-right' => 'Sidebar phải',
        ];
    }
}