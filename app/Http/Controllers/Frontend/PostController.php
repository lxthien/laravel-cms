<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function show(Post $post)
    {
        // Eager load relationships
        $post->load(['user', 'categories', 'tags', 'comments.user']);

        // Increment view count
        $post->increment('view_count');

        // Get related posts từ primary category
        $primaryCategory = $post->primaryCategory();

        $relatedPosts = collect();

        if ($primaryCategory) {
            $relatedPosts = Post::published()
                ->where('id', '!=', $post->id)
                ->whereHas('categories', function ($query) use ($primaryCategory) {
                    $query->where('categories.id', $primaryCategory->id);
                })
                ->with(['categories'])
                ->take(4)
                ->get();
        }

        // Only comments with status = 'approved' and parent_id = null (root comments)
        $comments = $post->comments()
            ->whereNull('parent_id')
            ->approved()
            ->with([
                'replies' => function ($q) {
                    $q->approved();
                },
                'user'
            ])
            ->orderBy('created_at')
            ->get();

        // Breadcrumb
        $breadcrumbs = [
            ['title' => 'Trang Chủ', 'url' => route('home')]
        ];

        foreach ($post->getBreadcrumb() as $cat) {
            $breadcrumbs[] = [
                'title' => $cat->name,
                'url' => url($cat->full_path)
            ];
        }

        $breadcrumbs[] = [
            'title' => $post->title,
            'url' => ''
        ];

        return view('frontend.posts.show', compact('post', 'relatedPosts', 'comments', 'breadcrumbs'));
    }
}