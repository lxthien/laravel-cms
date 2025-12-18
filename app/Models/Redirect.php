<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Redirect extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'source_url',
        'destination_url',
        'match_type',
        'status_code',
        'is_active',
        'hit_count',
        'last_hit_at',
        'order',
        'note',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_hit_at' => 'datetime',
        'hit_count' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Scope a query to only include active redirects.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
