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
    protected $url;
    protected $name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username, $password, $url, $name)
    {
        $this->username = $username;
        $this->password = $password;
        $this->url = $url;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Alta plataforma '.$this->name)
            ->view('emails.advisor_user')
            ->with([
                'username' => $this->username, // Rename to avoid conflicts
                'password' => $this->password,
                'url' => $this->url,
                'name' => $this->name,
            ]);
    }
}
