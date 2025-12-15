<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Models\Category;
use App\Http\Controllers\Frontend\PageController;

class DynamicRouteController extends Controller
{
    public function handle($path)
    {
        $segments = explode('/', $path);
        $segmentCount = count($segments);

        // Case 1: Chỉ có 1 segment
        // Có thể là: post, page, hoặc category cấp 1
        if ($segmentCount === 1) {
            $slug = $segments[0];

            // 1. Ưu tiên tìm Post trước (Single Post)
            $post = Post::published()
                ->where('slug', $slug)
                ->with(['categories', 'user'])
                ->first();

            if ($post) {
                return app(PostController::class)->show($post);
            }

            // 2. Tìm Page (Static Page)
            $page = Page::published()
                ->where('slug', $slug)
                ->first();

            if ($page) {
                // Sử dụng PageController vừa tạo để hiển thị
                return app(PageController::class)->show($slug);
            }

            // 3. Tìm Category cấp 1
            $category = Category::where('slug', $slug)
                ->whereNull('parent_id')
                ->where('status', 1)
                ->first();

            if ($category) {
                return app(CategoryController::class)->show($category);
            }
        }

        // Case 2: Có nhiều segments (2 trở lên)
        // Có thể là: category đa cấp HOẶC category/post
        else {
            // Thử tìm post ở segment cuối cùng
            $postSlug = end($segments);
            $post = Post::published()
                ->where('slug', $postSlug)
                ->with(['categories', 'user'])
                ->first();

            if ($post) {
                // Verify đường dẫn category có khớp với primary category của post không
                $primaryCategory = $post->primaryCategory();

                if ($primaryCategory) {
                    $expectedPath = $primaryCategory->full_path . '/' . $post->slug;

                    // Nếu path khớp, hiển thị post
                    if ($path === $expectedPath) {
                        return app(PostController::class)->show($post);
                    }
                }
            }

            // Nếu không phải post, xử lý như category đa cấp
            $category = $this->findCategoryByPath($segments);

            if ($category) {
                return app(CategoryController::class)->show($category);
            }
        }

        // Không tìm thấy gì, trả về 404
        abort(404);
    }

    /**
     * Tìm category theo đường dẫn phân cấp
     */
    private function findCategoryByPath(array $segments)
    {
        $parentId = null;
        $category = null;

        foreach ($segments as $slug) {
            $category = Category::where('slug', $slug)
                ->where('parent_id', $parentId)
                ->where('status', 1)
                ->first();

            if (!$category) {
                return null;
            }

            $parentId = $category->id;
        }

        return $category;
    }
}