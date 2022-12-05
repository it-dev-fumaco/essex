<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail_notice;
use DateTime;
use App\AbsentNotice;
use Auth;
use DB;
use DatePeriod;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Pagination\LengthAwarePaginator;

class AbsentNoticesController extends Controller
{
    public function noticesForApproval(Request $request){
        if ($request->ajax()) {
            $user = Auth::user()->user_id;
            $pending_notices = DB::table('notice_slip')
                ->join('users', 'notice_slip.user_id', '=', 'users.user_id')
                ->join('departments', 'departments.department_id', '=', 'users.department_id')
                ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                ->whereIn('users.department_id',function($query) use ($user){
                    $query->select('department_id')->from('department_approvers')->where('employee_id', $user);
                })
                ->where('notice_slip.status', 'For Approval')
                ->select('users.employee_name', 'notice_slip.*', 'departments.department', 'leave_types.leave_type')
                ->paginate(8);

            return view('client.tables.notices_for_approval_table', compact('pending_notices'))->render();
        }
    }

    public function store(Request $request){
        if (in_array($request->absence_type, [1, 2, 3, 4,7])) {
            $date_from = Carbon::parse($request->date_from);
            $date_to = Carbon::parse($request->date_to);
            $diff_in_days = $date_to->diffInDays($date_from);
            $duration = $diff_in_days + 1;
        }else{
            $time_from = Carbon::parse($request->time_from);
            $time_to = Carbon::parse($request->time_to);
            $diff_in_hours = $time_to->diffInMinutes($time_from);
            $duration = ($diff_in_hours / 60) * 0.125;
        }

        // get number of days absent
        $fdate = $request->date_from;
        $tdate = $request->date_to;
        $datetime1 = new DateTime($fdate);
        $datetime2 = new DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        $days = $days + 1;

        // get total & remaining number of leaves
        $year= date("Y");
        $leave_type = DB::table('employee_leaves')
                        ->where('leave_type_id', '=', $request->absence_type)
                        ->where('employee_id', '=', $request->user_id)
                        ->where('employee_leaves.year','=', $year)
                        ->select('employee_leaves.*')
                        ->first();
       
        // subtract number of days absent from total
        if (isset($leave_type->remaining)) {
                $remaining = $leave_type->remaining - $days;

                // update remaining number of leaves
                $employee_leave = DB::table('employee_leaves')
                        ->where('leave_id', '=', $leave_type->leave_id)
                        ->where('employee_id', '=', $request->user_id)
                        ->where('employee_leaves.year','=', $year)
                        ->update([
                            'remaining' => $remaining
                        ]);
        }

        $notice_slip = new AbsentNotice;
        $notice_slip->user_id = $request->user_id;
        $notice_slip->dept_id = $request->department;
        $notice_slip->leave_type_id = $request->absence_type;
        $notice_slip->date_from = $request->date_from;
        $notice_slip->date_to = $request->date_to;
        $notice_slip->time_from = $request->time_from;
        $notice_slip->time_to = $request->time_to;
        $notice_slip->means = $request->means;
        $notice_slip->reason = $request->reason;
        $notice_slip->time_reported = $request->time_reported;
        $notice_slip->info_by = $request->info_by;
        $notice_slip->status = 'FOR APPROVAL';
        $notice_slip->remarks = $request->remarks;
        $notice_slip->created_by = Auth::user()->employee_name;
        $notice_slip->duration = $duration;
        $notice_slip->save();

       //  $viewdetails =DB::table('notice_slip')
       //      ->join('leave_types','leave_types.leave_type_id','=','notice_slip.leave_type_id')
       //      ->select('notice_slip.*', 'leave_types.leave_type')
       //      ->orderBy('notice_id', 'desc')
       //      ->where('user_id', Auth::user()->user_id)
       //      ->first();

       //  $notice_id= $viewdetails->notice_id;

       //  $data = array(
       //      'employee_name'      =>  Auth::user()->employee_name,
       //      'year'               => now()->format('Y'),
       //      'slip_id'            => $notice_id
       //  );

       // $leave_appprover= DB::table('department_approvers')
       //              ->join('users', 'users.user_id', '=', 'department_approvers.employee_id')
       //              ->where('department_approvers.department_id',  Auth::user()->department_id)
       //              ->select('department_approvers.*','users.email')
       //              ->get();

       //  foreach ($leave_appprover as $row) {
       //      Mail::to($row->email)->send(new SendMail_notice($data));
       //  }

        return response()->json(['message' => 'Absent Notice Slip no. <b>' . $notice_slip->notice_id . '</b>']);
    }

