<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();
        
        $posts = $category->posts()
            ->published()
            ->with(['user', 'category'])
            ->latest('published_at')
            ->paginate(12);
        
        return view('frontend.categories.show', compact('category', 'posts'));
    }
}