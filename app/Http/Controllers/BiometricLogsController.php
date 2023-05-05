<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use DatePeriod;
use DateInterval;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Auth;

class BiometricLogsController extends Controller
{
    public function biometricLogs($user_id, $date_from, $date_to){
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            // ->where('biometric_id', '>', 704020)
            ->where('employee_id', (int)$user_id)
            ->whereBetween('bio_date', [$date_from, $date_to])
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->get();

        $biometric_logs = [];
        foreach ($biometric as $row) {
            // parse shift time in / out 
            $shift_time_in = $this->getShiftDetail('time_in', $row->bio_date, $user_id);
            $shift_time_out = $this->getShiftDetail('time_out', $row->bio_date, $user_id);

            $grace_period_in_mins = $this->getShiftDetail('grace_period_in_mins', $row->bio_date, $user_id);

            // add grace period to shift time in
            $shift_in_grace_period = Carbon::parse($shift_time_in)->addMinutes($grace_period_in_mins + 1)->format('H:i:s');
            $shift_in_grace_period = Carbon::parse($shift_in_grace_period)->format('H:i:s');

            $overtime = 0;
            $hrs_worked = 0;

            if ($row->timein != 0) {
                $time_in = Carbon::parse($row->timein)->format('H:i:s');
                // get late in minutes
                if ($time_in >= $shift_in_grace_period) {
                    $shift_in = Carbon::parse($shift_time_in)->addMinutes($grace_period_in_mins);
                    $late_in_minutes = (new Carbon($shift_in))->diff(new Carbon($time_in))->format('%i');
                }else{
                    $late_in_minutes = 0;
                }
                // get deductions
                $deductions = $this->attendanceRules($row->timein, $shift_time_in, $grace_period_in_mins);

                // set status 
                $status = $row->timein > $shift_in_grace_period ? 'late' : 'on time';
            }else{
                $status = '-';
            }

            if ($row->timeout != 0) {
                $time_out = Carbon::parse($row->timeout);
                // calculate overtime
                $overtime = (new Carbon($shift_time_out))->diff(new Carbon($row->timeout))->format('%h');
                // calculate hrs worked
                $hrs_worked = (new Carbon($row->timeout))->diff(new Carbon($shift_time_in))->format('%h');
                $hrs_worked = $hrs_worked - $this->getShiftDetail('breaktime_by_hour', $row->bio_date, $user_id);;
            }
            
            $biometric_logs[] = [
                'transaction_date' => $row->bio_date,
                'shift_schedule'   => $this->getShiftDetail('shift_schedule', $row->bio_date, $user_id),
                'time_in' => $row->timein,
                'in_location' => $row->locin,
                'time_out' => $row->timeout,
                'out_location' => $row->locout,
                'status' => $status,
                'hrs_worked' => $hrs_worked,
                'overtime' => (int)$overtime,
                'deductions' => $deductions,
                'late_in_minutes' => $late_in_minutes,
            ];
        }

        return $biometric_logs;
    }

    public function getShiftDetail($column, $date, $user){
        $detail = DB::table('shifts')
                        ->join('shift_schedule', 'shifts.shift_id', '=','shift_schedule.shift_id')
                        ->where('sched_date', $date)
                        ->select($column)
                        ->first();

        if (empty($detail)) {
             $detail = DB::table('shifts')
                        ->join('users', 'shifts.shift_id', '=','users.shift_id')
                        ->where('user_id', $user)
                        ->select($column)
                        ->first();
        }

        return $detail->$column;
    }

    public function biometricHistory(Request $request){
        // if ($request->ajax()) {
            $biometric_logs = $this->biometricLogs($request->employee, $request->start, $request->end);

            $working_days = $this->getWorkingDays($request->start, $request->end);

            $policy = DB::table('attendance_rules')->get();

            $reqHrs = $working_days * 8;

            $summary = [
                'date_from' => $request->start,
                'date_to' => $request->end,
                'ot_hours' => collect($biometric_logs)->sum('overtime'),
                'hrs_worked' => collect($biometric_logs)->sum('hrs_worked'),
                'working_days' => $working_days,
                'reqHrs' => $reqHrs,
                'deductions' => collect($biometric_logs)->sum('deductions'),
                'total_lates' => collect($biometric_logs)->sum('late_in_minutes')
            ];

            $data = [
                'biometric_logs'  => $biometric_logs,
                'summary'  => $summary,
                'policy' => $policy
            ];

            return view('client.tables.attendance_history_table', compact('data'))->render();
        // }
    }

