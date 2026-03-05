<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Auth;
use DB;
use Illuminate\Http\Request;

class HumanResourcesController extends Controller
{
    public function sessionDetails($column)
    {
        $detail = DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', Auth::user()->user_id)
            ->first();

        return $detail->$column;
    }

    public function showAnalytics()
    {
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $employeeCounts = DB::table('users')
            ->where('user_type', 'Employee')
            ->where('status', 'Active')
            ->selectRaw("
                count(*) as total,
                sum(case when employment_status = 'Regular' then 1 else 0 end) as regular,
                sum(case when employment_status = 'Contractual' then 1 else 0 end) as contractual,
                sum(case when employment_status = 'Probationary' then 1 else 0 end) as probationary
            ")
            ->first();

        $applicantCounts = DB::table('users')
            ->where('user_type', 'Applicant')
            ->selectRaw("
                count(*) as applicants,
                sum(case when applicant_status = 'Hired' then 1 else 0 end) as hired,
                sum(case when applicant_status = 'Declined' then 1 else 0 end) as declined,
                sum(case when applicant_status = 'Not Qualified' then 1 else 0 end) as not_qualified
            ")
            ->first();

        $totals = [
            'applicants' => (int) ($applicantCounts->applicants ?? 0),
            'hired' => (int) ($applicantCounts->hired ?? 0),
            'declined' => (int) ($applicantCounts->declined ?? 0),
            'not_qualified' => (int) ($applicantCounts->not_qualified ?? 0),
            'employees' => (int) ($employeeCounts->total ?? 0),
            'regular' => (int) ($employeeCounts->regular ?? 0),
            'contractual_probationary' => (int) (($employeeCounts->contractual ?? 0) + ($employeeCounts->probationary ?? 0)),
        ];

        return view('client.modules.human_resource.analytics', compact('designation', 'department', 'totals'));
    }

    public function hiringRate()
    {
        $row = DB::table('users')
            ->where('user_type', 'Applicant')
            ->selectRaw("
                count(*) as total,
                sum(case when applicant_status = 'Hired' then 1 else 0 end) as hired,
                sum(case when applicant_status = 'Declined' then 1 else 0 end) as declined,
                sum(case when applicant_status = 'Not Qualified' then 1 else 0 end) as not_qualified
            ")
            ->first();

        $total = (int) ($row->total ?? 0);
        $divisor = $total ?: 1;

        $data = [
            'hired' => round((($row->hired ?? 0) / $divisor) * 100, 2),
            'declined' => round((($row->declined ?? 0) / $divisor) * 100, 2),
            'not_qualified' => round((($row->not_qualified ?? 0) / $divisor) * 100, 2),
        ];

        return response()->json($data);
    }

    public function applicantsChart(Request $request)
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $countsByMonth = DB::table('users')
            ->where('source', 'Applicant')
            ->whereYear('created_at', $request->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month')
            ->all();

        $data = [];
        foreach ($months as $i => $month) {
            $monthNum = $i + 1;
            $data[] = [
                'month' => $month,
                'total' => (int) ($countsByMonth[$monthNum] ?? 0),
            ];
        }

        return response()->json($data);
    }

    public function employeesPerDeptChart()
    {
        return DB::table('users')->join('departments', 'departments.department_id', 'users.department_id')->select('department', DB::raw('COUNT(users.department_id) as total'))->where('user_type', 'Employee')->groupBy('users.department_id', 'department')->get();
    }

    public function jobSourceChart()
    {
        $counts = DB::table('users')
            ->whereNotNull('job_source')
            ->selectRaw('job_source, count(*) as total')
            ->groupBy('job_source')
            ->pluck('total', 'job_source')
            ->all();

        $data = [
            'jobstreet' => (int) ($counts['Jobstreet'] ?? 0),
            'indeed' => (int) ($counts['Indeed'] ?? 0),
            'walkin' => (int) ($counts['Walk-in'] ?? 0),
            'referrals' => (int) ($counts['Referrals'] ?? 0),
            'linkedIn' => (int) ($counts['LinkedIn'] ?? 0),
            'others' => (int) ($counts['Others'] ?? 0),
        ];

        return $data;
    }

    public function show_HR_training()
    {
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');
        $departments = DB::table('departments')->get();
        $training = DB::table('training')
            ->select('training.training_title', 'training.training_desc', 'training.training_date', 'training.date_submitted', 'training.proposed_by', 'training.status', 'training.training_id', 'training.remarks', 'training.department_name', 'training.department')->get();

        return view('client.modules.human_resource.training.index', compact('designation', 'department', 'departments', 'training'));
    }

    public function training_profile($id)
    {
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');
        $departments = DB::table('departments')->get();
        $training = DB::table('training')
            ->select('training.training_title', 'training.training_desc', 'training.training_date', 'training.date_submitted', 'training.proposed_by', 'training.status', 'training.training_id', 'training.remarks', 'training.department_name')
            ->where('training.training_id', $id)
            ->first();

        $attendees = DB::table('training_attendees')
            ->join('users', 'users.user_id', '=', 'training_attendees.user_id')
            ->where('training_id', $id)
            ->get();

        return view('client.modules.human_resource.training.training_profile', compact('designation', 'department', 'departments', 'training', 'attendees'));
    }

    public function add_HR_training(Request $request)
    {
        $date = date('Y-m-d');
        $training = new Training;
        $training->training_title = $request->training_title;
        $training->training_desc = $request->training_desc;
        $training->department = $request->department;
        $training->training_date = $request->training_date;
        $training->proposed_by = $request->proposed_by;
        $training->status = $request->training_status;
        $training->date_submitted = $date;
        $training->last_modified_by = Auth::user()->employee_name;
        $training->remarks = $request->remarks;
        $training->department_name = $request->department_name;

        $training->save();
        $get_id = DB::table('training')
            ->where('training_title', $request->training_title)
            ->where('training_date', $request->training_date)
            ->where('department', $request->department)
            ->first();

        if ($request->kpi_designation_new) {
            foreach ($request->kpi_designation_new as $i => $row) {
                $kpi_designation[] = [
                    'user_id' => $request->kpi_designation_new[$i],
                    'training_id' => $get_id->training_id];
            }
            DB::table('training_attendees')->insert($kpi_designation);
        }

        return redirect()->back()->with(['message' => ''.$request->training_title.' has been successfully added!']);
    }

    public function edit_HR_training(Request $request)
    {
        $training = Training::find($request->training_id);
        $training->training_title = $request->training_title;
        $training->training_desc = $request->training_desc;
        $training->department = $request->department;
        $training->training_date = $request->training_date;
        $training->proposed_by = $request->proposed_by;
        $training->status = $request->training_status;
        $training->last_modified_by = Auth::user()->employee_name;
        $training->remarks = $request->remarks;
        $training->department_name = $request->department_name;
        $training->save();

        $kpi_designation_id = collect($request->kpi_designation_id);

        // for insert
        if ($request->kpi_designation_new) {
            foreach ($request->kpi_designation_new as $i => $row) {
                $kpi_designation_new[] = [
                    'user_id' => $request->kpi_designation_new[$i],
                    'training_id' => $request->training_id,
                ];
            }

            DB::table('training_attendees')->insert($kpi_designation_new);
        }

        // for delete
        if ($request->kpi_designation_id) {
            $delete = DB::table('training_attendees')
                ->where('training_id', $request->training_id)
                ->whereIn('attendies_id', $request->old_kpi_designation)
                ->whereNotIn('attendies_id', $kpi_designation_id)
                ->delete();
        }

        // for update
        if ($request->kpi_designation_id) {
            foreach ($request->kpi_designation_id as $i => $row) {
                $kpi_designation_update = [
                    'user_id' => $request->kpi_designation_old[$i],
                    'last_modified_by' => Auth::user()->employee_name,
                ];

                DB::table('training_attendees')->where('attendies_id', $request->kpi_designation_id[$i])->update($kpi_designation_update);
            }
        }

        if ($request->ajax()) {
            return response()->json(['message' => ''.$request->training_title.' has been successfully updated!', 'id' => $request->training_id]);
        }

        return redirect()->back()->with(['message' => ''.$request->training_title.'  has been successfully updated!']);
    }

    public function delete_HR_training(Request $request)
    {
        $training = Training::find($request->training_id);
        $training->delete();

        return redirect()->back()->with(['message' => ' Training has been Successfully deleted!']);
    }

    public function Employee_list_edit(Request $request)
    {
        $employee_list = DB::table('users')
            ->where('user_type', 'Employee')
            ->where('status', 'Active')
            ->orderBy('employee_name', 'asc')->get();

        return response()->json($employee_list);

    }

    public function Employee_list(Request $request)
    {
        $employee_list = DB::table('users')
            ->where('department_id', $request->department)
            ->where('user_type', 'Employee')
            ->where('status', 'Active')
            ->orderBy('employee_name', 'asc')->get();

        return response()->json($employee_list);

    }

    public function edit_training_details($id)
    {
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');
        $departments = DB::table('departments')->get();
        $training = DB::table('training')
            ->select('training.training_title', 'training.training_desc', 'training.training_date', 'training.date_submitted', 'training.proposed_by', 'training.status', 'training.department', 'training.training_id', 'training.remarks', 'training.department_name')
            ->where('training.training_id', $id)
            ->first();
        $training_attendees = DB::table('training_attendees')
            ->join('users', 'users.user_id', '=', 'training_attendees.user_id')
            ->where('training_id', $training->training_id)->get();

        $data = [
            'training_attendees' => $training_attendees,
            'training' => $training,
        ];

        return response()->json($data);

    }
}
