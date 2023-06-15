<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use DB;
use Auth;
use Carbon\Carbon;
use App\Biometric_logs;
use DateTime;
use DatePeriod;
use DateInterval;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Traits\AttendanceTrait;

class AttendanceController extends Controller
{
    use AttendanceTrait;

    public function refreshAttendance(){
        $existing_bio = DB::table('biometrics')->where('employee_id', (int)Auth::user()->user_id)->where('type', '!=', 'adjustment')->select('biometric_id')->get();

        $bio_ids = '0000';

        if (!empty($existing_bio)) {

            foreach ($existing_bio as $bio_id) {
                $bio_ids .= $bio_id->biometric_id.',';
            }

            $bio_ids = rtrim($bio_ids,',');
            $bio_ids = "AND Transactions.[ID] NOT IN (".$bio_ids.")"; 
        }

        $attendance = DB::connection('access')->select('SELECT Transactions.[ID], Transactions.[date], Transactions.[time], Transactions.[SerialNo], Transactions.[TransType], Transactions.[pin], Transactions.[ReceivedDate], Transactions.[ReceivedTime], templates.[FirstName], templates.[LastName], UnitSiteQuery.[UnitName] FROM (Transactions LEFT JOIN UnitSiteQuery ON Transactions.Address = UnitSiteQuery.Address) LEFT JOIN templates ON (Transactions.pin = templates.pin) AND (Transactions.finger = templates.finger) WHERE (Transactions.[TransType] = 7 OR Transactions.[TransType] = 8) AND Transactions.[ID] > 704020 AND Transactions.[pin] = '.Auth::user()->user_id.' '.$bio_ids.'');

        $data = [];

        foreach ($attendance as $row) {

            $data[] = ['biometric_id' => $row->ID,
                    'bio_date' => $row->date,
                    'bio_time' => $row->time,
                    'serial_no' => $row->SerialNo,
                    'trans_type' => $row->TransType,
                    'employee_id' => $row->pin,
                    'received_date' => $row->ReceivedDate,
                    'received_time' => $row->ReceivedTime,
                    'unit_name' => $row->UnitName,
                    'type' => 'raw data',
                ];
        }

        DB::table('biometrics')->insert($data);

        return response()->json(['success' => 'Updated: Biometric Logs']);
    }

    public function getBioAdjustments(Request $request){
        if ($request->ajax()) {
            $adjustments = DB::table('biometrics')
                        ->join('users', 'users.user_id', '=', 'biometrics.employee_id')
                        ->select('biometrics.*', 'users.employee_name')
                        ->where('type', 'adjustment')
                        ->paginate(8);

            return view('client.tables.biometric_adjustments_table', compact('adjustments'))->render();
        }
    }

    public function addAdjustment(Request $request){
        if($request->transaction == 7) {
            $date=date('Y-m-d');
            $adj = Biometric_logs::find($request->rowid_data);
            $adj->user_id = $request->employee_id;
            $adj->transaction_date = $request->transaction_date;
            $adj->time_in = $request->adjusted_time;
            $adj->remarks = 'adjustment';
            $adj->adj_type = '7';
            $adj->last_date_modified = $date;
            $adj->last_modified_by=Auth::user()->employee_name;
            $adj->save();
        }elseif ($request->transaction == 8){
            $date=date('Y-m-d');
            $adj = Biometric_logs::find($request->rowid_data);
            $adj->user_id = $request->employee_id;
            $adj->transaction_date = $request->transaction_date;
            $adj->time_out = $request->adjusted_time;
            $adj->remarks = 'adjustment';
            $adj->adj_type = '8';
            $adj->last_date_modified = $date;
            $adj->last_modified_by=Auth::user()->employee_name;
            $adj->save();
        }

        return response()->json(['message' => 'Adjustment has been added.']);
    }

    public function deleteAdjustment(Request $request){
        DB::table('biometrics')
                ->where('biometric_id', $request->biometric_id)
                ->where('type', 'adjustment')
                ->delete();

        return response()->json(['message' => 'Adjustment has been deleted.']);;
    }

    public function showLateEmployeeReport(){
        return view('admin.reports.late_employees');
    }

    public function getLateEmployees(Request $request){
        $employees = DB::table('users')->where('user_type', 'Employee')->get();
        $late_employees = [];

        $from_date = Carbon::parse('first day of ' . $request->month . ' ' . $request->year)->format('Y-m-d');
        $to_date = Carbon::parse('last day of ' . $request->month . ' ' . $request->year)->format('Y-m-d');
        foreach ($employees as $emp) {
            $logs = $this->indexbio($emp->user_id, $from_date, $to_date);
            $total_lates = collect($logs)->sum('late_in_minutes');
                $late_employees[] = [
                    'access_id' => $emp->user_id,
                    'employee_name' => $emp->employee_name,
                    'total_lates' => $total_lates,
                ];
        }

        $sorted_data = collect($late_employees)->sortBy('total_lates')->reverse();
        
        return $sorted_data->values()->all();
    }

    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
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
                        $time_from = date('G:i', strtotime($row->time_from));
                        $time_to = date('G:i', strtotime($row->time_to));

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

