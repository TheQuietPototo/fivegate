<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdeaCheck extends Model
{
    protected $fillable = [
        'name',
        'email',
        'role',
        'path',
        'idea',
        'answers',
        'result_status',
        'stopped_gate',
    ];

    protected $casts = [
        'answers' => 'array',
        'stopped_gate' => 'integer',
    ];
}
