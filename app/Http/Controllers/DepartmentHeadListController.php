<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\DepartmentHead;



class DepartmentHeadListController extends Controller
{
    public function showlist(){
      $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        $designation = $detail->designation;
        $department = $detail->department;


        $employees = DB::table('users')->get();
        $departments = DB::table('departments')->get();
        $headlist = DB::table('department_head_list')
                            ->join('users', 'department_head_list.employee_id', '=', 'users.user_id')
                            ->join('departments', 'departments.department_id', '=', 'department_head_list.department_id')
                            ->select('users.employee_name', 'departments.department', 'department_head_list.*')
                            ->get();



      return view('client.modules.human_resource.department_heads.index',compact('detail','designation','department','employees','departments','headlist'));
    }
    public function store(Request $request){
        $department_head = new DepartmentHead;
        $department_head->department_id = $request->department;
        $department_head->employee_id = $request->employee;
        $department_head->save();

        return redirect()->back()->with("message", "Department Head successfully added");

    }
    public function update(Request $request, $id){
        $department_head = DepartmentHead::find($id);
        $department_head->department_id = $request->department;
        $department_head->employee_id = $request->employee;
        $department_head->last_modified_by = Auth::user()->employee_name;
        $department_head->save();

        return redirect()->back()->with("message", "Department Head successfully updated");
    }

    public function delete($id){
        DepartmentHead::destroy($id);
        return redirect()->back()->with("message", "Department Head successfully deleted");
    }

}