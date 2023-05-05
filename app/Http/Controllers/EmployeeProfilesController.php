<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Gatepass;
use App\ItemAccountability;
use App\User;
use App\AbsentNotice;
use Carbon\Carbon;
use DB;
use Auth;
use DateTime;
use DatePeriod;
use DateInterval;
use Illuminate\Pagination\LengthAwarePaginator;
use Hash;

class EmployeeProfilesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:admin');
    }

    public function fetchProfiles(Request $request){
    	if($request->ajax()){
            $details = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

            $designation = $details->designation;

            if (in_array($designation, ['Human Resources Head', 'Director of Operations', 'President'])) {
                $employee_profiles = User::join('departments','users.department_id','departments.department_id')
                            ->join('designation','users.designation_id','designation.des_id');
            }else{
                $depts = [];
                $departments = DB::table('department_approvers')->where('employee_id', Auth::user()->user_id)->get();
                foreach ($departments as $row) {
                    $depts[] = [
                        'department' => $row->department_id];
                }

                $employee_profiles = User::join('departments','users.department_id','departments.department_id')
                            ->join('designation','users.designation_id','designation.des_id')
                            ->whereIn('users.department_id', $depts);
            }
            if ($request->q) {
                $employee_profiles = $employee_profiles->where('employee_name', 'like' , '%'.$request->q.'%');
            }

            $employee_profiles = $employee_profiles->select('users.*','departments.department','designation.designation')
                            // ->orderBy('department')
                            // ->orderBy('designation')
                            ->orderBy('employee_name')
                            ->paginate(10);
            
            return view('client.tables.employee_profiles_table', compact('employee_profiles'))->render();
        }
    }

    public function viewProfile($user_id){
        $employee_profile = User::join('departments','users.department_id','departments.department_id')
                                    ->join('designation','users.designation_id','designation.des_id')
                                    ->where('user_id',$user_id)
                                    ->select('users.*','departments.department','designation.designation')
                                    ->first();

        $regular_shift = DB::table('shifts')
                                    ->join('users', 'shifts.shift_id', '=','users.shift_group_id')
                                    ->where('user_id', $user_id)
                                    ->select('shift_schedule')
                                    ->first();

        $shifts = DB::table('shifts')->get(); 
        $branch = DB::table('branch')->get(); 

        $pending_notices = DB::table('notice_slip')
                                ->join('leave_types','notice_slip.leave_type_id','leave_types.leave_type_id')
                                ->where('user_id',$user_id)
                                ->where('status','For Approval')
                                ->select('notice_slip.*','leave_type')
                                ->get();

        $pending_gatepasses = DB::table('gatepass')
                                ->where('user_id',$user_id)
                                ->where('status','For Approval')
                                ->get();

        $unreturned_items = DB::table('gatepass')
                                ->where('user_id',$user_id)
                                ->where('item_status','Unreturned')
                                ->get();

        $departments = DB::table('departments')->get();
        $designations = DB::table('designation')->get();

        $itemlist = DB::table('issued_item')
                        // ->select('item_id', 'item_code','item_desc','date_issued','issued_by','status','itemclass','brand','qty','model','serial_no','mcaddress')
                        ->where('issued_to',$user_id)
                        ->get();

        $code = new ItemAccountability();
        $lastcodeID = $code->orderBy('item_id', 'DESC')->pluck('item_id')->first();
        $newcodeID = $lastcodeID + 1;
        $neww= date('Y').'00000';
        $newly=$neww + $newcodeID;
        $newwwly='FUM'.'-'.$newly;

        $data = [
            'employee_profile' => $employee_profile,
            'regular_shift' => $regular_shift,
            'designation' => $this->sessionDetails('designation'),
            'department' => $this->sessionDetails('department'),
            'pending_notices' => $pending_notices,
            'pending_gatepasses' => $pending_gatepasses,
            'unreturned_items' => $unreturned_items,
            'departments' => $departments,
            'designations' => $designations,
            'shifts' => $shifts,
            'itemlist' => $itemlist,
            'newwwly' => $newwwly,
            'user_id' => $user_id,
            'branch' => $branch
        ];
        return view('client.view_employee_profile')->with($data);
    }
    
    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
    }

    public function resetEmployeePassword($user_id){
        $user = User::where('user_id',$user_id)->first();
        $user->password = bcrypt('fumaco');
        $user->save();

        return redirect()->route('client.view_employee_profile',$user_id);
    }

    public function updateEmployeeProfile(Request $request){
        $employee = User::where('user_id',$request->user_id)->first();
        $employee->employee_name = $request->employee_name;
        $employee->birth_date = $request->birth_date;
        $employee->address = $request->address;
        $employee->contact_no = $request->contact_no;
        $employee->sss_no = $request->sss_no;
        $employee->tin_no = $request->tin_no;
        $employee->civil_status = $request->civil_status;
        $employee->nick_name = $request->nick_name;
        $employee->designation_id = $request->designation;
        $employee->department_id = $request->department;
        $employee->employment_status = $request->employment_status;
        $employee->telephone = $request->telephone;
        $employee->email = $request->email;
        $employee->status = $request->status;
        $employee->user_group = $request->user_group;
        $employee->save();

        $employee = User::where('user_id',$request->user_id)->first();

        return redirect()->route('client.view_employee_profile',$request->user_id);
    }

    public function approveAbsentNotice($notice_id, $user_id){
        $notice = AbsentNotice::find($notice_id)->first();
        $notice->status = "Approved";
        $notice->save();

        return redirect()->route('client.view_employee_profile',$user_id);
    }

    public function changePassword(Request  $request){
        // dd($request->all());
        $employee = User::where('user_id',Auth::user()->user_id)->first();

        if (Hash::check($request->current_pass, $employee['password'])) {
            $employee->password = bcrypt($request->new_pass);
            $employee->save();
            Auth::logout();
            return redirect('/');
        } else {
            return redirect()->back();
        }
    }

    // public function getAttendance(Request $request, $id){
    //     $biometric = DB::table('biometrics')
    //         ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
    //         // ->where('biometric_id', '>', 704020)
    //         ->where('employee_id', (int)$id)
    //         ->whereBetween('bio_date', [$request->start, $request->end])
    //         ->orderBy('bio_date', 'desc')
    //         ->groupBy('bio_date')
    //         ->get();

    //     $biometric_logs = [];
    //     foreach ($biometric as $row) {
    //         // parse shift time in / out 
    //         $shift_time_in = $this->getShiftDetail('time_in', $row->bio_date, $id);
    //         $shift_time_out = $this->getShiftDetail('time_out', $row->bio_date, $id);

    //         $grace_period_in_mins = $this->getShiftDetail('grace_period_in_mins', $row->bio_date, $id);

    //         // add grace period to shift time in
    //         $shift_in_grace_period = Carbon::parse($shift_time_in)->addMinutes($grace_period_in_mins + 1)->format('H:i:s');
    //         $shift_in_grace_period = Carbon::parse($shift_in_grace_period)->format('H:i:s');

    //         $overtime = 0;
    //         $hrs_worked = 0;

    //         if ($row->timein != 0) {
    //             $time_in = Carbon::parse($row->timein)->format('H:i:s');
    //             // get late in minutes
    //             if ($time_in >= $shift_in_grace_period) {
    //                 $shift_in = Carbon::parse($shift_time_in)->addMinutes($grace_period_in_mins);
    //                 $late_in_minutes = (new Carbon($shift_in))->diff(new Carbon($time_in))->format('%i');
    //             }else{
    //                 $late_in_minutes = 0;
    //             }
    //             // get deductions
    //             $deductions = $this->attendanceRules($row->timein, $shift_time_in, $grace_period_in_mins);

    //             // set status 
    //             $status = $row->timein > $shift_in_grace_period ? 'late' : 'on time';
    //         }else{
    //             $status = '-';
    //         }

    //         if ($row->timeout != 0) {
    //             $time_out = Carbon::parse($row->timeout);
    //             // calculate overtime
    //             $overtime = (new Carbon($shift_time_out))->diff(new Carbon($row->timeout))->format('%h');
    //             // calculate hrs worked
    //             $hrs_worked = (new Carbon($row->timeout))->diff(new Carbon($shift_time_in))->format('%h');
    //             $hrs_worked = $hrs_worked - $this->getShiftDetail('breaktime_by_hour', $row->bio_date, $id);;
    //         }
            
    //         $biometric_logs[] = [
    //             'transaction_date' => $row->bio_date,
    //             'shift_schedule'   => $this->getShiftDetail('shift_schedule', $row->bio_date, $id),
    //             'time_in' => $row->timein,
    //             'in_loc' => $row->locin,
    //             'time_out' => $row->timeout,
    //             'out_loc' => $row->locout,
    //             'status' => $status,
    //             'hrs_worked' => $hrs_worked,
    //             'overtime' => (int)$overtime,
    //             'deductions' => $deductions,
    //             'late_in_minutes' => $late_in_minutes,
    //         ];
    //     }

    //     return response()->json($biometric_logs);
    // }

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

    // public function attendanceRules($time, $shift_time_in, $grace_period){
    //     $time_in = Carbon::parse($time);

    //     $rules = DB::table('attendance_rules')->get();

    //     $deductions = 0;
    //     foreach ($rules as $key => $row) {
    //         $from = Carbon::parse(Carbon::parse($shift_time_in)->addMinutes($row->from_minute));
    //         $to = Carbon::parse(Carbon::parse($shift_time_in)->addMinutes($row->to_minute));

    //         if ($time_in >= $from && $time_in <= $to) {
               
    //             $deductions = $row->deduction_in_mins;
    //         }
    //     }

    //     return $deductions;
    // }

    public function getNotices($employee_id){
        $data = DB::table('notice_slip')
                        ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
                        ->join('departments', 'users.department_id', '=', 'departments.department_id')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                        ->where('notice_slip.user_id', '=', $employee_id)
                        ->orderBy('notice_slip.notice_id', 'desc')
                        ->select('users.*', 'notice_slip.*', 'departments.department', 'leave_types.leave_type')
                        ->get();

        return response()->json($data);
    }

    public function getGatepass($employee_id){
        $data = DB::table('gatepass')
                        ->join('users', 'users.user_id', '=', 'gatepass.user_id')
                        ->where('gatepass.user_id', '=', $employee_id)
                        ->orderBy('gatepass.gatepass_id', 'desc')
                        ->select('users.*', 'gatepass.*')
                        ->get();

        return response()->json($data);
    }

    public function getLeaves($employee_id, $year){
        $data = DB::table('employee_leaves')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'employee_leaves.leave_type_id')
                        ->where('employee_leaves.employee_id', '=', $employee_id)
                        ->where('employee_leaves.year','=', $year)
                        ->get();

        return response()->json($data);
    }

    public function getExams($employee_id){
        $data = DB::table('exams')
                        ->join('examinee', 'examinee.exam_id', '=', 'exams.exam_id')
                        ->join('users', 'examinee.user_id', '=', 'users.id')
                        ->join('exam_group', 'exams.exam_group_id', '=', 'exam_group.exam_group_id')
                        ->where('examinee.user_id', $employee_id)
                        ->orderBy('validity_date','desc')
                        ->orderBy('date_of_exam','desc')
                        ->get();

        return response()->json($data);
    }

    public function getEvaluations($employee_id){
        $data = DB::table('evaluation_files')
                        ->join('users', 'users.user_id', '=', 'evaluation_files.employee_id')
                        ->where('employee_id', $employee_id)
                        ->select('users.employee_name', 'evaluation_files.*', DB::raw('(select employee_name from users where user_id = evaluation_files.evaluated_by) as evaluated_by'))
                        ->orderBy('id', 'desc')
                        ->get();

        return response()->json($data);
    }

    public function refreshAttendance($id){
        $existing_bio = DB::table('biometrics')->where('employee_id', (int)$id)->where('type', '!=', 'adjustment')->select('biometric_id')->get();

        $bio_ids = '0000';

        if (!empty($existing_bio)) {

            foreach ($existing_bio as $bio_id) {
                $bio_ids .= $bio_id->biometric_id.',';
            }

            $bio_ids = rtrim($bio_ids,',');
            $bio_ids = "AND Transactions.[ID] NOT IN (".$bio_ids.")"; 
        }

        $attendance = DB::connection('access')->select('SELECT Transactions.[ID], Transactions.[date], Transactions.[time], Transactions.[SerialNo], Transactions.[TransType], Transactions.[pin], Transactions.[ReceivedDate], Transactions.[ReceivedTime], templates.[FirstName], templates.[LastName], UnitSiteQuery.[UnitName] FROM (Transactions LEFT JOIN UnitSiteQuery ON Transactions.Address = UnitSiteQuery.Address) LEFT JOIN templates ON (Transactions.pin = templates.pin) AND (Transactions.finger = templates.finger) WHERE (Transactions.[TransType] = 7 OR Transactions.[TransType] = 8) AND Transactions.[ID] > 704020 AND Transactions.[pin] = '.$id.' '.$bio_ids.'');

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

    public function overallStatus($timein, $timeout, $datte, $datess, $user_id){
        $time_in = Carbon::parse($timein);
        $time_out =Carbon::parse($timeout);
        $notice = $this->checkNotices($datte, $user_id);
        $notice_id = $notice['notice_id'];
        $notice_status = $notice['status'];
   
        $isHoliday = $this->checkHoliday($datte);

        if ($notice['absence_type']) {
            $status = $notice['absence_type'];
        }elseif (!empty($timein) or !empty($timeout) ) {
            $status = 'Present';
        }elseif ($isHoliday) {
            $status = 'Holiday';
        }elseif ($datess->format('N') == 7) {
            $status = 'Sunday';
        }else{
            $status = 'Unfiled Absence';
        }

        return $status;
    }

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
        $statuss = $this->overallStatus($timein, $timeout, $datte, $datess, $user_id);
        $time_in = Carbon::parse($timein);
        $shift_in =Carbon::parse($shift_timein)->addMinutes((int)$grace_period - 1);

        if (empty($timein)) {
            $late_in_minutes = 0;
        }
        elseif($statuss == 'Half Day Absence'){
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
                'shift_timein' => $this->ShiftSpecial_timein($day, $datte, $user_id),
                'shift_timeout' => $this->ShiftSpecial_timeout($day, $datte, $user_id),
                'graceperiod' => $this->graceperiod($day, $datte, $user_id),
                'bio_date' => $this->biometricsfunc($user_id, $datte),
                'timein' => $this->bioTimein($user_id, $datte),
                'timeout' => $this->bioTimeout($user_id, $datte),
                'location_in' => $this->bioLocin($user_id, $datte),
                'location_out' => $this->bioLocout($user_id, $datte),
            ];
        }
        }else{
            break;
        }
           
            $sortedDesc = array_reverse(array_sort($dates));
        }

        return $sortedDesc;
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

    public function getAttendance(Request $request){
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

        return view('client.modules.human_resource.employees.tables.employee_attendance_table', compact('dates'));
    }
    

}