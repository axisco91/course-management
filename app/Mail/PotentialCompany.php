<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PotentialCompany extends Mailable
{
    use Queueable, SerializesModels;

    protected $url;
    protected $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $name)
    {
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

        return $this->subject('Registro nuevo empresa en '.$this->name)
            ->view('emails.potential_company')
            ->with([
                'url' => $this->url,
                'name' => $this->name,
            ]);
    }
}
