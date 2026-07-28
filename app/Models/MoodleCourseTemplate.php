<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodleCourseTemplate extends Model
{
    protected $fillable = [
        'training_action_id', 'web_platform_id', 'moodle_course_id', 'moodle_shortname', 'moodle_fullname',
    ];

    public function webPlatform()
    {
        return $this->belongsTo(WebPlatform::class);
    }
}
