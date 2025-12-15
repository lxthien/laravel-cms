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
            ->with(['categories', 'user'])
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('content', 'LIKE', "%{$query}%")
                    ->orWhere('excerpt', 'LIKE', "%{$query}%");
            })
            ->latest('published_at')
            ->paginate(15);

        return view('frontend.search', compact('posts', 'query'));
    }

    /**
     * API endpoint for search suggestions (autocomplete)
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = Post::published()
            ->where('title', 'LIKE', "%{$query}%")
            ->select('id', 'title', 'slug', 'featured_image')
            ->limit(8)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'url' => url($post->full_path),
                    'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
                ];
            });

        return response()->json($suggestions);
    }
}