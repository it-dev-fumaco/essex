<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypesController extends Controller
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
        $leave_types = LeaveType::all();

        return view('admin.leave_types')->with('leave_types', $leave_types);
    }

    public function store(Request $request)
    {
        $leave_type = new LeaveType;
        $leave_type->leave_type = $request->leave_type;
        $leave_type->color_legend = $request->color_legend;
        $leave_type->description = $request->description;
        $leave_type->applied_to_all = $request->applied_to_all;
        $leave_type->save();

        return redirect('/admin/leave_types')->with('message', 'Leave Type successfully added');
    }

    public function update(Request $request, $id)
    {
        $leave_type = LeaveType::find($id);
        $leave_type->leave_type = $request->leave_type;
        $leave_type->color_legend = $request->color_legend;
        $leave_type->description = $request->description;
        $leave_type->applied_to_all = $request->applied_to_all;
        $leave_type->save();

        return redirect('/admin/leave_types')->with('message', 'Leave Type successfully updated');
    }

    public function destroy($id)
    {
        LeaveType::destroy($id);

        return redirect('/admin/leave_types')->with('message', 'Leave Type successfully deleted');
    }
}
