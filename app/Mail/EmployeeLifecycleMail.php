<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class EmployeeLifecycleMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var array<string, mixed> */
    public array $mailData;

    public string $subjectLine;

    public string $template;

    /**
     * @param  array<string, mixed>  $mailData
     */
    public function __construct(string $subjectLine, string $template, array $mailData)
    {
        $this->subjectLine = $subjectLine;
        $this->template = $template;
        $this->mailData = $mailData;
    }

    public function build()
    {
        return $this
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject($this->subjectLine)
            ->view($this->template)
            ->with('data', $this->mailData);
    }
}

