<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Page;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = [
            'posts' => Post::count(),
            'pages' => Page::count(),
            'categories' => Category::count(),
            'users' => User::count(),
        ];

        $recentPosts = Post::with('categories', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPosts'));
    }
}