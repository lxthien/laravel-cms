<?php

namespace App\Observers;

use App\Models\ActionLog;
use App\Models\AdminNotification;
use App\Models\Comment;
use App\Models\ContactRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AdminNotificationObserver
{
    /**
     * Handle the "created" event.
     */
    public function created(Model $model): void
    {
        if ($model instanceof ContactRequest) {
            AdminNotification::createForContact($model);
        } elseif ($model instanceof Comment) {
            if ($model->status === 'pending') {
                AdminNotification::createForComment($model);
            }
        } elseif ($model instanceof User) {
            AdminNotification::createForUser($model);
        }
    }
}
