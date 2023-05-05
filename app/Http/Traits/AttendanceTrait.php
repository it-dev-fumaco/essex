<?php namespace App\Http\Traits;

use DB;
use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;

trait AttendanceTrait
{
    public function getWorkingDays($begin, $end){
        $start = new DateTime($begin);
        $end = new DateTime($end);
        $end->modify('+1 day');

        $holidays = DB::table('holidays')->select('holiday_date')->get();

        $period = new DatePeriod( $start, new DateInterval( 'P1D' ), $end );
        $days = 0;
        foreach($period as $day ){
            $dayOfWeek = $day->format( 'N' );
            if( $dayOfWeek < 7 ){
                $format = $day->format( 'Y-m-d');
                $days++;
                foreach ($holidays as $hol) {
                    if ($format == $hol->holiday_date) {
                        $days--;
                    }
                }
            }
        }

        return $days;
    }

    public function getLatesInMins($time_in, $shift_in, $grace, $date, $user){
        $late_in_mins = 0;
        if ($time_in != 0) {
            $time_in = Carbon::parse($time_in);
            $date = Carbon::parse($date);
            $shift_in = Carbon::parse($shift_in)->addMinutes((int)$grace);

            $filed_absences = $this->getNotices($date->format('Y-m-d'), $user);
            if (count($filed_absences) <= 0) {
                if ($time_in > $shift_in) {
                    $late_in_mins = $time_in->diffInMinutes($shift_in);
                }
            }
        }
        
        return $late_in_mins;
    }

    public function getShiftSchedule($date, $shift_group){
        $shift_date = Carbon::parse($date);
        $dayOfWeek = $shift_date->format('l');

        $shift_details = DB::table('shifts')->where('shift_group_id', $shift_group)->where('day_of_week', $dayOfWeek)->first();

        $special_shift = DB::table('shift_schedule')->whereDate('sched_date', $shift_date)->first();
        if ($special_shift) {
            $shift_details = $special_shift;
        }

        return $shift_details;
    }

    public function getBiometricLogs($user_id, $from_date, $to_date){
        $employee_details = DB::table('users')->where('user_id', $user_id)->first();
        $shift_group = $employee_details->shift_group_id;
        $from_date = Carbon::parse($from_date)->format('Y-m-d');
        $to_date = Carbon::parse($to_date)->format('Y-m-d');
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', (int)$user_id)
            ->whereBetween('bio_date', [$from_date, $to_date])
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->get()
            ->chunk(10);

        $data = [];
        foreach ($biometric as $row) {
            foreach ($row as $d) {
                $shift_details = $this->getShiftSchedule($d->bio_date, $shift_group);
                $late_in_mins = 0;
                if (!empty($d->locout)) {
                    $late_in_mins = $this->getLatesInMins($d->timein, $shift_details->time_in, $shift_details->grace_period_in_mins, $d->bio_date, $user_id);
                }
                $data[] = [
                    'bio_date' => $d->bio_date,
                    'time_in' => $d->timein,
                    'time_out' => $d->timeout,
                    'locin' => $d->locin,
                    'locout' => $d->locout,
                    'late_in_mins' => $late_in_mins
                ];
            }
        }

        return $data;
    }

    public function getNotices($transaction_date, $user_id){
        $datte = Carbon::parse($transaction_date);

        $notices = DB::table('notice_slip')->join('leave_types', 'leave_types.leave_type_id', 'notice_slip.leave_type_id')
                ->where('user_id', (int)$user_id)->where('status', 'APPROVED')->get()->chunk(10);

        $absence_dates = [];
        $data = [];
        foreach ($notices as $row) {
            foreach ($row as $d) {
                $start = new DateTime($d->date_from);
                $end = new DateTime($d->date_to);
                $end->modify('+1 day');

                $period = new DatePeriod( $start, new DateInterval( 'P1D' ), $end );

                foreach($period as $absent_date ){
                    $absence_dates[] = [
                        'date' => $absent_date->format( 'Y-m-d'),
                    ];
                }

                $absence_dates = array_column($absence_dates, 'date');

                if (in_array($datte->format('Y-m-d'), $absence_dates)) {
                    $data = [
                        'notice_id' => $d->notice_id,
                        'date_absent' => $datte->format('Y-m-d'),
                        'absence_type' => $d->leave_type,
                        'status' => $d->status,
                    ];
                }
            }
        }

        return $data;
    }

