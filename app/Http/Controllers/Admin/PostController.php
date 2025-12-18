<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Rules\UniqueSlugAcrossTables;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('post-list')) {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        // Admin và Editor xem tất cả posts
        // Author chỉ xem posts của mình
        $query = Post::with(['user', 'categories', 'tags']);

        if (auth()->user()->hasRole('author')) {
            $query->where('user_id', auth()->id());
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Eager load categories (nhiều) thay vì category (một)
        $posts = $query->latest()->paginate(20);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('post-create');

        $categories = Category::where('status', 1)->orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('post-create');

        // Auto-generate slug if empty
        if (!$request->filled('slug') && $request->filled('title')) {
            $request->merge(['slug' => Str::slug($request->title)]);
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => ['required', new UniqueSlugAcrossTables('posts')],
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'primary_category' => [
                'required',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($request) {
                    if (!in_array($value, $request->categories ?? [])) {
                        $fail('Danh mục chính phải nằm trong danh sách danh mục đã chọn.');
                    }
                },
            ],
            'excerpt' => 'nullable',
            'content' => 'required',
            'featured_image' => 'nullable',
            'status' => 'required|in:draft,published,pending',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable',
            'tags' => 'nullable|array',
            'gallery' => 'nullable|array',
        ]);

        // Upload featured image
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('posts', 'public');
        } elseif ($request->filled('featured_image') && is_string($request->featured_image)) {
            // Handle media manager selection (strip storage/ prefix if present)
            $path = $request->featured_image;
            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, 8);
            }
            $validated['featured_image'] = $path;
        }

        // Set user_id
        $validated['user_id'] = auth()->id();

        // Set published_at nếu status là published
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Create post (không cần category_id nữa)
        $postData = collect($validated)->except(['categories', 'primary_category', 'tags'])->toArray();
        $post = Post::create($postData);

        // Attach categories với is_primary flag
        $categoryData = [];
        foreach ($validated['categories'] as $categoryId) {
            $categoryData[$categoryId] = [
                'is_primary' => ($categoryId == $validated['primary_category'])
            ];
        }
        $post->categories()->attach($categoryData);

        // Xử lý Tags (quan trọng)
        if ($request->has('tags')) {
            $tagIds = $this->syncTags($request->tags);
            $post->tags()->attach($tagIds);
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Bài viết đã được tạo thành công!');
    }

    /**
     * Sync tags - tạo mới nếu chưa có, trả về array of IDs
     */
    private function syncTags($tags)
    {
        $tagIds = [];

        // Giới hạn số lượng tags
        if (count($tags) > 10) {
            throw new \Exception('Tối đa 10 tags cho một bài viết!');
        }

        foreach ($tags as $tag) {
            // Kiểm tra xem là tag mới hay tag có sẵn
            if (strpos($tag, 'new:') === 0) {
                // Tag mới - tạo mới
                $tagName = substr($tag, 4); // Bỏ prefix "new:"
                $tagName = trim($tagName);

                // Validate độ dài
                if (strlen($tagName) < 2 || strlen($tagName) > 50) {
                    continue; // Skip tag không hợp lệ
                }

                // Validate ký tự
                /* if (!preg_match('/^[a-zA-Z0-9\s\-_]+$/', $tagName)) {
                    continue;
                } */

                // Kiểm tra xem tag đã tồn tại chưa (case-insensitive)
                $existingTag = Tag::whereRaw('LOWER(name) = ?', [strtolower($tagName)])->first();

                if ($existingTag) {
                    $tagIds[] = $existingTag->id;
                } else {
                    // Tạo tag mới
                    $newTag = Tag::create([
                        'name' => $tagName,
                        'slug' => Str::slug($tagName)
                    ]);
                    $tagIds[] = $newTag->id;
                }
            } else {
                // Tag có sẵn - chỉ cần thêm ID
                $tagIds[] = $tag;
            }
        }

        return $tagIds;
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $this->authorize('post-list');

        $post->load(['user', 'category', 'tags', 'comments']);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Kiểm tra quyền
        if (auth()->user()->hasRole('author') && $post->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền sửa bài viết này.');
        }

        $this->authorize('post-edit');

        $categories = Category::where('status', 1)->orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Kiểm tra quyền
        if (auth()->user()->hasRole('author') && $post->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền sửa bài viết này.');
        }

        $this->authorize('post-edit');

        // Auto-generate slug if empty
        if (!$request->filled('slug') && $request->filled('title')) {
            $request->merge(['slug' => Str::slug($request->title)]);
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => ['required', new UniqueSlugAcrossTables('posts', $post->id)],
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'primary_category' => [
                'required',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($request) {
                    if (!in_array($value, $request->categories ?? [])) {
                        $fail('Danh mục chính phải nằm trong danh sách danh mục đã chọn.');
                    }
                },
            ],
            'excerpt' => 'nullable',
            'content' => 'required',
            'featured_image' => 'nullable',
            'status' => 'required|in:draft,published,pending',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable',
            'tags' => 'nullable|array',
            'gallery' => 'nullable|array',
        ]);

        // Upload new featured image
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            $validated['featured_image'] = $request->file('featured_image')
                ->store('posts', 'public');
        } elseif ($request->filled('featured_image') && is_string($request->featured_image)) {
            // Handle media manager selection
            $path = $request->featured_image;
            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, 8);
            }
            $validated['featured_image'] = $path;
        }

        // Update published_at
        if ($validated['status'] === 'published' && empty($post->published_at)) {
            $validated['published_at'] = now();
        }

        // Update post
        $postData = collect($validated)->except(['categories', 'primary_category', 'tags'])->toArray();
        $post->update($postData);

        // Sync categories với is_primary flag
        $categoryData = [];
        foreach ($validated['categories'] as $categoryId) {
            $categoryData[$categoryId] = [
                'is_primary' => ($categoryId == $validated['primary_category'])
            ];
        }
        $post->categories()->sync($categoryData);

        // Sync tags (sử dụng sync thay vì attach)
        if ($request->has('tags')) {
            $tagIds = $this->syncTags($request->tags);
            $post->tags()->sync($tagIds);  // sync thay vì attach
        } else {
            $post->tags()->detach();  // Xóa tất cả tags nếu không chọn
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Bài viết đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Kiểm tra quyền
        if (auth()->user()->hasRole('author') && $post->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xóa bài viết này.');
        }

        $this->authorize('post-delete');

        // Delete featured image
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Bài viết đã được xóa thành công!');
    }

    /**
     * Hiển thị danh sách posts theo category
     */
    public function byCategory(Category $category, Request $request)
    {
        // Query posts thuộc category này
        $query = Post::select('posts.*')
            ->whereHas('categories', function ($q) use ($category) {
                $q->where('categories.id', $category->id);
            })
            ->with(['user', 'categories']);

        // Filter by status nếu có
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search nếu có
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->latest('posts.created_at')->paginate(20);

        // Breadcrumb để biết đang ở category nào
        $breadcrumbs = $this->getCategoryBreadcrumbs($category);

        return view('admin.posts.by-category', compact('category', 'posts', 'breadcrumbs'));
    }

    /**
     * Lấy breadcrumb cho category
     */
    private function getCategoryBreadcrumbs(Category $category)
    {
        $breadcrumbs = [];
        $current = $category;

        while ($current) {
            array_unshift($breadcrumbs, $current);
            $current = $current->parent;
        }

        return $breadcrumbs;
    }
}