    //get working days excluding holidays
    public function getWorkingDays($start, $end){
        $start = new DateTime($start);
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

    public function attendanceRules($time, $shift_time_in, $grace_period){
        $time_in = Carbon::parse($time);

        $rules = DB::table('attendance_rules')->get();

        $deductions = 0;
        foreach ($rules as $key => $row) {
            $from = Carbon::parse(Carbon::parse($shift_time_in)->addMinutes($row->from_minute));
            $to = Carbon::parse(Carbon::parse($shift_time_in)->addMinutes($row->to_minute));

            if ($time_in >= $from && $time_in <= $to) {
               
                $deductions = $row->deduction_in_mins;
            }
        }

        return $deductions;
    }

    public function employeeAttendance(Request $request, $user_id){
        if ($request->ajax()) {
            $biometric_logs = $this->biometricLogs($user_id, $request->start, $request->end);

            // Get current page form url e.x. &page=1
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
     
            // Create a new Laravel collection from the array data
            $itemCollection = collect($biometric_logs);
     
            // Define how many items we want to be visible in each page
            $perPage = 8;
     
            // Slice the collection to get the items to display in current page
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
     
            // Create our paginator and pass it to the view
            $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
     
            // set url path for generted links
            $paginatedItems->setPath($request->url());

            $biometric_logs = $paginatedItems;

            return view('client.tables.attendance_table', compact('biometric_logs'))->render();
        }
    }

    public function statisticalReport($from_date, $to_date){
        $employees = DB::table('users')
                    ->join('designation', 'designation.des_id', '=', 'users.designation_id')
                    ->where('user_type', 'Employee')->where('shift_id', '>', 0)
                    ->orderBy('employee_name', 'asc')->get();

        $date_from = Carbon::parse($from_date);
        $date_to = Carbon::parse($to_date);

        $working_days = $this->getWorkingDays($date_from, $date_to);

        $reqHrs = $working_days * 8;

        $data = [];
        foreach ($employees as $row) {
            $biometric_logs = $this->biometricLogs($row->user_id, $from_date, $to_date);
            $total_absence = $this->getTotalAbsences($row->user_id, $date_from, $date_to);
            $hrs_worked = collect($biometric_logs)->sum('hrs_worked');
            $overtime_hrs = collect($biometric_logs)->sum('overtime');

            // working hours excluding OT
            $working_hrs = $hrs_worked - $overtime_hrs;

            // % working rate
            $working_rate = ($working_hrs / $reqHrs) * 100;

            $absence_rate = 100 - $working_rate;
            $data[] = [
                'user_id' => $row->user_id,
                'employee_name' => $row->employee_name,
                'designation' => $row->designation,
                'total_lates' => collect($biometric_logs)->sum('late_in_minutes'),
                'total_overtime' => $overtime_hrs,
                'total_working_hrs' => $hrs_worked,
                'total_absence' => $total_absence,
                'working_rate' => $working_rate,
                'absence_rate' => $absence_rate,
            ];

            $date_filters = [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ];
        }

        return view('client.reports.statistical_report', compact('data', 'date_filters'));
    }

    public function reportDateFilter(Request $request){
        return redirect('/admin/' . $request->report_name . '/' . $request->date_from .'/'.$request->date_to);
    }

    public function getTotalAbsences($employee_id, $date_from, $date_to){
        $absent_notices = DB::table('notice_slip')
                ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                ->where('user_id', $employee_id)
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

    public function showAttendanceAdjustments(Request $request){
        return view('client.attendance_adjustment_monitoring');
    }

    public function attendanceAdjMonitoring(Request $request){
        // if ($request->ajax()) {
            $biometrics = DB::table('biometrics')
            ->join('users', 'users.user_id', 'biometrics.employee_id')
            ->join('designation', 'designation.des_id', 'users.designation_id')
            ->select(DB::raw('employee_id, bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'), 'employee_name', 'designation')
            ->whereDate('bio_date', '!=', date('Y-m-d'))
            ->whereBetween('bio_date', [$request->start, $request->end])
            ->having('timeout', 0)
            ->orHaving('timein', 0)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date', 'employee_id', 'employee_name', 'designation')
            ->get();

            // Get current page form url e.x. &page=1
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
     
            // Create a new Laravel collection from the array data
            $itemCollection = collect($biometrics);
     
            // Define how many items we want to be visible in each page
            $perPage = 30;
     
            // Slice the collection to get the items to display in current page
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
     
            // Create our paginator and pass it to the view
            $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
     
            // set url path for generted links
            $paginatedItems->setPath($request->url());

            $biometrics = $paginatedItems;

            return view('client.tables.attendance_adjustment_monitoring_table', compact('biometrics'))->render();
        // }
    }

    public function employeeLeaveAnalytics($from_date, $to_date){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $employees = DB::table('users')
                    ->join('designation', 'designation.des_id', '=', 'users.designation_id')
                    ->where('user_type', 'Employee')->where('shift_id', '>', 0)
                    ->orderBy('employee_name', 'asc')->get();

        $date_from = Carbon::parse($from_date);
        $date_to = Carbon::parse($to_date);

        $working_days = $this->getWorkingDays($date_from, $date_to);

        $reqHrs = $working_days * 8;

        $data = [];
        foreach ($employees as $row) {
            $biometric_logs = $this->biometricLogs($row->user_id, $from_date, $to_date);
            $total_absence = $this->getTotalAbsences($row->user_id, $date_from, $date_to);
            $hrs_worked = collect($biometric_logs)->sum('hrs_worked');
            $overtime_hrs = collect($biometric_logs)->sum('overtime');

            // working hours excluding OT
            $working_hrs = $hrs_worked - $overtime_hrs;

            // % working rate
            $working_rate = ($working_hrs / $reqHrs) * 100;

            $absence_rate = 100 - $working_rate;
            $data[] = [
                'user_id' => $row->user_id,
                'employee_name' => $row->employee_name,
                'designation' => $row->designation,
                'total_lates' => collect($biometric_logs)->sum('late_in_minutes'),
                'total_overtime' => $overtime_hrs,
                'total_working_hrs' => $hrs_worked,
                'total_absence' => $total_absence,
                'working_rate' => $working_rate,
                'absence_rate' => $absence_rate,
            ];

            $date_filters = [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ];
        }

        return view('client.modules.absent_notice_slip.employee_leave_analytics', compact('designation', 'department', 'data', 'date_filters'));
    }

    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
    }

    public function attendanceAdjustmentMonitoring(Request $request){
        // if ($request->ajax()) {
              $biometrics = DB::table('biometric_logs')
                ->select('biometric_logs.*','designation.designation','users.employee_name')
            ->join('users', DB::raw('CAST(users.user_id AS UNSIGNED)'),'=', 'biometric_logs.user_id')
            ->join('designation', 'designation.des_id', 'users.designation_id')
            
            ->whereDate('transaction_date', '!=', date('Y-m-d'))
            ->whereBetween('transaction_date', [$request->start, $request->end])
            ->where(function ($query) {
                $query->where('time_in', null)
                        ->orWhere('time_out', null);})
            ->orderBy('transaction_date', 'desc')
            ->get();

            // Get current page form url e.x. &page=1
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
     
            // Create a new Laravel collection from the array data
            $itemCollection = collect($biometrics);
     
            // Define how many items we want to be visible in each page
            $perPage = 10;
     
            // Slice the collection to get the items to display in current page
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
     
            // Create our paginator and pass it to the view
            $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
     
            // set url path for generted links
            $paginatedItems->setPath($request->url());

            $biometrics = $paginatedItems;

            return view('client.modules.attendance.biometric_adjustments.adj_monitoring', compact('biometrics'))->render();
        // }
    }

    public function attendanceHistory(Request $request){
        // if ($request->ajax()) {
            $biometric_logs = $this->biometricLogs($request->employee, $request->start, $request->end);

            $working_days = $this->getWorkingDays($request->start, $request->end);

            $policy = DB::table('attendance_rules')->get();

            $reqHrs = $working_days * 8;

            $summary = [
                'date_from' => $request->start,
                'date_to' => $request->end,
                'ot_hours' => collect($biometric_logs)->sum('overtime'),
                'hrs_worked' => collect($biometric_logs)->sum('hrs_worked'),
                'working_days' => $working_days,
                'reqHrs' => $reqHrs,
                'deductions' => collect($biometric_logs)->sum('deductions'),
                'total_lates' => collect($biometric_logs)->sum('late_in_minutes')
            ];

            $data = [
                'biometric_logs'  => $biometric_logs,
                'summary'  => $summary,
                'policy' => $policy
            ];

            return view('client.modules.attendance.attendance_history.history', compact('data'))->render();
        // }
    }

    public function lateEmployees(Request $request){
        $employees = DB::table('users')->where('user_type', 'Employee')->get();
        $late_employees = [];

        $from_date = Carbon::parse('first day of ' . $request->month . ' ' . $request->year)->format('Y-m-d');
        $to_date = Carbon::parse('last day of ' . $request->month . ' ' . $request->year)->format('Y-m-d');
        foreach ($employees as $emp) {
            $logs = $this->biometricLogs($from_date, $to_date, $emp->user_id);
            $total_lates = collect($logs)->sum('late_in_minutes');
            if ($total_lates == 300) {
                $late_employees[] = [
                    'access_id' => $emp->user_id,
                    'employee_name' => $emp->employee_name,
                    'total_lates' => $total_lates,
                ];
            }
        }

        return response()->json($late_employees);
    }
}