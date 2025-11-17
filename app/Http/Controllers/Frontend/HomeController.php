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
        $featuredPosts = Post::published()
            ->with(['category', 'user'])
            ->latest('published_at')
            ->take(3)
            ->get();
        
        $latestPosts = Post::published()
            ->with(['category', 'user'])
            ->latest('published_at')
            ->skip(3)
            ->take(10)
            ->get();
        
        return view('frontend.home', compact('featuredPosts', 'latestPosts'));
    }
}