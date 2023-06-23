<?php namespace App\Traits;

use Auth;
use DB;
use Carbon\Carbon;
use Exception;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail_General;
trait EmailsTrait{
    private function send_mail($subject, $template, $recipient, $data_arr, $log = []){
        try {
            $data['mail_config'] = [
                'subject' => $subject,
                'template' => $template
            ];
    
            $data['data'] = $data_arr;
            
            Mail::to($recipient)->send(new SendMail_General($data));
            $success = 1;
            if(Mail::failures()){
                $success = 0;
            }
            
            if($log){
                DB::table('email_notifications')->insert([
                    'type' => $log['type'],
                    'recipient' => $log['recipient'],
                    'subject' => $log['subject'],
                    'template' => $log['template'],
                    'template_data' => $log['template_data'],
                    'email_sent' => $success
                ]);
            }

            return ['success' => $success , 'message' => $success ? 'email sent!' : 'An error occured. Please try again.'];
        } catch (\Throwable $e) {
            // return $e->getMessage();
            throw $e;
            return ['success' => 0, 'message' => $e->getMessage()];
        }
    }
}