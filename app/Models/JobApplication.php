<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'position',
        'trade',
        'experience',
        'cv_path',
        'status',
    ];
}