    public function updateNoticeDetails(Request $request){
        $notice_slip = AbsentNotice::find($request->notice_id);
        $notice_slip->leave_type_id = $request->absence_type;
        $notice_slip->date_from = $request->date_from;
        $notice_slip->date_to = $request->date_to;
        $notice_slip->time_from = $request->time_from;
        $notice_slip->time_to = $request->time_to;
        $notice_slip->means = $request->means;
        $notice_slip->reason = $request->reason;
        $notice_slip->time_reported = $request->time_reported;
        $notice_slip->info_by = $request->info_by;
        $notice_slip->last_modified_by = Auth::user()->employee_name;
        $notice_slip->save();

        return response()->json(['message' => 'Absent Notice no. <b>' . $notice_slip->notice_id . '</b> has been updated.']);
    }

    public function cancelNotice(Request $request){
        $notice_slip = AbsentNotice::find($request->notice_id);
        $notice_slip->status = 'CANCELLED';
        $notice_slip->last_modified_by = Auth::user()->employee_name;
        $notice_slip->save();

        // get number of days absent
        $fdate = $request->date_from;
        $tdate = $request->date_to;
        $datetime1 = new DateTime($fdate);
        $datetime2 = new DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        $days = $days + 1;

        // get total & remaining number of leaves
        $year= date("Y");
        $leave_type = DB::table('employee_leaves')
                        ->where('leave_type_id', '=', $request->leave_type)
                        ->where('employee_id', '=', $request->user_id)
                        ->where('employee_leaves.year','=', $year)
                        ->select('employee_leaves.*')
                        ->first();
       
        // subtract number of days absent from total
        if (isset($leave_type->remaining)) {
            $remaining = $leave_type->remaining + $days;

            // update remaining number of leaves
            $employee_leave = DB::table('employee_leaves')
                    ->where('leave_id', '=', $leave_type->leave_id)
                    ->where('employee_id', '=', $request->user_id)
                    ->where('employee_leaves.year','=', $year)
                    ->update([
                        'remaining' => $remaining
                    ]);
        }
       
        // subtract number of days absent from total
        if (isset($leave_type->remaining)) {
            $remaining = $leave_type->remaining + $days;

            // update remaining number of leaves
            $employee_leave = DB::table('employee_leaves')
                    ->where('leave_id', '=', $leave_type->leave_id)
                    ->where('employee_id', '=', $request->user_id)
                    ->update([
                        'remaining' => $remaining
                    ]);
        }
   
        return response()->json(['message' => 'Absent Notice no. <b>' . $notice_slip->notice_id . '</b> has been cancelled.']);
    }

    public function cancelNotice_per_employee(Request $request){
        $notice_slip = AbsentNotice::find($request->notice_id);
        $notice_slip->status = 'CANCELLED';
        $notice_slip->last_modified_by = Auth::user()->employee_name;
        $notice_slip->save();

        // get number of days absent
        $fdate = $request->date_from;
        $tdate = $request->date_to;
        $datetime1 = new DateTime($fdate);
        $datetime2 = new DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        $days = $days + 1;

        // get total & remaining number of leaves
        $year= date("Y");
        $leave_type = DB::table('employee_leaves')
                        ->where('leave_type_id', '=', $request->leave_id)
                        ->where('employee_id', '=', $request->user_id)
                        ->where('employee_leaves.year','=', $year)
                        ->select('employee_leaves.*')
                        ->first();
       
        // subtract number of days absent from total
        if (isset($leave_type->remaining)) {
            $remaining = $leave_type->remaining + $days;

            // update remaining number of leaves
            $employee_leave = DB::table('employee_leaves')
                    ->where('leave_id', '=', $leave_type->leave_id)
                    ->where('employee_id', '=', $request->user_id)
                    ->where('employee_leaves.year','=', $year)
                    ->update([
                        'remaining' => $remaining
                    ]);
        }
   
        return response()->json(['message' => 'Absent Notice no. <b>' . $notice_slip->notice_id . '</b> has been cancelled.']);
    }

