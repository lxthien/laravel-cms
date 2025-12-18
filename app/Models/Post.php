<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'canonical_url',
        'index',
        'follow',
        'gallery',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'index' => 'boolean',
        'follow' => 'boolean',
        'gallery' => 'array',
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
     * Get SEO title (fallback to title if empty)
     */
    public function getSeoTitleAttribute()
    {
        return $this->meta_title ?: $this->title;
    }

    /**
     * Get SEO description (fallback to excerpt)
     */
    public function getSeoDescriptionAttribute()
    {
        return $this->meta_description ?: $this->excerpt;
    }

    /**
     * Get robots meta
     */
    public function getRobotsMetaAttribute()
    {
        $index = $this->index ? 'index' : 'noindex';
        $follow = $this->follow ? 'follow' : 'nofollow';
        return "{$index}, {$follow}";
    }

    /**
     * Get full path URL của post (dùng primary category nếu có)
     * Output: slug-category-1/slug-category-2/post-slug
     * Hoặc chỉ: post-slug (nếu không có category)
     */
    protected function fullPath(): Attribute
    {
        return Attribute::make(
            get: function () {
                $primaryCategory = $this->primaryCategory();

                // Nếu không có primary category, chỉ trả về slug của post
                if (!$primaryCategory) {
                    return $this->slug;
                }

                // Lấy full path của primary category
                $categoryPath = $primaryCategory->full_path;

                // Kết hợp: category-path/post-slug
                return $categoryPath . '/' . $this->slug;
            }
        );
    }

    /**
     * Get breadcrumb cho post (dùng primary category)
     */
    public function getBreadcrumb()
    {
        $breadcrumb = [];
        $primaryCategory = $this->primaryCategory();

        if ($primaryCategory) {
            // Lấy tất cả parent categories
            $current = $primaryCategory;
            while ($current) {
                array_unshift($breadcrumb, $current);
                $current = $current->parent;
            }
        }

        return $breadcrumb;
    }
}