<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'service_type',
        'budget',
        'message',
        'status',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
    ];
}
