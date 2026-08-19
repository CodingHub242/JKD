<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'location',
        'client_name',
        'description',
        'cover_image',
        'gallery',
        'latitude',
        'longitude',
        'status',
        'featured',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'featured' => 'boolean',
        'sort_order' => 'integer',
        'active' => 'boolean',
    ];

    public function updates(): HasMany
    {
        return $this->hasMany(ProjectUpdate::class);
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'client_project')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function getLatestProgressAttribute(): int
    {
        return (int) $this->updates()->latest('posted_at')->value('progress') ?? 0;
    }
}
