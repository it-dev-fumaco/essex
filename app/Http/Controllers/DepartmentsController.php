<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Department;
use Validator;
use DB;

class DepartmentsController extends Controller
{
    public function index(){
        $departments = Department::all();

        return view('admin.department.index')->with("departments", $departments);
    }

    public function store(Request $request){
        $department = new Department;
        $department->department = $request->department;
        $department->save();

        return redirect()->back()->with(['message' => 'Department <b>' . $department->department . '</b>  has been added!']);
    }

    public function update(Request $request){
        $department = Department::find($request->id);
        $department->department = $request->department;
        $department->save();
        
        return redirect()->back()->with(['message' => 'Department <b>' . $department->department . '</b>  has been updated!']);
    }

    public function delete(Request $request){
        Department::destroy($request->id);

        return redirect()->back()->with(['message' => 'Department <b>' . $request->department . '</b>  has been deleted!']);
    }
}