    public function showStatisticalReport(Request $request, $from_date, $to_date){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $employees = DB::table('users')
                    ->join('designation', 'designation.des_id', '=', 'users.designation_id')
                    ->where('user_type', 'Employee')->where('shift_group_id', '>', 0)
                    ->orderBy('employee_name', 'asc')->get();

        $date_from = Carbon::parse($from_date);
        $date_to = Carbon::parse($to_date);

        $working_days = $this->getWorkingDays($date_from, $date_to);

        $reqHrs = $working_days * 8;

        $data = [];
        foreach ($employees as $row) {
            $logs = $this->indexbio($row->user_id, $from_date, $to_date);

            $total_absence = $this->getTotalAbsences($row->user_id, $date_from, $date_to);

            $hrs_worked = collect($logs)->sum('hrs_worked');
            $overtime_hrs = collect($logs)->sum('ot');

            // working hours excluding OT
            $working_hrs = $hrs_worked - $overtime_hrs;

            // % working rate
            $working_rate = ($working_hrs / $reqHrs) * 100;

            $absence_rate = 100 - $working_rate;

            $data[] = [
                'user_id' => $row->user_id,
                'employee_name' => $row->employee_name,
                'designation' => $row->designation,
                'total_lates' => collect($logs)->sum('late_in_minutes'),
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

        if ($request->ajax()) {
            return response()->json($data);
        }

        return view('client.modules.absent_notice_slip.employee_leave_analytics', compact('designation', 'department', 'data', 'date_filters'));
    }

    public function reportDateFilter(Request $request){
        return redirect('/' . $request->report_name . '/' . $request->date_from .'/'.$request->date_to);
    }

    public function updateEmployeesLogs(Request $request){
        $employees = DB::table('users')
                    ->where('user_type', 'Employee')
                    ->where('status', 'Active')
                    ->select('user_id')->get();


    foreach ($employees as $row) 
        {
                $try = "AND (Format(Transactions.[date],'yyyy-mm-dd') >= '".$request->from."' AND Format(Transactions.[date],'yyyy-mm-dd') <= '".$request->to."')";
              

                $for_delete = DB::table('biometric_logs')->where('user_id', (int)$row->user_id)
                        ->where(function($q) {
                            $q->where('time_in', null)->orWhere('time_out', null);
                        })->pluck('id');

                $delete = DB::table('biometric_logs')->whereIn('id', $for_delete)->delete();

                $complete_logs = DB::table('biometric_logs')->where('user_id', (int)$row->user_id)
                        ->whereNotIn('id', $for_delete)->pluck('transaction_date');

                $c_logs = [];

                foreach ($complete_logs as $d) {
                    $c_logs[] = ['date' => Carbon::parse($d)->format('Y-m-d H:i:s')];
                }

                $biometrics = DB::connection('access')->select("SELECT Transactions.[pin], Transactions.[date], MAX(iif (Transactions.[TransType] = 7, Transactions.[time], 0)) AS time_in, MAX(iif (Transactions.[TransType] = 8, Transactions.[time], 0)) AS time_out, MAX(iif (Transactions.[TransType] = 7, UnitSiteQuery.[UnitName], 0)) AS loc_in, MAX(iif (Transactions.[TransType] = 8, UnitSiteQuery.[UnitName], 0)) AS loc_out FROM (Transactions LEFT JOIN UnitSiteQuery ON Transactions.Address = UnitSiteQuery.Address) LEFT JOIN templates ON (Transactions.pin = templates.pin) AND (Transactions.finger = templates.finger) WHERE Transactions.[ID] > 704020 AND Transactions.[TransType] IN (7, 8) AND Transactions.[pin] = ".(int)$row->user_id."".$try." GROUP BY Transactions.[date], Transactions.[pin]");

                $biometrics = collect($biometrics)->whereNotIn('date', array_column($c_logs, 'date'));

                $logs = [];
                foreach ($biometrics as $row) {
                    $logs[] = [
                        'user_id' => $row->pin,
                        'transaction_date' => Carbon::parse($row->date)->format('Y-m-d'),
                        'time_in' => $row->loc_in != '0' ? Carbon::parse($row->time_in)->format('H:i:s') : null,
                        'time_out' => $row->loc_out != '0' ? Carbon::parse($row->time_out)->format('H:i:s') : null,
                        'location_in' => $row->loc_in,
                        'location_out' => $row->loc_out,
                        'remarks' => 'raw data'
                    ];
                }

                DB::table('biometric_logs')->insert($logs);
        }
    

        return response()->json(['message' => 'Updated: Biometric Logs from '.$request->from.' - '.$request->to.'.']);
    }

    public function showAnalytics(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $active_employees = DB::table('users')->where('user_type', 'Employee')->where('status', 'Active')->count();
        $present_today = DB::table('biometrics')->distinct('employee_id')->where('bio_date', date('Y-m-d'))->count('employee_id');
        $out_today = DB::table('notice_slip')->distinct('user_id')
                        ->whereDate('notice_slip.date_from', '<=', date("Y-m-d"))
                        ->whereDate('notice_slip.date_to', '>=', date("Y-m-d"))
                        ->where('notice_slip.status', 'Approved')->count();

        $totals = [
            'active_employees' => $active_employees,
            'present_today' => $present_today,
            'out_today' => $out_today,
            'late_today' => 0,
        ];

        return view('client.modules.attendance.analytics', compact('designation', 'department', 'totals'));
    }

    public function getDeductions(Request $request){
        $date_from = date('Y-m-d', strtotime($request->start));
        $date_to = date('Y-m-d', strtotime($request->end));

        $logs = $this->indexbio($request->employee, $date_from, $date_to);

        $total_deductions = collect($logs)->sum('late_in_minutes');

        return $total_deductions;
    }

    public function showAdjustmentMonitoring(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        return view('client.modules.attendance.biometric_adjustments.index', compact('designation', 'department'));
    }

    public function attendanceAdjHistory(Request $request){
        if ($request->ajax()) {
            $adjustments = DB::table('biometric_logs')
                        ->join('users', 'users.user_id', '=', 'biometric_logs.user_id')
                        ->select('biometric_logs.*', 'users.employee_name')
                        ->where('remarks', 'adjustment')
                        ->orderBy('transaction_date','DESC')
                        ->paginate(8);

            return view('client.modules.attendance.biometric_adjustments.adj_history', compact('adjustments'))->render();
        }
    }

    public function showAttendanceHistory(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $employees_per_dept = $this->getEmployeesPerDept();
        $employees = DB::table('users')->where('user_type', 'Employee')->orderBy('employee_name', 'asc')->get();

        return view('client.modules.attendance.attendance_history.index', compact('designation', 'department', 'employees', 'employees_per_dept'));
    }

    public function getEmployeesPerDept(){
        $depts = $this->getHandledDepts(Auth::user()->user_id);

        $employees = DB::table('users')->where('user_type', 'Employee');

        $employee_manager = DB::table('users')->where('user_id', Auth::user()->user_id);

        if (count($depts) > 0) {
            $depts = array_column($depts, 'department');
            $employees = $employees->whereIn('department_id', $depts);
            if (!in_array(Auth::user()->department_id, $depts)) {
                $employees = $employees->union($employee_manager);
            }
            $employees = $employees->orderBy('employee_name', 'asc')->get();
        }else{
            $employees = $employees->where('user_id', Auth::user()->user_id)->orderBy('employee_name', 'asc')->get();
        }
        return $employees;
    }

    public function getHandledDepts($user_id){
        $depts = [];
        $departments = DB::table('department_approvers')->where('employee_id', $user_id)->get();
        foreach ($departments as $row) {
            $depts[] = [
                'department' => $row->department_id];
        }

        return $depts;
    }

    public function showLateEmployees(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        return view('client.modules.attendance.late_employees', compact('designation', 'department'));
    }

    public function getAbsentEmployees(Request $request){
        $employees = DB::table('users')->where('user_type', 'Employee')->where('status', 'Active')->get();

        $from_date = new Carbon('first day of '. $request->month .' '. $request->year); 
        $to_date = new Carbon('last day of '. $request->month .' '. $request->year); 

        $data = [];
        foreach ($employees as $row) {
            $days_absent = $this->getTotalAbsences($row->user_id, $from_date->format('Y-m-d'), $to_date->format('Y-m-d'));
            if ($days_absent > 0) {
                $data[] = [
                    'employee_name' => $row->employee_name,
                    'days_absent' => $days_absent
                ];
            }
        }

        $sorted_data = collect($data)->sortBy('days_absent')->reverse();
        
        return $sorted_data->values()->all();
    }

    public function getPerfectAttendance(Request $request){
        $employees = DB::table('users')->where('user_type', 'Employee')->where('status', 'Active')->get();

        $from_date = new Carbon('first day of '. $request->month .' '. $request->year); 
        $to_date = new Carbon('last day of '. $request->month .' '. $request->year); 

        $working_days = $this->getWorkingDays($from_date->format('Y-m-d'), $to_date->format('Y-m-d'));

        $required_working_hrs = $working_days * 8;
        $required_working_mins = $required_working_hrs * 60;

        $data = [];
        foreach ($employees as $row) {
            $logs = $this->indexbio($row->user_id, $from_date->format('Y-m-d'), $to_date->format('Y-m-d'));

            $hrs_worked = collect($logs)->sum('hrs_worked');
            $late_in_minutes = collect($logs)->sum('late_in_minutes');
            $ot = collect($logs)->sum('ot');

            $working_mins = (($hrs_worked - $ot) * 60) - $late_in_minutes;

            if ($working_mins >= $required_working_mins) {
                $data[] = [
                    'user_id' => $row->user_id,
                    'employee_name' => $row->employee_name,
                    'working_days' => $working_days,
                ];
            }
        }

        // $sorted_data = collect($data)->sortBy('employee_name')->reverse();
        
        return $data;
    }

    public function indexbio($user_id, $datefrom, $dateto){
        $format = "Y-m-d";
        $mytime = Carbon::now();
        $mytime->modify('+1 day');
        $current=$mytime->format($format);
        $begin = new Carbon($datefrom);
        $end = new Carbon($dateto);
        $end->modify('+1 day');
        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $dates = [];
        $range= [];

        foreach($dateRange as $datess ){
            $datte =$datess->format( $format);
            $day= $datess->format( 'l');
            $timein=$this->bioTimein($user_id,$datte);
            $timeout=$this->bioTimeout($user_id, $datte);
            $shift_timein =$this->ShiftSpecial_timein($day,$datte,$user_id);
            $shift_timeout =$this->ShiftSpecial_timeout($day, $datte, $user_id);
            $grace_period = $this->graceperiod($day, $datte, $user_id) + 1;
            $statuss=$this->setStatus($timein, $shift_timein, $grace_period, $timeout, $datte, $datess, $user_id);
            $stat=$this->overallStatus($timein, $timeout, $datte, $datess, $user_id);
            $breaktime_by_hour=$this->breaktime_by_hour($day, $datte, $user_id);
            $gettotalworkhrs=$this->calculateTwh($timein, $shift_timein, $timeout, $breaktime_by_hour, $grace_period, $shift_timeout);
            $getovertime=$this->calculateOvertime($timein, $shift_timeout, $timeout);
            $late_in_minutes = $this->getTotalLates($timein, $shift_timein, $grace_period, $timeout, $datte, $datess, $user_id);
            $deduction = $this->attendanceRules($timein, $shift_timein, $grace_period);
            $bio=$this->getlastdata($user_id);

         if($datte < $current) {
            if($datte <= $bio){
            
                 $dates[] = [
                'range' => $datess->format( 'Y-m-d'),
                'late_in_minutes' => $late_in_minutes,
                'deduction' => $deduction,
                'day' => $day,
                'status' => $statuss,
                'stat' => $stat,
                'hrs_worked' => $gettotalworkhrs,
                'ot' => $getovertime,
                // 'shift_timein' => $this->ShiftSpecial_timein($day, $datte, $user_id),
                // 'shift_timeout' => $this->ShiftSpecial_timeout($day, $datte, $user_id),
                // 'graceperiod' => $this->graceperiod($day, $datte, $user_id),
                // 'bio_date' => $this->biometricsfunc($user_id, $datte),
                'timein' => $this->bioTimein($user_id, $datte),
                'timeout' => $this->bioTimeout($user_id, $datte),
                // 'location_in' => $this->bioLocin($user_id, $datte),
                // 'location_out' => $this->bioLocout($user_id, $datte),
            ];
        }
        }else{
            break;
        }
           
            $sortedDesc = array_reverse(array_sort($dates));
        }

        return $sortedDesc;
    }

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

    public function getlastdata($user_id){
       $biometric = DB::table('biometrics')
            ->select(DB::raw('MAX(bio_date) AS bio'))
            ->where('employee_id', (int)$user_id)
            ->first();

    return$biometric->bio;;
    }

    public function checkNotices($datte, $user_id){
        $datte = Carbon::parse($datte);

        $notices = DB::table('notice_slip')->join('leave_types', 'leave_types.leave_type_id', 'notice_slip.leave_type_id')
                ->where('user_id', $user_id)->where('status', 'APPROVED')->get();

        $absence_dates = [];
        $data = null;
        foreach ($notices as $i => $row) {
            $start = new DateTime($row->date_from);
            $end = new DateTime($row->date_to);
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
                    'notice_id' => $row->notice_id,
                    'absence_type' => $row->leave_type,
                    'status' => $row->status,
                ];
            }
        }

        return $data;
    }

