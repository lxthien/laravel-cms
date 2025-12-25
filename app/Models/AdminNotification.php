<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'notifiable_type',
        'notifiable_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // Polymorphic relation to the entity causing the notification
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Helpers to create notifications
    public static function createForContact(ContactRequest $contact)
    {
        return self::create([
            'type' => 'contact',
            'title' => 'Yêu cầu liên hệ mới',
            'message' => 'Bạn có yêu cầu liên hệ mới từ ' . $contact->name,
            'notifiable_type' => ContactRequest::class,
            'notifiable_id' => $contact->id,
        ]);
    }

    public static function createForComment(Comment $comment)
    {
        return self::create([
            'type' => 'comment',
            'title' => 'Bình luận mới',
            'message' => 'Có bình luận mới cần duyệt từ ' . ($comment->user ? $comment->user->name : $comment->name),
            'notifiable_type' => Comment::class,
            'notifiable_id' => $comment->id,
        ]);
    }

    public static function createForUser(User $user)
    {
        return self::create([
            'type' => 'user',
            'title' => 'Người dùng mới',
            'message' => 'Người dùng mới đăng ký: ' . $user->name,
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
        ]);
    }
}