    public function getTotalDaysPresent($user_id, $from_date, $to_date){
        $from_date = Carbon::parse($from_date)->format('Y-m-d');
        $to_date = Carbon::parse($to_date)->format('Y-m-d');

        $days_present = DB::table('biometric_logs')->where('user_id', (int)$user_id)
                ->whereBetween('transaction_date', [$from_date, $to_date])
                ->distinct()->count('transaction_date');

        return $days_present;
    }

    // get employees with perfect working days vs required working days
    public function getEmpPerfectDaysWorked($from_date, $to_date){
        $from_date = Carbon::parse($from_date)->format('Y-m-d');
        $to_date = Carbon::parse( $to_date)->format('Y-m-d');

        $working_days = $this->getWorkingDays($from_date, $to_date);

        $employees = DB::table('biometrics')
                ->join('users', 'users.user_id', 'biometrics.employee_id')
                ->whereBetween('bio_date', [$from_date, $to_date])
                ->select('employee_id', 'users.employee_name', DB::raw('COUNT(DISTINCT bio_date) as days_present'))
                ->groupBy('employee_id', 'users.employee_name')
                ->get()->chunk(10);

        $result = [];
        foreach ($employees as $row) {
            foreach ($row as $d) {
                if ($d->days_present >= $working_days) {
                    $result[] = [
                        'employee_id' => $d->employee_id,
                        'employee_name' => $d->employee_name,
                        'days_present' => $d->days_present,
                    ];
                }                
            }
        }

        return $result;
    }

    public function getTotalAbsences($employee_id, $date_from, $date_to){
        $absent_notices = DB::table('notice_slip')
                ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                ->where('user_id', (int)$employee_id)
                ->where('status', 'APPROVED')
                ->select('leave_types.leave_type', 'notice_slip.*')
                ->get();

        $from_date_filter = new DateTime($date_from);
        $to_date_filter = new DateTime($date_to);
        $to_date_filter->modify('+1 day');

        $period = new DatePeriod($from_date_filter, new DateInterval( 'P1D' ), $to_date_filter);

        $dates_from_filter = [];

        foreach($period as $date ){
            $dates_from_filter[] = [
                'date' => $date->format( 'Y-m-d')
            ];
        }

        $dates_from_filter = array_column($dates_from_filter, 'date');

        $days = 0;
        foreach ($absent_notices as $row) {
            $absent_from = new DateTime($row->date_from);
            $absent_to = new DateTime($row->date_to);
            $absent_to->modify('+1 day');

            $absence_period = new DatePeriod($absent_from, new DateInterval( 'P1D' ), $absent_to);

            foreach ($absence_period as $absence_date) {
                if (in_array($absence_date->format( 'Y-m-d'), $dates_from_filter)){
                    if (stripos(strtolower($row->leave_type), 'half')) {
                        $days = $days + 0.5;
                    }else if (stripos(strtolower($row->leave_type), 'undertime')) {
                        $time_from = Carbon::parse($row->time_from);
                        $time_to = Carbon::parse($row->time_to);

                        $hrs = $time_to->diffInHours($time_from) / 8;

                        $days = $days + $hrs;
                    }else{
                        $days++;
                    }
                }
            }
        }

        return $days;
    }

    public function getPerfectAttendance($from_date, $to_date){
        $from_date = Carbon::parse($from_date)->format('Y-m-d');
        $to_date = Carbon::parse($to_date)->format('Y-m-d');

        // employees with perferct working days
        $employees_with_perfect_days_worked = $this->getEmpPerfectDaysWorked($from_date, $to_date);

        // check employees (with perfect working days) for notices
        $employees_without_notices = [];
        foreach ($employees_with_perfect_days_worked as $row) {
            $total_days_absent = $this->getTotalAbsences($row['employee_id'], $from_date, $to_date);
            if ($total_days_absent <= 0) {
                $employees_without_notices[] = [
                    'employee_id' => $row['employee_id'],
                    'employee_name' => $row['employee_name'],
                    'days_worked' => $row['days_present'],
                    'days_absent_notice' => $total_days_absent
                ];
            }
        }

        $employees_with_perfect_attendance = [];
        foreach ($employees_without_notices as $row) {
            $biometric_logs = $this->getBiometricLogs($row['employee_id'], $from_date, $to_date);
            $total_lates = collect($biometric_logs)->sum('late_in_mins');
            if ($total_lates <= 0) {
                $employees_with_perfect_attendance[] = [
                    'employee_id' => $row['employee_id'],
                    'employee_name' => $row['employee_name'],
                    'days_worked' => $row['days_worked'],
                ];
            }
        }
    
        return $employees_with_perfect_attendance;
    }

