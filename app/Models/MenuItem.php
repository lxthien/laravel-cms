<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'target',
        'icon',
        'order',
        'css_class',
        'model_type',
        'model_id',
    ];

    protected $casts = [
        'order' => 'integer',
        'model_id' => 'integer',
    ];

    // Relationships
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Resolve the URL based on model or manual link
     */
    public function getUrl(): string
    {
        if ($this->model_type && $this->model_id) {
            $model = $this->model;
            if ($model) {
                // Assuming models have a 'slug' or 'full_path' or we use path helpers
                if ($this->model_type === 'App\Models\Page') {
                    return url($model->full_path);
                } elseif ($this->model_type === 'App\Models\Post') {
                    return url($model->full_path);
                } elseif ($this->model_type === 'App\Models\Category') {
                    return url($model->full_path);
                }
            }
        }

        return $this->url ?? '#';
    }

    /**
     * Check if the menu item is active
     */
    public function isActive(): bool
    {
        $currentUrl = request()->url();
        $targetUrl = $this->getUrl();

        return $currentUrl === $targetUrl;
    }
}
