<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_type',
        'mailable',
        'channel',
        'subject',
        'original_to',
        'final_to',
        'status',
        'error_message',
        'is_dry_run',
        'sent_at',
        'moodle_message_id',
        'idempotency_key',
        'tracing_id',
        'course_id',
        'student_id',
        'main_company_id',
    ];

    protected $casts = [
        'is_dry_run' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function tracing()
    {
        return $this->belongsTo(Tracing::class, 'tracing_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function mainCompany()
    {
        return $this->belongsTo(MainCompany::class, 'main_company_id');
    }
}
