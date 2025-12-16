<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'post', 'parent']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('name', 'like', "%{$search}%") // guest name
                    ->orWhereHas('post', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $comments = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.comments.index', compact('comments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,spam'
        ]);

        $comment = Comment::findOrFail($id);
        $comment->status = $request->status;
        $comment->save();

        return back()->with('success', 'Đã cập nhật trạng thái bình luận!');
    }

    public function approve($id)
    {
        return $this->updateStatus(new Request(['status' => 'approved']), $id);
    }

    public function destroy($id)
    {
        Comment::findOrFail($id)->delete();
        return back()->with('success', 'Đã xoá bình luận!');
    }
}