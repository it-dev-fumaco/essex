<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail_General extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_config = $this->data['mail_config'];
        $template_data = $this->data['data'];
        return $this->from(env('MAIL_FROM_ADDRESS'))->subject($mail_config['subject'])->view($mail_config['template'])->with('data', $template_data);
    }
}
