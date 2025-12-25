<?php

namespace App\Traits;

use App\Services\ActivityLogService;

trait LogsActivity
{
    /**
     * Khi model được tạo
     */
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $service = app(ActivityLogService::class);
            $modelName = class_basename($model);
            $service->logCreate($modelName, $model);
        });

        static::updated(function ($model) {
            // Get the changes that were just saved
            $changes = $model->getChanges();
            
            if (!empty($changes)) {
                $service = app(ActivityLogService::class);
                $modelName = class_basename($model);
                
                // Tạo danh sách thay đổi dễ đọc
                $changesList = [];
                foreach ($changes as $field => $newValue) {
                    // Get the original value from the model's original attributes
                    $oldValue = $model->getOriginal($field);
                    $changesList[$field] = [
                        'old' => $oldValue,
                        'new' => $newValue,
                    ];
                }

                $service->logUpdate($modelName, $model, $changesList);
            }
        });

        static::deleted(function ($model) {
            // Check if model uses soft deletes
            if (method_exists($model, 'isForceDeleting')) {
                if (!$model->isForceDeleting()) {
                    // Soft delete
                    $service = app(ActivityLogService::class);
                    $modelName = class_basename($model);
                    $service->log(
                        description: auth()->user()->name . " đã xóa (tạm thời) " . $modelName . ": " . ($model->title ?? $model->name ?? '#' . $model->id),
                        logName: 'soft_delete',
                        subject: null,
                        properties: [
                            'model_name' => $modelName,
                            'model_id' => $model->id,
                        ]
                    );
                }
            } else {
                // Hard delete (permanent delete)
                $service = app(ActivityLogService::class);
                $modelName = class_basename($model);
                $service->logDelete($modelName, $model);
            }
        });

        // Only register forceDeleted listener for models that support soft deletes
        if (method_exists(static::class, 'forceDeleted')) {
            static::forceDeleted(function ($model) {
                $service = app(ActivityLogService::class);
                $modelName = class_basename($model);
                $service->logDelete($modelName, $model);
            });
        }
    }
}
