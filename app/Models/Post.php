<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'view_count',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'view_count' => 'integer',
    ];

    // Sluggable configuration
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ==== Relationships ====
    
    /**
     * Many-to-Many với Categories
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_post')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    /**
     * Lấy category chính (primary)
     */
    public function primaryCategory()
    {
        return $this->categories()->wherePivot('is_primary', true)->first();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Approved comments only
    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('status', 'approved');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    // Helper methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at <= now();
    }

    /**
     * Lấy tất cả category cha để làm breadcrumb
     */
    public function getBreadcrumb()
    {
        $breadcrumb = [];
        $category = $this->category;

        while ($category) {
            array_unshift($breadcrumb, $category); // Thêm vào đầu mảng
            $category = $category->parent;
        }

        return $breadcrumb;
    }
}