    public function updateStatus(Request $request){
        // get number of days absent
        $fdate = $request->date_from;
        $tdate = $request->date_to;
        $datetime1 = new DateTime($fdate);
        $datetime2 = new DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        $days = $days + 1;

        // get total & remaining number of leaves
        $year= date("Y");

        // get total & remaining number of leaves
        $leave_type = DB::table('employee_leaves')
                        ->where('leave_type_id', '=', $request->leave_type)
                        ->where('employee_id', '=', $request->user_id)
                        ->where('employee_leaves.year','=', $year)
                        ->select('employee_leaves.*')
                        ->first();
       
        // subtract number of days absent from total
        if (isset($leave_type->remaining)) {
            if ($request->status == 'CANCELLED') {
                $remaining = $leave_type->remaining + $days;

            // update remaining number of leaves
            $employee_leave = DB::table('employee_leaves')
                    ->where('leave_id', '=', $leave_type->leave_id)
                    ->where('employee_id', '=', $request->user_id)
                    ->where('employee_leaves.year','=', $year)
                    ->update([
                        'remaining' => $remaining
                    ]);
            }elseif($request->status == 'DISAPPROVED') {
                $remaining = $leave_type->remaining + $days;

                // update remaining number of leaves
                $employee_leave = DB::table('employee_leaves')
                        ->where('leave_id', '=', $leave_type->leave_id)
                        ->where('employee_id', '=', $request->user_id)
                        ->where('employee_leaves.year','=', $year)
                        ->update([
                            'remaining' => $remaining
                        ]); 
            }  
    
        }

        // update status of notice slip
        $notice_slip = AbsentNotice::find($request->notice_id);
        $notice_slip->status = $request->status;
        $notice_slip->remarks = $request->remarks;
        $notice_slip->approved_by = $request->approved_by;
        $notice_slip->approved_date = date('Y-m-d H:i:s');
        $notice_slip->last_modified_by = Auth::user()->employee_name;
        $notice_slip->save();

        return response()->json(['message' => 'Absent Notice Slip no. <b>' . $notice_slip->notice_id . '</b> has been <b>' . $notice_slip->status. '</b>.']);
    }

    public function showLeaveCalendar(){
        return view('admin.leave_calendar');
    }

    public function employeeLeaves(){
        $leaves = DB::table('notice_slip')
                        ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                        ->select('notice_slip.notice_id', 'notice_slip.date_from',  'notice_slip.date_to', 'leave_types.leave_type', 'users.employee_name', 'leave_types.color_legend')
                        ->get();

        $data = array();
        foreach ($leaves as $leave) {
            $title = $leave->employee_name . ' - ' . $leave->leave_type;
            $data[] = array(
                'id'   => $leave->notice_id,
                'title'   => $title,
                'start'   => $leave->date_from,
                'end'   => $leave->date_to,
                'color' => $leave->color_legend
            );
        }

        return response()->json($data);
    }

    public function employeeBirthdates(){
        $leaves = DB::table('notice_slip')
                        ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                        ->select('notice_slip.notice_id', 'notice_slip.date_from',  'notice_slip.date_to', 'leave_types.leave_type', 'users.employee_name', 'leave_types.color_legend')
                        ->get();

        $data = array();
        foreach ($leaves as $leave) {
            $title = $leave->employee_name . ' - ' . $leave->leave_type;
            $data[] = array(
                'id'   => $leave->notice_id,
                'title'   => $title,
                'start'   => $leave->date_from,
                'end'   => $leave->date_to,
                'color' => $leave->color_legend
            );
        }

        return response()->json($data);
    }