    public function checkHoliday($datte){
        $date = Carbon::parse($datte);

        return DB::table('holidays')->where('holiday_date', $datte)->count();
    }          

    public function calculateOvertime($timein, $shift_timeout, $timeout){
        if(empty($timein) or empty($timeout) ){
            $overtime=0;
        }elseif ($shift_timeout > $timeout){
            $overtime = 0;
        }else{
            $overtime = $this->calculateHrs($shift_timeout, $timeout);
        }

        return $overtime;
    }

    public function calculateHrs($timein, $timeout){
        $start = Carbon::parse($timein);
        $end = Carbon::parse($timeout);
        $hrs = $end->diffInHours($start);

        return $hrs;
    }
    
    public function calculateTwh($timein, $shift_timein, $timeout, $breaktime_by_hour, $grace_period,$shift_timeout){
        
        $shift_timeinn = Carbon::parse($shift_timein);
        $grace_period = $shift_timeinn->addMinutes($grace_period)->format('H:i:s');
        // $grace_period = Carbon::parse($grace_period);
        $shift_timeoutt = Carbon::parse($shift_timeout);
        $shiftout = $shift_timeoutt->addMinutes('60.00')->format('H:i:s');


        if(empty($timein) or empty($timeout) ){
            $hrs_worked=0;
            //tama lahat ng oras
        }elseif($timein < $grace_period and $timeout <= $shiftout){
             $hrs_worked = $this->calculateHrs($shift_timein, $timeout) - $breaktime_by_hour;
             //tama ang time in at may over time
        }elseif ($timein < $grace_period and $timeout >= $shiftout) {
            $hrs_worked = $this->calculateHrs($shift_timein, $timeout) - $breaktime_by_hour;
        //lagpas sa timein at lagpas din ang time out
        }elseif ($timein >= $grace_period and $timeout >= $shiftout) {
            $diffHours = (strtotime($timeout) - strtotime($timein)) / 3600;
            $round= (round($diffHours, 2));
            $hrs_worked = $round - $breaktime_by_hour;
            //lagpas ang time in pero tama ang out
        }elseif ($timein >= $grace_period and $timeout <= $shiftout) {
            $diffHours = (strtotime($shift_timeout) - strtotime($timein)) / 3600;
            $round= (round($diffHours, 2));
            $hrs_worked = $round - $breaktime_by_hour;
        }

        return $hrs_worked;
    }

