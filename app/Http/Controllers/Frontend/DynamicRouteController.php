<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;

class DynamicRouteController extends Controller
{
    public function handle($path)
    {
        // Bước 1: Ưu tiên tìm Post trước vì URL của Post là duy nhất và không có cấp cha.
        $post = Post::published()->where('slug', $path)->first();

        if ($post) {
            // Nếu tìm thấy Post, gọi PostController để xử lý
            return app(PostController::class)->show($post);
        }

        // Bước 2: Nếu không phải Post, xử lý như một đường dẫn Category đa cấp.
        $segments = explode('/', $path);
        $category = null;
        $parentId = null;

        foreach ($segments as $slug) {
            $category = Category::where('slug', $slug)
                ->where('parent_id', $parentId)
                ->where('status', 1)
                ->first();

            if (!$category) {
                // Nếu một segment trong path không hợp lệ, thoát và báo 404
                $category = null; // Reset category
                break;
            }

            $parentId = $category->id;
        }

        if ($category) {
            // Nếu tìm thấy Category ở cuối đường dẫn, gọi CategoryController
            return app(CategoryController::class)->show($category);
        }

        // Nếu không tìm thấy gì, trả về trang 404
        abort(404);
    }
}