    public function fetchNotices(Request $request){
        if ($request->ajax()) {
            $notice_slips = DB::table('notice_slip')
                        ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
                        ->join('departments', 'users.department_id', '=', 'departments.department_id')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                        ->where('notice_slip.user_id', '=', Auth::user()->user_id)
                        ->orderBy('notice_slip.notice_id', 'desc')
                        ->select('users.*', 'notice_slip.*', 'departments.department', 'leave_types.leave_type')
                        ->paginate(8);

            $absence_types = DB::table('leave_types')
                        ->where('applied_to_all', '=', 1)
                        ->get();

            $leave_types = DB::table('employee_leaves')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'employee_leaves.leave_type_id')
                        ->select('leave_types.leave_type', 'leave_types.leave_type_id', 'employee_leaves.*')
                        ->where('employee_leaves.employee_id', '=', Auth::user()->user_id)
                        ->get();

            return view('client.tables.notices_table', compact('notice_slips', 'absence_types', 'leave_types'))->render();
        }
    }

    public function getNoticeDetails(Request $request){
        $notice_slip = DB::table('notice_slip')
            ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
            ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('notice_slip.notice_id', $request->id)
            ->select('notice_slip.*', 'users.employee_name', 'leave_types.leave_type', 'departments.department', DB::raw("(SELECT employee_name FROM users WHERE user_id = notice_slip.approved_by) as approved_by"))
            ->first();

        $data = [
            'date_from' => $notice_slip->date_from,
            'date_to' => $notice_slip->date_to,
            'leave_type_id' => $notice_slip->leave_type_id,
            'user_id' => $notice_slip->user_id,
            'notice_id' => $notice_slip->notice_id,
            'employee_name' => $notice_slip->employee_name,
            'department' => $notice_slip->department,
            'leave_type' => $notice_slip->leave_type,
            'time_from' => $notice_slip->time_from,
            'time_to' => $notice_slip->time_to,
            'means' => $notice_slip->means,
            'time_reported' => $notice_slip->time_reported,
            'info_by' => $notice_slip->info_by,
            'approved_by' => $notice_slip->approved_by,
            'approved_date' => Carbon::parse($notice_slip->approved_date)->format('M. d, Y h:i A'),
            'reason' => $notice_slip->reason,
            'remarks' => $notice_slip->remarks,
            'status' => $notice_slip->status,
            'date_filed' => Carbon::parse($notice_slip->date_filed)->format('M. d, Y h:i A'),
        ];

        return response()->json($data);
    }

    public function getAbsentToday(Request $request){
        if ($request->ajax()) {
            $startDateFilter = new DateTime($request->start);
            $endDateFilter = new DateTime($request->end);
            $endDateFilter->modify('+1 day');

            $period = new DatePeriod($startDateFilter, new DateInterval( 'P1D' ), $endDateFilter);

            $filterDates = [];
            $count = 0;

            foreach($period as $dates ){
                $filterDates[] = [
                    'dates' => $dates->format( 'Y-m-d')
                ];
            }

            $filterDates = array_column($filterDates, 'dates');

            $out_today = DB::table('notice_slip')
                            ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
                            ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                            ->where('notice_slip.status', 'APPROVED')
                            ->get();

            $absentToday = [];

            foreach ($out_today as $absent) {
            
                $absentStartDate = new DateTime($absent->date_from);
                $absentEndDate = new DateTime($absent->date_to);
                $absentEndDate->modify('+1 day');

                $absentPeriod = new DatePeriod($absentStartDate, new DateInterval( 'P1D' ), $absentEndDate);

                foreach($absentPeriod as $absentDate ){
                    if (in_array($absentDate->format( 'Y-m-d'), $filterDates)){
                        // $count = 'found';
                        $absentToday[] = array(
                            'id' => $absent->notice_id,
                            'employee_name' => $absent->employee_name,
                            'leave_type' => $absent->leave_type,
                            'date_from' => $absent->date_from,
                            'date_to' => $absent->date_to,
                            'time_from' => $absent->time_from,
                            'time_to' => $absent->time_to,
                        );
                        break;
                    }
                }
            }
            return view('client.tables.out_office_table', compact('absentToday'))->render();
        }
    }

    public function getAbsentNotices(Request $request){
        // if ($request->ajax()) {
            $startDateFilter = new DateTime($request->start);
            $endDateFilter = new DateTime($request->end);
            $endDateFilter->modify('+1 day');

            $period = new DatePeriod($startDateFilter, new DateInterval( 'P1D' ), $endDateFilter);

            $filterDates = [];
            $notices = [];

            foreach($period as $dates ){
                $filterDates[] = [
                    'dates' => $dates->format( 'Y-m-d')
                ];
            }

            $filterDates = array_column($filterDates, 'dates');

            $absentNotices = DB::table('notice_slip');
            if ($request->employee) {
                $absentNotices = $absentNotices->where('user_id', $request->employee);
            }
            if ($request->leave_type) {
                $absentNotices = $absentNotices->where('leave_type_id', $request->leave_type);
            }
            if ($request->department) {
                $absentNotices = $absentNotices->where('dept_id', $request->department);
            }
            $absentNotices = $absentNotices->get();

            foreach ($absentNotices as $absent) {
                $absentStartDate = new DateTime($absent->date_from);
                $absentEndDate = new DateTime($absent->date_to);
                $absentEndDate->modify('+1 day');

                $absentPeriod = new DatePeriod($absentStartDate, new DateInterval( 'P1D' ), $absentEndDate);

                foreach($absentPeriod as $absentDate ){
                    if (in_array($absentDate->format( 'Y-m-d'), $filterDates)){
                        $notices[] = [
                            'id' => $absent->notice_id];
                        break;
                    }
                }
            }

            $notice_ids = array_column($notices, 'id');

            $details = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

            $designation = $details->designation;

            if (in_array($designation, ['Human Resources Head', 'Director of Operations'])) {
                $departments = DB::table('departments')->get();
            }else{
                $departments = DB::table('department_approvers')->where('employee_id', Auth::user()->user_id)->get();
            }

            $depts = [];
            
            foreach ($departments as $row) {
                $depts[] = [
                    'department' => $row->department_id];
            }

            $filteredNotices = DB::table('notice_slip')
                            ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
                            ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                            ->whereIn('notice_id', $notice_ids);

            $filteredManagerNotices = DB::table('notice_slip')
                            ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
                            ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                            ->whereIn('notice_id', $notice_ids)
                            ->where('notice_slip.user_id', Auth::user()->user_id)
                            ->select('users.employee_name', 'notice_slip.*', 'leave_types.leave_type');

            if ($departments->count() > 0) {
                $depts = array_column($depts, 'department');
                $filteredNotices = $filteredNotices->whereIn('users.department_id', $depts);
                if (!in_array(Auth::user()->department_id, $depts)) {
                    $filteredNotices = $filteredNotices->union($filteredManagerNotices);
                }
            }else{
                $filteredNotices = $filteredNotices->where('notice_slip.user_id', Auth::user()->user_id);
            }
            
            $filteredNotices = $filteredNotices->select('users.employee_name', 'notice_slip.*', 'leave_types.leave_type')
                            ->orderBy('notice_id', 'desc')
                            ->paginate(10);

            return view('client.tables.manage_notices_table', compact('filteredNotices'))->render();
        // }
    }

    public function printNotice(Request $request, $id){
        $notice = AbsentNotice::join('users', 'users.user_id', '=', 'notice_slip.user_id')
                            ->join('departments', 'departments.department_id', '=', 'users.department_id')
                            ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                            ->select('users.employee_name', 'notice_slip.*', 'departments.department', 'leave_types.leave_type', DB::raw("(SELECT employee_name FROM users WHERE user_id = notice_slip.approved_by) as approved_by"), DB::raw("(SELECT designation FROM users JOIN designation ON designation.des_id = users.designation_id WHERE user_id = notice_slip.approved_by) as appr_designation"))
                            ->find($id);

        return view('client.print.printNotice', compact('notice'));
    }

    public function countPendingNotices(Request $request){
        if ($request->ajax()) {
           $count_pending_notices = DB::table('notice_slip')
                                    ->join('users', 'notice_slip.user_id', '=', 'users.user_id')
                                    ->join('departments', 'departments.department_id', '=', 'users.department_id')
                                    ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                                    ->join('department_approvers', 'department_approvers.department_id', '=', 'notice_slip.dept_id')
                                    ->where('notice_slip.status', 'FOR APPROVAL')
                                    ->where('department_approvers.employee_id', Auth::user()->user_id)
                                    ->count();

            return $count_pending_notices;
        }
    }

    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
    }

    public function showAnalytics(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $department_list = DB::table('departments')->get();

        $absent_slips = DB::table('notice_slip')->get();

        $total_approved = $absent_slips->where('status', 'APPROVED')->count();
        $total_disapproved = $absent_slips->where('status', 'DISAPPROVED')->count();
        $total_cancelled = $absent_slips->where('status', 'CANCELLED')->count();
        $total_pending = $absent_slips->where('status', 'FOR APPROVAL')->count();

        $totals = [
            'total_approved' => $total_approved,
            'total_disapproved' => $total_disapproved,
            'total_cancelled' => $total_cancelled,
            'total_pending' => $total_pending,
        ];

        return view('client.modules.absent_notice_slip.analytics', compact('designation', 'department', 'totals', 'department_list'));
    }

    public function leaveTypeStats(Request $request){
        $leave_types = DB::table('leave_types')->get();

        $absent_notices = DB::table('notice_slip')
                ->select(DB::raw('MONTH(IFNULL(date_from_converted, date_from)) AS month, YEAR(IFNULL(date_from_converted, date_from)) AS year'))
                ->where('status', 'APPROVED')
                ->having('month', $request->month)
                ->having('year', $request->year)
                ->get();

        $total_notices = $absent_notices->count();

        $data = [];
        foreach ($leave_types as $row) {
            $notices = DB::table('notice_slip')
                    ->select(DB::raw('MONTH(IFNULL(date_from_converted, date_from)) AS month, YEAR(IFNULL(date_from_converted, date_from)) AS year'))
                    ->where('status', 'APPROVED')
                    ->where('leave_type_id', $row->leave_type_id)
                    ->having('month', $request->month)
                    ->having('year', $request->year)
                    ->get();

            $total_per_leave_type = $notices->count();

            $data[] = [
                'leave_type' => $row->leave_type,
                'total' => $total_per_leave_type,
                'color_legend' => $row->color_legend,
                'percentage' => round(($total_per_leave_type / $total_notices) * 100, 2)
            ];
        }

        return response()->json($data);
    }

    public function absenceRate(Request $request, $year){
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $data = [];
        foreach ($months as $i => $month) {
            $i = $i + 1;
            // get total absent days per month
            $absent_per_month = $this->getAbsentInDays($request->department, null, $month, $month, $year, 'APPROVED');

            $data[] = [
                'month' => $month,
                // 'data' => $absent_per_month
                'total_days' => $absent_per_month['days'],
                'total_users' => $absent_per_month['users'],
            ];
        }

        return response()->json($data);
    }

    public function filterEmployeeLeaveAnalytics(Request $request){
        return redirect('/module/absent_notice_slip/leave_analytics/' . $request->date_from .'/'.$request->date_to);
    }

    public function showNoticeHistory(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $employees_per_dept = $this->getEmployeesPerDept();

        $absent_type_list = DB::table('leave_types')->get();
        $department_list = DB::table('departments')->get();

        $employees = DB::table('users')->where('user_type', 'Employee')->orderBy('employee_name', 'asc')->get();

        $data = ['designation', 'department', 'employees_per_dept', 'employees', 'absent_type_list', 'department_list'];

        return view('client.modules.absent_notice_slip.history', compact($data));
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

    public function leaveAllocationChart(){
        $leaves = DB::table('employee_leaves')->get();

        $filed_vl_in_days = $this->getAbsentInDays(null, 1, 'January', 'December', date('Y'), 'APPROVED');
        $taken_vl_in_days = $filed_vl_in_days['days'] - $filed_vl_in_days['planned'];
        $planned_vl_in_days = $filed_vl_in_days['planned'];
        $unallocated_vl_in_days = $leaves->where('leave_type_id', 1)->sum('remaining');
        $pending_vl_in_days = $this->getAbsentInDays(null, 1, 'January', 'December', date('Y'), 'FOR APPROVAL');

        $filed_sl_in_days = $this->getAbsentInDays(null, 2, 'January', 'December', date('Y'), 'APPROVED');
        $taken_sl_in_days = $filed_sl_in_days['days'] - $filed_sl_in_days['planned'];
        $planned_sl_in_days = $filed_sl_in_days['planned'];
        $unallocated_sl_in_days = $leaves->where('leave_type_id', 2)->sum('remaining');
        $pending_sl_in_days = $this->getAbsentInDays(null, 2, 'January', 'December', date('Y'), 'FOR APPROVAL');

        return [
            'vacation_leave' => [
                'taken' => $taken_vl_in_days,
                'planned' => $planned_vl_in_days,
                'unallocated' => $unallocated_vl_in_days,
                'pending' => $pending_vl_in_days['days'],
            ],
            'sick_leave' => [
                'taken' => $taken_sl_in_days,
                'planned' => $planned_sl_in_days,
                'unallocated' => $unallocated_sl_in_days,
                'pending' => $pending_sl_in_days['days']
            ],
        ];
    }

    public function getAbsentInDays($department, $type, $month1, $month2, $year, $status){
        $notices = DB::table('notice_slip')
                ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                ->where('status', $status);

        if ($department) {
            $notices = $notices->where('notice_slip.dept_id', $department);
        }

        if ($type) {
            $notices = $notices->where('notice_slip.leave_type_id', (int)$type);
        }

        $notices = $notices->get();

        $from_date_filter = new DateTime('first day of ' .$month1 .' '. $year);
        $to_date_filter = new DateTime('last day of ' .$month2 .' '. $year);
        $to_date_filter->modify('+1 day');

        $period = new DatePeriod($from_date_filter, new DateInterval( 'P1D' ), $to_date_filter);

        $dates_from_filter = [];

        foreach($period as $date ){
            $dates_from_filter[] = [
                'date' => $date->format( 'Y-m-d')
            ];
        }

        $data = [];
        $users = [];
        $dates_from_filter = array_column($dates_from_filter, 'date');

        $days = 0;
        $planned = 0;
        foreach ($notices as $row) {
            $absent_from = new DateTime($row->date_from);
            $absent_to = new DateTime($row->date_to);
            $absent_to->modify('+1 day');

            $absence_period = new DatePeriod($absent_from, new DateInterval( 'P1D' ), $absent_to);

            foreach ($absence_period as $absence_date) {
                if (in_array($absence_date->format( 'Y-m-d'), $dates_from_filter)){
                    $dayOfWeek = $absence_date->format( 'N' );
                    if( $dayOfWeek < 7 ){
                        if (!in_array($row->user_id, $users)) {
                            array_push($users, $row->user_id);
                        }
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
                        if ($absence_date->format( 'Y-m-d') > date('Y-m-d')) {
                            if ($status == 'APPROVED') {
                                $planned++;
                            }
                        }
                    }
                }
            }
        }

        return ['planned' => $planned, 'days' => $days, 'users' => collect($users)->count()];
    }
}