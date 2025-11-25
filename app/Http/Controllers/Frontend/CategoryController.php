<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        // 1. Start with Home
        $breadcrumbs = [
            ['title' => 'Trang Chủ', 'url' => route('home')]
        ];

        // 2. Get category parents
        $parents = $category->getBreadcrumb();
        
        // 3. Loop through parents (including self) to build breadcrumb data
        foreach ($parents as $cat) {
            // If it's the last item (current page), URL can be empty or #
            $isLast = $cat->id === $category->id;
            
            $breadcrumbs[] = [
                'title' => $cat->name,
                'url'   => $isLast ? '' : url($cat->full_path)
            ];
        }
        
        $posts = $category->posts()
            ->published()
            ->with(['user', 'category'])
            ->latest('published_at')
            ->paginate(12);
        
        return view('frontend.categories.show', compact('category', 'posts', 'breadcrumbs'));
    }
}