    // public function overallStatus($timein, $timeout, $datte, $datess, $user_id){
    //     $time_in = Carbon::parse($timein);
    //     $time_out =Carbon::parse($timeout);
    //     $notice = $this->checkNotices($datte, $user_id);
    //     $notice_id = $notice['notice_id'];
    //     $notice_status = $notice['status'];
   
    //     $isHoliday = $this->checkHoliday($datte);

    //     if ($notice['absence_type']) {
    //         $status = $notice['absence_type'];
    //     }elseif (!empty($timein) or !empty($timeout) ) {
    //         $status = 'Present';
    //     }elseif ($isHoliday) {
    //         $status = 'Holiday';
    //     }elseif ($datess->format('N') == 7) {
    //         $status = 'Sunday';
    //     }else{
    //         $status = 'Unfiled Absence';
    //     }

    //     return $status;
    // }

    public function setStatus($timein, $shift_timein, $grace_period, $timeout, $datte, $datess, $user_id){
        $statuss = $this->overallStatus($timein, $timeout, $datte, $datess, $user_id);
        $timein = Carbon::parse($timein);
        $shift_timein = Carbon::parse($shift_timein);
            
        $grace_period = $shift_timein->addMinutes($grace_period)->format('H:i:s');
        $grace_period = Carbon::parse($grace_period);

        if($statuss == 'Half Day Absence'){
          $status = 'on time';
        }elseif($timein >= $grace_period) {
            $status = 'late';
        }else{
            $status = 'on time';
        }
        return $status;
    }

