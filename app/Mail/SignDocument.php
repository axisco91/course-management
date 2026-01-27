<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignDocument extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $key;
    protected $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $key, $url)
    {
        $this->name = $name;
        $this->key = $key;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Firmar documento')
            ->view('emails.sign_document')
            ->with(['name' => $this->name, 'key' => $this->key, 'url' => $this->url]);
    }
}
