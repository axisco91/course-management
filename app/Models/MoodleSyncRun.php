<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodleSyncRun extends Model
{
    protected $fillable = [
        'course_id', 'operation', 'status', 'stage', 'attempt', 'error_message', 'started_at', 'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