    public function getTotalLates($timein, $shift_timein, $grace_period, $timeout, $datte, $datess, $user_id){
        $status = $this->overallStatus($timein, $timeout, $datte, $datess, $user_id);
        $time_in = Carbon::parse($timein);
        $shift_in =Carbon::parse($shift_timein)->addMinutes((int)$grace_period - 1);

        if (empty($timein)) {
            $late_in_minutes = 0;
        }
        elseif($status == 'Half Day Absence'){
          $late_in_minutes = 0;

        }elseif($time_in > $shift_in){
            $late_in_minutes = $time_in->diffInMinutes($shift_in);
        }else{
            $late_in_minutes = 0;
        }

        return $late_in_minutes;
    }

    public function graceperiod($day, $datte, $user_id){
        $shifts = DB::table('shift_schedule')
                ->join('users', 'shift_schedule.shift_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shift_schedule.shift_id','=','shift_groups.id')
                ->where('user_id', $user_id)
                ->where('sched_date', $datte)
                ->first();
        
        if (empty($shifts)) {
            $gracep= $this->grace($day, $datte, $user_id);
        }else{
            $gracep= $shifts->grace_period_in_mins;
        }

        return $gracep;
    }
    
    public function grace($day, $datte, $user_id){
        $detail = DB::table('shifts')
                ->join('users', 'shifts.shift_group_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shifts.shift_group_id','=','shift_groups.id')
                ->where('user_id', $user_id)
                ->where('day_of_week', $day)
                ->first();
        
        if(empty($detail)){
            $var=0;
        }else{
            $var=$detail->grace_period_in_mins;
        }

        return $var;
    }

