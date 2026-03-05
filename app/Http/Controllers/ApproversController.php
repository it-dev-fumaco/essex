<?php

namespace App\Http\Controllers;

use App\Models\Approver;
use Auth;
use DB;
use Illuminate\Http\Request;

class ApproversController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $approvers = DB::table('department_approvers')
            ->join('users', 'department_approvers.employee_id', '=', 'users.user_id')
            ->join('departments', 'departments.department_id', '=', 'department_approvers.department_id')
            ->select('users.employee_name', 'departments.department', 'department_approvers.*')
            ->get();

        $employees = DB::table('users')->get();
        $departments = DB::table('departments')->get();

        return view('admin.approvers', ['approvers' => $approvers, 'employees' => $employees, 'departments' => $departments]);
    }

    public function store(Request $request)
    {
        $approver = new Approver;
        $approver->department_id = $request->department;
        $approver->employee_id = $request->employee;
        $approver->save();

        return redirect('/admin/approvers')->with('message', 'Approver successfully added');
    }

    public function update(Request $request, $id)
    {
        $approver = Approver::find($id);
        $approver->department_id = $request->department;
        $approver->employee_id = $request->employee;
        $approver->last_modified_by = Auth::user()->employee_name;
        $approver->save();

        return redirect('/admin/approvers')->with('message', 'Approver successfully updated');
    }

    public function destroy($id)
    {
        Approver::destroy($id);

        return redirect('/admin/approvers')->with('message', 'Approver successfully deleted');
    }

    public function showLeaveApprovers()
    {
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $employees = DB::table('users')->get();
        $departments = DB::table('departments')->get();

        $approvers = DB::table('department_approvers')
            ->join('users', 'department_approvers.employee_id', '=', 'users.user_id')
            ->join('departments', 'departments.department_id', '=', 'department_approvers.department_id')
            ->select('users.employee_name', 'departments.department', 'department_approvers.*')
            ->get();

        return view('client.modules.absent_notice_slip.leave_approvers.index', compact('designation', 'department', 'approvers', 'employees', 'departments'));
    }

    public function sessionDetails($column)
    {
        $detail = DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', Auth::user()->user_id)
            ->first();

        return $detail->$column;
    }

    public function approverCreate(Request $request)
    {
        $approver = new Approver;
        $approver->department_id = $request->department;
        $approver->employee_id = $request->employee;
        $approver->save();

        return redirect('/module/absent_notice_slip/leave_approvers')->with('message', 'Approver successfully added');
    }

    public function approverUpdate(Request $request, $id)
    {
        $approver = Approver::find($id);
        $approver->department_id = $request->department;
        $approver->employee_id = $request->employee;
        $approver->last_modified_by = Auth::user()->employee_name;
        $approver->save();

        return redirect('/module/absent_notice_slip/leave_approvers')->with('message', 'Approver successfully updated');
    }

    public function approverDelete($id)
    {
        Approver::destroy($id);

        return redirect('/module/absent_notice_slip/leave_approvers')->with('message', 'Approver successfully deleted');
    }
}
