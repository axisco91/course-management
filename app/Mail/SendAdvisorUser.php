<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAdvisorUser extends Mailable
{
    use Queueable, SerializesModels;

    protected $username;
    protected $password;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Alta plataforma Zona Formación')
            ->view('emails.potential_training_contract')
            ->with([
                'username' => $this->username, // Rename to avoid conflicts
                'password' => $this->password
            ]);
    }
}
