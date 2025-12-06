<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy các bài viết mới nhất
        $featuredPosts = Post::select('posts.*')
            ->published() // Hoặc where('posts.status', 'published')...
            ->with(['categories'])
            ->latest('posts.published_at')
            ->paginate(12);
        
        $latestPosts = Post::published()
            ->latest('published_at')
            ->skip(3)
            ->take(10)
            ->get();
        
        return view('frontend.home', compact('featuredPosts', 'latestPosts'));
    }
}