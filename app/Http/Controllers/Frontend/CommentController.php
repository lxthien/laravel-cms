<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function show()
    {
        $comments = $post->comments()->whereNull('parent_id')->approved()->with('replies.user')->orderBy('created_at')->get();
        
        // Truyền xuống view
        return view('frontend.posts.show', compact('post', 'comments'));
    }

    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
            'name' => auth()->guest() ? 'required|max:100' : 'nullable',
            'email' => auth()->guest() ? 'required|email|max:100' : 'nullable',
        ]);

        $comment = new \App\Models\Comment();
        $comment->post_id = $post->id;
        $comment->content = $data['content'];
        $comment->parent_id = $data['parent_id'] ?? null;
        $comment->status = 'pending'; // hoặc auto approve nếu là admin
        
        if (auth()->check()) {
            $comment->user_id = auth()->id();
            $comment->name = auth()->user()->name;
            $comment->email = auth()->user()->email;
        } else {
            $comment->name = $data['name'];
            $comment->email = $data['email'];
        }
        $comment->save();
        
        return back()->with('success', 'Bình luận của bạn đã gửi. Vui lòng chờ admin duyệt.');
    }
}