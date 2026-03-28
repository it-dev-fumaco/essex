<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class AbsentNoticeOwnerStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var array<string, mixed> */
    public array $data;

    /** @param array<string, mixed> $data */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $subject = $this->data['subject'] ?? 'Absent Notice Update';

        return $this
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject($subject)
            ->view('kiosk.Mail.template.notice_owner_status')
            ->with('data', $this->data);
    }
}

