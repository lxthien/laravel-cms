<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log activity
     *
     * @param string $description Mô tả hành động
     * @param string $logName Loại hành động (create, update, delete, login, etc.)
     * @param mixed $subject Model đối tượng bị ảnh hưởng
     * @param array $properties Thông tin bổ sung
     */
    public function log(
        string $description,
        string $logName = 'activity',
        $subject = null,
        array $properties = []
    ): ActivityLog {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception('User not authenticated');
        }

        return ActivityLog::create([
            'user_id' => $user->id,
            'log_name' => $logName,
            'description' => $description,
            'subject_id' => $subject?->id,
            'subject_type' => $subject ? get_class($subject) : null,
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log create action
     */
    public function logCreate(string $modelName, $model, array $properties = []): ActivityLog
    {
        return $this->log(
            description: Auth::user()->name . " đã tạo {$modelName}: " . ($model->title ?? $model->name ?? '#' . $model->id),
            logName: 'create',
            subject: $model,
            properties: array_merge($properties, [
                'model_name' => class_basename($model),
                'model_id' => $model->id,
            ])
        );
    }

    /**
     * Log update action
     */
    public function logUpdate(string $modelName, $model, array $changes = []): ActivityLog
    {
        return $this->log(
            description: Auth::user()->name . " đã cập nhật {$modelName}: " . ($model->title ?? $model->name ?? '#' . $model->id),
            logName: 'update',
            subject: $model,
            properties: array_merge([
                'model_name' => class_basename($model),
                'model_id' => $model->id,
            ], $changes ? ['changes' => $changes] : [])
        );
    }

    /**
     * Log delete action
     */
    public function logDelete(string $modelName, $model, array $properties = []): ActivityLog
    {
        return $this->log(
            description: Auth::user()->name . " đã xóa {$modelName}: " . ($model->title ?? $model->name ?? '#' . $model->id),
            logName: 'delete',
            subject: null,
            properties: array_merge($properties, [
                'model_name' => class_basename($model),
                'model_id' => $model->id,
                'deleted_data' => $model->toArray(),
            ])
        );
    }

    /**
     * Log login action
     */
    public function logLogin($user): ActivityLog
    {
        return $this->log(
            description: "{$user->name} đã đăng nhập vào hệ thống",
            logName: 'login',
            subject: $user,
            properties: ['user_email' => $user->email]
        );
    }

    /**
     * Log logout action
     */
    public function logLogout($user): ActivityLog
    {
        return $this->log(
            description: "{$user->name} đã đăng xuất khỏi hệ thống",
            logName: 'logout',
            subject: $user,
            properties: ['user_email' => $user->email]
        );
    }

    /**
     * Log settings change
     */
    public function logSettingChange(string $setting, $oldValue, $newValue): ActivityLog
    {
        return $this->log(
            description: Auth::user()->name . " đã thay đổi cài đặt: {$setting}",
            logName: 'setting_change',
            properties: [
                'setting_name' => $setting,
                'old_value' => $oldValue,
                'new_value' => $newValue,
            ]
        );
    }

    /**
     * Get recent activities
     */
    public function getRecent(int $limit = 20)
    {
        return ActivityLog::with('user', 'subject')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get activities by log name
     */
    public function getByLogName(string $logName, int $limit = 20)
    {
        return ActivityLog::where('log_name', $logName)
            ->with('user', 'subject')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get user activities
     */
    public function getUserActivities($userId, int $limit = 20)
    {
        return ActivityLog::where('user_id', $userId)
            ->with('user', 'subject')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get activities by model
     */
    public function getModelActivities(string $modelType, $modelId = null)
    {
        $query = ActivityLog::where('subject_type', $modelType)
            ->with('user', 'subject')
            ->latest();

        if ($modelId) {
            $query->where('subject_id', $modelId);
        }

        return $query->get();
    }

    /**
     * Clear old logs (optional)
     */
    public function clearOldLogs(int $daysOld = 90): int
    {
        return ActivityLog::where('created_at', '<', now()->subDays($daysOld))->delete();
    }
}
