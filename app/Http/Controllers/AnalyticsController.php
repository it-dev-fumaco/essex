<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
    }

    public function showAttendanceAnalytics(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $active_employees = DB::table('users')->where('user_type', 'Employee')->where('status', 'Active')->count();
        $present_today = DB::table('biometrics')->where('bio_date', date('Y-m-d'))->distinct()->count('employee_id');
        $out_today = DB::table('notice_slip')
                        ->whereDate('notice_slip.date_from', '<=', date("Y-m-d"))
                        ->whereDate('notice_slip.date_to', '>=', date("Y-m-d"))
                        ->where('notice_slip.status', 'Approved')->distinct()->count('user_id');

        $totals = [
            'active_employees' => $active_employees,
            'present_today' => $present_today,
            'out_today' => $out_today,
            'late_today' => 0,
        ];

        return view('client.modules.analytics.attendance_analytics', compact('designation', 'department', 'totals'));
    }

    public function showHrAnalytics(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $users = DB::table('users')->get();

        $total_employees = collect($users)->where('user_type', 'Employee')->where('status', 'Active')->count();
        $total_regular = collect($users)->where('user_type', 'Employee')->where('status', 'Active')->where('employment_status', 'Regular')->count();
        $total_contractual = collect($users)->where('user_type', 'Employee')->where('status', 'Active')->where('employment_status', 'Contractual')->count();
        $total_probationary = collect($users)->where('user_type', 'Employee')->where('status', 'Active')->where('employment_status', 'Probationary')->count();

        $total_contractual_probationary = $total_contractual + $total_probationary;

        $total_applicants = collect($users)->where('user_type', 'Applicant')->count();
        $total_hired = collect($users)->where('applicant_status', 'Hired')->count();
        $total_declined = collect($users)->where('applicant_status', 'Declined')->count();
        $total_not_qualified = collect($users)->where('applicant_status', 'Not Qualified')->count();

        $totals = [
            'applicants' => $total_applicants,
            'hired' => $total_hired,
            'declined' => $total_declined,
            'not_qualified' => $total_not_qualified,
            'employees' => $total_employees,
            'regular' => $total_regular,
            'contractual_probationary' => $total_contractual_probationary,
        ];

        return view('client.modules.analytics.hr_analytics', compact('designation', 'department', 'totals'));
    }

    public function showGatepassAnalytics(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $gatepass = DB::table('gatepass')->where('status', 'APPROVED')->get();

        $total_gatepass = $gatepass->count();
        $total_unreturned = $gatepass->where('item_type', '=', 'Returnable')->where('item_status', '=', 'Unreturned')->count();
        $total_pending = $gatepass->where('status', 'FOR APPROVAL')->count();

        $totals = [
            'gatepass' => $total_gatepass,
            'unreturned_items' => $total_unreturned,
            'pending' => $total_pending
        ];

        return view('client.modules.analytics.gatepass_analytics', compact('designation', 'department', 'totals'));
    }

    public function showNoticesAnalytics(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $absent_slips = DB::table('notice_slip')->get();
        $department_list = DB::table('departments')->get();

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

        return view('client.modules.analytics.notices_analytics', compact('designation', 'department', 'totals', 'department_list'));
    }

    public function employeesPerfectAttendance(Request $request){
        $from_date = new Carbon('first day of '. $request->month .' '. $request->year); 
        $to_date = new Carbon('last day of '. $request->month .' '. $request->year); 

        return $this->getPerfectAttendance($from_date, $to_date);
    }

    public function employeesUnfiledAbsences(Request $request){
        $from_date = new Carbon('first day of '. $request->month .' '. $request->year); 
        $to_date = new Carbon('last day of '. $request->month .' '. $request->year); 

        $employees_with_perfect_attendance = $this->getPerfectAttendance($from_date, $to_date);

        $excluded_employees = array_column($employees_with_perfect_attendance, 'employee_id');

        $employees_list = DB::table('users')
                ->where('user_type', 'Employee')
                ->where('status', 'Active')
                ->whereNotIn('user_id', $excluded_employees)
                ->get()->chunk(20);

        $employees_unfiled_absences = [];
        foreach ($employees_list as $row) {
            foreach ($row as $d) {
                $unfiled_absences = $this->getUnfiledAbsences($d->user_id, $from_date, $to_date);
                if (count($unfiled_absences['summary']) > 0) {
                    $employees_unfiled_absences[] = $unfiled_absences['summary'];
                }
            }
        }

        $sorted_data = collect($employees_unfiled_absences)->sortBy('total')->reverse();

        return $sorted_data->values()->all();
    }
}