<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseEndReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $tutorName;
    protected $subjectCode;

    public function __construct(string $tutorName, string $subjectCode)
    {
        $this->tutorName = $tutorName;
        $this->subjectCode = $subjectCode;
    }

    public function build()
    {
        return $this->from((string) config('mail.from.address'), $this->tutorName)
            ->subject('ULTIMO DIA CURSO '.$this->subjectCode)
            ->view('emails.course_end_reminder')
            ->with([
                'tutorName' => $this->tutorName,
                'subjectCode' => $this->subjectCode,
            ]);
    }
}
