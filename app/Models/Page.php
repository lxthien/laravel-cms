<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'template',
        'status',
        'order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'show_in_menu',
        'is_homepage',
        'parent_id',
        'user_id',
        'gallery',
    ];

    protected $casts = [
        'show_in_menu' => 'boolean',
        'is_homepage' => 'boolean',
        'order' => 'integer',
        'gallery' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id')->orderBy('order');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('title');
    }

    // Accessor
    public function getFullPathAttribute()
    {
        if ($this->parent_id) {
            return $this->parent->full_path . '/' . $this->slug;
        }
        return $this->slug;
    }

    // Auto generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        // Nếu đặt làm homepage, bỏ homepage cũ
        static::saving(function ($page) {
            if ($page->is_homepage) {
                static::where('id', '!=', $page->id)->update(['is_homepage' => false]);
            }
        });
    }
}