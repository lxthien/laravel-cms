<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->published()
            ->with(['category', 'user', 'tags', 'comments.user'])
            ->firstOrFail();
        
        // Tăng view count
        $post->incrementViewCount();
        
        // Bài viết liên quan
        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->take(4)
            ->get();
        
        return view('frontend.posts.show', compact('post', 'relatedPosts'));
    }
}