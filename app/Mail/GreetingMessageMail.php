<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GreetingMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $studentName;
    protected $courseName;
    protected $courseGroup;
    protected $courseStartDate;
    protected $courseEndDate;
    protected $platformName;
    protected $platformUrl;
    protected $platformUsername;
    protected $platformPassword;

    public function __construct(
        string $studentName,
        string $courseName,
        ?string $courseGroup,
        ?string $courseStartDate,
        ?string $courseEndDate,
        string $platformName,
        ?string $platformUrl,
        ?string $platformUsername,
        ?string $platformPassword
    ) {
        $this->studentName = $studentName;
        $this->courseName = $courseName;
        $this->courseGroup = $courseGroup;
        $this->courseStartDate = $courseStartDate;
        $this->courseEndDate = $courseEndDate;
        $this->platformName = $platformName;
        $this->platformUrl = $platformUrl;
        $this->platformUsername = $platformUsername;
        $this->platformPassword = $platformPassword;
    }

    public function build()
    {
        return $this->subject('Bienvenido/a al curso - '.$this->platformName)
            ->view('emails.greeting_message')
            ->with([
                'studentName' => $this->studentName,
                'courseName' => $this->courseName,
                'courseGroup' => $this->courseGroup,
                'courseStartDate' => $this->courseStartDate,
                'courseEndDate' => $this->courseEndDate,
                'platformName' => $this->platformName,
                'platformUrl' => $this->platformUrl,
                'platformUsername' => $this->platformUsername,
                'platformPassword' => $this->platformPassword,
            ]);
    }
}
