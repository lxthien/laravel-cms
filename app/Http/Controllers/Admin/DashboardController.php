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
            'comments' => \App\Models\Comment::count(),
            'pending_comments' => \App\Models\Comment::pending()->count(),
        ];

        // Data cho biểu đồ: Số lượng bài viết trong 6 tháng gần nhất
        $monthlyPosts = Post::selectRaw('COUNT(*) as count, MONTH(created_at) as month, YEAR(created_at) as year')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Chuẩn bị dữ liệu cho Chart.js
        $chartData = [
            'labels' => [],
            'data' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $chartData['labels'][] = $date->format('M Y');

            $found = $monthlyPosts->first(function ($item) use ($month, $year) {
                return $item->month == $month && $item->year == $year;
            });

            $chartData['data'][] = $found ? $found->count : 0;
        }

        // Top 5 bài viết xem nhiều nhất
        $topPosts = Post::orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        $recentPosts = Post::with('categories', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'chartData', 'topPosts'));
    }
}