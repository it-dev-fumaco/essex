<?php namespace App\Traits;

use DB;
use DateTime;
use DatePeriod;
use DateInterval;

trait AbsentNoticeTrait
{
    // public function getTotalAbsences($employee_id, $date_from, $date_to){
    //     $absent_notices = DB::table('notice_slip')
    //             ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
    //             ->where('user_id', $employee_id)
    //             ->where('status', 'APPROVED')
    //             ->select('leave_types.leave_type', 'notice_slip.*')
    //             ->get();

    //     $from_date_filter = new DateTime($date_from);
    //     $to_date_filter = new DateTime($date_to);
    //     $to_date_filter->modify('+1 day');

    //     $period = new DatePeriod($from_date_filter, new DateInterval( 'P1D' ), $to_date_filter);

    //     $dates_from_filter = [];

    //     foreach($period as $date ){
    //         $dates_from_filter[] = [
    //             'date' => $date->format( 'Y-m-d')
    //         ];
    //     }

    //     $dates_from_filter = array_column($dates_from_filter, 'date');

    //     $days = 0;
    //     foreach ($absent_notices as $row) {
    //         $absent_from = new DateTime($row->date_from);
    //         $absent_to = new DateTime($row->date_to);
    //         $absent_to->modify('+1 day');

    //         $absence_period = new DatePeriod($absent_from, new DateInterval( 'P1D' ), $absent_to);

    //         foreach ($absence_period as $absence_date) {
    //             if (in_array($absence_date->format( 'Y-m-d'), $dates_from_filter)){
    //                 if (stripos(strtolower($row->leave_type), 'half')) {
    //                     $days = $days + 0.5;
    //                 }else if (stripos(strtolower($row->leave_type), 'undertime')) {
    //                     $time_from = date('G:i', strtotime($row->time_from));
    //                     $time_to = date('G:i', strtotime($row->time_to));

    //                     $hrs = $time_to->diffInHours($time_from) / 8;

    //                     $days = $days + $hrs;
    //                 }else{
    //                     $days++;
    //                 }
    //             }
    //         }
    //     }

    //     return $days;
    // }

    public function getUnfiledAbsences($user_id, $date_from, $date_to){
        $format = 'Y-m-d';
        $start = new DateTime($date_from);
        $end = new DateTime($date_to);
        $end->modify('+1 day');

        $period = new DatePeriod( $start, new DateInterval( 'P1D' ), $end );

        $data = [];
        $days = 0;
        foreach($period as $date_period ){
            $biometric_count = DB::table('biometrics')
                    ->whereDate('bio_date', $date_period->format($format))
                    ->where('employee_id', $user_id)
                    ->count();

            $absent_count = $this->getTotalAbsences($user_id, $date_period->format($format), $date_period->format($format));

            if ($date_period->format($format) < date($format) && $date_period->format('N') < 7) {
                if ($biometric_count == 0 && $absent_count == 0) {
                	$days++;
                    $data[] = [
                        'date' => $date_period->format($format),
                        'biometrics' => $biometric_count,
                        'notices' => $absent_count,
                        'status' => 'Unfiled Absence'
                    ];
                }
            }
        }

        return ['total_days' => $days, 'list' => $data];
    }
}
