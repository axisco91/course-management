<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TracingMilestoneMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected string $tutorName,
        protected string $mailType,
        protected string $subjectCode,
        protected string $formativeActionLabel,
        protected string $milestoneLabel,
        protected ?string $milestoneDate,
        protected ?string $courseEndDate,
        protected ?string $finalResult = null
    ) {
    }

    public function build()
    {
        $subject = match ($this->mailType) {
            'quarter' => 'SEGUIMIENTO 25% AF '.$this->subjectCode,
            'half' => 'SEGUIMIENTO 50% AF '.$this->subjectCode,
            'three_quarters' => 'SEGUIMIENTO 75% AF '.$this->subjectCode,
            'final' => 'FIN AF '.$this->subjectCode,
            default => 'Seguimiento de curso - ' . $this->milestoneLabel,
        };

        return $this->from((string) config('mail.from.address'), $this->tutorName)
            ->subject($subject)
            ->view('emails.tracing_milestone')
            ->with([
                'tutorName' => $this->tutorName,
                'mailType' => $this->mailType,
                'subjectCode' => $this->subjectCode,
                'formativeActionLabel' => $this->formativeActionLabel,
                'milestoneLabel' => $this->milestoneLabel,
                'milestoneDate' => $this->milestoneDate,
                'courseEndDate' => $this->courseEndDate,
                'finalResult' => $this->finalResult,
            ]);
    }
}
