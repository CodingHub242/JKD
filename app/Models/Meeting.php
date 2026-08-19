<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'topic',
        'scheduled_at',
        'duration_minutes',
        'jitsi_room',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];
}
