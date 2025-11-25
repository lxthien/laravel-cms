<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Category extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'image',
        'meta_title',
        'meta_description',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer',
    ];

    // Sluggable configuration
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    // Relationships
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // Parent category (self-referencing)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Child categories (self-referencing)
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeParentOnly($query)
    {
        return $query->whereNull('parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Get breadcrumb array: [Parent > Child > Current]
     */
    public function getBreadcrumb()
    {
        $breadcrumb = [];
        $current = $this;

        while ($current) {
            // Prepend current category to the beginning of the array
            array_unshift($breadcrumb, $current);
            $current = $current->parent;
        }

        // Remove the current category itself from the list if you want 
        // the breadcrumb to be only "Parents", but typically you include it 
        // as the last active item. We will keep it here.
        
        return $breadcrumb;
    }

    /**
     * Lấy đường dẫn đầy đủ của category, ví dụ: 'cha/con/chau'
     */
    protected function fullPath(): Attribute
    {
        return Attribute::make(
            get: function () {
                $path = $this->slug;
                $parent = $this->parent;

                while ($parent) {
                    $path = $parent->slug . '/' . $path;
                    $parent = $parent->parent;
                }

                return $path;
            }
        );
    }
}