    public function breaktime_by_hour($day, $datte, $user_id){
        $detail = DB::table('shift_schedule')
                ->join('users', 'shift_schedule.shift_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shift_schedule.shift_id','=','shift_groups.id')
                ->where('user_id', $user_id)->where('sched_date', $datte)->first();

        if (empty($detail)) {
            $var=$this->breaktime_by_hour_shift($day, $datte, $user_id);
        }else {
            $var= $detail->breaktime_by_hr;
        } 
            
        return $var;
    }

    public function breaktime_by_hour_shift($day, $datte, $user_id){
        $detail = DB::table('shifts')
                ->join('users', 'shifts.shift_group_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shifts.shift_group_id','=','shift_groups.id')
                ->where('user_id', $user_id)->where('day_of_week', $day)->first();

        if(empty($detail)){
            $var='0';
        }else{
            $var=$detail->breaktime_by_hour;
        }
        
        return $var;
    }

    public function ShiftSpecial_timein($day, $datte, $user_id){
        $detail = DB::table('shift_schedule')
                ->join('users', 'shift_schedule.shift_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shift_schedule.shift_id','=','shift_groups.id')
                ->where('user_id', $user_id)->where('sched_date', $datte)->first();

        if (empty($detail)) {
            $var=$this->Shifttime_in($day, $datte, $user_id);
        }else {
            $var= $detail->time_in;
        } 
            
        return $var;
    }

    public function ShiftSpecial_timeout($day, $datte, $user_id){
        $detail = DB::table('shift_schedule')
                ->join('users', 'shift_schedule.shift_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shift_schedule.shift_id','=','shift_groups.id')
                ->where('user_id', $user_id)->where('sched_date', $datte)->first();

        if (empty($detail)) {
            $var=$this->Shifttime_out($day, $datte, $user_id);
        }else {
            $var= $detail->time_out;
        }

        return $var;
    }

    public function Shifttime_in($day, $datte, $user_id){
        $detail = DB::table('shifts')
                ->join('users', 'shifts.shift_group_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shifts.shift_group_id','=','shift_groups.id')
                ->where('user_id', $user_id)->where('day_of_week', $day)->first();

        if(empty($detail)){
            $var="00:00:00";
        }else{
            $var=$detail->time_in;
        }

        return $var;
    }

    public function Shifttime_out($day, $datte, $user_id){
        $detail = DB::table('shifts')
                ->join('users', 'shifts.shift_group_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shifts.shift_group_id','=','shift_groups.id')
                ->where('user_id', $user_id)->where('day_of_week', $day)->first();

        if(empty($detail)){
            $var="00:00:00";
        }else{
            $var=$detail->time_out;
        }

        return $var;
    }
   
    public function attendanceRules($timein, $shift_timein, $grace_period){
        $time_in = Carbon::parse($timein)->format('H:i:s');

        $rules = DB::table('attendance_rules')->get();

        $deduction = 0;
        
        foreach ($rules as $key => $row) {
            $from = Carbon::parse(Carbon::parse($shift_timein)->addMinutes($row->from_minute))->format('H:i:s');
            $to = Carbon::parse(Carbon::parse($shift_timein)->addMinutes($row->to_minute + 1))->format('H:i:s');
            if ($time_in >= $from && $time_in <= $to ) {
                $deduction = $row->deduction_in_mins;
                break;
            }
        }

        return $deduction;
    }

    public function biometricsfunc($user_id, $datte){
        $biometric = DB::table('biometrics')->select('bio_date')
            ->where('employee_id', (int)$user_id)
            ->where('bio_date', $datte)
            ->first();

        if (empty($biometric)) {
            $var="empty";
        }else{
            $var= $biometric->bio_date;
        } 

        return $var;
    }

    public function bioTimein($user_id, $datte){
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', (int)$user_id)
            ->where('bio_date', $datte)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->first();

        if(empty($biometric)) {
            $var=null;
        }elseif($biometric->timein == '0') {
            $var=null;
        }else{
            $var= $biometric->timein;
        }
        return $var;
    }

