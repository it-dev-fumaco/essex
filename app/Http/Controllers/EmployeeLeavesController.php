<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\EmployeeLeave;
use DB;
use Auth;
use Carbon\Carbon;

class EmployeeLeavesController extends Controller
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
        $employee_leaves = DB::table('employee_leaves')
                            ->join('users', 'employee_leaves.employee_id', '=', 'users.user_id')
                            ->join('leave_types', 'employee_leaves.leave_type_id', '=', 'leave_types.leave_type_id')
                            ->select('employee_leaves.*', 'users.employee_name', 'leave_types.leave_type')
                            ->get();
        $employees = DB::table('users')->get();
        $leave_types = DB::table('leave_types')->get();

        return view('admin.employee_leaves', ["employees" => $employees, "employee_leaves" => $employee_leaves, "leave_types" => $leave_types]);
    }

    public function store(Request $request){
        $employee_leave = new EmployeeLeave;
        $employee_leave->employee_id = $request->employee;
        $employee_leave->leave_type_id = $request->leave_type;
        $employee_leave->total = $request->total;
        $employee_leave->remaining = $request->total;
        $employee_leave->year = $request->year;
        $employee_leave->save();

        return redirect('/admin/employee_leaves')->with('message', 'Employee Leave successfully added');
    }

    public function update(Request $request, $id){
        $employee_leave = EmployeeLeave::find($id);
        $employee_leave->employee_id = $request->employee;
        $employee_leave->leave_type_id = $request->leave_type;
        $employee_leave->total = $request->total;
        $employee_leave->remaining = $request->total;
        $employee_leave->year = $request->year;
        $employee_leave->last_modified_by = Auth::user()->employee_name;
        $employee_leave->save();

        return redirect('/admin/employee_leaves')->with('message', 'Employee Leave successfully updated');
    }

    public function destroy($id){
        EmployeeLeave::destroy($id);
        
        return redirect('/admin/employee_leaves')->with('message', 'Employee Leave successfully deleted');
    }

    public function leaveBalances(){
        $employee_leaves = DB::table('employee_leaves')
                            ->join('users', 'employee_leaves.employee_id', '=', 'users.user_id')
                            ->join('leave_types', 'employee_leaves.leave_type_id', '=', 'leave_types.leave_type_id')
                            ->select('employee_leaves.*', 'users.employee_name', 'leave_types.leave_type')
                            ->get();
        $employees = DB::table('users')->get();
        $leave_types = DB::table('leave_types')->get();

        return view('admin.leave_balances', ["employees" => $employees, "employee_leaves" => $employee_leaves, "leave_types" => $leave_types]);
    }

    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
    }

    public function showLeaveBalances(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $employee_leaves = DB::table('employee_leaves')->join('users', 'employee_leaves.employee_id', '=', 'users.user_id')
            ->join('leave_types', 'employee_leaves.leave_type_id', '=', 'leave_types.leave_type_id')
            ->select('employee_leaves.*', 'users.employee_name', 'leave_types.leave_type')
            ->orderBy('employee_leaves.created_at', 'desc')->get();

        $employees = DB::table('users')->get();
        $leave_types = DB::table('leave_types')->get();

        return view('client.modules.absent_notice_slip.leave_balances.index', compact('employees', 'employee_leaves', 'leave_types', 'designation', 'department'));
    }

    public function leaveBalanceCreate(Request $request){
        $employee_leave = new EmployeeLeave;
        $employee_leave->employee_id = $request->employee;
        $employee_leave->leave_type_id = $request->leave_type;
        $employee_leave->total = $request->total;
        $employee_leave->remaining = $request->total;
        $employee_leave->year = $request->year;
        $employee_leave->save();

        return redirect('/module/absent_notice_slip/leave_balances')->with('message', 'Employee Leave successfully added');
    }

    public function leaveBalanceUpdate(Request $request, $id){
        $employee_leave = EmployeeLeave::find($id);
        $employee_leave->employee_id = $request->employee;
        $employee_leave->leave_type_id = $request->leave_type;
        $employee_leave->total = $request->total;
        $employee_leave->remaining = $request->total;
        $employee_leave->year = $request->year;
        $employee_leave->last_modified_by = Auth::user()->employee_name;
        $employee_leave->save();

        return redirect('/module/absent_notice_slip/leave_balances')->with('message', 'Employee Leave successfully updated');
    }

    public function leaveBalanceDelete($id){
        EmployeeLeave::destroy($id);
        
        return redirect('/module/absent_notice_slip/leave_balances')->with('message', 'Employee Leave successfully deleted');
    }

    public function employeeLeaveBalanceCreate(Request $request){
        DB::beginTransaction();
        try {
             // get employee leaves
            $query = DB::table('employee_leaves as el')->join('users as u', 'el.employee_id', 'u.user_id')
                ->join('leave_types as lt', 'el.leave_type_id', 'lt.leave_type_id')
                ->where('u.status', 'Active')->where('u.user_type', 'Employee')->where('u.employment_status', 'Regular')
                ->select('el.employee_id', 'el.leave_type_id', DB::raw('MAX(el.total) as total_leave'), 'u.employee_name', 'lt.leave_type')
                ->groupBy('el.employee_id', 'el.leave_type_id', 'u.employee_name', 'lt.leave_type')
                ->orderBy('u.user_id', 'desc')->get();

            $values = [];
            foreach($query as $r) {
                $existing = DB::table('employee_leaves')->where('employee_id', $r->employee_id)->where('leave_type_id', $r->leave_type_id)->where('year', $request->next_year)->exists();
                if (!$existing) {
                    $values[] = [
                        'employee_id' => $r->employee_id,
                        'leave_type_id' => $r->leave_type_id,
                        'total' => $r->total_leave,
                        'remaining' => $r->total_leave,
                        'year' => $request->next_year,
                        'created_by' => Auth::user()->email,
                        'last_modified_by' => Auth::user()->email,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ];
                }
            }

            DB::table('employee_leaves')->insert($values);
            
            DB::commit();

            return redirect()->back()->with('message', 'Employee Leave successfully updated.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('message', 'Something went wrong. Please try again.');
        }
    }
}