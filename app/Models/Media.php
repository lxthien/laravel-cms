<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'mime_type',
        'alt_text',
        'caption',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    protected $appends = [
        'url',
        'thumbnail',
        'name',
        'human_readable_size',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeImages($query)
    {
        return $query->where('file_type', 'image');
    }

    public function scopeDocuments($query)
    {
        return $query->where('file_type', 'document');
    }

    public function scopeVideos($query)
    {
        return $query->where('file_type', 'video');
    }

    // Helper methods
    public function getFullUrl(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getUrl($conversion = ''): string
    {
        return $this->getFullUrl();
    }

    public function getUrlAttribute(): string
    {
        return $this->getFullUrl();
    }

    public function getThumbnailAttribute(): string
    {
        if ($this->file_type === 'image') {
            return $this->getFullUrl();
        }
        return ''; // Or a default icon URL
    }

    public function getNameAttribute(): string
    {
        return $this->file_name;
    }

    public function getHumanReadableSizeAttribute(): string
    {
        return $this->getFormattedSize();
    }

    public function getFormattedSize(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