    public function bioTimeout($user_id, $datte){
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', (int)$user_id)
            ->where('bio_date', $datte)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->first();

        if(empty($biometric)) {
            $var=null;
        }elseif($biometric->timeout == '0') {
            $var=null;
        }else{
            $var= $biometric->timeout;
        }

        return $var;
    }

    public function bioLocin($user_id, $datte){
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', (int)$user_id)
            ->where('bio_date', $datte)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->first();

        if (empty($biometric)) {
            $var="empty";
        }else{
            $var= $biometric->locin;
        } 
            
        return $var;
    }

    public function bioLocout($user_id, $datte){
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', (int)$user_id)
             ->where('bio_date', $datte)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->first();

        if (empty($biometric)) {
            $var="empty";
        }else {
            $var= $biometric->locout;
        } 
            
        return $var;
    }

    public function index(Request $request){
        $policy = DB::table('attendance_rules')->get();

        $working_days = $this->getWorkingDays($request->start, $request->end);

        $reqHrs = $working_days * 8;

        $dates = $this->indexbio($request->user_id, $request->start, $request->end);


        $late_in_minutess = collect($dates)->sum('late_in_minutes');
        $ot = collect($dates)->sum('ot'); 
        $hrs_worked = collect($dates)->sum('hrs_worked');
        $deduction = collect($dates)->sum('deduction');

        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
     
        // Create a new Laravel collection from the array data
        $itemCollection = collect($dates);
     
        // Define how many items we want to be visible in each page
        $perPage = 8;
     
        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
     
        // Create our paginator and pass it to the view
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
     
        // set url path for generted links
        $paginatedItems->setPath($request->url());

        $dates = $paginatedItems;

        return view('client.tables.attendance_table', compact('dates'));
    }

    public function attendance_history(Request $request){
        $policy = DB::table('attendance_rules')->get();

        $date_from = date('Y-m-d', strtotime($request->start));
        $date_to = date('Y-m-d', strtotime($request->end));

        $working_days = $this->getWorkingDays($date_from, $date_to);

        $reqHrs = $working_days * 8;

        $dates = $this->indexbio($request->user_id, $date_from, $date_to);
        $late_in_minutess = collect($dates)->sum('late_in_minutes');
        $ot = collect($dates)->sum('ot'); 
        $hrs_worked = collect($dates)->sum('hrs_worked');
        $deduction = collect($dates)->sum('deduction');

        return view('client.tables.attendance_history_table', compact('dates','late_in_minutess','ot','hrs_worked','deduction','date_from', 'date_to', 'reqHrs', 'working_days','policy'));
    }

    public function AttendanceAdjUpdateall(){
        $biometrics = DB::table('biometric_logs')
            ->whereDate('transaction_date', '!=', date('Y-m-d'))
            ->where(function ($query) {
                $query->where('time_in', null)->orWhere('time_out', null);
            })
            ->orderBy('transaction_date', 'desc')->get();

        $data = [];
        foreach ($biometrics as $row) {
            $date = date("l", strtotime($row->transaction_date));

            $shift = DB::table('shift_schedule')
                    ->join('users', 'shift_schedule.shift_id', '=','users.shift_group_id')
                    ->join('shift_groups', 'shift_schedule.shift_id','=','shift_groups.id')
                    ->where('sched_date', $row->transaction_date)->first();

            if (empty($shift)) {
                $shift=DB::table('users')
                    ->join('shifts','users.shift_group_id','=','shifts.shift_group_id')
                    ->where('user_id',(int)$row->user_id)->where('shifts.day_of_week', $date)
                    ->first();
            }
 
            if ($date !== 'Sunday') {
                if($row->time_in == 0) {
                    $date=date('Y-m-d');
                    $adj = Biometric_logs::find($row->id);
                    $adj->user_id = $row->user_id;
                    $adj->transaction_date = $row->transaction_date;
                    $adj->time_in = $shift->time_in;
                    $adj->time_out = $row->time_out;
                    $adj->remarks = 'adjustment';
                    $adj->adj_type = '7';
                    $adj->last_date_modified = $date;
                    $adj->last_modified_by=Auth::user()->employee_name;
                    $adj->save();
                }elseif ($row->time_out == 0){
                    $date=date('Y-m-d');
                    $adj = Biometric_logs::find($row->id);
                    $adj->user_id = $row->user_id;
                    $adj->transaction_date = $row->transaction_date;
                    $adj->time_in = $row->time_in;
                    $adj->time_out = $shift->time_out;
                    $adj->remarks = 'adjustment';
                    $adj->adj_type = '8';
                    $adj->last_date_modified = $date;
                    $adj->last_modified_by=Auth::user()->employee_name;
                    $adj->save();
                }
            }
        }
        
        return response()->json(['message' => 'All Adjustment has been added.']); 
    }

