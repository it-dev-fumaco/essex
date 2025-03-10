<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail_General;
use Carbon\Carbon;
use DB;
use App\Traits\EmailsTrait;

class WorksaryEmailCommand extends Command
{
    use EmailsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:worksary {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Work Anniversary Email';

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
        $users = $users->whereMonth('date_joined', Carbon::now()->format('m'));
        $users = $users->whereDay('date_joined', Carbon::now()->format('d'));
        $users = $users->whereYear('date_joined', '<', Carbon::now()->format('Y'));
        $users = $users->when($this->option('id'), function ($q){
            $q->where('id', $this->option('id'));
        });
        $users = $users->where('user_type', 'Employee');
        $users = $users->select('id', 'employee_name', 'nick_name', 'email', 'date_joined');
        $users = $users->get();

        $sent_notifications = DB::table('email_notifications')->where('type', 'Work Anniversary Email')->whereDate('created_at', Carbon::now()->startOfDay())->where('email_sent', 1)
            ->when($this->option('id'), function ($q){
                $q->where('user_id', $this->option('id'));
            })
            ->get();

        foreach ($users as $user) {
            $name = $user->nick_name ? $user->nick_name : explode(' ', $user->employee_name)[0];
            $no_of_years = Carbon::now()->diffInYears(Carbon::parse($user->date_joined));
            $subject = 'Happy Work Anniversary '.$name.'!';

            $data = [
                'name' => $name,
                'no_of_years' => $no_of_years
            ];
    
            $log = [
                'type' => 'Work Anniversary Email',
                'recipient' => $user->email,
                'subject' => $subject,
                'template' => 'admin.email_template.work_anniv',
                'template_data' => json_encode($data)
            ];
            $mail = $this->send_mail($subject, 'admin.email_template.work_anniv', $user->email, $data, $log);
        }
    }
}
