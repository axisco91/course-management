<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TemplateMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private string $messageSubject,
        private string $messageBody,
        private string $senderName
    ) {
    }

    public function build()
    {
        return $this->from((string) config('mail.from.address'), $this->senderName)
            ->subject($this->messageSubject)
            ->view('emails.template_message')
            ->with(['bodyHtml' => $this->messageBody]);
    }
}
