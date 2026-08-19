<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectUpdate extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'body',
        'progress',
        'image',
        'posted_at',
    ];

    protected $casts = [
        'progress' => 'integer',
        'posted_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
