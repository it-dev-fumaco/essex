<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail_General;
use Carbon\Carbon;

class BirthdayEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Birthday Email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::where('status', 'Active');
        $users = $users->whereMonth('birth_date', Carbon::now()->format('m'));
        $users = $users->whereDay('birth_date', Carbon::now()->format('d'));
        $users = $users->where('user_type', 'Employee');
        $users = $users->select('employee_name', 'nick_name', 'email');
        $users = $users->get();

        foreach ($users as $user) {
            $name = $user->nick_name ? $user->nick_name : explode(' ', $user->employee_name)[0];
    
            $data = ['name' => $name];

            $mail = $this->send_mail('Happy Birthday '.$name.'!', 'admin.email_template.birthday', $user->email, $data);
        }
    }

    private function send_mail($subject, $template, $recipient, $data_arr){
        try {
            $data['mail_config'] = [
                'subject' => $subject,
                'template' => $template
            ];
    
            $data['data'] = $data_arr;
    
            Mail::to($recipient)->send(new SendMail_General($data));
            if(Mail::failures()){
                return ['success' => 0, 'message' => 'An error occured. Please try again.'];
            }

            return ['success' => 1, 'message' => 'email sent!'];
        } catch (\Exception $e) {
            // return $e->getMessage();
            return ['success' => 0, 'message' => $e->getMessage()];
        }
    }
}