    public function updateAttendanceLogs($employee){
        $for_delete = DB::table('biometric_logs')->where('user_id', $employee)
                ->where(function($q) {
                    $q->where('time_in', null)->orWhere('time_out', null);
                })->pluck('id');

        $delete = DB::table('biometric_logs')->whereIn('id', $for_delete)->delete();

        $complete_logs = DB::table('biometric_logs')->where('user_id', $employee)
                ->whereNotIn('id', $for_delete)->pluck('transaction_date');

        $c_logs = [];
        foreach ($complete_logs as $d) {
            $c_logs[] = ['date' => Carbon::parse($d)->format('Y-m-d H:i:s')];
        }

        $biometrics = DB::connection('access')->select("SELECT Transactions.[pin], Transactions.[date], MAX(iif (Transactions.[TransType] = 7, Transactions.[time], 0)) AS time_in, MAX(iif (Transactions.[TransType] = 8, Transactions.[time], 0)) AS time_out, MAX(iif (Transactions.[TransType] = 7, UnitSiteQuery.[UnitName], 0)) AS loc_in, MAX(iif (Transactions.[TransType] = 8, UnitSiteQuery.[UnitName], 0)) AS loc_out FROM (Transactions LEFT JOIN UnitSiteQuery ON Transactions.Address = UnitSiteQuery.Address) LEFT JOIN templates ON (Transactions.pin = templates.pin) AND (Transactions.finger = templates.finger) WHERE Transactions.[ID] > 704020 AND Transactions.[pin] = ".$employee." GROUP BY Transactions.[date], Transactions.[pin]");

        $biometrics = collect($biometrics)->whereNotIn('date', array_column($c_logs, 'date'));

        $logs = [];
        foreach ($biometrics as $row) {
            $logs[] = [
                'user_id' => $row->pin,
                'transaction_date' => Carbon::parse($row->date)->format('Y-m-d'),
                'time_in' => $row->loc_in != '0' ? Carbon::parse($row->time_in)->format('H:i:s') : null,
                'time_out' => $row->loc_out != '0' ? Carbon::parse($row->time_out)->format('H:i:s') : null,
                'location_in' => $row->loc_in,
                'location_out' => $row->loc_out
            ];
        }

        DB::table('biometric_logs')->insert($logs);
        
        return response()->json(['success' => 'Updated: Biometric Logs']);
    }

    public function employeeAttendanceHistory(Request $request, $employee){
        $employee_logs = $this->attendanceLogs($employee, $request->start, $request->end);

        $working_days = $this->getWorkingDays($request->start, $request->end);
        $reqHrs = $working_days * 8;

        $summary_details = [
            'date_from' => $request->start,
            'date_to' => $request->end,
            'working_days' => $working_days,
            'reqHrs' => $reqHrs,
        ];
        
        return view('client.tables.attendance_history_table', compact('employee_logs', 'summary_details'));
    }

    public function employeeAttendanceDashboard(Request $request, $employee){
        $employee_logs = $this->attendanceLogs($employee, $request->start, $request->end);

        $start = $request->start;
        $end = $request->end;
        $total_late = collect($employee_logs)->sum('late_in_minutes');
        $total_overtime = collect($employee_logs)->sum('overtime');
        $total_hours_worked = collect($employee_logs)->sum('hrs_worked');
        // $no_of_days = count($employee_logs);
        $no_of_days = collect($employee_logs)->where('day_of_week', '!=', 'Sunday')->count();
        $required_working_hrs = $no_of_days * 8;

        // // Get current page form url e.x. &page=1
        // $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // // Create a new Laravel collection from the array data
        // $itemCollection = collect($employee_logs);
        // // Define how many items we want to be visible in each page
        // $perPage = 8;
        // // Slice the collection to get the items to display in current page
        // $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        // // Create our paginator and pass it to the view
        // $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        // // set url path for generted links
        // $paginatedItems->setPath($request->url());

        // $logs = $paginatedItems;

        return view('client.tables.attendance_table', compact('employee_logs', 'start', 'end', 'total_late', 'total_overtime', 'total_hours_worked', 'no_of_days', 'required_working_hrs'));
    }

    public function employeeLateDeductions($employee){
        $date_from = Carbon::parse('first day of this month')->format('Y-m-d');
        $date_to = Carbon::parse('last day of this month')->format('Y-m-d');

        $emp_lates = $this->attendanceLogs($employee, $date_from, $date_to);

        return collect($emp_lates)->sum('late_in_minutes');
    }
}