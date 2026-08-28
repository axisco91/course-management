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
        private string $senderName,
        private ?string $attachmentPath = null,
        private ?string $attachmentName = null
    ) {
    }

    public function build()
    {
        $mail = $this->from((string) config('mail.from.address'), $this->senderName)
            ->subject($this->messageSubject)
            ->view('emails.template_message')
            ->with(['bodyHtml' => $this->messageBody]);

        if ($this->attachmentPath !== null) {
            $mail->attach($this->attachmentPath, [
                'as' => $this->attachmentName ?: basename($this->attachmentPath),
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
