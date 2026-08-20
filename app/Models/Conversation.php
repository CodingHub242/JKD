<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'last_activity_at',
        'agent_joined_at',
        'visitor_typing_at',
        'agent_typing_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'agent_joined_at' => 'datetime',
        'visitor_typing_at' => 'datetime',
        'agent_typing_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function lastMessage(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