    public function getUnfiledAbsences($user_id, $date_from, $date_to){
        $format = 'Y-m-d';
        $start = new DateTime($date_from);
        $end = new DateTime($date_to);
        $end->modify('+1 day');

        $user_details = DB::table('users')->where('user_id', $user_id)->first();

        $period = new DatePeriod( $start, new DateInterval('P1D'), $end );

        $days_worked = $this->getTotalDaysPresent($user_id, $date_from, $date_to);
        $required_working_days = $this->getWorkingDays($date_from, $date_to);

        $list = [];
        $summary = [];
        $days = 0;
        if ($days_worked < $required_working_days) {
            foreach($period as $date_period ){
                $is_holiday = DB::table('holidays')->whereDate('holiday_date', $date_period->format($format))->count();
                if ($is_holiday <= 0) {
                    $biometric_count = DB::table('biometric_logs')
                        ->where('user_id', (int)$user_id)
                        ->whereDate('transaction_date', $date_period->format($format))
                        ->distinct()->count('transaction_date');

                    if ($biometric_count <= 0) {
                        if ($date_period->format($format) < date($format) && $date_period->format('N') < 7) {
                            $filed_absences = $this->getNotices($date_period->format($format), $user_id);
                            if (count($filed_absences) <= 0) {
                                $days++;
                                $list[] = [
                                    'employee_id' => $user_id,
                                    'employee_name' => $user_details->employee_name,
                                    'date' => $date_period->format($format),
                                    'total' => $days
                                ];
                            }
                            $summary = [
                                'employee_id' => $user_id,
                                'employee_name' => $user_details->employee_name,
                                'date' => $date_period->format($format),
                                'total' => $days
                            ];
                        }
                    }
                }
            }
        }
        
        return ['list' => $list, 'summary' => $summary];
    }

    // new 
    public function overallStatus($logs, $transaction_date, $employee){
        if(is_array($logs)){
            $time_in = $logs ? $logs['time_in'] : null;
            $time_out = $logs ? $logs['time_out'] : null;
        }else{ // for object
            $time_in = $logs ? $logs->time_in : null;
            $time_out = $logs ? $logs->time_out : null;
        }

        $hasNotice = $this->getNotices($transaction_date, $employee);
   
        $isHoliday = DB::table('holidays')->where('holiday_date', $transaction_date)->first();

        if ($hasNotice) {
            $status = $hasNotice['absence_type'];
        }elseif (!empty($time_in) or !empty($time_out) ) {
            $status = 'Present';
        }elseif ($isHoliday) {
            $status = 'Holiday';
        }elseif (Carbon::parse($transaction_date)->format('N') == 7) {
            $status = 'Sunday';
        }else{
            $status = 'Unfiled Absence';
        }

        return $status;
    }

    public function getShiftDetails($transaction_date, $user_id){
        $dayOfWeek = Carbon::parse($transaction_date)->format('l');
        $user_details = DB::table('users')->where('user_id', $user_id)->first();

        $special_shift = DB::table('shift_schedule')
                ->where('branch_id', $user_details->branch)
                ->where('department_id', $user_details->department_id)
                ->where('sched_date', $transaction_date)
                ->select('time_in', 'time_out', 'breaktime_by_hr as breaktime', 'grace_period_in_mins')
                ->first();

        if (empty($special_shift)) {
            $regular_shift = DB::table('shifts')
                ->where('shift_group_id', $user_details->shift_group_id)
                ->where('day_of_week', $dayOfWeek)
                ->select('time_in', 'time_out', 'breaktime_by_hour as breaktime', 'grace_period_in_mins')
                ->first();
        }

        $shift_detail = empty($special_shift) ? $regular_shift : [];
            
        return $shift_detail;
    }

    public function timeInStatus($attendance_status, $time_in, $shift_in, $grace_period){
        $parsed_time_in = Carbon::parse($time_in);
        $shift_time_in = Carbon::parse($shift_in)->addMinutes($grace_period + 1);
        $shift_in = Carbon::parse($shift_in)->addMinutes($grace_period);

        if($attendance_status == 'Half Day Absence'){
            $status = 'on time';
        }elseif($parsed_time_in >= $shift_time_in) {
            $status = 'late';
        }else{
            $status = 'on time';
        }

        $status = $time_in ? $status : null;
        $late_in_minutes = ($status == 'late') ? $parsed_time_in->diffInMinutes($shift_in) : 0;

        return ['late_in_minutes' => $late_in_minutes, 'status' => $status];
    }

