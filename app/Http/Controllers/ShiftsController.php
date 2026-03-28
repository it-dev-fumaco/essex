<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftGroup;
use Auth;
use DB;
use Illuminate\Http\Request;

class ShiftsController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();

        return view('admin.shifts')->with('shifts', $shifts);
    }

    public function store(Request $request)
    {
        $shift = new Shift;
        $shift->shift_schedule = $request->shiftSchedule;
        $shift->time_in = $request->timeIn;
        $shift->time_out = $request->timeOut;
        $shift->breaktime_by_hour = $request->breaktime;
        $shift->grace_period_in_mins = $request->gracePeriod;
        $shift->remarks = $request->remarks;
        $shift->created_by = Auth::user()->employee_name;
        $shift->save();

        return redirect('/admin/shifts')->with('message', 'Shift successfully added');
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::find($id);
        $shift->shift_schedule = $request->shiftSchedule;
        $shift->time_in = $request->timeIn;
        $shift->time_out = $request->timeOut;
        $shift->breaktime_by_hour = $request->breaktime;
        $shift->grace_period_in_mins = $request->gracePeriod;
        $shift->remarks = $request->remarks;
        $shift->last_modified_by = Auth::user()->employee_name;
        $shift->save();

        return redirect('/admin/shifts')->with('message', 'Shift successfully updated');
    }

    public function destroy($id)
    {
        Shift::destroy($id);

        return redirect('/admin/shifts')->with('message', 'Shift successfully deleted');
    }

    public function getShiftSchedules(Request $request)
    {
        if ($request->ajax()) {
            $shift_schedule = DB::table('shift_schedule')
                ->join('shifts', 'shifts.shift_id', '=', 'shift_schedule.shift_id')
                ->join('branch', 'shift_schedule.branch_id', '=', 'branch.branch_id')
                ->join('departments', 'departments.department_id', '=', 'shift_schedule.department_id')
                ->paginate(8);

            return view('client.tables.shift_schedules_table', compact('shift_schedule'))->render();
        }
    }

    public function addShiftSchedule(Request $request)
    {
        $data = [
            'shift_id' => $request->shift_schedule,
            'sched_date' => $request->schedule_date,
            'branch_id' => $request->branch,
            'department_id' => $request->department,
            'remarks' => $request->remarks,
            'created_by' => Auth::user()->employee_name,
        ];

        $shift_schedule = DB::table('shift_schedule')->insert($data);

        return response()->json(['message' => 'Schedule <b>'.$request->schedule_date.'</b> has been added.']);
    }

    public function editShiftSchedule(Request $request)
    {
        $data = [
            'shift_id' => $request->shift_schedule,
            'sched_date' => $request->schedule_date,
            'branch_id' => $request->branch,
            'department_id' => $request->department,
            'remarks' => $request->remarks,
            'last_modified_by' => Auth::user()->employee_name,
        ];

        $shift_schedule = DB::table('shift_schedule')->where('schedule_id', $request->schedule_id)->update($data);

        return response()->json(['message' => 'Schedule <b>'.$request->schedule_date.'</b> has been updated.']);
    }

    // public function deleteShiftSchedule(Request $request){
    //     $shift_schedule = DB::table('shift_schedule')->where('schedule_id', $request->schedule_id)->delete();

    //     return response()->json(['message' => 'Schedule <b>' . $request->schedule_date . '</b> has been deleted.']);
    // }

    public function getShifts(Request $request)
    {
        if ($request->ajax()) {
            $shifts = Shift::paginate(8);

            return view('client.tables.shifts_table', compact('shifts'))->render();
        }
    }

    public function getShiftDetails(Request $request)
    {
        $shift_details = Shift::find($request->id);

        return response()->json($shift_details);
    }

    public function addShift(Request $request)
    {
        $data = [
            'shift_schedule' => $request->schedule_name,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'breaktime_by_hour' => $request->breaktime,
            'grace_period_in_mins' => $request->grace_period,
            'created_by' => Auth::user()->employee_name,
        ];

        $shifts = DB::table('shifts')->insert($data);

        return response()->json(['message' => 'Shift <b>'.$request->schedule_name.'</b> has been added.']);
    }

    public function editShift(Request $request)
    {
        $data = [
            'shift_schedule' => $request->schedule_name,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'breaktime_by_hour' => $request->breaktime,
            'grace_period_in_mins' => $request->grace_period,
            'last_modified_by' => Auth::user()->employee_name,
        ];

        $shifts = DB::table('shifts')->where('shift_id', $request->shift_id)->update($data);

        return response()->json(['message' => 'Shift <b>'.$request->schedule_name.'</b> has been updated.']);
    }

    public function deleteShift(Request $request)
    {
        DB::table('shifts')->where('shift_id', $request->shift_id)->delete();

        return response()->json(['message' => 'Shift <b>'.$request->shift_name.'</b> has been deleted.']);
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

    public function showEmployeeShifts()
    {
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $shift_groups = ShiftGroup::all();

        $custom_shifts = DB::table('shift_schedule')->get();

        $branch = DB::table('branch')->get();
        $department_list = DB::table('departments')->get();

        return view('client.modules.attendance.employee_shifts.index', compact('department_list', 'branch', 'designation', 'department', 'shift_groups', 'custom_shifts'));
    }

    public function getEmployeeShiftDetails($group_id)
    {
        $shift_group_details = ShiftGroup::find($group_id);

        $shift_schedules = DB::table('shifts')->where('shift_group_id', $group_id)->get();

        return [
            'group_details' => $shift_group_details,
            'schedule' => $shift_schedules,
        ];
    }

    public function createShiftSchedule(Request $request)
    {
        $shift_group = new ShiftGroup;
        $shift_group->shift_group_name = $request->shift_group_name;
        $shift_group->remarks = $request->remarks;
        $shift_group->created_by = Auth::user()->employee_name;
        $shift_group->save();

        if ($shift_group->id > 0) {
            $data = [];
            foreach ($request->day_of_week as $key => $value) {
                if ($request->time_in[$key]) {
                    $data[] = [
                        'shift_group_id' => $shift_group->id,
                        'day_of_week' => $request->day_of_week[$key],
                        'time_in' => date('G:i:s', strtotime($request->time_in[$key])),
                        'time_out' => date('G:i:s', strtotime($request->time_out[$key])),
                        'breaktime_by_hour' => $request->breadktime[$key],
                        'grace_period_in_mins' => $request->grace_period[$key],
                        'created_by' => Auth::user()->employee_name,
                    ];
                }
            }

            Shift::insert($data);
        }

        return redirect()->back()->with(['msg_shift_group' => 'Shift Group <b>'.$shift_group->shift_group_name.'</b> has been created.']);
    }

    public function updateShiftSchedule(Request $request)
    {
        $shift_group = ShiftGroup::find($request->shift_group_id);
        $shift_group->shift_group_name = $request->shift_group_name;
        $shift_group->remarks = $request->remarks;
        $shift_group->last_modified_by = Auth::user()->employee_name;
        $shift_group->save();

        if ($shift_group->id > 0) {
            $data = [];
            foreach ($request->shift_id as $key => $value) {
                $shift = Shift::find($request->shift_id[$key]);
                $shift->time_in = date('G:i:s', strtotime($request->time_in[$key]));
                $shift->time_out = date('G:i:s', strtotime($request->time_out[$key]));
                $shift->breaktime_by_hour = $request->breadktime[$key];
                $shift->grace_period_in_mins = $request->grace_period[$key];
                $shift->last_modified_by = Auth::user()->employee_name;
                $shift->save();
            }
        }

        return redirect()->back()->with(['msg_shift_group' => 'Shift Group <b>'.$shift_group->shift_group_name.'</b> has been updated.']);
    }

    public function deleteShiftSchedule(Request $request, $id)
    {
        ShiftGroup::destroy($id);
        Shift::where('shift_group_id', $id)->delete();

        return redirect()->back()->with(['msg_shift_group' => 'Shift Group <b>'.$request->shift_group_name.'</b> has been deleted.']);
    }

    public function createSpecialShift(Request $request)
    {
        $data = [
            'branch_id' => $request->branch,
            'department_id' => $request->department,
            'sched_date' => $request->schedule_date,
            'time_in' => date('G:i:s', strtotime($request->time_in)),
            'time_out' => date('G:i:s', strtotime($request->time_out)),
            'breaktime_by_hr' => $request->breaktime,
            'grace_period_in_mins' => $request->grace_period,
            'remarks' => $request->remarks,
            'created_by' => Auth::user()->employee_name,
        ];

        DB::table('shift_schedule')->insert($data);

        return redirect()->back()->with(['msg_special_shift' => 'Special Shift <b>'.$request->schedule_date.'</b> has been created.']);
    }

    public function updateSpecialShift(Request $request, $id)
    {
        $data = [
            'branch_id' => $request->branch,
            'department_id' => $request->department,
            'sched_date' => $request->schedule_date,
            'time_in' => date('G:i:s', strtotime($request->time_in)),
            'time_out' => date('G:i:s', strtotime($request->time_out)),
            'breaktime_by_hr' => $request->breaktime,
            'grace_period_in_mins' => $request->grace_period,
            'remarks' => $request->remarks,
            'last_modified_by' => Auth::user()->employee_name,
        ];

        DB::table('shift_schedule')->where('schedule_id', $id)->update($data);

        return redirect()->back()->with(['msg_special_shift' => 'Special Shift <b>'.$request->schedule_date.'</b> has been updated.']);
    }

    public function deleteSpecialShift(Request $request, $id)
    {

        DB::table('shift_schedule')->where('schedule_id', $id)->delete();

        return redirect()->back()->with(['msg_special_shift' => 'Special Shift <b>'.$request->sched_date.'</b> has been deleted.']);
    }
}
