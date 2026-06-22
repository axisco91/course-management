<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseEndReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $studentName;
    protected $courseName;
    protected $courseEndDate;

    public function __construct(string $studentName, string $courseName, string $courseEndDate)
    {
        $this->studentName = $studentName;
        $this->courseName = $courseName;
        $this->courseEndDate = $courseEndDate;
    }

    public function build()
    {
        return $this->subject('Recordatorio: tu curso finaliza en una semana')
            ->view('emails.course_end_reminder')
            ->with([
                'studentName' => $this->studentName,
                'courseName' => $this->courseName,
                'courseEndDate' => $this->courseEndDate,
            ]);
    }
}