    public function overtimeHrs($attendance_status, $time_out, $shift_time_out){
        $time_out = Carbon::parse($time_out);
        $shift_time_out = Carbon::parse($shift_time_out);

        if(empty($time_out) || $shift_time_out > $time_out){
            $overtime = 0;
        }else{
            $overtime = $time_out->diffInHours($shift_time_out);
        }

        $overtime = $attendance_status == 'Present' ? $overtime : 0;

        return $overtime;
    }

    public function totalHrsWorked($logs, $shift_details){
        $time_in = $logs ? $logs->time_in : null;
        $time_out = $logs ? $logs->time_out : null;

        $parsed_shift_in = Carbon::parse($shift_details ? $shift_details->time_in : '00:00:00');
        $parsed_shift_out = Carbon::parse($shift_details ? $shift_details->time_out : '00:00:00');
        $grace_period = $shift_details ? $shift_details->grace_period_in_mins : 0;
        $parsed_shift_in_grace_period = $parsed_shift_in->addMinutes($grace_period);
        $breaktime = $shift_details ? $shift_details->breaktime : 0;

        $parsed_time_in = Carbon::parse($time_in);
        $parsed_time_out = Carbon::parse($time_out);
        
        if (!empty($time_in) && !empty($time_out)) {
            $minutes_worked = $parsed_time_out->diffInMinutes($parsed_time_in);
            $hrs_worked = ($minutes_worked / 60) - $breaktime;
            if ($parsed_time_in <= $parsed_shift_in_grace_period) {
                $hrs_worked = $parsed_shift_out > $parsed_time_out ? round($hrs_worked, 2) : round($hrs_worked);
            }elseif ($parsed_time_in > $parsed_shift_in_grace_period) {
                $hrs_worked = round($hrs_worked, 2);
            }else{
                $hrs_worked = 0;
            }
        }else{
            $hrs_worked = 0;
        }

        return $hrs_worked;
    }

    public function attendanceLogs($employee, $date_from, $date_to){
        $begin = new Carbon($date_from);
        $end = new Carbon($date_to);
        $end->modify('+1 day');

        $dateRange = new DatePeriod($begin, new DateInterval('P1D'), $end);
        $emp_last_transaction_date = DB::table('biometric_logs')->where('user_id', (int)$employee)->max('transaction_date');

        $employee_logs = [];
        foreach ($dateRange as $date) {
            if ($date->format('Y-m-d') <= $emp_last_transaction_date) {
                $dayOfWeek = $date->format('l');
                $logs = DB::table('biometric_logs')->where('user_id',(int) $employee)
                    ->where('transaction_date', $date->format('Y-m-d'))->first();

                $attendance_status = $this->overallStatus($logs, $date->format('Y-m-d'), $employee);

                $shift_details = $this->getShiftDetails($date->format('Y-m-d'), $employee);
                $shift_time_in = $shift_details ? $shift_details->time_in : '00:00:00';
                $shift_time_out = $shift_details ? $shift_details->time_out : '00:00:00';
                $breaktime = $shift_details ? $shift_details->breaktime : 0;
                $grace_period = $shift_details ? $shift_details->grace_period_in_mins : 0;

                $time_in = $logs ? $logs->time_in : null;
                $time_out = $logs ? $logs->time_out : null;
                $location_in = $logs ? $logs->location_in : null;
                $location_out = $logs ? $logs->location_out : null;

                $time_in_status = $this->timeInStatus($attendance_status, $time_in, $shift_time_in, $grace_period);

                $overtime_hrs = $this->overtimeHrs($attendance_status, $time_out, $shift_time_out);

                $hrs_worked = $this->totalHrsWorked($logs, $shift_details);

                $employee_logs[] = [
                    'transaction_date' => $date->format('Y-m-d'),
                    'day_of_week' => $dayOfWeek,
                    'time_in' => $time_in,
                    'time_out' => $time_out,
                    'location_in' => $location_in,
                    'location_out' => $location_out,
                    'attendance_status' => $attendance_status,
                    'shift_time_in' => $shift_time_in,
                    'shift_time_out' => $shift_time_out,
                    'breaktime' => $breaktime,
                    'grace_period' => $grace_period,
                    'time_in_status' => $time_in_status['status'],
                    'late_in_minutes' => $time_in_status['late_in_minutes'],
                    'overtime' => $overtime_hrs,
                    'hrs_worked' => $hrs_worked
                ];
            }   
        }

        $employee_logs = array_reverse(array_sort($employee_logs));

        return $employee_logs;
    }
}