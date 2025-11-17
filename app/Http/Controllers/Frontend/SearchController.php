<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return redirect()->route('home');
        }
        
        // Tìm kiếm cơ bản với LIKE
        $posts = Post::published()
            ->with(['category', 'user'])
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%");
            })
            ->latest('published_at')
            ->paginate(15);
        
        return view('frontend.search', compact('posts', 'query'));
    }
}