<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
use App\CalendarEvent;
use App\Examinee;
use App\ExaminationResult;
use App\ExamGroup;
use App\User;
use DateTime;
use DatePeriod;
use DateInterval;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branch_list = DB::table('branch')->get();
        $all_departments = DB::table('departments')->get();
        $absent_type_list = DB::table('leave_types')->get();
        $absence_types = DB::table('leave_types')
            ->where('applied_to_all', '=', 1)->get();

        $regular_shift = DB::table('shifts')
            ->join('users', 'shifts.shift_group_id', '=','users.shift_group_id')
            ->where('user_id', Auth::user()->user_id)
            ->select('shifts.*')->get();

        $emp_item_accountability = DB::table('issued_item')->where('issued_to', Auth::user()->user_id)->get();

        $employee_shifts = DB::table('shifts')->get();

        // departments handled by logged in user / manager
        $handledDepts = $this->getHandledDepts(Auth::user()->user_id);
        $department_list = DB::table('departments')->whereIn('department_id', $handledDepts)->get();

        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $employees_per_dept = $this->getEmployeesPerDept();
        $employees = DB::table('users')->where('user_type', 'Employee')->orderBy('employee_name', 'asc')->get();

        $year= date("Y");
        $leave_types = DB::table('employee_leaves')
            ->join('leave_types', 'leave_types.leave_type_id', '=', 'employee_leaves.leave_type_id')
            ->where('employee_leaves.employee_id', '=', Auth::user()->user_id)
            ->where('employee_leaves.year','=', $year)
            ->select('leave_types.leave_type', 'leave_types.leave_type_id', 'leave_types.applied_to_all', 'employee_leaves.*')
            ->get();

        $pending_notices = DB::table('notice_slip')
            ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
            ->where('status', '=', 'For Approval')
            ->where('user_id', '=', Auth::user()->user_id);

        $pending_notices = $pending_notices->get();
        $pending_notices_count = $pending_notices->count();

        $pending_gatepasses = DB::table('gatepass')
            ->where('status', '=', 'For Approval')
            ->where('user_id', '=', Auth::user()->user_id);

        $pending_gatepasses = $pending_gatepasses->get();
        $pending_gatepasses_count = $pending_gatepasses->count();

        $pending_requests = $pending_notices_count + $pending_gatepasses_count;

        $approvers = DB::table('department_approvers')
            ->join('users', 'users.user_id', '=', 'department_approvers.employee_id')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->where('department_approvers.department_id', '=', Auth::user()->department_id)
            ->select('users.employee_name', 'designation.designation', 'department_approvers.employee_id', 'users.image')
            ->get();

        $awaiting_notices = DB::table('notice_slip')
            ->join('department_approvers', 'department_approvers.department_id', '=', 'notice_slip.dept_id')
            ->where('notice_slip.status', 'For Approval')
            ->where('department_approvers.employee_id', Auth::user()->user_id)
            ->count();

        $awaiting_gatepass = 0;
        $gatepass_approvers = ['Operations Manager', 'President', 'Director of Operations', 'Product Manager', 'Human Resources Head'];
        if (in_array($designation, $gatepass_approvers)) {
            $awaiting_gatepass = DB::table('gatepass')
                ->join('users', 'users.user_id', '=', 'gatepass.user_id')
                ->where('gatepass.status', '=', 'For Approval')
                ->select('gatepass.*', 'users.employee_name')
                ->count();        
        }

        $departmentHeads = DB::table('department_head_list')->distinct()->pluck('employee_id');
        
        $awaiting_approval = $awaiting_notices + $awaiting_gatepass;

        $out_today = DB::table('notice_slip')
            ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
            ->whereDate('notice_slip.date_from', '<=', date("Y-m-d"))
            ->whereDate('notice_slip.date_to', '>=', date("Y-m-d"))
            ->where('notice_slip.status', 'Approved')
            ->select('users.employee_name', 'leave_types.leave_type', 'designation.designation', 'notice_slip.date_from', 'notice_slip.date_to', 'notice_slip.time_from', 'notice_slip.time_to', 'users.image');

        $out_of_office_today = $out_today->get();
        $on_leave_today = $out_today->count();


        $clientexams = Examinee::join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
            ->join('users', 'examinee.user_id', '=', 'users.id')
            ->join('exam_group', 'exams.exam_group_id', '=', 'exam_group.exam_group_id')
            ->select('examinee.*', 'exams.exam_title', 'users.employee_name', 'exam_group.exam_group_description')
            ->where('examinee.user_id',Auth::user()->id)
            ->orderBy('validity_date','desc')
            ->orderBy('date_of_exam','desc')
            ->get();

        $userDept = User::join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('users.department_id', Auth::user()->department_id)
            ->select('department')
            ->first();

        
        $employee_profiles = User::where('users.department_id',Auth::user()->department_id)
            ->join('departments','users.department_id','departments.department_id')
            ->join('designation','users.designation_id','designation.des_id')
            ->select('users.*','departments.department','designation.designation')
            ->orderBy('department')
            ->orderBy('designation')
            ->orderBy('employee_name')
            ->paginate(10);

        $date = new Carbon();
        $date->addDays(93);
        $getholiday = CalendarEvent::whereBetween('holiday_date',[new Carbon(),$date])
            ->orWhere('category', 'Regular Holiday')
            ->whereMonth('holiday_date', '>=', Carbon::now()->format('m'))->whereMonth('holiday_date', '<=', Carbon::now()->addMonth(3)->format('m'))
            ->select('description', DB::raw('DATE_FORMAT(holiday_date, "'.Carbon::now()->format('Y').'-%m-%d") as holiday_date'))->distinct('holiday_date')->orderBy('holiday_date')->get();

        $department_heads= DB::table('department_head_list')
            ->join('departments','department_head_list.department_id','=','departments.department_id')
            ->where('employee_id',Auth::user()->user_id)
            ->get();
        if(!$department_heads->isEmpty()){
          $depart='head';
        }
        else{
          $department_heads= DB::table('users')
            ->join('departments','users.department_id','=','departments.department_id')
            ->where('user_id',Auth::user()->user_id)
            ->get();
          $depart='employee';
        }

        $kpi_schedules = $this->getKpiNextSched();

        $holiday_reminder = 0;
        $department = DB::table('departments')->where('department_id', Auth::user()->department_id)->pluck('department')->first();
        if($department == 'Human Resources'){
            $special_holidays = DB::table('holidays')->where('category', 'Special Holiday')->whereYear('holiday_date', Carbon::now()->format('Y'))->exists();
            $holiday_reminder = !$special_holidays ? 1 : 0;
        }
        
        $reports_to = DB::table('users')->join('designation','users.designation_id','designation.des_id')->where('user_id', Auth::user()->reporting_to)->first();

        return view('client.homepage', compact('branch_list', 'all_departments', 'employee_shifts', 'department_list', 'handledDepts', 'employees', 'absent_type_list', 'designation', 'department', 'regular_shift', 'employees_per_dept', 'leave_types', 'approvers', 'out_of_office_today', 'absence_types', 'on_leave_today', 'awaiting_approval', 'pending_notices', 'pending_notices_count', 'pending_gatepasses', 'pending_gatepasses_count', 'pending_requests', 'clientexams', 'employee_profiles', 'userDept', 'emp_item_accountability','getholiday', 'departmentHeads','department_heads','depart', 'kpi_schedules', 'holiday_reminder', 'reports_to'));

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

    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
    }

    public function showCalendar(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $out_of_office_today = DB::table('notice_slip')
            ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
            ->whereDate('notice_slip.date_from', '<=', date("Y-m-d"))
            ->whereDate('notice_slip.date_to', '>=', date("Y-m-d"))
            ->where('notice_slip.status', 'Approved')
            ->select('users.employee_name', 'leave_types.leave_type', 'designation.designation', 'notice_slip.date_from', 'notice_slip.date_to', 'notice_slip.time_from', 'notice_slip.time_to', 'users.image')
            ->get();

        return view('client.leave_calendar', compact('designation', 'department', 'out_of_office_today'));
    }

    public function getLeaves(){
        $designation = $this->sessionDetails('designation');

        $handledDepts = $this->getHandledDepts(Auth::user()->user_id);

        if (in_array($designation, ['HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President'])) {
            $leaves = DB::table('notice_slip')
                        ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                        ->whereIn('notice_slip.status', ['APPROVED', 'FOR APPROVAL'])
                        ->select('notice_slip.notice_id', 'users.employee_name', 'notice_slip.date_from', 'notice_slip.date_to', 'leave_types.leave_type', 'notice_slip.status')
                        ->get();
        }elseif (count($handledDepts) > 0) {
            $leaves = DB::table('notice_slip')
                        ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                        ->whereIn('users.department_id', $handledDepts)
                        ->whereIn('notice_slip.status', ['APPROVED', 'FOR APPROVAL'])
                        ->select('notice_slip.notice_id', 'users.employee_name', 'notice_slip.date_from', 'notice_slip.date_to', 'leave_types.leave_type', 'notice_slip.status')
                        ->get();
        }else{
            $leaves = DB::table('notice_slip')
                        ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                        ->where('users.department_id', Auth::user()->department_id)
                        ->whereIn('notice_slip.status', ['APPROVED', 'FOR APPROVAL'])
                        ->select('notice_slip.notice_id', 'users.employee_name', 'notice_slip.date_from', 'notice_slip.date_to', 'leave_types.leave_type', 'notice_slip.status')
                        ->get();
        }

        $data = array();
        foreach ($leaves as $leave) {
            $title = $leave->employee_name . ' - ' . $leave->leave_type;
            if ($leave->status == 'FOR APPROVAL') {
                $color = '#F39C12';
            }elseif ($leave->status == 'APPROVED') {
                $color = '#2980B9';
            }else{
                $color = '#BDC3C7';
            }

            $leave_date_to = new DateTime($leave->date_to);
            $leave_date_to->modify('+1 day');
            $leave_date_to = $leave_date_to->format( 'Y-m-d');

            $data[] = array(
                'id'   => $leave->notice_id,
                'title'   => $title,
                'start'   => $leave->date_from,
                'end'   => $leave_date_to,
                'color' => $color
            );
        }

        return response()->json($data);
    }

    public function showForApproval(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $handledDepts = $this->getHandledDepts(Auth::user()->user_id);
        $department_list = DB::table('departments')->get();
        $employees_per_dept = $this->getEmployeesPerDept();
        $employees = DB::table('users')->where('user_type', 'Employee')->orderBy('employee_name', 'asc')->get();
        $absent_type_list = DB::table('leave_types')->get();

        return view('client.for_approval', compact('department_list', 'designation', 'department', 'handledDepts', 'employees', 'absent_type_list', 'employees_per_dept'));
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

    public function showExamPanel(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $departments = DB::table('departments')->get();
        $examgroups = DB::table('exam_group')->get();
        $exam_types = DB::table('exam_type')->get();

        return view('client.exam_panel', compact('designation', 'department', 'examgroups', 'departments', 'exam_types'));
    }

    public function showExams(){
        $exams = DB::table('exams')
                ->join('exam_group','exams.exam_group_id','=','exam_group.exam_group_id')
                ->join('departments','exams.department_id','=', 'departments.department_id', 'left outer')
                ->select('exams.*','departments.department','exam_group.exam_group_description')
                ->orderBy('exams.exam_id','desc')
                ->get();

        $departments = DB::table('departments')->get();
        $examgroups = DB::table('exam_group')->get();
        $exam_types = DB::table('exam_type')->get();
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        return view('client.tab_exams', compact('designation', 'department', 'examgroups', 'departments', 'exam_types', 'exams'));
    }

    public function cancelOnGoingExam($id){
        DB::beginTransaction();
        try {
            $checker = DB::table('examinee')->where('status', 'On Going')->where('examinee_id', $id)->first();
            if(!$checker){
                return response()->json(['success' => false, 'message' => 'Exam has already been canceled.']);
            }

            DB::table('examinee')->where('examinee_id', $id)->update([
                'start_time' => null,
                'end_time' => null,
                'duration' => null,
                'time_spent' => null,
                'date_taken' => null,
                'status' => 'Not Started',
                'remaining_time' => null,
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Exam Canceled.']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'An error occured. Please try again later.']);
        }
    }

    public function showExaminees(Request $request){
        $examgroups = DB::table('exam_group')->get();
        $exam_types = DB::table('exam_type')->get();
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $exams = DB::table('exams')->orderBy('department_id')->get();
        $examinees = DB::table('examinee')
            ->join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
            ->join('users', 'examinee.user_id', '=', 'users.id')
            ->where('examinee.status', '!=', 'On Going')
            ->select('examinee.*', 'exams.exam_title', 'users.employee_name', 'users.user_type')
            ->orderBy('examinee.examinee_id', 'desc')
            ->get();

        $examDepts = DB::table('exams')->select('department_id')->distinct('department_id')->get();
        
        $deptIDs = [null];
        foreach($examDepts as $edept){
            $hasUsers = User::where('department_id',$edept->department_id)->get();
            if(count($hasUsers) > 0){
                array_push($deptIDs, $edept->department_id);
            }
        }
        $users = User::whereIn('department_id',$deptIDs)->orWhereNull('department_id')->orderBy('department_id')->get();
        $departments = DB::table('departments')->whereIn('department_id',$deptIDs)->orderBy('department_id')->get();

        $data = [
            'exams' => $exams,
            'users' => $users,
            'examinees' => $examinees,
            'departments' => $departments
        ];

        if ($request->ajax()) {
            $examinees = DB::table('examinee')
                ->join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
                ->join('users', 'examinee.user_id', '=', 'users.id')
                ->select('examinee.*', 'exams.exam_title', 'users.employee_name', 'users.user_type')
                ->where('examinee.status', 'On Going')->get();

            return view('client.tab_examinees_tbl', compact('examinees'));
        }

        return view('client.tab_examinees', compact('designation', 'department', 'examgroups', 'departments', 'exam_types', 'examinees', 'exams', 'users'));
    }

    public function showExaminationReport(Request $request){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $exam_results = ExaminationResult::join('examinee','examination_result.examinee_id','examinee.examinee_id')
                                        ->join('users','examinee.user_id','users.id')
                                        ->join('exams','examinee.exam_id','exams.exam_id')
                                        ->join('exam_group','exams.exam_group_id', 'exam_group.exam_group_id')
                                        ->select('examination_result.*','exam_group.exam_group_description','exams.exam_title','users.employee_name','examinee.date_taken', 'users.user_type');

        if ($request->ajax()) {
            if ($request->exam_date) {
                $exam_results = $exam_results->whereDate('examinee.date_taken', $request->exam_date);
            }

            if ($request->user_type) {
                $exam_results = $exam_results->where('users.user_type', $request->user_type);
            }

            $exam_results = $exam_results->orderBy('examinee.date_taken', 'desc')->get();

            return response()->json($exam_results);
        }

        $exam_results = $exam_results->orderBy('examinee.date_taken', 'desc')->get();

        return view('client.tab_examination_report', compact('designation', 'department', 'exam_results'));
    }

    public function getEvaluations(Request $request){
        $designation = $this->sessionDetails('designation');

        $files = DB::table('evaluation_files')
            ->join('users', 'users.user_id', '=', 'evaluation_files.employee_id');
                    
        if (!in_array($designation, ['Human Resources Head', 'Director of Operations', 'President'])) {
            $files = $files->where('evaluation_files.employee_id', Auth::user()->user_id);
        }
        
        $files = $files->select('users.employee_name', 'evaluation_files.*', DB::raw('(select employee_name from users where user_id = evaluation_files.evaluated_by) as evaluated_by'))
            ->orderBy('id', 'desc')->paginate(8);

        return view('client.tables.evaluation_table', compact('files', 'designation'))->render();
    }
    public function forBranch(Request $request)
    {
        //         $branch = DB::table('branch')
        //            ->join('users', 'users.branch', '=','branch.branch_id')
        //            ->where('user_id', Auth::user()->user_id)
        //            ->first();

        // return view('client.userprofile', compact('branch'));
        // // dd($branch);\

        $date = new Carbon();
        $date->addDays(93);
        $getholiday= CalendarEvent::whereBetween('holiday_date',[new Carbon(),$date])->get();
        return $getholiday;
        
    }

    public function getWorkingDays(){
        $date = new DateTime('first day of previous month');
        $datee = new DateTime('last day of previous month');
        $begin = $date->format('Y-m-d');
        $endd = $datee->format('Y-m-d');
        $start = new DateTime($begin);
        $end = new DateTime($endd);
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
    public function getPerfectAttendance(){

        $from_date = Carbon::parse('first day of previous month')->format('Y-m-d');
        $to_date = Carbon::parse('last day of previous month')->format('Y-m-d');
        $user_id=Auth::user()->user_id;

        $attendance_dates = DB::table('biometrics')->where('employee_id',(int)$user_id)
                ->whereBetween('bio_date', [$from_date, $to_date])->select('bio_date')
                ->distinct('bio_date')->count('bio_date');

        return $attendance_dates;
    }
    public function getTotalDaysPresent(){
        $date = new DateTime('first day of previous month');
        $datee = new DateTime('last day of previous month');
        $from_date = $date->format('Y-m-d');
        $to_date = $datee->format('Y-m-d');

        $days_present = DB::table('biometrics')->where('employee_id', Auth::user()->user_id)
                ->whereBetween('bio_date', [$from_date, $to_date])->select('bio_date')
                ->distinct('bio_date')->count('bio_date');

        return $days_present;
    }
    public function getLatesInMins($time_in, $shift_in, $grace){
        $late_in_mins = 0;
        if ($time_in != 0) {
            $time_in = Carbon::parse($time_in);
            $shift_in = Carbon::parse($shift_in)->addMinutes((int)$grace);

            if ($time_in > $shift_in) {
                $late_in_mins = $time_in->diffInMinutes($shift_in);
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
    public function getBiometricLogs(){
        $user_id=Auth::user()->user_id;
        $employee_details = DB::table('users')->where('user_id', $user_id)->first();
        $shift_group = $employee_details->shift_group_id;
        $from_date = Carbon::parse('first day of previous month')->format('Y-m-d');
        $to_date = Carbon::parse('last day of previous month')->format('Y-m-d');
        $biometric = DB::table('biometric_logs')
            ->where('user_id', (int)$user_id)
            ->whereBetween('transaction_date', [$from_date, $to_date])
            ->orderBy('transaction_date', 'desc')
            ->get()
            ->chunk(10);

        $data = [];
        foreach ($biometric as $row) {
            foreach ($row as $d) {
                $date= date("l", strtotime($d->transaction_date));
                if ($date !== 'Sunday') {
                    $shift_details = $this->getShiftSchedule($d->transaction_date, $shift_group);
                    // dd($shift_details);
                    $late_in_mins = $this->getLatesInMins($d->time_in, $shift_details->time_in, $shift_details->grace_period_in_mins);
                    $data[] = [
                        'bio_date' => $d->transaction_date,
                        'time_in' => $d->time_in,
                        'time_out' => $d->time_out,
                        'locin' => $d->location_in,
                        'locout' => $d->location_out,
                        'late_in_mins' => $late_in_mins
                    ];
                }
            }
        }

        return $data;
    }
    public function getprofile(Request $request){
    $detail = DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', Auth::user()->user_id)
            ->first();

        $designation = $detail->designation;
        $department = $detail->department;
        $biodata= $this->getBiometricLogs();
        $late_in_minutess = collect($biodata)->sum('late_in_mins');
        $workingdays= $this->getWorkingDays();
        
        $presentdays= collect($biodata)->count('bio_date');
        $absent= $workingdays - $presentdays;
        $compute=($presentdays / $workingdays)* 100;
    
        $lastmonthname = \Carbon\Carbon::now();
        $month= $lastmonthname->subMonth()->format('F');

        $round_compute=(round($compute, 2));
        $absentrate= 100 - $compute;
        $round_absent=(round($absentrate, 2));
        return view('client.userprofile', compact('round_compute','designation','department','workingdays','round_absent','late_in_minutess','absent','month'));
    }

    // get reports for submission for the next scheduled date
    public function getKpiNextSched(){
        $eval_sched = DB::table('evaluation_schedule')->where('is_active', 1)->get();

        $kpi_schedules = [];
        foreach ($eval_sched as $row) {
            $d1 = Carbon::parse($row->start_date)->format('Y-m-d');
            $d2 = Carbon::parse('last day of December '.$row->year)->format('Y-m-d');

            $s_date = new DateTime($d1);
            $e_date = new DateTime($d2);
            $e_date->modify('+1 day');

            if ($row->period == 'Monthly') {
                $interval = 'P1M';
            }elseif ($row->period == 'Quarterly') {
                $interval = 'P3M';
            }elseif ($row->period == 'Semi-Annual') {
                $interval = 'P6M';
            }elseif ($row->period == 'Annual') {
                $interval = 'P1Y';
            }

            $range = new DatePeriod($s_date, new DateInterval($interval), $e_date);
            $schedules = [];
            $sched = null;
            foreach ($range as $dd) {
                $schedules[] = [$dd->format('Y-m-d')];
                if ($dd->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                    $next_schedule = $dd->format('Y-m-d');
                    $incoming_sched_date = Carbon::now()->addDays(3)->format('Y-m-d');
                    if ($next_schedule <= $incoming_sched_date) {
                        $sched = Carbon::parse($next_schedule)->format('F d, Y');
                        array_push($kpi_schedules, array($row->period, $sched));
                    }
                }
            }

            // $kpi_schedules[] = [
            //     // 'period' => $row->period,
            //     // 'schedules' => $schedules,
            //     // 'next_schedule' => 
            //     $sched
            // ];
        }

        return $kpi_schedules;
    }

    public function systemUnderMaitenance(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        return view('client.under_maintenance', compact('designation', 'department'));
    }
}