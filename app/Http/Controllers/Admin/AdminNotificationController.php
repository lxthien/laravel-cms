<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        $notifications = AdminNotification::recent()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications for the dropdown.
     */
    public function unread()
    {
        $notifications = AdminNotification::unread()->recent()->take(5)->get();

        // Transform the collection to include formatted dates and links
        $notifications->transform(function ($notification) {
            $link = '#';
            if ($notification->type === 'contact') {
                $link = route('admin.contacts.index'); // Adjust route if needed
            } elseif ($notification->type === 'comment') {
                $link = route('admin.comments.index'); // Adjust route if needed
            } elseif ($notification->type === 'user') {
                $link = route('admin.users.index'); // Adjust route if needed
            }

            $notification->link = $link;
            $notification->time_ago = $notification->created_at->diffForHumans();
            return $notification;
        });

        return response()->json([
            'notifications' => $notifications,
            'count' => AdminNotification::unread()->count()
        ]);
    }

    /**
     * Get unread notification count.
     */
    public function getCount()
    {
        return response()->json([
            'count' => AdminNotification::unread()->count()
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = AdminNotification::findOrFail($id);

        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        AdminNotification::unread()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
