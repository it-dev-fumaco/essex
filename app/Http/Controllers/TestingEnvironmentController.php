<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail_notice;
use Carbon\Carbon;

class TestingEnvironmentController extends Controller
{
    public function local_login(){
        Auth::loginUsingId(306);

        return redirect('/');
    }

    public function test_mail(){
        try {
            $data = array(
                'employee_name'     => 'test',
                'year'              => 'test',
                'slip_id'           => 'test',
                'reported_to'       => 'test',
                'means'             => 'test',
                'reason'            => 'test',
                'token'             => 'test',
                'leave_type'        => 'test',
                'from'              => 'test',
                'to'                => 'test',
                'department'        => 'test',
                'approver'        => 'test'
            );
            $email = 'jave.kulong@fumaco.com';
            Mail::to($email)->send(new SendMail_notice($data));

            return 1;

            // DB::table('email_notifications')->insert([
            //     'type' => 'Absent Notice Slip',
            //     'recipient' => $email,
            //     'subject' => 'Absent Notice Slip - FOR YOUR APPROVAL',
            //     'template' => 'kiosk.Mail.template.notice_template',
            //     'template_data' => json_encode($data),
            //     'email_sent' => Mail::failures() ? 0 : 1
            // ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function test_db(){
        $existing_bio = DB::table('biometrics')->where('employee_id', (int)Auth::user()->user_id)->where('type', '!=', 'adjustment')->select('biometric_id')->get();

        $bio_ids = null;
        if (!empty($existing_bio)) {
            $bio_ids = '0000';

            foreach ($existing_bio as $bio_id) {
                $bio_ids .= $bio_id->biometric_id.',';
            }

            $bio_ids = rtrim($bio_ids,',');
            $bio_ids = "AND Transactions.[ID] NOT IN (".$bio_ids.")"; 
        }

        $startDate = Carbon::parse('May 12, 2024')->startOfDay();
        $endDate = Carbon::parse('May 30, 2024')->endOfDay();
        $user_id = Auth::user()->user_id;
        $attendance = DB::connection('access')->table('Transactions')
            ->where('pin', $user_id)->whereIn('TransType', [7, 8])
            ->whereRaw("date >= #$startDate# AND date <= #$endDate#")
            ->orderByDesc('date')->get();

        $addresses = collect($attendance)->pluck('Address');

        $USQ = DB::connection('access')->table('UnitSiteQuery')->whereIn('Address', $addresses)->get(['Address', 'UnitName'])->groupBy('Address');
        $template = DB::connection('access')->table('templates')->where('pin', (int)$user_id)->get(['pin', 'FirstName', 'LastName'])->groupBy('pin');

        $data = [];
        foreach ($attendance as $row) {
            $address_unit_name = isset($USQ[$row->Address]) ? collect($USQ[$row->Address])->pluck('UnitName')->first() : null;
            $data[] = [
                'biometric_id' => $row->ID,
                'bio_date' => $row->date,
                'bio_time' => $row->time,
                'serial_no' => $row->SerialNo,
                'trans_type' => $row->TransType,
                'employee_id' => $row->pin,
                'received_date' => $row->ReceivedDate,
                'received_time' => $row->ReceivedTime,
                'unit_name' => $address_unit_name,
                'type' => 'raw data',
            ];
        }

        return $data;
    }
}
