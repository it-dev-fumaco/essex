<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
class SendMail_gatepass extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

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
        $username = Auth::user()->employee_name;
        return $this->from(env('MAIL_FROM_ADDRESS'))->subject(''.$username.' filed gatepass request')->view('kiosk.Mail.template.gatepass_template')->with('data', $this->data);    }
}
