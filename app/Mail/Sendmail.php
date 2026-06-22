<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Sendmail extends Mailable
{
    use Queueable, SerializesModels;

    public array $mailData;

    /**
     * Create a new message instance.
     */
    public function __construct(array $mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->mailData['title'] ?? 'New Company')
            ->view('emails.sendmail')
            ->with([
                'mailData' => $this->mailData,
            ]);
    }
}
