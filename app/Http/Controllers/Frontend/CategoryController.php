<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        // Load posts với pagination
        $posts = $category->posts()
            ->published()
            ->with(['user', 'categories'])
            ->latest('published_at')
            ->paginate(12);
        
        $breadcrumbs = [
            ['title' => 'Trang Chủ', 'url' => route('home')]
        ];
        
        foreach ($category->getBreadcrumb() as $cat) {
            $breadcrumbs[] = [
                'title' => $cat->name,
                'url'   => url($cat->full_path)
            ];
        }

        return view('frontend.categories.show', compact('category', 'posts', 'breadcrumbs'));
    }
}