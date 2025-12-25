<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user', 'subject')->latest();

        // Filter by log type
        if ($request->filled('log_type')) {
            $query->where('log_name', $request->log_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);
        $users = User::orderBy('name')->get();

        // Log types cho filter
        $logTypes = [
            'create' => 'Tạo mới',
            'update' => 'Cập nhật',
            'delete' => 'Xóa',
            'soft_delete' => 'Xóa (tạm thời)',
            'login' => 'Đăng nhập',
            'logout' => 'Đăng xuất',
            'setting_change' => 'Thay đổi cài đặt',
        ];

        return view('admin.activity-logs.index', compact('logs', 'users', 'logTypes'));
    }

    /**
     * Show single activity log detail
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user', 'subject');

        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Get activity logs for a specific model
     */
    public function modelHistory(string $modelType, int $modelId)
    {
        $logs = ActivityLog::where('subject_type', $modelType)
            ->where('subject_id', $modelId)
            ->with('user')
            ->latest()
            ->paginate(20);

        $modelName = class_basename($modelType);

        return view('admin.activity-logs.model-history', compact('logs', 'modelName', 'modelId', 'modelType'));
    }

    /**
     * Get user activity timeline
     */
    public function userTimeline(User $user)
    {
        $logs = ActivityLog::where('user_id', $user->id)
            ->with('subject')
            ->latest()
            ->paginate(30);

        return view('admin.activity-logs.user-timeline', compact('user', 'logs'));
    }

    /**
     * Export activity logs (CSV)
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('log_type')) {
            $query->where('log_name', $request->log_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        $filename = 'activity-logs-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ];

        $callback = function () use ($logs) {
            $out = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($out, "%s", chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            fputcsv($out, ['ID', 'User', 'Action', 'Description', 'Model', 'Date']);

            foreach ($logs as $log) {
                $userName = optional($log->user)->name ?? 'System';
                $modelName = $log->subject_type ? class_basename($log->subject_type) : '';
                $date = $log->created_at ? $log->created_at->toDateTimeString() : '';

                $row = [
                    $log->id,
                    $userName,
                    $log->log_name,
                    $log->description,
                    $modelName,
                    $date,
                ];

                fputcsv($out, $row);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Statistics dashboard
     */
    public function statistics()
    {
        $totalLogs = ActivityLog::count();

        $logsByType = ActivityLog::selectRaw('log_name, COUNT(*) as count')
            ->groupBy('log_name')
            ->get();

        $logsByUser = ActivityLog::selectRaw('user_id, COUNT(*) as count')
            ->with('user:id,name')
            ->groupBy('user_id')
            ->orderByRaw('count DESC')
            ->limit(10)
            ->get();

        $todayLogs = ActivityLog::whereDate('created_at', today())->count();

        $thisWeekLogs = ActivityLog::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        $thisMonthLogs = ActivityLog::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Daily activity trend for the last 30 days
        $dailyTrend = ActivityLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill in missing dates with zero counts
        $trendData = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trendData->push([
                'date' => now()->subDays($i)->format('d/m'),
                'count' => $dailyTrend->get($date)->count ?? 0,
            ]);
        }

        return view('admin.activity-logs.statistics', compact(
            'totalLogs',
            'logsByType',
            'logsByUser',
            'todayLogs',
            'thisWeekLogs',
            'thisMonthLogs',
            'trendData'
        ));
    }
}
