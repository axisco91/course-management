<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GreetingMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $subjectCode;
    protected $formativeActionLabel;
    protected $tutorName;
    protected $totalHours;
    protected $courseStartDate;
    protected $courseEndDate;

    public function __construct(
        string $subjectCode,
        string $formativeActionLabel,
        string $tutorName,
        ?string $totalHours,
        ?string $courseStartDate,
        ?string $courseEndDate
    ) {
        $this->subjectCode = $subjectCode;
        $this->formativeActionLabel = $formativeActionLabel;
        $this->tutorName = $tutorName;
        $this->totalHours = $totalHours;
        $this->courseStartDate = $courseStartDate;
        $this->courseEndDate = $courseEndDate;
    }

    public function build()
    {
        return $this->from((string) config('mail.from.address'), $this->tutorName)
            ->subject('BIENVENIDA DOCENTE AF '.$this->subjectCode)
            ->view('emails.greeting_message')
            ->with([
                'subjectCode' => $this->subjectCode,
                'formativeActionLabel' => $this->formativeActionLabel,
                'tutorName' => $this->tutorName,
                'totalHours' => $this->totalHours,
                'courseStartDate' => $this->courseStartDate,
                'courseEndDate' => $this->courseEndDate,
            ]);
    }
}
