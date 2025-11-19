<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['user', 'post', 'parent'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.comments.index', compact('comments'));
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->status = 'approved';
        $comment->save();
        return back()->with('success', 'Đã duyệt bình luận!');
    }

    public function destroy($id)
    {
        Comment::findOrFail($id)->delete();
        return back()->with('success', 'Đã xoá bình luận!');
    }
}