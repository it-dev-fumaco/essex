<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;

class HolidayController extends Controller
{
    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
    }
    
    public function index(){
        $holidays = DB::table('holidays')->get();

        return view('admin.holiday.index')->with("holidays", $holidays);
    }

    public function store(Request $request){
        $data = [
            'holiday_date' => $request->holiday_date,
            'description' => $request->description
        ];

        $branch = DB::table('holidays')->insert($data);

        return redirect()->back()->with(['message' => 'Holiday <b>' . $request->holiday_date . '</b>  has been added!']);
    }

    public function update(Request $request){
        $data = [
            'holiday_date' => $request->holiday_date,
            'description' => $request->description
        ];


        $branch = DB::table('holidays')->where('id', $request->id)->update($data);
        
        return redirect()->back()->with(['message' => 'Holiday <b>' . $request->holiday_date . '</b>  has been updated!']);
    }

    public function delete(Request $request){
        DB::table('holidays')->where('id', $request->id)->delete();

        return redirect()->back()->with(['message' => 'Holiday <b>' . $request->holiday_date . '</b> has been deleted!']);
    }
    
    public function indexholiday(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');
        $holidays = DB::table('holidays')->orderBy('id', 'desc')->get();

        return view('client.modules.attendance.holiday_entry.index', compact('holidays', 'designation', 'department'));
    }

    public function storeholiday(Request $request){
        $data = [
            'holiday_date' => $request->holiday_date,
            'description' => $request->description,
            'category' => $request->category,
            'created_by' => Auth::user()->employee_name
        ];

        $branch = DB::table('holidays')->insert($data);

        return redirect()->back()->with(['message' => 'Holiday <b>' . $request->holiday_date . '</b>  has been added!']);
    }

    public function updateholiday(Request $request){
        $data = [
            'holiday_date' => $request->holiday_date,
            'description' => $request->description,
            'category' => $request->category,
            'last_modified_by' => Auth::user()->employee_name
        ];

        $branch = DB::table('holidays')->where('id', $request->id)->update($data);
        
        return redirect()->back()->with(['message' => 'Holiday <b>' . $request->holiday_date . '</b>  has been updated!']);
    }

    public function deleteholiday(Request $request){
        DB::table('holidays')->where('id', $request->id)->delete();

        return redirect()->back()->with(['message' => 'Holiday <b>' . $request->holiday_date . '</b> has been deleted!']);
    }
}