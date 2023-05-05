<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Carbon\Carbon;
use Auth;
use DB;
use DateTime;
use DatePeriod;
use DateInterval;
use App\DataInputKPI;
use App\KPIResult;
use App\DataInputModel;
use App\Http\Traits\KpiTrait;
use App\Http\Traits\AttendanceTrait;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class EvaluationController extends Controller
{
    use KpiTrait;
    use AttendanceTrait;

    public function addEvaluation(Request $request){

        $validator = Validator::make($request->all(), [
           'employee_id' => 'required',
           'title' => 'required',
           'evaluation_file' => 'required|mimes:pdf',
           'evaluation_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'   => $validator->errors()->all(),
                'class_name'  => 'danger',
                'icon' => 'fa-times-circle-o'
            ]);
        }else{
            $filenametostore = null;
            if($request->hasFile('evaluation_file')){
                $file = $request->file('evaluation_file');

                //get filename with extension
                $filenamewithextension = $file->getClientOriginalName();
         
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
         
                //get file extension
                $extension = $file->getClientOriginalExtension();
         
                //filename to store
                $filenametostore = $filename.'_'.uniqid().'.'.$extension;
         
                Storage::put('public/uploads/evaluations/'. $filenametostore, fopen($file, 'r+'));
            }

            $data[] = [
                'employee_id' => $request->employee_id,
                'title' => $request->title,
                'evaluation_file' => $filenametostore,
                'evaluation_date' => $request->evaluation_date,
                'evaluated_by' => Auth::user()->user_id,
                'remarks' => $request->remarks
            ];

            $evaluation = DB::table('evaluation_files')->insert($data);

            return response()->json([
                'message'   => ['Evaluation <b>' . $request->title . '</b> has been added.'],
                'class_name'  => 'success',
                'icon' => 'fa-check-square-o'
            ]);
        }
    }

    public function editEvaluation(Request $request){
        $validator = Validator::make($request->all(), [
           'employee_id' => 'required',
           'title' => 'required',
           'evaluation_file' => 'mimes:pdf',
           'evaluation_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'   => $validator->errors()->all(),
                'class_name'  => 'danger',
                'icon' => 'fa-times-circle-o'
            ]);
        }else{
            $filenametostore = $request->eval_file;
            if($request->hasFile('evaluation_file')){
                $file = $request->file('evaluation_file');

                //get filename with extension
                $filenamewithextension = $file->getClientOriginalName();
         
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
         
                //get file extension
                $extension = $file->getClientOriginalExtension();
         
                //filename to store
                $filenametostore = $filename.'_'.uniqid().'.'.$extension;
         
                Storage::put('public/uploads/evaluations/'. $filenametostore, fopen($file, 'r+'));
            }

            $data = [
                'employee_id' => $request->employee_id,
                'title' => $request->title,
                'evaluation_file' => $filenametostore,
                'evaluation_date' => $request->evaluation_date,
                'remarks' => $request->remarks,
                'last_modified_by' => Auth::user()->employee_name
            ];

            $evaluation = DB::table('evaluation_files')->where('id', $request->id)->update($data);

            return response()->json([
                'message'   => ['Evaluation <b>' . $request->title . '</b> has been updated.'],
                'class_name'  => 'success',
                'icon' => 'fa-check-square-o'
            ]);
        }
    }

    public function deleteEvaluation(Request $request){
        DB::table('evaluation_files')->where('id', $request->id)->delete();

        return response()->json([
                'message'   => ['Evaluation <b>' . $request->evaluation_title . '</b> has been deleted.'],
                'class_name'  => 'success',
                'icon' => 'fa-check-square-o'
            ]);
    }

    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
    }

    public function showObjectives(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $department_list = DB::table('departments')->get();

        return view('client.modules.evaluation.objective.index', compact('designation', 'department', 'department_list'));
    }

    public function getObjectives(Request $request){
        $objectives = DB::table('objective')->paginate(10);

        return view('client.modules.evaluation.objective.tables.objective_table', compact('objectives'))->render();
    }

    public function getObjectiveDetails($id){
        $objective = DB::table('objective')->where('obj_id', $id)->first();

        return response()->json($objective);
    }

    public function createObjective(Request $request){
        $data = [
            'obj_description' => $request->objective,
            'target' => $request->target,
        ];

        $id = DB::table('objective')->insertGetId($data);

        $result = [
            'id' => $id,
            'message' => 'Objective <b>' . $request->objective . '</b> has been created.',
        ];

        return response()->json($result);
    }

    public function updateObjective(Request $request){
        $data = [
            'obj_description' => $request->objective,
            'target' => $request->target,
            'last_modified_by' => Auth::user()->employee_name,
        ];

        $id = DB::table('objective')->where('obj_id', $request->id)->update($data);

        $result = [
            'id' => $request->id,
            'message' => 'Objective <b>' . $request->objective . '</b> has been updated.',
        ];

        return response()->json($result);
    }

    public function deleteObjective(Request $request){
        $id = DB::table('objective')->where('obj_id', $request->id)->delete();

        $result = [
            'id' => $request->id,
            'message' => 'Objective <b>' . $request->objective . '</b> has been deleted.',
        ];

        return response()->json($result);
    }

    // ajax
    public function getDesignations(Request $request){
        return DB::table('designation')
                ->when($request->department, function($query) use ($request){
                    return $query->where('department_id', $request->department);
                })->get();
    }
    // ajax
    public function getEmployees(Request $request){
        return DB::table('users')
                ->where('status', 'Active')
                ->where('user_type', 'Employee')
                ->when($request->department, function($query) use ($request){
                    return $query->where('department_id', $request->department);
                })->get();
    }

    public function showKPI(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $department_list = DB::table('departments')->get();

        $objective_list = DB::table('objective')->get();

        return view('client.modules.evaluation.kpi.index', compact('designation', 'department', 'department_list', 'objective_list'));
    }

    public function getKPI(Request $request){
        $kpi = DB::table('kpi')
                ->join('objective', 'objective.obj_id', 'kpi.objective_id')
                ->select('objective.obj_description', 'kpi.*')
                ->where('category', 'Quantitative')
                ->when($request->department, function($query) use ($request){
                    return $query->where('kpi.department_id', $request->department);
                })
                ->when($request->objective, function($query) use ($request){
                    return $query->where('kpi.objective_id', $request->objective);
                })
                ->orderBy('kpi_id', 'desc')
                ->paginate(10);

        return view('client.modules.evaluation.kpi.tables.kpi_designation_table', compact('kpi'))->render();
    }

    public function getKpiDetails($id){
        $kpi_details = DB::table('kpi')->where('kpi_id', $id)->first();
        $kpi_designations = DB::table('kpi_per_designation')
                    ->join('designation', 'kpi_per_designation.designation_id', 'designation.des_id')
                    ->where('kpi_id', $id)->get();

        $data = [
            'kpi_details' => $kpi_details,
            'kpi_designations' => $kpi_designations,
        ];

        return response()->json($data);
    }

    public function getMetricDetails($id){
        $metric_details = DB::table('metrics')->where('metric_id', $id)->first();

        return response()->json($metric_details);
    }

    public function createKPI(Request $request){
        $data = [
            'objective_id' => $request->objective,
            'department_id' => $request->department,
            'category' => $request->category,
            'kpi_description' => $request->kpi_description,
            'evaluation_period' => $request->period,
            'target' => $request->target,
            'formula' => $request->formula,
            'weight_average' => $request->weight_average,
            'set_kpi' => $request->set_kpi_perdepartment,
            'set_manual' => $request->set_kpi_manual,
            'created_by' => Auth::user()->employee_name,
        ];

        $id = DB::table('kpi')->insertGetId($data);

        if ($request->set_kpi_perdepartment == 0) {
            if ($request->kpi_designation_new) {
                foreach($request->kpi_designation_new as $i => $row){
                      $kpi_designation[] = [
                        'kpi_id' => $id,
                        'designation_id' => $request->kpi_designation_new[$i],
                        'created_by' => Auth::user()->employee_name
                    ];
                } 
                DB::table('kpi_per_designation')->insert($kpi_designation);
            }
        }

        return response()->json(['message' => 'New KPI has been created.', 'id' => $id, 'description' => $request->kpi_description]);
    }

    public function updateKPI(Request $request){
        
        $data = [
            'objective_id' => $request->objective,
            'category' => $request->category,
            'kpi_description' => $request->kpi_description,
            'target' => $request->target,
            'evaluation_period' => $request->period,
            'formula' => $request->formula,
            'weight_average' => $request->weight_average,
            'remarks' => $request->remarks,
            'set_kpi' => $request->checkvalue,
            'set_manual' => $request->checkvaluemanual,
            'last_modified_by' => Auth::user()->employee_name,
        ];

        $kpi_designation_id = collect($request->kpi_designation_id);

        // for delete
        if ($request->kpi_designation_id) {
            $delete = DB::table('kpi_per_designation')
                ->where('kpi_id', $request->id)
                ->whereIn('id', $request->old_kpi_designation)
                ->whereNotIn('id', $kpi_designation_id)
                ->delete();
        }
        

        // for insert
        if ($request->kpi_designation_new) {
            foreach($request->kpi_designation_new as $i => $row){
                $kpi_designation_new[] = [
                    'kpi_id' => $request->id,
                    'designation_id' => $request->kpi_designation_new[$i],
                ];
            }

            DB::table('kpi_per_designation')->insert($kpi_designation_new);
        }
        
        // for update
        if ($request->kpi_designation_id) {
            foreach($request->kpi_designation_id as $i => $row){
                $kpi_designation_update = [
                    'designation_id' => $request->kpi_designation_old[$i],
                    'last_modified_by' => Auth::user()->employee_name
                ];

                DB::table('kpi_per_designation')->where('id', $request->kpi_designation_id[$i])->update($kpi_designation_update);
            }
        }

        DB::table('kpi')->where('kpi_id', $request->id)->update($data);

        return response()->json(['message' => 'KPI <b>' . $request->kpi_description . '</b> has been updated.']);
    }

    public function deleteKPI(Request $request){
        DB::table('kpi')->where('kpi_id', $request->id)->delete();
        DB::table('kpi_per_designation')->where('kpi_id', $request->id)->delete();

        return response()->json(['message' => 'KPI <b>' . $request->kpi_description . '</b> has been deleted.']);
    }

    public function createMetrics(Request $request){
        $data = [];
        foreach ($request->metric as $i => $row) {
            $data[] = [
                'kpi_id' => $request->kpi_id,
                // 'designation_id' => $request->designation,
                'metric_name' => $request->metric_name[$i],
                'metric_description' => $request->metric[$i],
                // 'target' => $request->target[$i],
                'metric_type' => $request->metric_type,
                // 'formula_guide' => $request->formula[$i],
            ];
        }

        DB::table('metrics')->insert($data);

        return response()->json(['message' => 'Metrics has been created.']);
    }

    public function updateMetric(Request $request){
        $data = [
            'metric_name' => $request->metric_name,
            'metric_description' => $request->metric,
            'target' => $request->target,
            // 'weight_average' => $request->weight_average,
            'remarks' => $request->remarks,
        ];

        DB::table('metrics')->where('metric_id', $request->id)->update($data);

        return response()->json(['message' => 'Metrics has been updated.']);
    }

    public function deleteMetric(Request $request){
        DB::table('metrics')->where('metric_id', $request->id)->delete();

        return response()->json(['message' => 'Metric <b>' . $request->metric_description . '</b> has been deleted.']);
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

    public function kpiPerDept(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $handledDepts = $this->getHandledDepts(Auth::user()->user_id);
        $department_list = DB::table('departments');
        // if (!in_array($designation, ['Human Resources Head', 'Director of Operations', 'President'])) {
        //     $department_list = $department_list->whereIn('department_id', $handledDepts);
        // }

        $department_list = $department_list->get();
        
        $dept_list = [];
        foreach ($department_list as $row) {
            $kpi_count = DB::table('kpi')->where('department_id', $row->department_id)->count();
            $dept_list[] = [
                'department_id' => $row->department_id,
                'department' => $row->department,
                'kpi_count' => $kpi_count,
            ];
        }

        return view('client.modules.evaluation.setup.index', compact('designation', 'department', 'dept_list'));
    }

    public function setupKPI($department_id){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $dept_details = DB::table('departments')->where('department_id', $department_id)->first();

        $designation_list = DB::table('designation')->where('department_id', $department_id)->get();

        $objective_list = DB::table('objective')->get();

        $data = ['designation', 'department', 'dept_details', 'objective_list', 'designation_list'];

        return view('client.modules.evaluation.setup.kpi_setup', compact($data));
    }

    public function kpiTree($department){
        $kpi_list = DB::table('kpi')->where('department_id', $department)->get();
        $tree = [];
        foreach ($kpi_list as $lvl1) {
            $metrics = DB::table('metrics')->where('kpi_id', $lvl1->kpi_id)->get();
            $metric_list = [];
            foreach ($metrics as $lvl2) {
                $data_inputs = DB::table('data_input')->where('metric_id', $lvl2->metric_id)->get();
                $input_list = [];
                foreach ($data_inputs as $lvl3) {
                    $input_list[] = [
                        'input_id' => $lvl3->input_id,
                        'data_input' => $lvl3->data_input
                    ];
                }
                $metric_list[] = [
                    'metric_id' => $lvl2->metric_id,
                    'metric_name' => $lvl2->metric_name,
                    'metric_description' => $lvl2->metric_description,
                    'input_list' => $input_list
                ];
            }

            $tree[] = [
                'kpi_id' => $lvl1->kpi_id,
                'kpi_description' => $lvl1->kpi_description,
                'metric_list' => $metric_list
            ];
        }

        return view('client.modules.evaluation.setup.kpi_tree', compact('tree'));
    }

    public function createDataInputs(Request $request){
        $data = [];
        foreach ($request->data_input as $i => $row) {
            $data[] = [
                'metric_id' => $request->metric_id,
                'data_input' => $request->data_input[$i],
                'created_by' => Auth::user()->employee_name,
            ];
        }

        DB::table('data_input')->insert($data);

        return response()->json(['message' => 'Data Input has been created.']);
    }

    public function updateDataInput(Request $request){
        $data = [
            'data_input' => $request->input,
            'remarks' => $request->remarks,
            'last_modified_by' => Auth::user()->employee_name
        ];

        DB::table('data_input')->where('input_id', $request->id)->update($data);

        return response()->json(['message' => 'Data Input has been updated.']);
    }

    public function deleteDataInput(Request $request){
        DB::table('data_input')->where('input_id', $request->id)->delete();

        return response()->json(['message' => 'Data Input has been deleted.']);
    }

    public function showEvalSchedules(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $eval_scheds = DB::table('evaluation_schedule')->get();
        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.evaluation_schedule.index', compact('designation', 'department', 'eval_scheds', 'year_list'));
    }

    public function getSubmissionSchedules($period, $start_date, $year){
        $d1 = Carbon::parse($start_date)->format('Y-m-d');
        $d2 = Carbon::parse('last day of December'. $year)->format('Y-m-d');

        $s_date = new DateTime($d1);
        $e_date = new DateTime($d2);
        $e_date->modify('+1 day');

        if ($period == 'Monthly') {
            $interval = 'P1M';
        }elseif ($period == 'Quarterly') {
            $interval = 'P3M';
        }elseif ($period == 'Semi-Annual') {
            $interval = 'P6M';
        }elseif ($period == 'Annual') {
            $interval = 'P1Y';
        }

        $date_period = new DatePeriod($s_date, new DateInterval($interval), $e_date);

        $schedules = [];
        foreach ($date_period as $date) {
            $schedules[] = [
                'scheduled_date' => $date->format('Y-m-d')
            ];
        }

        return $schedules;
    }

    public function addEvalSchedule(Request $request){
        $requestedData = [
            'period' => $request->period,
            'start_date' => $request->start_date,
            'year' => $request->year,
            'is_active' => $request->is_active ? 1 : 0
        ];

        DB::table('evaluation_schedule')->insert($requestedData);

        return redirect()->back()->with(['message' => 'Evaluation Schedule has been created.']);
    }

    public function updateEvalSchedule(Request $request, $id){
        $requestedData = [
            'period' => $request->period,
            'start_date' => $request->start_date,
            'year' => $request->year,
            'is_active' => $request->is_active ? 1 : 0,
            'last_modified_by' => Auth::user()->employee_name,
        ];

        DB::table('evaluation_schedule')->where('eval_sched_id', $id)->update($requestedData);

        return redirect()->back()->with(['message' => 'Evaluation Schedule has been updated.']);
    }

    public function deleteEvalSchedule($id){

        DB::table('evaluation_schedule')->where('eval_sched_id', $id)->delete();

        return redirect()->back()->with(['message' => 'Evaluation Schedule has been deleted.']);
    }

    public function viewEvalSchedule($id){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $schedule_details = DB::table('evaluation_schedule')->where('eval_sched_id', $id)->first();

        $kpi_list = DB::table('kpi')->where('evaluation_period', $schedule_details->period)->get();
                        // ->where('category', 'Quantitative')

        $schedules = $this->getSubmissionSchedules($schedule_details->period, $schedule_details->start_date, $schedule_details->year);

        return view('client.modules.evaluation.evaluation_schedule.view', compact('designation', 'department', 'schedule_details', 'kpi_list', 'schedules'));
    }

    public function showAppraisal(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $handledDepts = $this->getHandledDepts(Auth::user()->user_id);
        $employee_list = DB::table('users');
        if (!in_array($designation, ['Human Resources Head', 'Director of Operations', 'President'])) {
            $employee_list = $employee_list->whereIn('department_id', $handledDepts);
        }

        $employee_list = $employee_list->orderBy('employee_name', 'asc')->get();

        $performance_appraisal = DB::table('appraisal_result')
                    ->join('users', 'users.user_id', 'appraisal_result.employee_id')
                    ->select(DB::raw('(SELECT employee_name FROM users WHERE user_id = appraisal_result.evaluated_by) as evaluator'), 'users.employee_name', 'appraisal_result.status', 'appraisal_result.evaluation_date', 'appraisal_result.appraisal_result_id')->get();

        return view('client.modules.evaluation.appraisal.index', compact('designation', 'department', 'employee_list', 'performance_appraisal'));
    }

    public function viewAppraisal($id){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $appraisal_result = DB::table('appraisal_result')->where('appraisal_result_id', $id)->first();

        $employee_id = $appraisal_result->employee_id;

        $employee_details = DB::table('users')
                ->join('departments', 'departments.department_id', 'users.department_id')
                ->join('designation', 'designation.des_id', 'users.designation_id')
                ->join('shift_groups', 'shift_groups.id', 'users.shift_group_id')
                ->select('users.*', 'designation.designation', 'departments.department','shift_groups.shift_group_name')
                ->where('users.user_id', $employee_id)->first();

        $ratee = DB::table('users')->join('designation', 'users.designation_id', 'designation.des_id')
                    ->where('user_id', $appraisal_result->evaluated_by)
                    ->select('users.employee_name', 'designation.designation')->first();

        $last_evaluation_date = DB::table('appraisal_result')
                ->where('employee_id', $employee_id)
                ->where('status', 'Submitted')
                ->max('evaluation_date');

        $appraisal_details = [
            'evaluation_period_from' => $appraisal_result->evaluation_period_from,
            'evaluation_period_to' => $appraisal_result->evaluation_period_to,
            'purpose' => $appraisal_result->purpose_type,
            'last_evaluation_date' => $last_evaluation_date
        ];

        $qualitative_kpi = DB::table('kpi')->where('category', 'Qualitative')->get();

        $qualitative_kpi_set = DB::table('qualitative_kpi_result')
                ->join('kpi', 'qualitative_kpi_result.kpi_id', 'kpi.kpi_id')
                ->where('qualitative_kpi_result.appraisal_result_id', $id)
                ->where('category', 'Qualitative')->get();

        return view('client.modules.evaluation.appraisal.view', compact('designation', 'department', 'employee_details', 'appraisal_details', 'qualitative_kpi', 'qualitative_kpi_set', 'appraisal_result', 'ratee'));
    }

    public function deleteAppraisal(Request $request, $id){
        DB::table('appraisal_result')->where('appraisal_result_id', $id)->delete();
        DB::table('qualitative_kpi_result')->where('appraisal_result_id', $id)->delete();

        return redirect('/evaluation/appraisal')->with(['message' => 'Performance Appraisal for <b>'.$request->employee_name.'</b> been deleted.']);
    }

    public function createAppraisal(Request $request){
        $user_id = $request->employee;
        $purpose = $request->purpose;
        $from_month = $request->period_from_month;
        $from_year = $request->period_from_year;
        $to_month = $request->period_to_month;
        $to_year = $request->period_to_year;

        return redirect('/evaluation/appraisal/form/'.$user_id.'/'.$from_month.'/'.$from_year.'/'.$to_month.'/'.$to_year.'/'.$purpose);
    }

    public function showAppraisalForm($user_id, $from_month, $from_year, $to_month, $to_year, $purpose){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $eval_period_from = date("F", mktime(0, 0, 0, $from_month, 10)) .' '. $from_year;
        $eval_period_to = date("F", mktime(0, 0, 0, $to_month, 10)) .' '. $to_year;

        $period_from = new Carbon('first day of '.$eval_period_from.'');
        $period_to = new Carbon('last day of '.$eval_period_to.'');

        $last_evaluation_date = DB::table('appraisal_result')
                ->where('employee_id', $user_id)
                ->where('status', 'Submitted')
                ->max('evaluation_date');

        $appraisal_details = [
            'evaluation_period_from' => $period_from,
            'evaluation_period_to' => $period_to,
            'purpose' => $purpose,
            'last_evaluation_date' => $last_evaluation_date
        ];

        $employee_details = DB::table('users')
                ->join('departments', 'departments.department_id', 'users.department_id')
                ->join('designation', 'designation.des_id', 'users.designation_id')
                ->join('shift_groups', 'shift_groups.id', 'users.shift_group_id')
                ->select('users.*', 'designation.designation', 'departments.department','shift_groups.shift_group_name')
                ->where('users.user_id', $user_id)->first();

        $qualitative_kpi = DB::table('kpi')->where('category', 'Qualitative')->get();

        return view('client.modules.evaluation.appraisal.form', compact('designation', 'department', 'employee_details', 'appraisal_details', 'qualitative_kpi'));
    }

    public function printAppraisal($id){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $appraisal_result = DB::table('appraisal_result')->where('appraisal_result_id', $id)->first();

        $employee_id = $appraisal_result->employee_id;

        $employee_details = DB::table('users')
                ->join('departments', 'departments.department_id', 'users.department_id')
                ->join('designation', 'designation.des_id', 'users.designation_id')
                ->join('shift_groups', 'shift_groups.id', 'users.shift_group_id')
                ->select('users.*', 'designation.designation', 'departments.department','shift_groups.shift_group_name')
                ->where('users.user_id', $employee_id)->first();

        $ratee = DB::table('users')->join('designation', 'users.designation_id', 'designation.des_id')
                    ->where('user_id', $appraisal_result->evaluated_by)
                    ->select('users.employee_name', 'designation.designation')->first();

        $last_evaluation_date = DB::table('appraisal_result')
                ->where('employee_id', $employee_id)
                ->where('status', 'Submitted')
                ->max('evaluation_date');

        $appraisal_details = [
            'evaluation_period_from' => $appraisal_result->evaluation_period_from,
            'evaluation_period_to' => $appraisal_result->evaluation_period_to,
            'purpose' => $appraisal_result->purpose_type,
            'last_evaluation_date' => $last_evaluation_date
        ];

        $qualitative_kpi = DB::table('kpi')->where('category', 'Qualitative')->get();

        $qualitative_kpi_set = DB::table('qualitative_kpi_result')
                ->join('kpi', 'qualitative_kpi_result.kpi_id', 'kpi.kpi_id')
                ->where('qualitative_kpi_result.appraisal_result_id', $id)
                ->where('category', 'Qualitative')->get();

        return view('client.modules.evaluation.appraisal.print', compact('designation', 'department', 'employee_details', 'appraisal_details', 'qualitative_kpi', 'qualitative_kpi_set', 'appraisal_result', 'ratee'));
    }

    public function saveAppraisal(Request $request){
        $appraisal_result = [
            'employee_id' => $request->employee_id,
            'overall_ratings' => $request->overall_rating,
            'recommendations' => $request->recommendation,
            'remarks' => $request->remarks,
            'status' => $request->status,
            'improvement_areas' => $request->improvement_areas,
            'good_points' => $request->good_points,
            'evaluation_date' => $request->evaluation_date,
            'evaluated_by' => $request->evaluated_by,
            'evaluation_period_from' => $request->evaluation_from,
            'evaluation_period_to' => $request->evaluation_to,
            'purpose_type' => $request->purpose,
        ];

        $appraisal_id = DB::table('appraisal_result')->insertGetId($appraisal_result);

        $qualitative_result = [];
        foreach ($request->kpi as $i => $row) {
            $rating = 'rating'.$request->kpi[$i];
            $qualitative_result[] = [
                'appraisal_result_id' => $appraisal_id,
                'kpi_id' => $request->kpi[$i],
                'rating' => $request->$rating,
                'comment' => $request->comment[$i]
            ];
        }

        DB::table('qualitative_kpi_result')->insert($qualitative_result);

        return redirect('/evaluation/appraisal/view/'.$appraisal_id)->with(['message' => 'Performance Appraisal has been saved as Draft.']);                    
    }

    public function empStats(Request $request, $employee_id){
        $evaluation_period_from = $request->evaluation_period_from;
        $evaluation_period_to = $request->evaluation_period_to; 

        $unfiled_absences = $this->getUnfiledAbsences($employee_id, $evaluation_period_from, $evaluation_period_to);
        $working_days = $this->getWorkingDays($evaluation_period_from, $evaluation_period_to);
        
        $logs = $this->attendanceLogs($employee_id, $evaluation_period_from, $evaluation_period_to);
        $days_present = $this->getTotalDaysPresent($employee_id, $evaluation_period_from, $evaluation_period_to);

        $working_rate = ($days_present / $working_days) * 100;
        $absence_rate = 100 - $working_rate;

        $stats = [
            'unfiled_absences' => $unfiled_absences['summary']['total'],
            'total_lates' => collect($logs)->sum('late_in_minutes').' min(s)',
            'working_rate' => round($working_rate, 2).'%',
            'absence_rate' => round($absence_rate, 2).'%',
        ];

        return response()->json($stats);
    }

    public function empDataInputsERP(Request $request, $employee_id){
        $employee_details = DB::table('users')->where('user_id', $employee_id)->first();
        $evaluation_period_from = $request->evaluation_period_from;
        $evaluation_period_to = $request->evaluation_period_to;

        $result = null;
        if ($employee_details->department_id == 3) {
            $result = $this->engineeringEmpKpiERP($employee_id, $evaluation_period_from, $evaluation_period_to);
        }

        return $result;
    }

    public function empKpiManualEntry($employee_id, $evaluation_period_from, $evaluation_period_to){
        $employee_details = DB::table('users')->where('user_id', $employee_id)->first();

        $from_date_filter = new DateTime($evaluation_period_from);
        $to_date_filter = new DateTime($evaluation_period_to);
        $to_date_filter->modify('+1 day');

        $period = new DatePeriod($from_date_filter, new DateInterval('P1M'), $to_date_filter);

        $kpi_timeliness = [];
        foreach ($period as $m) {
            $month_list[] = [
                'month' => $m->format('F'),
                'year' => $m->format('Y'),
            ];

            $timeliness_det = DB::table('kpi_datainput_result')
                ->select('due_date', 'status', 'evaluation_period')
                ->whereYear('due_date', $m->format('Y'))
                ->whereMonth('due_date', $m->format('m'))
                ->where('user_id', $employee_id)
                ->distinct()->get();

            $kpi_timeliness[] = [
                'month' => $m->format('m Y'),
                'timeliness_det' => $timeliness_det,
            ];
        }

        $kpi_list = DB::table('kpi')
                ->whereIn('kpi_id',function($query) use ($employee_details){
                    $query->select('kpi_id')->from('kpi_per_designation')->where('designation_id', $employee_details->designation_id);
                })->get();

        $quantitative_kpi_result = [];
        foreach ($kpi_list as $kpi) {
            $metric_list = [];
            $metrics = DB::table('metrics')->where('kpi_id', $kpi->kpi_id)->get();
            foreach ($metrics as $metric) {
                $metrics_per_month = [];
                foreach ($period as $m) {
                    $metrics_per_emp = DB::table('kpi_datainput_result')
                            ->join('data_input', 'data_input.input_id', 'kpi_datainput_result.data_input_id')
                            ->where('kpi_datainput_result.user_id', $employee_id)->where('month', (int)$m->format('m'))
                            ->where('year', $m->format('Y'))->where('data_input.metric_id', $metric->metric_id)->get();

                    if (count($metrics_per_emp) <= 0) {
                        $metrcis_all = DB::table('kpi_datainput_result')
                            ->join('data_input', 'data_input.input_id', 'kpi_datainput_result.data_input_id')
                            ->where('kpi_datainput_result.user_id', null)->where('month', (int)$m->format('m'))
                            ->where('year', $m->format('Y'))->where('data_input.metric_id', $metric->metric_id)
                            ->get();
                    }

                    $metric_result = count($metrics_per_emp) <= 0 ? $metrcis_all : $metrics_per_emp;
                    
                    $total = collect($metric_result)->sum('answer');
                    $metrics_per_month[] = [
                        'month' => $m->format('m'),
                        'year' => $m->format('Y'),
                        'total' => $total,
                    ];
                }

                $input_list = [];
                $data_inputs = DB::table('data_input')->where('metric_id', $metric->metric_id)->get();
                foreach ($data_inputs as $input) {
                    $result_per_month = [];
                    foreach ($period as $m) {
                        $data_input_per_emp = DB::table('kpi_datainput_result')
                            ->where('data_input_id', $input->input_id)->where('user_id', $employee_id)
                            ->where('month', (int)$m->format('m'))->where('year', $m->format('Y'))->first();

                        $total_per_emp = $data_input_per_emp ? $data_input_per_emp->answer : 0;
                        
                        $total_all = 0;
                        if (!$data_input_per_emp) {
                            $data_input_all = DB::table('kpi_datainput_result')
                                ->where('data_input_id', $input->input_id)->where('user_id', null)
                                ->where('month', (int)$m->format('m'))->where('year', $m->format('Y'))->first();

                            $total_all = $data_input_all ? $data_input_all->answer : 0;
                        }

                        $total_inputs = $total_per_emp <= 0 ? $total_all : $total_per_emp;
                        $result_per_month[] = [
                            'month' => $m->format('m'),
                            'year' => $m->format('Y'),
                            'total' => $total_inputs,
                        ];
                    }

                    $input_list[] = [
                        'data_input_id' => $input->input_id,
                        'data_input' => $input->data_input,
                        'result_per_month' => $result_per_month,
                    ];
                }

                $metric_list[] = [
                    'metric_description' => $metric->metric_description,
                    'metrics_per_month' => $metrics_per_month,
                    'data_inputs' => $input_list
                ];
            }

            $kpi_result_per_month = [];
            foreach ($period as $m) {
                $kpi_totals = DB::table('kpi_result')->where('kpi_id', $kpi->kpi_id)
                        ->where('month', (int)$m->format('m'))
                        ->where('year', $m->format('Y'))->first();

                $total = $kpi_totals ? $kpi_totals->kpi_answer : null;;

                $kpi_result_per_month[] = [
                    'month' => $m->format('m'),
                    'year' => $m->format('Y'),
                    'total' => $total
                ];
            }
            
            $quantitative_kpi_result[] = [
                'kpi_description' => $kpi->kpi_description,
                'kpi_result_per_month' => $kpi_result_per_month,
                'metrics' => $metric_list
            ];
        }

        return view('client.modules.evaluation.appraisal.tables.department.engineering.engineering_emp_kpi_manual', compact('quantitative_kpi_result', 'month_list', 'kpi_timeliness'));
    }

    public function empDataInputsManualEntry(Request $request, $employee_id){
        $employee_details = DB::table('users')->where('user_id', $employee_id)->first();
        $evaluation_period_from = $request->evaluation_period_from;
        $evaluation_period_to = $request->evaluation_period_to;

        $result = $this->empKpiManualEntry($employee_id, $evaluation_period_from, $evaluation_period_to);

        return $result;
    }

    public function updateAppraisal(Request $request){
        $appraisal_result = [
            'overall_ratings' => $request->overall_rating,
            'recommendations' => $request->recommendation,
            'remarks' => $request->remarks,
            'status' => $request->status,
            'improvement_areas' => $request->improvement_areas,
            'good_points' => $request->good_points,
            'evaluation_date' => $request->evaluation_date,
            'evaluated_by' => $request->evaluated_by,
        ];
        
        DB::table('appraisal_result')->where('appraisal_result_id', $request->appraisal_result_id)->update($appraisal_result);

        $kpi_result = collect($request->kpi_result_id);

        $for_delete = DB::table('qualitative_kpi_result')
                ->where('appraisal_result_id', $request->appraisal_result_id)
                ->whereNotIn('kpi_result_id', $kpi_result)->delete();

        if ($request->kpi_result_id) {
            foreach($request->kpi_result_id as $i => $row){
                $rating = 'rating'.$request->kpi_result_id[$i];
                $qualitative_result = [
                    'rating' => $request->$rating,
                    'comment' => $request->comment[$i]
                ];

                DB::table('qualitative_kpi_result')->where('kpi_result_id', $request->kpi_result_id[$i])->update($qualitative_result);
            }
        }

        $for_insert = [];
        if ($request->new_kpi) {
            foreach($request->new_kpi as $i => $row){
                $rating = 'new_rating'.$request->new_kpi[$i];
                $for_insert[] = [
                    'appraisal_result_id' => $request->appraisal_result_id,
                    'kpi_id' => $request->new_kpi[$i],
                    'rating' => $request->$rating,
                    'comment' => $request->new_comment[$i]
                ];
            }
        }

        DB::table('qualitative_kpi_result')->insert($for_insert);
    
        return redirect('/evaluation/appraisal')->with(['message' => 'Performance Appraisal has been Submitted.']);
    }

    public function qualitativeKpi(){
        return DB::table('kpi')->where('category', 'Qualitative')->get();
    }

    public function showEmployeeInputsDept(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $handledDepts = $this->getHandledDepts(Auth::user()->user_id);
        $department_list = DB::table('departments');
        if (!in_array($designation, ['Human Resources Head', 'Director of Operations', 'President'])) {
            $department_list = $department_list->whereIn('department_id', $handledDepts);
        }

        $department_list = $department_list->get();

        return view('client.modules.evaluation.employee_inputs.index', compact('designation', 'department', 'department_list'));
    }

    public function showEmployeeInputsForm($department_id){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi = DB::table('kpi')->where('department_id', $department_id)->get();
        $designation_list = DB::table('designation')->where('department_id', $department_id)->get();

        $department_name = DB::table('departments')->where('department_id', $department_id)->pluck('department')->first();

        $kpi_data = [];
        foreach ($kpi as $row) {
            $kpi_data[] = [
                'id' => $row->kpi_id,
                'description' => $row->kpi_description,
            ];
        }

        return view('client.modules.evaluation.employee_inputs.form', compact('designation', 'department', 'designation_list', 'kpi_data', 'department_name'));
    }

    public function viewEmployeeInputs($department_id){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $designation_list = DB::table('designation')->where('department_id', $department_id)->get();

        $department_name = DB::table('departments')->where('department_id', $department_id)->pluck('department')->first();
        $kpi = DB::table('kpi')->where('department_id', $department_id)->get();
        $employee_data_inputs = [];
        foreach ($kpi as $row) {
            $employee_data_inputs[] = [
                'id' => $row->kpi_id,
                'kpi_description' => $row->kpi_description,
                'designation_metrics' => $this->getKpiDesignation($row->kpi_id, $department_id)
            ];
        }

        return view('client.modules.evaluation.employee_inputs.view', compact('designation', 'department', 'department_name', 'employee_data_inputs'));
    }

    // get designation from kpi's created with metrics
    public function getKpiDesignation($kpi_id, $department_id){
        $designation = DB::table('designation')
                    // ->whereIn('des_id',function($query) use ($kpi_id){
                    //     $query->select('designation_id')->from('metrics')->where('kpi_id', $kpi_id);
                    // })
                    ->where('department_id', $department_id)
                    ->get();

        $list = [];
        foreach ($designation as $row) {
            $list[] = [
                'designation_id' => $row->des_id,
                'designation_name' => $row->designation,
                'metrics' => $this->getMetrics($kpi_id, $row->des_id),
            ];
        }

        return $list;
    }

    // get metrics from kpi
    public function getMetrics($kpi_id, $designation_id){
        return DB::table('metrics')
                ->when($kpi_id, function($query) use ($kpi_id){
                    return $query->where('kpi_id', $kpi_id);
                })
                ->when($designation_id, function($query) use ($designation_id){
                    return $query->where('designation_id', $designation_id);
                })
                ->whereNotNull('entry_schedule')
                ->get();
    }

    public function updateEmpInputs(Request $request){
        $metric_result = collect($request->metric_id);
        $old_metric_id = collect($request->old_metric_id);

        $for_delete = DB::table('metrics')
                ->whereIn('metric_id', $old_metric_id)
                ->whereNotIn('metric_id', $metric_result)->delete();

        // for update
        if ($request->metric_id) {
            foreach($request->metric_id as $i => $row){
                $metrics = [
                    'metric_description' => $request->input_details[$i],
                    'entry_schedule' => $request->schedule[$i]
                ];

                DB::table('metrics')->where('metric_id', $request->metric_id[$i])->update($metrics);
            }
        }

        // for insert
        if ($request->new_kpi) {
            $requestedData = [];
            foreach ($request->new_kpi as $i => $row) {
                $requestedData[] = [
                    'kpi_id' => $request->new_kpi[$i],
                    'designation_id' => $request->new_designation[$i],
                    'metric_description' => $request->new_input_details[$i],
                    'entry_schedule' => $request->new_schedule[$i],
                    'metric_type' => $request->metric_type,
                ];
            }

            DB::table('metrics')->insert($requestedData);
        }

        return redirect()->back()->with(['message' => 'Employee Data Input Setup has been saved.']);
    }

    // START OBJECTIVE TREE
    // for overall quality objective tree
    public function viewObjectiveTree($objective){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $objective_details = DB::table('objective')->where('obj_id', $objective)->first();

        $tree_data = [
            'objective_id' => $objective_details->obj_id,
            'objective_description' => $objective_details->obj_description,
            'target' => $objective_details->target,
            'last_modified_by' => $objective_details->last_modified_by,
            'updated_at' => $objective_details->updated_at,
            'nodes' => $this->getDeptObjTree($objective_details->obj_id),
        ];

        return view('client.modules.evaluation.objective.view', compact('designation', 'department', 'tree_data'));
    }

    public function getDeptObjTree($objective){
        $department_list = DB::table('departments')
                ->whereIn('department_id', function($query) use ($objective){
                    $query->select('department_id')->from('kpi')->where('objective_id', $objective);
                })
                ->get();

        $result = [];
        foreach ($department_list as $row) {
            $result[] = [
                'department_id' => $row->department_id,
                'department_name' => $row->department,
                'kpi_details' => $this->getKpiObjTree($objective, $row->department_id),
            ];
        }

        return $result;
    }

    public function getKpiObjTree($objective, $department){
        $kpi_list = DB::table('kpi')
                ->when($objective, function($query) use ($objective){
                    return $query->where('objective_id', $objective);
                })
                ->when($department, function($query) use ($department){
                    return $query->where('department_id', $department);
                })->select('kpi_id', 'kpi_description')
                ->get();

        $result = [];
        foreach ($kpi_list as $row) {
            $result[] = [
                'kpi_id' => $row->kpi_id,
                'kpi_description' => $row->kpi_description,
                'metrics' => $this->getMetricsObjTree($row->kpi_id)
            ];
        }

        return $result;
    }

    public function getMetricsObjTree($kpi){
        $metric_list = DB::table('metrics')
                ->when($kpi, function($query) use ($kpi){
                    return $query->where('kpi_id', $kpi);
                })->select('metric_id', 'metric_description')
                ->get();

        $result = [];
        foreach ($metric_list as $row) {
            $result[] = [
                'metric_id' => $row->metric_id,
                'metric_description' => $row->metric_description,
            ];
        }

        return $result;
    }
    // END OBJECTIVE TREE

    //////Patrick
    public function metric_data(){
        $data = DB::table('metrics')
            ->join('kpi', 'kpi.kpi_id','=','metrics.kpi_id')
            ->where('designation_id', Auth::user()->designation_id )
            ->get();
           
        return $data;
    }
    
    public function getEmpAppraisal($user){
        $appraisal_list = DB::table('appraisal_result')
                // ->join('users', 'users.user_id', 'appraisal_result.evaluated_by')
                ->where('appraisal_result.employee_id', $user)
                ->where('appraisal_result.status', 'Submitted')
                ->select(DB::raw('(SELECT employee_name FROM users WHERE user_id = appraisal_result.evaluated_by) AS evaluated_by'), 'appraisal_result.status', 'appraisal_result.evaluation_date', 'appraisal_result.appraisal_result_id', 'appraisal_result.purpose_type', 'appraisal_result.evaluation_period_from', 'appraisal_result.evaluation_period_to')
                ->get();

        return view('client.modules.evaluation.appraisal.tables.employee_appraisal_list', compact('appraisal_list'));
    }

    public function getEmpKpiResult(Request $request, $user){
        $kpi_result = DB::table('kpi_result')
                ->join('kpi', 'kpi.kpi_id', 'kpi_result.kpi_id')
                ->where('user_id', $user)
                ->where('kpi_result.month', $request->filmonth)
                ->where('kpi_result.year', $request->filyear)
                ->get();

        return view('client.modules.evaluation.kpi.tables.employee_kpi_result', compact('kpi_result'));
    }

    public function viewEmpAppraisalResult($user_id){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $appraisal_result = DB::table('appraisal_result')->where('appraisal_result_id', $user_id)->first();

        $employee_id = $appraisal_result->employee_id;

        $ratee = DB::table('users')->join('designation', 'users.designation_id', 'designation.des_id')
                    ->where('user_id', $appraisal_result->evaluated_by)
                    ->select('users.employee_name', 'designation.designation')->first();

        $appraisal_details = [
            'evaluation_period_from' => $appraisal_result->evaluation_period_from,
            'evaluation_period_to' => $appraisal_result->evaluation_period_to,
            'purpose' => $appraisal_result->purpose_type,
        ];

        $kpi_data_inputs = DB::table('kpi_datainput_result')
                ->join('kpi','kpi_datainput_result.kpi_id','=','kpi.kpi_id')
                ->where('kpi_datainput_result.user_id', $user_id)
                ->select('kpi_datainput_result.kpi_id', 'kpi_description')
                ->groupBy('kpi_datainput_result.kpi_id', 'kpi_description')->get();

        $kpi_result_summary = [];
        foreach ($kpi_data_inputs as $row) {
            $kpi_metrics = DB::table('kpi_datainput_result')
                ->join('metrics','metrics.metric_id','=','kpi_datainput_result.metric_id')
                ->join('kpi','kpi_datainput_result.kpi_id','=','kpi.kpi_id')
                ->where('kpi_datainput_result.user_id', $user_id)
                ->where('kpi_datainput_result.kpi_id', $row->kpi_id)
                ->whereBetween('kpi_datainput_result.month', [(int)$from_month, (int)$to_month])
                ->whereBetween('kpi_datainput_result.year', [$from_year, $to_year])
                ->select(DB::raw('SUM(kpi_datainput_result.answer) as total'), 'metrics.metric_description')
                ->groupBy('kpi_datainput_result.metric_id', 'metrics.metric_description')
                ->get();   

            $kpi_result_summary[] = [
                'kpi_id' => $row->kpi_id,
                'kpi_description' => $row->kpi_description,
                'metrics_summary' => $kpi_metrics
            ];
        }

        $unfiled_absences = $this->getUnfiledAbsences($user_id, $period_from, $period_to);
        $working_days = $this->getWorkingDays($period_from, $period_to);

        $logs = $this->getBiometricLogs($user_id, $period_from, $period_to);
        $days_present = $this->getTotalDaysPresent($user_id, $period_from, $period_to);

        $working_rate = ($days_present / $working_days) * 100;
        $absence_rate = 100 - $working_rate;

        $stats = [
            'unfiled_absences' => $unfiled_absences['summary']['total'],
            'total_lates' => collect($logs)->sum('late_in_mins'),
            'working_rate' => $working_rate,
            'absence_rate' => $absence_rate,
        ];

        $qualitative_kpi = DB::table('kpi')->where('category', 'Qualitative')->get();

        $qualitative_kpi_set = DB::table('qualitative_kpi_result')
                ->join('kpi', 'qualitative_kpi_result.kpi_id', 'kpi.kpi_id')
                ->where('qualitative_kpi_result.appraisal_result_id', $user_id)
                ->where('category', 'Qualitative')->get();

        $employee_details = DB::table('users')
                ->join('departments', 'departments.department_id', 'users.department_id')
                ->join('designation', 'designation.des_id', 'users.designation_id')
                ->join('shift_groups', 'shift_groups.id', 'users.shift_group_id')
                ->select('users.*', 'designation.designation', 'departments.department','shift_groups.shift_group_name')
                ->where('users.user_id', $employee_id)->first();

        return view('client.modules.evaluation.appraisal.result', compact('designation', 'department', 'employee_details', 'appraisal_details', 'qualitative_kpi', 'qualitative_kpi_set', 'appraisal_result', 'stats', 'kpi_result_summary', 'ratee'));
    }

    public function showKpiResult(Request $request){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $department_list = DB::table('departments')->get();

        $year_list = DB::table('fiscal_year')->get();
        return view('client.modules.evaluation.reports.kpi_result', compact('designation', 'department', 'department_list', 'year_list'));
    }

    public function getKpiResult(Request $request){
        $kpi_list = DB::table('kpi')->where('department_id', $request->department)->where('set_kpi', 1)->get();

        $result = [];
        foreach($kpi_list as $lvl1){
            $metrics = $this->kpiMetrics($lvl1->kpi_id);
            $metric_data = [];
                foreach ($metrics as $lvl2) {
                    $data_input_list = $this->metricDataInputs($lvl2->metric_id);
                    $data_input = [];
                    foreach ($data_input_list as $lvl3) {
                        $data_input_result = $this->dataInputResult($lvl3->input_id, $request->month, $request->year);
                        $data_input_total = $data_input_result ? $data_input_result->answer : 'No data submitted';
                        $input_result_id = $data_input_result ? $data_input_result->id : null;
                        $data_input[] = [
                            'data_input_id' => $lvl3->input_id,
                            'data_input' => $lvl3->data_input,
                            'result' => $data_input_total,
                            'input_result_id' => $input_result_id
                        ];
                    }
                    $metric_result = $this->metricResult($lvl2->metric_id, $request->month, $request->year);
                    $metric_data[] = [
                        'metric_id' => $lvl2->metric_id,
                        'metric_description' => $lvl2->metric_description,
                        'metric_result' => $metric_result,
                        'data_input_list' => $data_input
                    ];
                }
            
            $result[] = [
                'kpi_id' => $lvl1->kpi_id,
                'kpi_description' => $lvl1->kpi_description,
                'metrics' => $metric_data,
            ];
        }

        // return $result;

        return view('client.modules.evaluation.reports.kpi_result_table', compact('result'));
    }

    public function updateDataInputResult(Request $request){
        $values = [
            'answer' => $request->result,
            'last_modified_by' => Auth::user()->employee_name
        ];

        DB::table('kpi_datainput_result')->where('id', $request->result_id)->update($values);

        return response()->json(['message' => 'Data Input Result for <b>'. $request->data_input . '</b> has been updated.']);
    }

    // OVERVIEW PER DEPARTMENT
    public function informationTechnology_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');
        
        $kpi=DB::table('kpi')
            ->where('kpi.department_id', 9)
            ->get();

        return view('client.modules.evaluation.overview.department.IT_index', compact('designation', 'department','kpi'));
    }

    public function kpi1_stats(Request $request){

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $current= date('n');
        $current_year= date('Y');

        $data = [];
        foreach ($months as $i => $month) {
            $target=$this->target_kpi(52);
            $percentage= collect($target)->sum('target');
            $i = $i + 1;
            $data_inputs = DB::table('kpi_datainput_result')
                ->join('data_input','data_input.input_id','=','kpi_datainput_result.data_input_id')
                ->join('metrics','metrics.metric_id','=','data_input.metric_id')
                ->where('kpi_datainput_result.month', $i)
                ->where('kpi_datainput_result.year', $request->year)
                ->get();
                
            $sumoflate = collect($data_inputs)->where('kpi_id', 52)->where('metric_id', 89)->sum('answer');
            $sumofontime = collect($data_inputs)->where('kpi_id', 52)->where('metric_id', 115)->sum('answer');
            $sumoflateandontime = $sumoflate + $sumofontime;

            if($sumofontime == 0){
                $var=0;
            }else{
                $var=$sumofontime;
            }
            if ($sumoflateandontime == 0) {
                $var1=1;
            }else{
                $var1=$sumoflateandontime;
            }

            $computation= ($var/ $var1) * 100;

            if ($current_year == $request->year) {
                if ($i <= $current) {
                    $data[] = [
                        'month' => $month,
                        'total' => round($computation,2),
                        'target'=>$percentage,
                    ];
                }
            }else{
                $data[] = [
                    'month' => $month,
                    'total' => round($computation,2),
                    'target'=>$percentage,
                ];
            }
        }

        return $data;
    }

    public function target_kpi($kpi){
        $target=DB::table('kpi')->select('target')->where('kpi_id',$kpi)->get();
        $data = [];
        foreach ($target as $i) {
            $data[] = [
                'target'=>$i->target,
            ];
        }

        return $data;
    }

    public function kpi2_stats(Request $request){
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $current= date('n');
        $current_year= date('Y');

        $data = [];
        foreach ($months as $i => $month) {
            $target=$this->target_kpi(52);

            $i = $i + 1;
            $data_inputs = DB::table('kpi_datainput_result')
                ->join('data_input','data_input.input_id','=','kpi_datainput_result.data_input_id')
                ->join('metrics','metrics.metric_id','=','data_input.metric_id')
                ->where('kpi_datainput_result.month', $i)
                ->where('kpi_datainput_result.year', $request->year)
                ->where('kpi_datainput_result.user_id', null)
                ->get();
                
            $sumofdowntime = collect($data_inputs)->where('kpi_id', 53)->where('metric_id', 114)->sum('answer');
            $totalhrsinperiod = collect($data_inputs)->where('kpi_id', 53)->where('metric_id', 113)->sum('answer');

            if($sumofdowntime == 0){
                $var=1;
            }else{
                $var=$sumofdowntime;
            }
            if ($totalhrsinperiod == 0) {
                $var1=1;
            }else{
                $var1=$totalhrsinperiod;
            }
            $computation= (($var1 - $var) / $var1) * 100;
            $percentage= collect($target)->sum('target');
            if ($current_year == $request->year) {
                if ($i <= $current) {
                    $data[] = [
                        'month' => $month,
                        'total' => round($computation,2),
                        'target'=>$percentage,
                        
                    ];
                }
            }else{
                $data[] = [
                    'month' => $month,
                    'total' => round($computation,2),
                    'target'=>$percentage,
                ];
            }
        }

        return response()->json($data);
    }

    public function kpi3_stats(Request $request){
        $months = ['2018', '2019', '2020', '2021', '2022', '2023', '2024', '2025'];

        $data = [];
        foreach ($months as $i => $month) {
            $target=$this->target_kpi(64);
            $i = $i + 1;

            $data_inputs = DB::table('kpi_datainput_result')
                ->join('data_input','data_input.input_id','=','kpi_datainput_result.data_input_id')
                ->join('metrics','metrics.metric_id','=','data_input.metric_id')
                ->where('kpi_datainput_result.month', $i)
                ->where('kpi_datainput_result.year', $request->year)

                ->get();
            $total = collect($data_inputs)->where('kpi_id', 64)->where('metric_id', 116)->sum('answer');
            $percentage= collect($target)->sum('target');
            $current= date('n');
            $current_year= date('Y');
            if ($current_year == $request->year) {
                if ($i <= $current) {
                    $data[] = [
                        'month' => $month,
                        'total' => $total,
                        'target'=>$percentage,
                    ];
                }
            }else{
                $data[] = [
                    'month' => $month,
                    'total' => $total,
                    // 'target'=>$trydate,
                ];
            }
        }

        return $data;
    }

    public function technicalLevel_stats(Request $request){
        $kpi = DB::table('kpi_datainput_result')
        ->join('data_input','data_input.input_id','=','kpi_datainput_result.data_input_id')
        ->where('kpi_datainput_result.month', $request->tech_month)    
        ->where('kpi_datainput_result.year', $request->tech_year)
        ->get();
        $data=[];
        // $sum_allLevel= collect($kpi)->sum('answer');
        $level_1=collect($kpi)->where('data_input_id', 74)->sum('answer');
        $level_2=collect($kpi)->where('data_input_id', 75)->sum('answer');
        $level_3=collect($kpi)->where('data_input_id', 76)->sum('answer');
        $sum_allLevel = $level_1 + $level_2 + $level_3;

        if($sum_allLevel == 0){
            $var=1;
        }else{
            $var=$sum_allLevel;
        }
        
        $data = [
            'level1' => round(($level_1 / $var) * 100, 2),
            'level2' => round(($level_2 / $var) * 100, 2),
            'level3' => round(($level_3 / $var) * 100, 2)
        ];

        return response()->json($data);
    }

    public function viewKPIresult_IT(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        return view('client.modules.evaluation.overview.department.it.IT_kpi_result', compact('designation', 'department'));
    }

    public function IT_departmentKpiResult(Request $request, $department){
        $month = $request->month;
        $year = $request->year;
        $evaluation_period = $request->period;

        $department_name = DB::table('departments')->where('department_id', $department)->pluck('department')->first();
        $kpi_result = [];
        $kpi_list = DB::table('kpi')->join('kpi_result', 'kpi_result.kpi_id', 'kpi.kpi_id')
            ->where('kpi.set_kpi', 1)->where('kpi.department_id', $department)
            ->where('kpi.evaluation_period', $evaluation_period)->where('kpi_result.set_to_all', 1)
            ->where('kpi_result.month', $month)->where('kpi_result.year', $year)->get();

        if (count($kpi_list) > 0) {
            $is_per_department = 1;
            foreach ($kpi_list as $kpi) {
                $submission_details = DB::table('metrics')
                    ->join('data_input', 'metrics.metric_id', 'data_input.metric_id')
                    ->join('kpi_datainput_result', 'kpi_datainput_result.data_input_id', 'data_input.input_id')
                    ->where('kpi_datainput_result.evaluation_period', $evaluation_period)
                    ->where('kpi_datainput_result.month', $month)->where('kpi_datainput_result.year', $year)
                    ->where('metrics.kpi_id', $kpi->kpi_id)->where('kpi_datainput_result.user_id', null)
                    ->select('kpi_datainput_result.date_submitted', 'kpi_datainput_result.status', 'kpi_datainput_result.submitted_by')
                    ->distinct()->first();

                $kpi_metric = [];
                $kpi_metric_list = DB::table('metrics')->where('kpi_id', $kpi->kpi_id)->get();
                foreach ($kpi_metric_list as $kpi_metric_row) {
                    $data_inputs = DB::table('data_input')
                            ->join('kpi_datainput_result', 'kpi_datainput_result.data_input_id', 'data_input.input_id')
                            ->where('metric_id', $kpi_metric_row->metric_id)
                            ->where('user_id', null)
                            ->where('month', $month)->where('year', $year)
                            ->select('data_input.data_input', DB::raw('SUM(kpi_datainput_result.answer) as total'))
                            ->groupBy('data_input.data_input')
                            ->get();

                    $kpi_metric[] = [
                        'metric_id' => $kpi_metric_row->metric_id,
                        'metric_description' => $kpi_metric_row->metric_description,
                        'metric_total' => collect($data_inputs)->sum('total'),
                        'data_inputs' => $data_inputs
                    ];
                }
                $kpi_result[] = [
                    'kpi_id' => $kpi->kpi_id,
                    'kpi_description' => $kpi->kpi_description,
                    'kpi_metrics' => $kpi_metric,
                    'date_submitted' => $submission_details ? $submission_details->date_submitted : null,
                    'submitted_by' => $submission_details ? $submission_details->submitted_by : null,
                    'status' => $submission_details ? $submission_details->status : null,
                ];
            }
        }else{
            $is_per_department = 0;
            $kpi_list = DB::table('kpi')
                ->where('department_id', $department)->where('set_kpi', 0)
                ->where('evaluation_period', $evaluation_period)
                ->get();

            $kpi_result = [];
            foreach ($kpi_list as $kpi) {
                $employee_result = [];
                $employee_list = DB::table('users')
                        ->select('employee_name', 'user_id')
                        ->whereIn('designation_id',function($query) use ($kpi){
                            $query->select('designation_id')->from('kpi_per_designation')->where('kpi_id', $kpi->kpi_id);
                        })
                        ->whereIn('user_id',function($query) use ($month, $year){
                            $query->select('user_id')->from('kpi_datainput_result')->where('month', $month)->where('year', $year);
                        })->get();

                $kpi_metric = [];
                $kpi_metric_list = DB::table('metrics')->where('kpi_id', $kpi->kpi_id)->get();
                foreach ($kpi_metric_list as $kpi_metric_row) {
                    $data_inputs = DB::table('data_input')
                            ->join('kpi_datainput_result', 'kpi_datainput_result.data_input_id', 'data_input.input_id')
                            ->where('metric_id', $kpi_metric_row->metric_id)->where('user_id', '!=', null)
                            ->where('month', $month)->where('year', $year)
                            ->select('data_input.data_input', DB::raw('SUM(kpi_datainput_result.answer) as total'))
                            ->groupBy('data_input.data_input')->get();

                    $kpi_metric[] = [
                        'metric_id' => $kpi_metric_row->metric_id,
                        'metric_description' => $kpi_metric_row->metric_description,
                        'metric_total' => collect($data_inputs)->sum('total'),
                        'data_inputs' => $data_inputs
                    ];
                }

                foreach ($employee_list as $emp) {
                    $submission_details = DB::table('metrics')
                        ->join('data_input', 'metrics.metric_id', 'data_input.metric_id')
                        ->join('kpi_datainput_result', 'kpi_datainput_result.data_input_id', 'data_input.input_id')
                        ->where('kpi_datainput_result.evaluation_period', $evaluation_period)
                        ->where('kpi_datainput_result.month', $month)->where('kpi_datainput_result.year', $year)
                        ->where('metrics.kpi_id', $kpi->kpi_id)->where('kpi_datainput_result.user_id', $emp->user_id)
                        ->select('kpi_datainput_result.date_submitted', 'kpi_datainput_result.status', 'kpi_datainput_result.submitted_by')
                        ->distinct()->first();

                    $metric_result = [];
                    $metric_list = DB::table('metrics')->where('kpi_id', $kpi->kpi_id)->get();
                    foreach ($metric_list as $metric) {
                        $data_input_result = [];
                        $data_input_list = DB::table('data_input')
                                ->join('kpi_datainput_result', 'kpi_datainput_result.data_input_id', 'data_input.input_id')
                                ->select('kpi_datainput_result.data_input_id', 'data_input.data_input', DB::raw('SUM(kpi_datainput_result.answer) as total'))
                                ->where('metric_id', $metric->metric_id)->where('month', $month)
                                ->where('year', $year)->where('user_id', $emp->user_id)
                                ->groupBy('kpi_datainput_result.data_input_id', 'data_input.data_input')->get();

                        foreach ($data_input_list as $input) {
                            $data_input_result[] = [
                                'data_input_id' => $input->data_input_id,
                                'data_input' => $input->data_input,
                                'total' => $input->total,
                            ];
                        }

                        $metric_result[] = [
                            'metric_id' => $metric->metric_id,
                            'metric_description' => $metric->metric_description,
                            'data_input_result' => $data_input_result,
                        ];
                    }

                    $employee_result[] = [
                        'user_id' => $emp->user_id,
                        'employee_name' => $emp->employee_name,
                        'metric_result' => $metric_result,
                        'date_submitted' => $submission_details ? $submission_details->date_submitted : null,
                        'submitted_by' => $submission_details ? $submission_details->submitted_by : null,
                        'status' => $submission_details ? $submission_details->status : null,
                    ];
                }

                $kpi_result[] = [
                    'kpi_id' => $kpi->kpi_id,
                    'kpi_description' => $kpi->kpi_description,
                    'kpi_metrics' => $kpi_metric,
                    'employee_result' => $employee_result
                ];
            }
        }

        return view('client.modules.evaluation.reports.data_input_result_table', compact('kpi_result', 'department_name', 'is_per_department'))->render();
    }

    public function viewKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $department_list = DB::table('departments')->get();

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.reports.data_input_result', compact('year_list', 'department_list', 'designation', 'department'));
    }

    public function departmentKpiResult(Request $request, $department){
        $month = $request->month;
        $year = $request->year;
        $evaluation_period = $request->period;

        $department_name = DB::table('departments')->where('department_id', $department)->pluck('department')->first();
        $kpi_result = [];
        $kpi_list = DB::table('kpi')->join('kpi_result', 'kpi_result.kpi_id', 'kpi.kpi_id')
            ->where('kpi.set_kpi', 1)->where('kpi.department_id', $department)
            ->where('kpi.evaluation_period', $evaluation_period)->where('kpi_result.set_to_all', 1)
            ->where('kpi_result.month', $month)->where('kpi_result.year', $year)->get();

        if (count($kpi_list) > 0) {
            $is_per_department = 1;
            foreach ($kpi_list as $kpi) {
                $submission_details = DB::table('metrics')
                    ->join('data_input', 'metrics.metric_id', 'data_input.metric_id')
                    ->join('kpi_datainput_result', 'kpi_datainput_result.data_input_id', 'data_input.input_id')
                    ->where('kpi_datainput_result.evaluation_period', $evaluation_period)
                    ->where('kpi_datainput_result.month', $month)->where('kpi_datainput_result.year', $year)
                    ->where('metrics.kpi_id', $kpi->kpi_id)->where('kpi_datainput_result.user_id', null)
                    ->select('kpi_datainput_result.date_submitted', 'kpi_datainput_result.status', 'kpi_datainput_result.submitted_by')
                    ->distinct()->first();

                $kpi_metric = [];
                $kpi_metric_list = DB::table('metrics')->where('kpi_id', $kpi->kpi_id)->get();
                foreach ($kpi_metric_list as $kpi_metric_row) {
                    $data_inputs = DB::table('data_input')
                            ->join('kpi_datainput_result', 'kpi_datainput_result.data_input_id', 'data_input.input_id')
                            ->where('metric_id', $kpi_metric_row->metric_id)
                            ->where('user_id', null)
                            ->where('month', $month)->where('year', $year)
                            ->select('data_input.data_input', DB::raw('SUM(kpi_datainput_result.answer) as total'))
                            ->groupBy('data_input.data_input')
                            ->get();

                    $kpi_metric[] = [
                        'metric_id' => $kpi_metric_row->metric_id,
                        'metric_description' => $kpi_metric_row->metric_description,
                        'metric_total' => collect($data_inputs)->sum('total'),
                        'data_inputs' => $data_inputs
                    ];
                }
                $kpi_result[] = [
                    'kpi_id' => $kpi->kpi_id,
                    'kpi_description' => $kpi->kpi_description,
                    'kpi_metrics' => $kpi_metric,
                    'date_submitted' => $submission_details ? $submission_details->date_submitted : null,
                    'submitted_by' => $submission_details ? $submission_details->submitted_by : null,
                    'status' => $submission_details ? $submission_details->status : null,
                ];
            }
        }else{
            $is_per_department = 0;
            $kpi_list = DB::table('kpi')
                ->where('department_id', $department)->where('set_kpi', 0)
                ->where('evaluation_period', $evaluation_period)->get();

            $kpi_result = [];
            foreach ($kpi_list as $kpi) {
                $employee_result = [];
                $employee_list = DB::table('users')
                        ->select('employee_name', 'user_id')
                        ->whereIn('designation_id',function($query) use ($kpi){
                            $query->select('designation_id')->from('kpi_per_designation')->where('kpi_id', $kpi->kpi_id);
                        })
                        ->whereIn('user_id',function($query) use ($month, $year){
                            $query->select('user_id')->from('kpi_datainput_result')->where('month', $month)->where('year', $year);
                        })->get();

                $kpi_metric = [];
                $kpi_metric_list = DB::table('metrics')->where('kpi_id', $kpi->kpi_id)->get();
                foreach ($kpi_metric_list as $kpi_metric_row) {
                    $data_inputs = DB::table('data_input')
                            ->join('kpi_datainput_result', 'kpi_datainput_result.data_input_id', 'data_input.input_id')
                            ->where('metric_id', $kpi_metric_row->metric_id)->where('user_id', '!=', null)
                            ->where('month', $month)->where('year', $year)
                            ->select('data_input.data_input', DB::raw('SUM(kpi_datainput_result.answer) as total'))
                            ->groupBy('data_input.data_input')->get();

                    $kpi_metric[] = [
                        'metric_id' => $kpi_metric_row->metric_id,
                        'metric_description' => $kpi_metric_row->metric_description,
                        'metric_total' => collect($data_inputs)->sum('total'),
                        'data_inputs' => $data_inputs
                    ];
                }

                foreach ($employee_list as $emp) {
                    $submission_details = DB::table('metrics')
                        ->join('data_input', 'metrics.metric_id', 'data_input.metric_id')
                        ->join('kpi_datainput_result', 'kpi_datainput_result.data_input_id', 'data_input.input_id')
                        ->where('kpi_datainput_result.evaluation_period', $evaluation_period)
                        ->where('kpi_datainput_result.month', $month)->where('kpi_datainput_result.year', $year)
                        ->where('metrics.kpi_id', $kpi->kpi_id)->where('kpi_datainput_result.user_id', $emp->user_id)
                        ->select('kpi_datainput_result.date_submitted', 'kpi_datainput_result.status', 'kpi_datainput_result.submitted_by')
                        ->distinct()->first();

                    $metric_result = [];
                    $metric_list = DB::table('metrics')->where('kpi_id', $kpi->kpi_id)->get();
                    foreach ($metric_list as $metric) {
                        $data_input_result = [];
                        $data_input_list = DB::table('data_input')
                                ->join('kpi_datainput_result', 'kpi_datainput_result.data_input_id', 'data_input.input_id')
                                ->select('kpi_datainput_result.data_input_id', 'data_input.data_input', DB::raw('SUM(kpi_datainput_result.answer) as total'))
                                ->where('metric_id', $metric->metric_id)->where('month', $month)
                                ->where('year', $year)->where('user_id', $emp->user_id)
                                ->groupBy('kpi_datainput_result.data_input_id', 'data_input.data_input')->get();

                        foreach ($data_input_list as $input) {
                            $data_input_result[] = [
                                'data_input_id' => $input->data_input_id,
                                'data_input' => $input->data_input,
                                'total' => $input->total,
                            ];
                        }

                        $metric_result[] = [
                            'metric_id' => $metric->metric_id,
                            'metric_description' => $metric->metric_description,
                            'data_input_result' => $data_input_result,
                        ];
                    }

                    $employee_result[] = [
                        'user_id' => $emp->user_id,
                        'employee_name' => $emp->employee_name,
                        'metric_result' => $metric_result,
                        'date_submitted' => $submission_details ? $submission_details->date_submitted : null,
                        'submitted_by' => $submission_details ? $submission_details->submitted_by : null,
                        'status' => $submission_details ? $submission_details->status : null,
                    ];
                }

                $kpi_result[] = [
                    'kpi_id' => $kpi->kpi_id,
                    'kpi_description' => $kpi->kpi_description,
                    'kpi_metrics' => $kpi_metric,
                    'employee_result' => $employee_result
                ];
            }
        }

        return view('client.modules.evaluation.reports.data_input_result_table', compact('kpi_result', 'department_name', 'is_per_department'))->render();
    }

    public function engineeringDataInputsERP(Request $request){
        $month = $request->month;
        $year = $request->year;

        $employees = DB::table('users')->where('department_id', 3)->get();
        $emp_dwg_timeliness_result = [];
        $emp_dwg_completion_result = [];
        foreach ($employees as $row) {
            $rfd = DB::connection('mysql_erp')->select('SELECT (SELECT access_id FROM `tabEmployee` WHERE name = drawn_by) as d, name, docstatus, category, DATEDIFF(date_issued, creation) as date_difference FROM `tabRequest for Drawing` WHERE MONTH(creation) = '.$month.' AND YEAR(creation) = '. $year.' AND date_issued IS NOT NULL AND (SELECT access_id FROM `tabEmployee` WHERE name = drawn_by) = "'.$row->user_id.'"');

            $dwgs = DB::connection('mysql_erp')->select('SELECT name FROM `tabRequest for Drawing` WHERE MONTH(creation) = '.$month.' AND YEAR(creation) = '. $year.' AND (SELECT access_id FROM `tabEmployee` WHERE name = drawn_by) = "'.$row->user_id.'"');

            $total_dwgs = collect($dwgs)->count();
            $accomplished_dwgs = collect($rfd)->where('docstatus', '<', 2)->count();
            $delayed_dwgs = collect($rfd)->where('date_difference', '>', 2)->count();

            $cancelled_dwgs = collect($rfd)->where('docstatus', '>', 1)->count();

            $rfd_accomplished_lamp_post = collect($rfd)->where('docstatus', '<', 2)->where('category', 'Lamp Post')->count();
            $rfd_accomplished_luminaire = collect($rfd)->where('docstatus', '<', 2)->where('category', 'Luminaire')->count();
            $rfd_accomplished_ins_guide = collect($rfd)->where('docstatus', '<', 2)->where('category', 'Installation Guide')->count();
            $rfd_accomplished_others = collect($rfd)->where('category', 'Others')->count();

            if ($accomplished_dwgs > 0) {
                $timeliness_percentage = (($accomplished_dwgs - $delayed_dwgs) / $accomplished_dwgs) * 100;
                $emp_dwg_timeliness_result[] = [
                    'employee_name' => $row->employee_name,
                    'accomplished_dwgs' => $accomplished_dwgs,
                    'delayed_dwgs' => $delayed_dwgs,
                    'result' => round($timeliness_percentage, 2),
                ];
                $completion_percentage = ($accomplished_dwgs / ($total_dwgs - $cancelled_dwgs)) * 100;
                $emp_dwg_completion_result[] = [
                    'employee_name' => $row->employee_name,
                    'accomplished_lamp_post' => $rfd_accomplished_lamp_post,
                    'accomplished_luminaire' => $rfd_accomplished_luminaire,
                    'accomplished_ins_guide' => $rfd_accomplished_ins_guide,
                    'accomplished_others' => $rfd_accomplished_others,
                    'cancelled_dwgs' => $cancelled_dwgs,
                    'result' => round($completion_percentage, 2),
                ];
            }
        }

        $result = [
            'timeliness_result' => $emp_dwg_timeliness_result,
            'completion_result' => $emp_dwg_completion_result,
        ];

        return view('client.modules.evaluation.overview.department.engineering.inputs_erp_table', compact('result'));
    }

    public function rfdPerMonthChart($year){
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $data = [];
        foreach ($months as $i => $month) {
            $i = $i + 1;
            $rfd = DB::connection('mysql_erp')->select('SELECT name, category, creation FROM `tabRequest for Drawing` WHERE MONTH(creation) = '.$i.' AND YEAR(creation) = '. $year);

            $total_rfd_per_month = collect($rfd)->count();
            $total_rfd_for_others = collect($rfd)->where('category', 'Others')->count();
            $total_rfd_for_lamp_post = collect($rfd)->where('category', 'Lamp Post')->count();
            $total_rfd_for_luminaires = collect($rfd)->where('category', 'Luminaire')->count();
    
            $data[] = [
                'month' => $month,
                'total_rfd_per_month' => $total_rfd_per_month,
                'total_rfd_for_others' => $total_rfd_for_others,
                'total_rfd_for_lamp_post' => $total_rfd_for_lamp_post,
                'total_rfd_for_luminaires' => $total_rfd_for_luminaires
            ];
        }

        return response()->json($data);
    }

    public function rfdDistributionChart($year){
        $rfd = DB::connection('mysql_erp')->select('SELECT name, category, creation FROM `tabRequest for Drawing` WHERE YEAR(creation) = '. $year);

        $total_rfd = collect($rfd)->count();
        $total_rfd_for_others = collect($rfd)->where('category', 'Others')->count();
        $total_rfd_for_lamp_post = collect($rfd)->where('category', 'Lamp Post')->count();
        $total_rfd_for_luminaires = collect($rfd)->where('category', 'Luminaire')->count();

        $rfd_others = ($total_rfd_for_others / $total_rfd) * 100;
        $rfd_lamp_post = ($total_rfd_for_lamp_post / $total_rfd) * 100;
        $rfd_luminaires = ($total_rfd_for_luminaires / $total_rfd) * 100;

        $so_dwgs = DB::connection('mysql_erp')->select('SELECT rfd.name, rfd.creation, rfd.date_issued FROM `tabRequest for Drawing` rfd JOIN `tabSales Order Item` ON rfd.name = `tabSales Order Item`.request_for_drawing WHERE `tabSales Order Item`.docstatus = 1 AND YEAR(rfd.creation) = '. $year.' AND YEAR(`tabSales Order Item`.creation) = '. $year.' AND rfd.date_issued IS NOT NULL GROUP BY rfd.name, rfd.creation, rfd.date_issued');

        $approved_dwgs = DB::connection('mysql_erp')->select('SELECT name, creation, date_issued FROM `tabRequest for Drawing` WHERE YEAR(creation) = '. $year.' AND date_issued IS NOT NULL AND docstatus < 2');

        $total_so_dwgs = collect($so_dwgs)->count();
        $total_approved_dwgs = collect($approved_dwgs)->count();

        if ($total_so_dwgs > 0) {
            $success_rate = ($total_so_dwgs / $total_approved_dwgs) * 100;
        }

        $success_rate = $total_so_dwgs > 0 ? $success_rate : 0;

        $data = [
            'rfd_for_lamp_post' => round($rfd_lamp_post, 2),
            'rfd_for_luminaires' => round($rfd_luminaires, 2),
            'rfd_for_others' => round($rfd_others, 2),
            'success_rate' => round($success_rate, 2) .'%'
        ];

        return response()->json($data);
    }

    public function rfdTotals(){
        $rfd = DB::connection('mysql_erp')->select('SELECT name, workflow_state, creation FROM `tabRequest for Drawing`');

        $total_rfd_requests = collect($rfd)->count();
        $total_rfd_in_process = collect($rfd)->where('workflow_state', 'In Process')->count();
        $total_rfd_for_checking = collect($rfd)->where('workflow_state', 'For Checking')->count();
        $total_rfd_for_approval = collect($rfd)->where('workflow_state', 'For Approval')->count();
        $total_rfd_approved = collect($rfd)->where('workflow_state', 'Approved')->count();
        $total_rfd_for_revision = collect($rfd)->where('workflow_state', 'For Revision')->count();
        $total_rfd_cancelled = collect($rfd)->where('workflow_state', 'Cancelled')->count();

        $data = [
            'total_rfd_requests' => $total_rfd_requests,
            'total_rfd_in_process' => $total_rfd_in_process,
            'total_rfd_for_checking' => $total_rfd_for_checking,
            'total_rfd_for_approval' => $total_rfd_for_approval,
            'total_rfd_approved' => $total_rfd_approved,
            'total_rfd_for_revision' => $total_rfd_for_revision,
            'total_rfd_cancelled' => $total_rfd_cancelled,
        ];

        return $data;
    }

    public function engineeringKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.engineering.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function rfdTimeliness(Request $request, $year){
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $data = [];
        foreach ($months as $i => $month) {
            $i = $i + 1;
            $rfd = DB::connection('mysql_erp')->select('SELECT name, category, date_issued, DATEDIFF(date_issued, creation) as date_difference FROM `tabRequest for Drawing` WHERE MONTH(creation) = '.$i.' AND YEAR(creation) = '. $year.' AND date_issued IS NOT NULL');

            $accomplished_dwgs = collect($rfd)->count();
            $delayed_dwgs = collect($rfd)->where('date_difference', '>', 2)->count();

            if ($request->category) {
                $accomplished_dwgs = collect($rfd)->where('category', $request->category)->count();
                $delayed_dwgs = collect($rfd)->where('category', $request->category)->where('date_difference', '>', 2)->count();
            }

            $percentage = 0;
            if ($accomplished_dwgs > 0) {
                $percentage = (($accomplished_dwgs - $delayed_dwgs) / $accomplished_dwgs) * 100;

                $data[] = [
                    'month' => $month,
                    'accomplished_dwgs' => $accomplished_dwgs,
                    'delayed_dwgs' => $delayed_dwgs,
                    'percentage' => round($percentage, 2),
                ];
            }  
        }

        return response()->json($data);
    }

    public function rfdCompletion($year){
        $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $month_no = $year == date('Y') ? date('m') : 12;

        $m = [];
        for ($i = 1; $i <= $month_no; $i++) {
            array_push($m, $months[$i]);
        }

        $rfds = DB::connection('mysql_erp')->table('tabRequest for Drawing')
                ->select('name', DB::raw('MONTH(creation) AS mcreation'), 'date_issued', 'status', 'docstatus')
                ->where(DB::raw('YEAR(creation)'), $year)
                ->get();

        $accomplished_dwgs = DB::connection('mysql_erp')->table('tabRequest for Drawing')
                ->select('name', DB::raw('MONTH(date_issued) AS mcreation'), 'date_issued', 'workflow_state', 'docstatus')
                ->where(DB::raw('YEAR(date_issued)'), $year)
                ->whereIn('workflow_state', ['For Checking', 'For Approval', 'Approved', 'Cancelled'])
                ->get();

        $cancelled_dwgs = DB::connection('mysql_erp')->table('tabRequest for Drawing')
                ->select('name', DB::raw('MONTH(creation) AS mcreation'), 'date_issued', 'status', 'docstatus')
                ->where(DB::raw('YEAR(creation)'), $year)
                ->whereIn('workflow_state', ['Approved'])
                ->where('status', 'Cancelled - NEW RFD CREATED')
                ->get();

        $cancelled_dwgs_arr = $accomplished_dwgs_arr = $total_dwgs_arr = [''];
        $accomplished_dwgs_total = $overall_dwgs_total = 0;
        
        for ($i = 1; $i <= 12; $i++) {
            $accomplished_dwgs_total = collect($accomplished_dwgs)->where('mcreation', $i)->count();
            $overall_dwgs_total = collect($rfds)->where('mcreation', $i)->count();
            $cancelled_dwgs_per_month = collect($cancelled_dwgs)->where('mcreation', $i)->count();

            array_push($accomplished_dwgs_arr, $accomplished_dwgs_total);
            array_push($cancelled_dwgs_arr, $cancelled_dwgs_per_month);
            array_push($total_dwgs_arr, $overall_dwgs_total);
        }

        $a = $accomplished_dwgs_arr;
        $b = $total_dwgs_arr;
        $c = $cancelled_dwgs_arr;

        $a1 = $a[1];
        $a2 = $a[1] + $a[2];
        $a3 = $a[1] + $a[2] + $a[3];
        $a4 = $a[1] + $a[2] + $a[3] + $a[4];
        $a5 = $a[1] + $a[2] + $a[3] + $a[4] + $a[5];
        $a6 = $a[1] + $a[2] + $a[3] + $a[4] + $a[5] + $a[6];
        $a7 = $a[1] + $a[2] + $a[3] + $a[4] + $a[5] + $a[6] + $a[7];
        $a8 = $a[1] + $a[2] + $a[3] + $a[4] + $a[5] + $a[6] + $a[7] + $a[8];
        $a9 = $a[1] + $a[2] + $a[3] + $a[4] + $a[5] + $a[6] + $a[7] + $a[8] + $a[9];
        $a10 = $a[1] + $a[2] + $a[3] + $a[4] + $a[5] + $a[6] + $a[7] + $a[8] + $a[9] + $a[10];
        $a11 = $a[1] + $a[2] + $a[3] + $a[4] + $a[5] + $a[6] + $a[7] + $a[8] + $a[9] + $a[10] + $a[11];
        $a12 = $a[1] + $a[2] + $a[3] + $a[4] + $a[5] + $a[6] + $a[7] + $a[8] + $a[9] + $a[10] + $a[11] + $a[12];

        $bc1 = $b[1] - $c[1];
        $bc2 = $b[1] + $b[2] - $c[1] - $c[2];
        $bc3 = $b[1] + $b[2] + $b[3] - $c[1] - $c[2] - $c[3];
        $bc4 = $b[1] + $b[2] + $b[3] + $b[4] - $c[1] - $c[2] - $c[3] - $c[4];
        $bc5 = $b[1] + $b[2] + $b[3] + $b[4] + $b[5] - $c[1] - $c[2] - $c[3] - $c[4] - $c[5];
        $bc6 = $b[1] + $b[2] + $b[3] + $b[4] + $b[5] + $b[6] - $c[1] - $c[2] - $c[3] - $c[4] - $c[5] - $c[6];
        $bc7 = $b[1] + $b[2] + $b[3] + $b[4] + $b[5] + $b[6] + $b[7] - $c[1] - $c[2] - $c[3] - $c[4] - $c[5] - $c[6] - $c[7];
        $bc8 = $b[1] + $b[2] + $b[3] + $b[4] + $b[5] + $b[6] + $b[7] + $b[8] - $c[1] - $c[2] - $c[3] - $c[4] - $c[5] - $c[6] - $c[7] - $c[8];
        $bc9 = $b[1] + $b[2] + $b[3] + $b[4] + $b[5] + $b[6] + $b[7] + $b[8] + $b[9] - $c[1] - $c[2] - $c[3] - $c[4] - $c[5] - $c[6] - $c[7] - $c[8] - $c[9];
        $bc10 = $b[1] + $b[2] + $b[3] + $b[4] + $b[5] + $b[6] + $b[7] + $b[8] + $b[9] + $b[10] - $c[1] - $c[2] - $c[3] - $c[4] - $c[5] - $c[6] - $c[7] - $c[8] - $c[9] - $c[10];
        $bc11 = $b[1] + $b[2] + $b[3] + $b[4] + $b[5] + $b[6] + $b[7] + $b[8] + $b[9] + $b[10] + $b[11] - $c[1] - $c[2] - $c[3] - $c[4] - $c[5] - $c[6] - $c[7] - $c[8] - $c[9] - $c[10] - $c[11];
        $bc12 = $b[1] + $b[2] + $b[3] + $b[4] + $b[5] + $b[6] + $b[7] + $b[8] + $b[9] + $b[10] + $b[11] + $b[12] - $c[1] - $c[2] - $c[3] - $c[4] - $c[5] - $c[6] - $c[7] - $c[8] - $c[9] - $c[10] - $c[11] - $c[12];

        $r1 = round(($a1 / $bc1) * 100, 2);
        $r2 = round(($a2 / $bc2) * 100, 2);
        $r3 = round(($a3 / $bc3) * 100, 2);
        $r4 = round(($a4 / $bc4) * 100, 2);
        $r5 = round(($a5 / $bc5) * 100, 2);
        $r6 = round(($a6 / $bc6) * 100, 2);
        $r7 = round(($a7 / $bc7) * 100, 2);
        $r8 = round(($a8 / $bc8) * 100, 2);
        $r9 = round(($a9 / $bc9) * 100, 2);
        $r10 = round(($a10 / $bc10) * 100, 2);
        $r11 = round(($a11 / $bc11) * 100, 2);
        $r12 = round(($a12 / $bc12) * 100, 2);

        $data = [$r1, $r2, $r3, $r4, $r5, $r6, $r7, $r8, $r9, $r10, $r11, $r12];

        return response()->json(['months' => $m, 'percentage' => $data]);
    }

    public function rfdQuality($year){
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 0; $i < $month_no; $i++) { 
            $month_name = $months[$i];
            $data_inputs = DB::table('kpi_datainput_result')->select('data_input_id', 'user_id', 'answer', 'month')
                    ->where('month', $i + 1)->where('year', $year)->get();

            $accomplished_dwgs = collect($data_inputs)->where('data_input_id', 75)->sum('answer');
            $revision_dept = collect($data_inputs)->where('data_input_id', 7)->sum('answer');
            $revision_customer = collect($data_inputs)->where('data_input_id', 8)->sum('answer');

            if ($accomplished_dwgs > 0) {
                $percentage = (($accomplished_dwgs - ($revision_dept + $revision_customer)) / $accomplished_dwgs) * 100;
            }
                
            $percentage = $accomplished_dwgs > 0 ? $percentage : 0;

            $data[] = [
                'month' => $month_name,
                'accomplished_dwgs' => $accomplished_dwgs,
                'revision_dept' => $revision_dept,
                'revision_customer' => $revision_customer,
                'percentage' => round($percentage, 2)
            ];
        }

        return response()->json($data);
    }

    public function rfdSuccessRate($year){        
        $so_dwgs = DB::connection('mysql_erp')->select('SELECT rfd.name, rfd.category, rfd.date_issued FROM `tabRequest for Drawing` rfd JOIN `tabSales Order Item` ON rfd.name = `tabSales Order Item`.request_for_drawing WHERE `tabSales Order Item`.docstatus = 1 AND YEAR(rfd.creation) = '. $year.' AND YEAR(`tabSales Order Item`.creation) = '. $year.' AND rfd.date_issued IS NOT NULL GROUP BY rfd.name, rfd.category, rfd.date_issued');

        $approved_dwgs = DB::connection('mysql_erp')->select('SELECT name, category, date_issued FROM `tabRequest for Drawing` WHERE YEAR(creation) = '. $year.' AND date_issued IS NOT NULL AND docstatus < 2');

        $rfd_lamp_post_dwgs = collect($approved_dwgs)->where('category', 'Lamp Post')->count();
        $rfd_luminaire_dwgs = collect($approved_dwgs)->where('category', 'Luminaire')->count();
        $rfd_ins_guide_dwgs = collect($approved_dwgs)->where('category', 'Installation Guide')->count();
        $rfd_others_dwgs = collect($approved_dwgs)->where('category', 'Others')->count();

        $so_rfd_lamp_post_dwgs = collect($so_dwgs)->where('category', 'Lamp Post')->count();
        $so_rfd_luminaire_dwgs = collect($so_dwgs)->where('category', 'Luminaire')->count();
        $so_rfd_ins_guide_dwgs = collect($so_dwgs)->where('category', 'Installation Guide')->count();
        $so_rfd_others_dwgs = collect($so_dwgs)->where('category', 'Others')->count();

        if ($so_rfd_lamp_post_dwgs > 0) {
            $rfd_lamp_post_rate = ($so_rfd_lamp_post_dwgs / $rfd_lamp_post_dwgs) * 100;
        }

        if ($so_rfd_luminaire_dwgs > 0) {
            $rfd_luminaire_rate = ($so_rfd_luminaire_dwgs / $rfd_luminaire_dwgs) * 100;
        }

        if ($so_rfd_ins_guide_dwgs > 0) {
            $rfd_ins_guide_rate = ($so_rfd_ins_guide_dwgs / $rfd_ins_guide_dwgs) * 100;
        }

        if ($so_rfd_others_dwgs > 0) {
            $rfd_others_rate = ($so_rfd_others_dwgs / $rfd_others_dwgs) * 100;
        }

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $chart_data = [];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 0; $i < $month_no; $i++) { 
            $month_name = $months[$i];
            $n = $i + 1;
            $rfd_so = DB::connection('mysql_erp')->select('SELECT rfd.name, rfd.category FROM `tabRequest for Drawing` rfd JOIN `tabSales Order Item` soi ON rfd.name = soi.request_for_drawing WHERE soi.docstatus = 1 AND YEAR(rfd.creation) = '.$year.' AND YEAR(soi.creation) = '.$year.' AND rfd.date_issued IS NOT NULL AND MONTH(rfd.creation) = '.$n.' AND MONTH(soi.creation) = '.$n.' GROUP BY rfd.name, rfd.category');

            $so_lamp_post_dwgs = collect($rfd_so)->where('category', 'Lamp Post')->count();
            $so_luminaire_dwgs = collect($rfd_so)->where('category', 'Luminaire')->count();
            $so_ins_guide_dwgs = collect($rfd_so)->where('category', 'Installation Guide')->count();
            $so_others_dwgs = collect($rfd_so)->where('category', 'Others')->count();

            $chart_data[] = [
                'month' => $month_name,
                'lamp_post' => $so_lamp_post_dwgs,
                'luminaire' => $so_luminaire_dwgs,
                'ins_guide' => $so_ins_guide_dwgs,
                'others' => $so_others_dwgs,
            ];
        }

        $data = [
            'rfd_lamp_post_rate' => round($so_rfd_lamp_post_dwgs > 0 ? $rfd_lamp_post_rate : 0, 2) .'%',
            'rfd_luminaire_rate' => round($so_rfd_luminaire_dwgs > 0 ? $rfd_luminaire_rate : 0, 2) .'%',
            'rfd_ins_guide_rate' => round($so_rfd_ins_guide_dwgs > 0 ? $rfd_ins_guide_rate : 0, 2) .'%',
            'rfd_others_rate' => round($so_rfd_others_dwgs > 0 ? $rfd_others_rate : 0, 2) .'%',
            'chart_data' => $chart_data
        ];

        return response()->json($data);
    }

    // DATA INPUT ENTRY
    public function subquery_dataInput($kpi){
        $metrics = DB::table('metrics')
                ->select('metrics.metric_name','metrics.metric_id','metrics.kpi_id')
                ->join('data_input','data_input.metric_id','=','metrics.metric_id')
                ->where('kpi_id', $kpi)->distinct()->get();
        
        $datainput = [];
        foreach ($metrics as $row) {
            $table = DB::table('metrics')
                ->join('data_input','data_input.metric_id','=','metrics.metric_id')
                ->where('kpi_id', $kpi )->where( 'metrics.metric_id', $row->metric_id)
                ->get();

            $datainput[] = [
                'metric_id' => $row->metric_id,
                'metric_name' => $row->metric_name,
                'kpi_id' => $row->kpi_id,
                'nodess' => $table,
            ];
        }

        return $datainput;
    }

    public function dataInput(Request $request){
        $department_heads= DB::table('department_head_list')
                            ->join('departments','department_head_list.department_id','=','departments.department_id')
                            ->where('employee_id',Auth::user()->user_id)
                            ->get();
        if(!$department_heads->isEmpty()){
          $depart='head';
        }
        else{
          $department_heads= DB::table('users')
                            ->join('departments','users.department_id','=','departments.department_id')
                            ->where('user_id',Auth::user()->user_id)
                            ->get();
          $depart='employee';
        }
        ///////////////////////////////////////////////////////////////////

        $users=DB::table('users')
                    ->select('designation_id','department_id','user_id')
                    ->where('user_id', $request->employeelist)
                    ->first();
        $user_designation= $users->designation_id;
        $user_user_id= $users->user_id;

        /////////////////////////////////////////////////////////////////////
        if ($depart == 'employee') {
            $kpi = DB::table('kpi_per_designation')
                ->select('kpi.kpi_description','kpi.kpi_id','kpi.evaluation_period','kpi.target','kpi.weight_average','kpi.set_manual')
                ->join('kpi','kpi_per_designation.kpi_id','=','kpi.kpi_id')
                ->where('designation_id', Auth::user()->designation_id)
                ->where('kpi.evaluation_period', $request->eval_period)
                ->distinct()
                ->get();
                    $datainput = [];
                        foreach ($kpi as $row) {
                             
                                    $datainput[] = [
                                        'kpi_id' => $row->kpi_id,
                                        'kpi_description' => $row->kpi_description,
                                        'kpi_target' => $row->target,
                                        'kpi_weight' => $row->weight_average,
                                        'set_manual' => $row->set_manual,
                                        // 'nodes' => $this->evaluationDesignation($department_id, $row->kpi_id),
                                        'nodes' => $this->subquery_dataInput($row->kpi_id),
                                        // 'user_id'=> $user_user_id,
                                    ];
                        }
        }elseif($request->entry == 'per_employee') {
            $kpi = DB::table('kpi_per_designation')
                ->select('kpi.kpi_description','kpi.kpi_id','kpi.evaluation_period','kpi.target','kpi.weight_average','kpi.set_manual')
                ->join('kpi','kpi_per_designation.kpi_id','=','kpi.kpi_id')
                ->where('designation_id', $user_designation)
                ->where('kpi.evaluation_period', $request->eval_period)
                ->distinct()
                ->get();
                        $datainput = [];
                            foreach ($kpi as $row) {
                                        $datainput[] = [
                                            'kpi_id' => $row->kpi_id,
                                            'kpi_description' => $row->kpi_description,
                                            'kpi_target' => $row->target,
                                            'kpi_weight' => $row->weight_average,
                                            'set_manual' => $row->set_manual,
                                            // 'nodes' => $this->evaluationDesignation($department_id, $row->kpi_id),
                                            'nodes' => $this->subquery_dataInput($row->kpi_id),
                                            // 'user_id'=> $user_user_id,
                                        ];
                            }
        }elseif($request->entry == 'per_department') {
            $kpi = DB::table('kpi')
                ->select('kpi.kpi_description','kpi.kpi_id','kpi.evaluation_period','kpi.target','kpi.weight_average','kpi.set_manual')
                ->where('department_id', $request->dept)
                ->where('kpi.evaluation_period', $request->eval_period)
                ->where('kpi.set_kpi', 1)
                ->distinct()
                ->get();
                    $datainput = [];
                        foreach ($kpi as $row) {
                             
                                    $datainput[] = [
                                        'kpi_id' => $row->kpi_id,
                                        'kpi_description' => $row->kpi_description,
                                        'kpi_target' => $row->target,
                                        'kpi_weight' => $row->weight_average,
                                        'set_manual' => $row->set_manual,
                                        // 'nodes' => $this->evaluationDesignation($department_id, $row->kpi_id),
                                        'nodes' => $this->subquery_dataInput($row->kpi_id),
                                        // 'user_id'=> $user_user_id,
                                    ];
                        }

            
        }

                
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        $designation = $detail->designation;
        $department = $detail->department;
        if ($request->eval_period == "Monthly") {
            $eval_sched_id=1;
            $eval_type='Monthly';
        }elseif ($request->eval_period == "Quarterly") {
            $eval_sched_id=2;
            $eval_type='Quarterly';
        }elseif ($request->eval_period == "Semi-Annual") {
            $eval_sched_id=3;
            $eval_type='Semi-Annual';
        }elseif ($request->eval_period == "Annual") {
            $eval_sched_id=4;
            $eval_type='Annual';
        }

        $schedule_details = DB::table('evaluation_schedule')->where('eval_sched_id', $eval_sched_id)->first();
        $scheduless = $this->getSubmissionSchedules($schedule_details->period, $schedule_details->start_date, $schedule_details->year);

        return view('client.modules.evaluation.metrics.datainput', compact('designation', 'department', 'datainput','department_heads','depart','schedule_details','scheduless','eval_type'));
    }

    public function getemployeeperdept(Request $request){
        $output="";
        if ($request->deptvalidate == 'head') {
            $users = DB::table('users')->where('department_id', $request->dept)->get();
            foreach($users as $row){
                $output .= '<option value="'.$row->user_id.'">'.$row->employee_name.'</option>';
            }
        }

        if ($request->deptvalidate == 'employee') {
            $users = DB::table('users')->where('user_id', Auth::user()->user_id)->get();
            foreach($users as $row){
                $output .= '<option value="'.$row->user_id.'">'.$row->employee_name.'</option>';
            }
        }

        return json_encode($output);
    }

     public function savedataInput(Request $request){
            $date=date('Y-m-d');
            $users=DB::table('users')
                    ->select('designation_id','department_id','user_id')
                    ->where('user_id', $request->user_id)
                    ->first();
            $department_heads= DB::table('department_head_list')
                     ->join('departments','department_head_list.department_id','=','departments.department_id')
                    ->where('employee_id',Auth::user()->user_id)
                    ->get();
            if(!$department_heads->isEmpty()){
                $depart='head';
            }
            else{
                $department_heads= DB::table('users')
                ->join('departments','users.department_id','=','departments.department_id')
                ->where('user_id',Auth::user()->user_id)
                ->get();
                $depart='employee';
            }

            $data = $request->all();
            $ans = $data['answer'];
            $data_input_id = $data['input_id'];
            $answerkpi = $data['answerkpi'];
            $set_manual = $data['set_manual'];
            $kpiID = $data['kpiID'];
            $kpi_target = $data['kpi_target'];
            $kpi_weight = $data['kpi_weight'];
            $period = $request->eval_period;
            $filtermonth = date('m', strtotime('-1 months', strtotime($request->schedule_date)));
            $filteryear = date('Y', strtotime('-1 months', strtotime($request->schedule_date)));
            $submission_date = Carbon::parse($request->schedule_date)->format('Y-m-d');
            $date_submitted = date('Y-m-d');
            $status = $date_submitted > $submission_date ? 'late' : 'on time';


            if ($depart == 'employee') {
                if(DB::table('kpi_datainput_result')
                    ->where('due_date', '=', $submission_date)
                    ->where('year', '=', $filteryear)
                    ->where('month', '=', $filtermonth)
                    ->where('user_id', '=', $request->user_id)
                    ->exists()){
                    return response()->json(['message' => '1']);   
                }
                else
                {
                    foreach($answerkpi as $keys => $inputs) {
                        $set=0;
                        if ($inputs > 0) {
                            $savekpi = new KPIResult;
                            $savekpi->kpi_id = isset($kpiID[$keys]) ? $kpiID[$keys] : '';
                            $savekpi->kpi_answer = isset($answerkpi[$keys]) ? $answerkpi[$keys] : '';
                            $savekpi->user_id=Auth::user()->user_id;
                            $savekpi->month = $period == 'Annual'? 'null': $filtermonth;
                            $savekpi->year = $period == $filteryear;
                            $savekpi->set_to_all =$set;
                            $savekpi->target =isset($kpi_target[$keys]) ? $kpi_target[$keys] : '';
                            $savekpi->weight =isset($kpi_weight[$keys]) ? $kpi_weight[$keys] : '';
                            $savekpi->submitted_by =  Auth::user()->employee_name;
                            $savekpi->last_modified_by =  Auth::user()->employee_name;
                            $savekpi->save();
                        }            
                    }
                    foreach($data_input_id as $key => $input) {
                        $scores = new DataInputModel;
                        $scores->user_id=  Auth::user()->user_id;
                        $scores->answer = isset($ans[$key]) ? $ans[$key] : ''; 
                        $scores->data_input_id = isset($data_input_id[$key]) ? $data_input_id[$key] : ''; 
                        $scores->month = $period == 'Annual'? 'null': $filtermonth;
                        $scores->year = $filteryear;
                        $scores->date_submitted = $date;
                        $scores->due_date = $submission_date; //
                        $scores->status = $status; //
                        $scores->evaluation_period = $period; //
                        $scores->submitted_by =  Auth::user()->employee_name;
                        $scores->last_modified_by =  Auth::user()->employee_name;
                        $scores->save();
                    }
                    return response()->json(['message' => "Form is successfully submitted"]);
                }
            
            }else{
                    if($request->entry_val == 'per_department'){
                        if (DB::table('kpi_datainput_result')
                            ->select('kpi.department_id','kpi_datainput_result.month','kpi_datainput_result.year')
                            ->join('data_input','data_input.input_id','=','kpi_datainput_result.data_input_id')
                            ->join('metrics','metrics.metric_id','=','data_input.metric_id')
                            ->join('kpi', 'metrics.kpi_id','=','kpi.kpi_id')
                            ->where('kpi.department_id', '=', $request->depart_id)
                            ->where('kpi_datainput_result.due_date', '=', $submission_date)
                            ->where('kpi_datainput_result.year', '=', $filteryear)
                            ->where('kpi_datainput_result.month', '=', $filtermonth)
                            ->where('kpi_datainput_result.user_id', '=', null)
                            ->exists()){
                                return response()->json(['message' => '1']);
                            }
                        else{
                            $set='1';
                            foreach($data_input_id as $key => $input) {
                                $scores = new DataInputModel;
                                $scores->user_id=  null;
                                $scores->answer = isset($ans[$key]) ? $ans[$key] : ''; 
                                $scores->data_input_id = isset($data_input_id[$key]) ? $data_input_id[$key] : ''; 
                                $scores->month = $period == 'Annual'? 'null': $filtermonth;
                                $scores->year = $filteryear;
                                $scores->date_submitted = $date;
                                $scores->due_date = $submission_date; //
                                $scores->status = $status; //
                                $scores->evaluation_period = $period; //
                                $scores->submitted_by =   Auth::user()->employee_name;
                                $scores->last_modified_by =  Auth::user()->employee_name;
                                $scores->save();
                            }
                            foreach($answerkpi as $keys => $inputs){
                                if ($inputs > 0) {
                                    $savekpi = new KPIResult;
                                    $savekpi->kpi_id = isset($kpiID[$keys]) ? $kpiID[$keys] : '';
                                    $savekpi->kpi_answer = isset($answerkpi[$keys]) ? $answerkpi[$keys] : '';
                                    $savekpi->user_id=null;
                                    $savekpi->month=$period == 'Annual'? 'null': $filtermonth;
                                    $savekpi->year = $filteryear;
                                    $savekpi->set_to_all =$set;
                                    $savekpi->target =isset($kpi_target[$keys]) ? $kpi_target[$keys] : '';
                                    $savekpi->weight =isset($kpi_weight[$keys]) ? $kpi_weight[$keys] : '';
                                    $savekpi->submitted_by =  Auth::user()->employee_name;
                                    $savekpi->last_modified_by =  Auth::user()->employee_name;
                                    $savekpi->save();
                                    }
                                }
                                return response()->json(['message' => "Form is successfully submitted"]);
                            }
                    }else
                    {  
                        if (DB::table('kpi_datainput_result')   
                            ->where('due_date', '=', $submission_date)
                            ->where('year', '=', $filteryear)
                            ->where('month', '=', $filtermonth)
                            ->where('user_id', '=', $request->user_id)
                            ->exists()){
                        return response()->json(['message' => '1']);
                        }
                        else{ 
                            $user_n=Auth::user()->user_id;
                            $set='0';
                            foreach($answerkpi as $keys => $inputs) {
                                if ($inputs > 0) {
                                    $savekpi = new KPIResult;
                                    $savekpi->kpi_id = isset($kpiID[$keys]) ? $kpiID[$keys] : '';
                                    $savekpi->kpi_answer = isset($answerkpi[$keys]) ? $answerkpi[$keys] : '';
                                    $savekpi->user_id=$request->user_id;
                                    $savekpi->month = $period == 'Annual'? 'null': $filtermonth;
                                    $savekpi->year = $filteryear;
                                    $savekpi->set_to_all =$set;
                                    $savekpi->target =isset($kpi_target[$keys]) ? $kpi_target[$keys] : '';
                                    $savekpi->weight =isset($kpi_weight[$keys]) ? $kpi_weight[$keys] : '';
                                    $savekpi->submitted_by =  Auth::user()->employee_name;
                                    $savekpi->last_modified_by =  Auth::user()->employee_name;
                                    $savekpi->save();
                                }                           
                            }  
                            foreach($data_input_id as $key => $input) {
                                $scores = new DataInputModel;
                                $scores->user_id= $request->user_id;
                                $scores->answer = isset($ans[$key]) ? $ans[$key] : ''; 
                                $scores->data_input_id = isset($data_input_id[$key]) ? $data_input_id[$key] : ''; 
                                $scores->month = $period == 'Annual'? 'null': $filtermonth;
                                         $scores->year = $filteryear;
                                $scores->date_submitted = $date;
                                $scores->due_date = $submission_date; //
                                $scores->status = $status; //
                                $scores->evaluation_period = $period; //
                                $scores->submitted_by = Auth::user()->employee_name;
                                $scores->last_modified_by =  Auth::user()->employee_name;
                                $scores->save();
                            }
                            return response()->json(['message' => "Form is successfully submitted!"]);
                        }
                    }
                }  
    }
    //    

    public function gettblDatainput($user_id,$year,$sched){
       
        
       
        $data=DB::table('metrics')
        ->select('data_input.input_id','data_input.data_input','kpi_datainput_result.answer','kpi_datainput_result.month','kpi_datainput_result.user_id','kpi_datainput_result.answer','kpi_datainput_result.data_input_id','kpi_datainput_result.year','kpi.kpi_description','kpi.kpi_id','kpi.evaluation_period','kpi.department_id')
        ->join('data_input','metrics.metric_id','=','data_input.metric_id')
        ->join('kpi_datainput_result','kpi_datainput_result.data_input_id','=','data_input.input_id')
        ->join('kpi','metrics.kpi_id','=','kpi.kpi_id')
        ->whereIn('kpi_datainput_result.user_id', [null,Auth::user()->user_id])
        ->where('kpi.department_id', Auth::user()->department_id)
        // ->where('kpi_datainput_result.user_id', null)
        ->where('kpi_datainput_result.month', $user_id)
        ->where('kpi_datainput_result.year', $year)
        ->where('kpi.evaluation_period', $sched)
        ->get();

        return $data;
    }

    public function tbldatainput(Request $request){
        $tbldatainput= array();
        $getdata=$this->gettblDatainput($request->filmonths,$request->filyears,$request->schedentry);
        $tbldatainput= collect($getdata)->groupBy('kpi_description');

        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
     
        // Create a new Laravel collection from the array data
        $itemCollection = collect($tbldatainput);
     
        // Define how many items we want to be visible in each page
        $perPage = 1;
     
        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
     
        // Create our paginator and pass it to the view
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
     
        // set url path for generted links
        $paginatedItems->setPath($request->url());

        $data = $paginatedItems;
        return view('client.modules.evaluation.metrics.datainput_table', compact('data'));
    }

    public function metricdata($kpi,$user_id){
        $datametric= DB::table('data_input')
            ->select('metrics.metric_description','metrics.kpi_id','metrics.metric_id')
            ->join('metrics','metrics.metric_id','=','data_input.metric_id')
            ->where('metrics.kpi_id',$kpi)->distinct()->get();

        $data=[];
        foreach ($datametric as $key) {
            $datain= DB::table('kpi_datainput_result')
                ->select('data_input.data_input','kpi_datainput_result.answer','kpi_datainput_result.user_id','kpi_datainput_result.month','kpi_datainput_result.year')
                ->join('data_input','data_input.input_id','=','kpi_datainput_result.data_input_id')
                ->where('data_input.metric_id',$key->metric_id)->where('user_id', $user_id)
                ->where('kpi_datainput_result.month', 7)
                ->where('kpi_datainput_result.year', 2019)
                ->get();

            $data[] = [
                'metric_id'=>$key->metric_id,
                'metric_description'=>$key->metric_description,
                'kpi_id'=>$key->kpi_id,
                'nodessss' => $datain
            ];
        }

        return $data;
    }

    public function datainputt($desig, $kpi){
        $datametric = DB::table('users')->select('user_id','employee_name')->where('designation_id', $desig )->get();
        foreach ($datametric as $key) {
            $data[] = [
                'user_id'=>$key->user_id,
                'employee_name'=>$key->employee_name,
                'nodesss' => $this->metricdata($kpi,$key->user_id)
            ];
        }

        return $data;
    }

    public function mtric($kpi){
        $metrics_table = DB::table('kpi_per_designation')
                ->select('kpi_per_designation.kpi_id','kpi_per_designation.designation_id','designation.designation')
                ->join('designation','designation.des_id','=','kpi_per_designation.designation_id')
                ->where('kpi_id', $kpi )->distinct()->get();
        
        $data = [];
        foreach ($metrics_table as $key) {
            $data[] = [
                'designation_id'=>$key->designation_id,
                'designation'=>$key->designation,
                'nodess' => $this->datainputt($key->designation_id, $kpi)
            ];
        }

        return $data;
    }

    public function overview_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $department_heads= DB::table('department_head_list')
                            ->join('departments','department_head_list.department_id','=','departments.department_id')
                            ->where('employee_id',Auth::user()->user_id)->get();

        return view('client.modules.evaluation.overview.index', compact('designation', 'department', 'department_heads','datainput_table'));
    }

    public function getdatainput_overview(Request $request){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $department_heads= DB::table('department_head_list')
                            ->join('departments','department_head_list.department_id','=','departments.department_id')
                            ->where('employee_id',Auth::user()->user_id)->get();

        $kpi_table = DB::table('kpi')->where('kpi.department_id', $request->dept)->get();
        $datainput_table = [];
        $data = [];
        foreach ($kpi_table as $row) {
            $datainput_table[] = [
                'kpi_id' => $row->kpi_id,
                'kpi_description' => $row->kpi_description,
                'department_id' => $row->department_id,
                'nodes' => $this->mtric($row->kpi_id)
            ];
        }

        return view('client.modules.evaluation.overview.overview_table', compact('datainput_table'));
    }

    // kpi per department start
    public function accounting_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 1)
        ->get();
        return view('client.modules.evaluation.overview.department.accounting.index', compact('designation', 'department','kpi'));
    }

    public function accounting_index2(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi = DB::table('kpi')->where('kpi.department_id', 1)->get();

        return view('client.modules.evaluation.overview.department.accounting.index2', compact('designation', 'department','kpi'));
    }

    public function pinvPerMonthChart($year){
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $pinvs = DB::connection('mysql_erp')->table('tabPurchase Invoice')
                ->where('docstatus', 1)->whereYear('posting_date', $year)
                ->where('company', 'FUMACO Inc.')
                ->selectRaw('tc_name, MONTH(posting_date) as m')->get();

        $chart_data = [];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
            $pinv_per_month = collect($pinvs)->where('m', $i)->count();
            $cash_pinv = collect($pinvs)->where('m', $i)->where('tc_name', 'Cash')->count();
            $other_pinv = collect($pinvs)->where('m', $i)->where('tc_name', '!=', 'Cash')->count();

            $chart_data[] = [
                'month' => $months[$i],
                'total' => $pinv_per_month,
                'cash_pinv' => $cash_pinv,
                'other_pinv' => $other_pinv,
            ];
        }

        return response()->json($chart_data);
    }

    public function salesInvAnalysis(Request $request, $year){
        $branch = $request->branch;
        $type = $request->type;
        $month = $request->month;
        $sinv = DB::connection('mysql_erp')->table('tabSales Invoice')
            ->where('docstatus', 1)
            ->whereYear('issued_date', $year)
            ->whereIn('sales_type', ['Regular Sales', 'Sales on Consignment'])
            ->where('company', 'FUMACO Inc.')
            ->when($month, function ($query, $month) {
                return $query->whereMonth('issued_date', $month);
            })
            ->when($type, function ($query, $type) {
                return $query->where('sales_type', $type);
            })
            ->when($branch, function ($query, $branch) {
                return $query->where('branch', $branch);
            })
            ->get();

        $total_sinv = collect($sinv)->count();
        $cash_sinv = collect($sinv)->where('tc_name', 'Cash')->count();
        $credit_sinv = collect($sinv)->where('tc_name', '!=', 'Cash')->count();

        $delinquent = DB::connection('mysql_erp')->table('tabPayment Entry AS pe')
            ->join('tabPayment Entry Reference AS per', 'pe.name', 'per.parent')
            ->join('tabSales Invoice AS si', 'per.reference_name', 'si.name')
            ->whereIn('si.sales_type', ['Regular Sales', 'Sales on Consignment'])
            ->where('si.company', 'FUMACO Inc.')->where('si.total_advance', '>', 0)
            ->when($month, function ($query, $month) {
                return $query->whereMonth('si.issued_date', $month);
            })
            ->when($type, function ($query, $type) {
                return $query->where('si.sales_type', $type);
            })
            ->when($branch, function ($query, $branch) {
                return $query->where('si.branch', $branch);
            })
            ->whereYear('si.issued_date', $year)
            ->where('per.reference_doctype', 'Sales Invoice')->where('si.tc_name', 'Cash')
            ->where('pe.docstatus', 1)->whereRaw('pe.posting_date != si.issued_date')
            ->select('si.issued_date', 'si.tc_name', 'si.sales_invoice_reference_no', 'si.customer', 'si.grand_total', 'si.status')
            ->get();

        $delinquent_inv = collect($delinquent)->count();

        $beyond_terms_paid = DB::connection('mysql_erp')->table('tabPayment Entry AS pe')
            ->join('tabPayment Entry Reference AS per', 'pe.name', 'per.parent')
            ->join('tabSales Invoice AS si', 'per.reference_name', 'si.name')
            ->whereIn('si.sales_type', ['Regular Sales', 'Sales on Consignment'])
            ->where('si.company', 'FUMACO Inc.')
            ->when($month, function ($query, $month) {
                return $query->whereMonth('si.issued_date', $month);
            })
            ->when($type, function ($query, $type) {
                return $query->where('si.sales_type', $type);
            })
            ->when($branch, function ($query, $branch) {
                return $query->where('si.branch', $branch);
            })
            ->whereYear('si.issued_date', $year)
            ->where('per.reference_doctype', 'Sales Invoice')->where('si.tc_name', '!=', 'Cash')
            ->where('pe.docstatus', 1)->whereRaw('pe.posting_date != si.issued_date')
            ->select('si.issued_date as si_posting_date', 'pe.posting_date as pe_posting_date', 'si.tc_name', 'si.name', 'si.sales_invoice_reference_no', 'si.customer', 'si.grand_total', 'si.status')
            ->get();

        $beyond_terms_unpaid = DB::connection('mysql_erp')->table('tabSales Invoice')
            ->whereYear('issued_date', $year)
            ->whereIn('sales_type', ['Regular Sales', 'Sales on Consignment'])
            ->where('company', 'FUMACO Inc.')
            ->when($month, function ($query, $month) {
                return $query->whereMonth('issued_date', $month);
            })
            ->when($type, function ($query, $type) {
                return $query->where('sales_type', $type);
            })
            ->when($branch, function ($query, $branch) {
                return $query->where('branch', $branch);
            })
            ->where('docstatus', 1)->whereIn('status', ['Unpaid', 'Overdue'])
            ->where('tc_name', '!=', 'Cash')
            ->select('issued_date as si_posting_date', 'tc_name', 'name', 'sales_invoice_reference_no', 'customer', 'grand_total', 'status')
            ->get();
        
        $beyond_terms = 0;
        $beyond_terms_arr = [];
        foreach ($beyond_terms_paid as $value) {
            $days = 0;
            $splittedstring = explode(" ", $value->tc_name);
            foreach ($splittedstring as $val) {
                if ((int)$val) {
                    $days = (int)$val;
                }
            }

            $si_due_date = Carbon::parse($value->si_posting_date)->addDays($days);
            $si_due_date = $si_due_date->format('Y-m-d');
            if ($days > 0) {
                if ($value->pe_posting_date > $si_due_date) {
                    $beyond_terms++;
                    $beyond_terms_arr[] = [
                        'issued_date' => $value->si_posting_date,
                        'tc_name' => $value->tc_name,
                        'sales_invoice_reference_no' => $value->sales_invoice_reference_no,
                        'customer' => $value->customer,
                        'grand_total' => $value->grand_total,
                        'status' => $value->status,
                    ];
                }
            }
        }

        foreach ($beyond_terms_unpaid as $value) {
            $days = 0;
            $splittedstring = explode(" ", $value->tc_name);
            foreach ($splittedstring as $val) {
                if ((int)$val) {
                    $days = (int)$val;
                }
            }

            $si_due_date = Carbon::parse($value->si_posting_date)->addDays($days);
            $si_due_date = $si_due_date->format('Y-m-d');
            if ($days > 0) {
                if (date('Y-m-d') > $si_due_date) {
                    $beyond_terms++;
                    $beyond_terms_arr[] = [
                        'issued_date' => $value->si_posting_date,
                        'tc_name' => $value->tc_name,
                        'sales_invoice_reference_no' => $value->sales_invoice_reference_no,
                        'customer' => $value->customer,
                        'grand_total' => $value->grand_total,
                        'status' => $value->status,
                    ];
                }
            }
        }

        $percentage = ($total_sinv > 0) ? (($total_sinv - ($beyond_terms + $delinquent_inv)) / $total_sinv) * 100 : 0;

        $data = [
            'total_sinv' => $total_sinv,
            'cash_sinv' => $cash_sinv,
            'credit_sinv' => $credit_sinv,
            'delinquent_inv' => $delinquent_inv,
            'beyond_terms' => $beyond_terms,
            'beyond_terms_list' => $beyond_terms_arr,
            'delinquent' => $delinquent,
            'percentage' => round($percentage, 2)
        ];

        return $data;
    }

    public function salesInvAnalysisCtx(Request $request, $year){
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $chart_data = [];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
            $request->request->add(['month' => $i]);
            $sinv = $this->salesInvAnalysis($request, $year);

            $chart_data[] = [
                'month' => $months[$i],
                'total' => $sinv['percentage'],
            ];
        }

        return response()->json($chart_data);
    }

    public function purchaseInvAnalysis(Request $request, $year){
        $branch = $request->branch;
        $month = $request->month;
        $pinv = DB::connection('mysql_erp')->table('tabPurchase Invoice')
            ->where('docstatus', 1)
            ->whereYear('posting_date', $year)
            ->where('company', 'FUMACO Inc.')
            ->when($month, function ($query, $month) {
                return $query->whereMonth('posting_date', $month);
            })
            ->when($branch, function ($query, $branch) {
                return $query->where('branch', $branch);
            })
            ->get();

        $total_pinv = collect($pinv)->count();
        $cash_pinv = collect($pinv)->where('tc_name', 'Cash')->count();
        $credit_pinv = collect($pinv)->where('tc_name', '!=', 'Cash')->count();

        $delinquent = DB::connection('mysql_erp')->table('tabPayment Entry AS pe')
            ->join('tabPayment Entry Reference AS per', 'pe.name', 'per.parent')
            ->join('tabPurchase Invoice AS pi', 'per.reference_name', 'pi.name')
            ->where('pi.company', 'FUMACO Inc.')->where('pi.total_advance', '>', 0)
            ->when($month, function ($query, $month) {
                return $query->whereMonth('pi.posting_date', $month);
            })
            ->when($branch, function ($query, $branch) {
                return $query->where('pi.branch', $branch);
            })
            ->whereYear('pi.posting_date', $year)
            ->where('per.reference_doctype', 'Purchase Invoice')->where('pi.tc_name', 'Cash')
            ->where('pe.docstatus', 1)->whereRaw('pe.posting_date != pi.posting_date')
            ->select('pi.posting_date', 'pi.tc_name', 'pi.bill_no', 'pi.supplier', 'pi.grand_total', 'pi.status')
            ->get();

        $delinquent_pinv = collect($delinquent)->count();

        $beyond_terms_paid = DB::connection('mysql_erp')->table('tabPayment Entry AS pe')
            ->join('tabPayment Entry Reference AS per', 'pe.name', 'per.parent')
            ->join('tabPurchase Invoice AS pi', 'per.reference_name', 'pi.name')
            ->where('pi.company', 'FUMACO Inc.')
            ->when($month, function ($query, $month) {
                return $query->whereMonth('pi.posting_date', $month);
            })
            ->when($branch, function ($query, $branch) {
                return $query->where('pi.branch', $branch);
            })
            ->whereYear('pi.posting_date', $year)
            ->where('per.reference_doctype', 'Purchase Invoice')->where('pi.tc_name', '!=', 'Cash')
            ->where('pe.docstatus', 1)->whereRaw('pe.posting_date != pi.posting_date')
            ->select('pi.posting_date as si_posting_date', 'pe.posting_date as pe_posting_date', 'pi.tc_name', 'pi.name')
            ->get();

        $beyond_terms_unpaid = DB::connection('mysql_erp')->table('tabPurchase Invoice')
            ->whereYear('posting_date', $year)
            ->when($month, function ($query, $month) {
                return $query->whereMonth('posting_date', $month);
            })
            ->when($branch, function ($query, $branch) {
                return $query->where('branch', $branch);
            })
            ->where('docstatus', 1)->whereIn('status', ['Unpaid', 'Overdue'])
            ->where('tc_name', '!=', 'Cash')->get();
        
        $beyond_terms = 0;
        $beyond_terms_arr = [];
        foreach ($beyond_terms_paid as $value) {
            $days = 0;
            $splittedstring = explode(" ", $value->tc_name);
            foreach ($splittedstring as $val) {
                if ((int)$val) {
                    $days = (int)$val;
                }
            }

            $pi_due_date = Carbon::parse($value->si_posting_date)->addDays($days);
            $pi_due_date = $pi_due_date->format('Y-m-d');
            if ($days > 0) {
                if ($value->pe_posting_date > $pi_due_date) {
                    $beyond_terms++;
                    $beyond_terms_arr[] = [
                        'sinv' => $value->name,
                    ];
                }
            }
        }

        foreach ($beyond_terms_unpaid as $value) {
            $days = 0;
            $splittedstring = explode(" ", $value->tc_name);
            foreach ($splittedstring as $val) {
                if ((int)$val) {
                    $days = (int)$val;
                }
            }

            $pi_due_date = Carbon::parse($value->posting_date)->addDays($days);
            $pi_due_date = $pi_due_date->format('Y-m-d');
            if ($days > 0) {
                if (date('Y-m-d') > $pi_due_date) {
                    $beyond_terms++;
                    $beyond_terms_arr[] = [
                        'sinv' => $value->name,
                    ];
                }
            }
        }

        $percentage = ($total_pinv > 0) ? (($total_pinv - ($beyond_terms + $delinquent_pinv)) / $total_pinv) * 100 : 0;

        $data = [
            'total_pinv' => $total_pinv,
            'cash_pinv' => $cash_pinv,
            'credit_pinv' => $credit_pinv,
            'delinquent_pinv' => $delinquent_pinv,
            'beyond_terms' => $beyond_terms,
            'delinquent' => $delinquent,
            'percentage' => round($percentage, 2)
        ];

        return $data;
    }

    public function purchaseInvAnalysisCtx(Request $request, $year){
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $chart_data = [];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
            $request->request->add(['month' => $i]);
            $pinv = $this->purchaseInvAnalysis($request, $year);

            $chart_data[] = [
                'month' => $months[$i],
                'total' => $pinv['percentage'],
            ];
        }

        return response()->json($chart_data);
    }

    public function cashReceiptChart(Request $request, $year){
        $branch = $request->branch;
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $chart_data = [];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
           $je = DB::connection('mysql_erp')->table('tabJournal Entry AS je')
                ->join('tabJournal Entry Account AS jea', 'je.name', 'jea.parent')
                ->selectRaw('sum(jea.debit_in_account_currency) as debit')
                ->where('je.docstatus', 1)
                ->where('je.company', 'FUMACO Inc.')
                ->where('je.classification', 'Income')
                ->where('je.voucher_type', '!=', 'Opening Entry')
                ->when($branch, function ($query, $branch) {
                    return $query->whereRaw('(SELECT branch FROM `tabUser` where name = je.owner) = ?', $branch);
                })
                ->whereYear('je.posting_date', $year)
                ->whereMonth('je.posting_date', $i);

            $ge = DB::connection('mysql_erp')->table('tabGL Entry AS ge')->join('tabPayment Entry AS pe', 'ge.voucher_no', 'pe.name')
                    ->selectRaw('sum(ge.debit) as debit')
                    ->where('ge.docstatus', 1)
                    ->where('ge.company', 'FUMACO Inc.')
                    ->where('ge.voucher_type', 'Payment Entry')
                    ->where('pe.party_type', 'Customer')
                    ->whereYear('ge.posting_date', $year)
                    ->whereMonth('ge.posting_date', $i)
                    ->when($branch, function ($query, $branch) {
                        return $query->whereRaw('(SELECT branch FROM `tabUser` where name = ge.owner) = ?', $branch);
                    })
                    ->union($je)
                    ->sum('debit');

            $chart_data[] = [
                'month' => $months[$i],
                'total' => round($ge, 2),
            ];
        }

        return response()->json($chart_data);
    }

    public function cashDisbursementChart(Request $request, $year){
        $branch = $request->branch;
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $chart_data = [];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
           $je = DB::connection('mysql_erp')->table('tabJournal Entry AS je')
                ->join('tabJournal Entry Account AS jea', 'je.name', 'jea.parent')
                ->selectRaw('sum(jea.debit_in_account_currency) as debit')
                ->where('je.docstatus', 1)
                ->where('je.company', 'FUMACO Inc.')
                ->where('je.classification', 'like', '%Expense%')
                ->where('je.voucher_type', '!=', 'Opening Entry')
                ->whereYear('je.posting_date', $year)
                ->when($branch, function ($query, $branch) {
                    return $query->whereRaw('(SELECT branch FROM `tabUser` where name = je.owner) = ?', $branch);
                })
                ->whereMonth('je.posting_date', $i);

            $ge = DB::connection('mysql_erp')->table('tabGL Entry AS ge')->join('tabPayment Entry AS pe', 'ge.voucher_no', 'pe.name')
                    ->selectRaw('sum(ge.debit) as debit')
                    ->where('ge.docstatus', 1)
                    ->where('ge.company', 'FUMACO Inc.')
                    ->where('ge.voucher_type', 'Payment Entry')
                    ->where('pe.payment_type', 'Pay')
                    ->whereYear('ge.posting_date', $year)
                    ->whereMonth('ge.posting_date', $i)
                    ->when($branch, function ($query, $branch) {
                    return $query->whereRaw('(SELECT branch FROM `tabUser` where name = ge.owner) = ?', $branch);
                })
                    ->unionAll($je)
                    ->sum('debit');

            $chart_data[] = [
                'month' => $months[$i],
                'total' => round($ge, 2),
            ];
        }

        return response()->json($chart_data);
    }

    public function topExpenses($year){
        $expenses = DB::connection('mysql_erp')->table('tabExpense Claim Detail AS ecd')
            ->join('tabExpense Claim AS ec', 'ecd.parent', 'ec.name')
            ->where('ec.company', 'FUMACO Inc.')->whereYear('expense_date', $year)
            ->selectRaw('ecd.expense_type, SUM(ecd.amount) as total')
            ->groupBy('ecd.expense_type')->orderBy('total', 'desc')->limit(10)->get();

        return response()->json($expenses);
    }

    public function sales_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi = DB::table('kpi')->where('kpi.department_id', 2)->get();

        $current = date('Y');
        $prev = $current - 1;

        $sales_current = DB::connection('mysql_erp')->table('tabSales Order')
                ->whereYear('transaction_date', $current)->where('docstatus', 1)
                ->where('status', '!=', 'Closed')->where('company', 'FUMACO Inc.')
                ->sum('grand_total');

        $sales_prev = DB::connection('mysql_erp')->table('tabSales Order')
                ->whereYear('transaction_date', $prev)->where('docstatus', 1)
                ->where('status', '!=', 'Closed')->where('company', 'FUMACO Inc.')
                ->sum('grand_total');

        $sales_current_shorten = $this->shortenNumeral($sales_current);
        $sales_prev_shorten = $this->shortenNumeral($sales_prev);


        $annual_sales = [
            'current_sales_shorten' => $sales_current_shorten,
            'current_sales' => $sales_current,
            'current_year' => $current,
            'previous_sales_shorten' => $sales_prev_shorten,
            'previous_sales' => $sales_prev,
            'previous_year' => $prev,
        ];

        return view('client.modules.evaluation.overview.department.sales.index', compact('designation', 'department','kpi', 'annual_sales'));  
    }

    public function sales_totals(){
        $sales_order = DB::connection('mysql_erp')->table('tabSales Order')
            ->where('docstatus', 1)->count();
        $overdue_sales_order = DB::connection('mysql_erp')->table('tabSales Order')
            ->whereIn('sales_type', ['Regular Sales', 'Sales DR', 'Sales on Consignment'])
            ->where('delivery_date', '<', date('Y-m-d'))->where('status', '!=', 'Closed')
            ->where('docstatus', 1)->where('per_delivered', '<', 100)->count();
        $delivery_note = DB::connection('mysql_erp')->table('tabDelivery Note')
            ->whereIn('sales_type', ['Regular Sales', 'Sales DR', 'Sales on Consignment'])
            ->where('docstatus', 1)->count();

        $for_delivery = DB::connection('mysql_erp')->table('tabSales Order')
            ->whereIn('sales_type', ['Regular Sales', 'Sales DR', 'Sales on Consignment'])
            ->where('docstatus', 1)->where('per_delivered', '<', 100)->count();

        $data = [
            'sales_order' => $sales_order,
            'overdue_sales_order' => $overdue_sales_order,
            'delivery_note' => $delivery_note,
            'for_delivery' => $for_delivery
        ];

        return response()->json($data);
    }

    public function salesChart($year){
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
            $sales_per_month = DB::connection('mysql_erp')->table('tabSales Order')
                ->whereMonth('transaction_date', $i)->whereYear('transaction_date', $year)
                ->where('docstatus', 1)->where('status', '!=', 'Closed')
                ->where('company', 'FUMACO Inc.')
                ->whereIn('sales_type', ['Regular Sales', 'Sales on Consignment', 'Sales DR'])
                ->select('grand_total', 'sales_type')
                ->get();

            $overall = collect($sales_per_month)->sum('grand_total');
            $reg = collect($sales_per_month)->where('sales_type', 'Regular Sales')->sum('grand_total');
            $consignment = collect($sales_per_month)->where('sales_type', 'Sales on Consignment')->sum('grand_total');
            $dr = collect($sales_per_month)->where('sales_type', 'Sales DR')->sum('grand_total');

            $chart_data[] = [
                'month_no' => $i,
                'month' => $months[$i],
                'total' => round($overall, 2),
                'regular_sales' => round($reg, 2),
                'sales_consignment' => round($consignment, 2),
                'sales_dr' => round($dr, 2)
            ];
        }

        return response()->json($chart_data);
    }

    public function opportunityStats($year){
        $opty = DB::connection('mysql_erp')->table('tabOpportunity')
        ->whereYear('creation', $year)->select('status')->get();

        $won = collect($opty)->where('status', 'Won')->count();
        $open = collect($opty)->where('status', 'Open')->count();
        $lost = collect($opty)->where('status', 'Lost')->count();
        $qtn = collect($opty)->where('status', 'Quotation')->count();

        $data = [
            'won' => $won,
            'open' => $open,
            'lost' => $lost,
            'qtn' => $qtn,
        ];

        return response()->json($data);
    }

    public function shortenNumeral($n){
        if ($n < 1000) return $n;
        $suffix = ['','k','M','G','T','P','E','Z','Y'];
        $power = floor(log($n, 1000));

        return round($n/(1000**$power),1,PHP_ROUND_HALF_EVEN).$suffix[$power];
    }
    
    public function engineering_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 3)
        ->get();

        $last_update = DB::table('kpi_datainput_result')->whereIn('data_input_id', [75, 7, 8])->max('date_submitted');

        return view('client.modules.evaluation.overview.department.engineering.index', compact('designation', 'department','kpi', 'last_update'));
    }

    public function customer_service_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 4)
        ->get();
        return view('client.modules.evaluation.overview.department.customer_service.index', compact('designation', 'department','kpi'));
    }

    public function cskpi1_stat(Request $request){
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $current= date('n');
        $current_year= date('Y');

        $data = [];
        foreach ($months as $i => $month) {
            $target=$this->target_kpi(43);
            $percentage= collect($target)->sum('target');
            $i = $i + 1;
            $data_inputs = DB::table('kpi_datainput_result')
            ->join('data_input','data_input.input_id','=','kpi_datainput_result.data_input_id')
            ->join('metrics','metrics.metric_id','=','data_input.metric_id')
            ->where('kpi_datainput_result.month', $i)
            ->where('kpi_datainput_result.year',$request->year)
            ->get();

            $product_perf = collect($data_inputs)->where('kpi_id', 43)->where('metric_id', 272)->sum('answer');
            $product_perf_avg = ($product_perf/4)  ;
            $delandserv_perf = collect($data_inputs)->where('kpi_id', 43)->where('metric_id', 273)->sum('answer');
            $delandserv_perf_avg = ($delandserv_perf/4);
            $tech_perf = collect($data_inputs)->where('kpi_id', 43)->where('metric_id', 274)->sum('answer');
            $tech_perf_avg = ($tech_perf/4);

            $sales_perf = collect($data_inputs)->where('kpi_id', 43)->where('metric_id', 275)->sum('answer');
            $sales_perf_avg = ($sales_perf/5);
            $price_cost_perf = collect($data_inputs)->where('kpi_id', 43)->where('metric_id', 276)->sum('answer');
            $price_cost_perf_avg = ($price_cost_perf/3);

            $computation= ($product_perf_avg + $delandserv_perf_avg + $tech_perf_avg + $sales_perf_avg + $price_cost_perf_avg)/5 ;

            if ($current_year == $request->year) {
                    if ($i <= $current) {
                        $data[] = [
                        'month' => $month,
                        'total' => round($computation,2),
                        'target'=>$percentage,
                    ];
                }
            }else{
              $data[] = [
                    'month' => $month,
                    'total' => round($computation,2),
                    'target'=>$percentage,
                ];
            }
        }

        return $data;
    }

    public function within_departmentfaultPie(Request $request, $year){
        $month = $request->month;
        $total_delivery_order = DB::table('kpi_datainput_result')
                ->where('user_id', null)->where('data_input_id', 175)
                ->when($month, function ($query, $month) {
                    return $query->where('month', $month);
                })
                ->where('year', $year)->sum('answer');

        $ids = [176, 177, 178];
        $data_inputs = DB::table('kpi_datainput_result')
            ->whereIn('data_input_id', $ids)
            ->where('year', $year)->get()
            ->where('month', $month);

        $inputs = [];
        foreach ($ids as $id) {
            $cause = DB::table('data_input')->where('input_id', $id)->first();
            $input_result = collect($data_inputs)->where('data_input_id', $id)->sum('answer');
            $percentage = ($total_delivery_order > 0) ? ($input_result / $total_delivery_order) * 100 : 0;
            $inputs[] = [
                'cause' => $cause->data_input,
                'percentage' => round($percentage, 1)
            ];
        }

        return response()->json($inputs);
    }

    public function not_within_departmentfaultPie(Request $request, $year){
        $month = $request->month;
        $total_delivery_order = DB::table('kpi_datainput_result')
                ->where('user_id', null)->where('data_input_id', 175)
                ->when($month, function ($query, $month) {
                    return $query->where('month', $month);
                })
                ->where('year', $year)->sum('answer');

        $ids = [179, 180, 181, 182, 183, 184, 185];
        $data_inputs = DB::table('kpi_datainput_result')
            ->whereIn('data_input_id', $ids)
            ->where('year', $year)->get()
            ->where('month', $month);

        $inputs = [];
        foreach ($ids as $id) {
            $cause = DB::table('data_input')->where('input_id', $id)->first();
            $input_result = collect($data_inputs)->where('data_input_id', $id)->sum('answer');
            $percentage = ($total_delivery_order > 0) ? $input_result : 0;
            $inputs[] = [
                'cause' => $cause->data_input,
                'percentage' => round($percentage, 1)
            ];
        }

        return response()->json($inputs);
    }

    public function cskpi2_stat(Request $request){
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $current= date('n');
        $current_year= date('Y');

        $data = [];
        foreach ($months as $i => $month) {
            $target=$this->target_kpi(44);
            $percentage= collect($target)->sum('target');
            $i = $i + 1;
            $data_inputs = DB::table('kpi_datainput_result')
            ->join('data_input','data_input.input_id','=','kpi_datainput_result.data_input_id')
            ->join('metrics','metrics.metric_id','=','data_input.metric_id')
            ->where('kpi_datainput_result.month', $i)
            ->where('kpi_datainput_result.year',$request->year)
            ->get();

            $total_deliveries = collect($data_inputs)->where('kpi_id', 44)->where('metric_id', 278)->sum('answer');
            $within_dept_fault = collect($data_inputs)->where('kpi_id', 44)->where('metric_id', 279)->sum('answer');
            $diff= $total_deliveries - $within_dept_fault;

            if($total_deliveries == 0){
                $var1=1;
            }else{
                $var1=$total_deliveries;
            }
            if ($diff == 0) {
                $var=0;
            }else{
                $var=$diff;
            }
            $computation= ($var/ $var1) * 100;

            if ($current_year == $request->year) {
                    if ($i <= $current) {
                        $data[] = [
                        'month' => $month,
                        'total' => round($computation,2),
                        'target'=>$percentage,
                    ];
                }
            }else{
              $data[] = [
                    'month' => $month,
                    'total' => round($computation,2),
                    'target'=>$percentage,
                ];
            }
        }

        return $data;
    }

    public function csperformance_chart(Request $request, $year){
        if ($request->month == 01) {
            $year = $request->year - 1;
            $month = 12;
        }else{
            $year= $request->year;
            $month=$request->month - 1;
        }

        $prev_month_ts = $request->month - 1;

        $data = [];
            $previous_month_data_input =DB::table('kpi_datainput_result')
            ->join('data_input','data_input.input_id','=','kpi_datainput_result.data_input_id')
            ->join('metrics','metrics.metric_id','=','data_input.metric_id')
            ->where('kpi_datainput_result.month', $month)
            ->where('kpi_datainput_result.year',$year)
            ->get();

            $data_inputs = DB::table('kpi_datainput_result')
            ->join('data_input','data_input.input_id','=','kpi_datainput_result.data_input_id')
            ->join('metrics','metrics.metric_id','=','data_input.metric_id')
            ->where('kpi_datainput_result.month', $request->month)
            ->where('kpi_datainput_result.year',$request->year)
            ->get();

            $product_perf = collect($data_inputs)->where('kpi_id', 43)->where('metric_id', 272)->sum('answer');
            $product_perf_avg = ($product_perf/4)  ;
            $delandserv_perf = collect($data_inputs)->where('kpi_id', 43)->where('metric_id', 273)->sum('answer');
            $delandserv_perf_avg = ($delandserv_perf/4);
            $tech_perf = collect($data_inputs)->where('kpi_id', 43)->where('metric_id', 274)->sum('answer');
            $tech_perf_avg = ($tech_perf/4);

            $sales_perf = collect($data_inputs)->where('kpi_id', 43)->where('metric_id', 275)->sum('answer');
            $sales_perf_avg = ($sales_perf/5);
            $price_cost_perf = collect($data_inputs)->where('kpi_id', 43)->where('metric_id', 276)->sum('answer');
            $price_cost_perf_avg = ($price_cost_perf/3);

            ////////////////////////////////////////////////
            $product_perf_previous = collect($previous_month_data_input)->where('kpi_id', 43)->where('metric_id', 272)->sum('answer');
            $product_perf_avg_previous = ($product_perf_previous/4)  ;
            $delandserv_perf_previous = collect($previous_month_data_input)->where('kpi_id', 43)->where('metric_id', 273)->sum('answer');
            $delandserv_perf_avg_previous = ($delandserv_perf_previous/4);
            $tech_perf_previous = collect($previous_month_data_input)->where('kpi_id', 43)->where('metric_id', 274)->sum('answer');
            $tech_perf_avg_previous = ($tech_perf_previous/4);

            $sales_perf_previous = collect($previous_month_data_input)->where('kpi_id', 43)->where('metric_id', 275)->sum('answer');
            $sales_perf_avg_previous = ($sales_perf_previous/5);
            $price_cost_perf_previous = collect($previous_month_data_input)->where('kpi_id', 43)->where('metric_id', 276)->sum('answer');
            $price_cost_perf_avg_previous = ($price_cost_perf_previous/3);

            $product_perf_avg_status = $product_perf_avg > $product_perf_avg_previous ? 'up' : 'down';
            $delandserv_perf_avg_status = $delandserv_perf_avg > $delandserv_perf_avg_previous ? 'up' : 'down';
            $tech_perf_avg_status = $tech_perf_avg > $tech_perf_avg_previous ? 'up' : 'down';
            $sales_perf_avg_status = $sales_perf_avg > $sales_perf_avg_previous ? 'up' : 'down';
            $price_cost_perf_avg_status = $price_cost_perf_avg > $price_cost_perf_avg_previous ? 'up' : 'down';

            $data[] = [
                'month' => $month,
                'product_perf' => round($product_perf_avg, 1),
                'product_perf_status'=> $product_perf_avg_status,
                'delivery_service_perf' => round($delandserv_perf_avg, 1),
                'delivery_service_perf_status'=> $delandserv_perf_avg_status,
                'technical_perf' => round($tech_perf_avg, 1),
                'technical_perf_status'=> $tech_perf_avg_status,
                'sales_perf' => round($sales_perf_avg, 1),
                'sales_perf_status'=> $sales_perf_avg_status,
                'price_cost_perf' => round($price_cost_perf_avg, 1),
                'price_cost_perf_status'=> $price_cost_perf_avg_status
            ];
        
        return $data;
    }

    public function salesTotal(){
        $sales_order = DB::connection('mysql_erp')->select('SELECT name, workflow_state, creation FROM `tabSales Order`');
        $quoation = DB::connection('mysql_erp')->select('SELECT name, workflow_state, creation FROM `tabQuotation`');
        $delivery_note = DB::connection('mysql_erp')->select('SELECT name, workflow_state, sales_type, creation FROM `tabDelivery Note`');
        $sales_invoices = DB::connection('mysql_erp')->select('SELECT name, status, creation FROM `tabSales Invoice`');

        $total_sales_order = collect($sales_order)->where('workflow_state', 'Approved')->count();
        $total_quotation = collect($quoation)->where('workflow_state', 'Approved')->count();
        $total_delivery_note = collect($delivery_note)
        ->where('workflow_state', 'Approved')
        ->whereIn('sales_type', ['Regular Sales', 'Sales DR', 'Sales on Consignment'])
        ->count();
        $total_sales_invoices = collect($sales_invoices)->where('status', 'Paid')->count();
        $total_sales_invoices = collect($sales_invoices)->whereIn('status', ['Return', 'Credit Note Issued', 'Submitted', 'Paid', 'Unpaid', 'Overdue'])->count();

        $data = [
            'total_sales_order' => $total_sales_order,
            'total_quotation' => $total_quotation,
            'total_delivery_note' => $total_delivery_note,
            'total_sales_invoices' => $total_sales_invoices

        ];

        return $data;
    }

    public function salesOrder_data($year, $month){

        $sales_order = DB::connection('mysql_erp')->select('SELECT reference_doctype,subject,reference_name,subject, communication_date, creation FROM `tabCommunication` WHERE MONTH(communication_date) = '.$month.' AND YEAR(communication_date) = '. $year.'');

        $total_for_approval = collect($sales_order)
        ->where('reference_doctype', 'Sales Order')
        ->where('subject','For Approval');
        $data = [];
        foreach ($total_for_approval as $row) {
          $total_approved = collect($sales_order)
            ->where('reference_doctype', 'Sales Order')
            ->where('subject', 'Approved');
         
            foreach ($total_approved as $row1) {
              if ($row->reference_name == $row1->reference_name) {
                  $approve=Carbon::parse($row1->communication_date);
                  $for_approval=Carbon::parse($row->communication_date);
                  $totalDuration = $approve->diffInSeconds($for_approval);
                  $reference_name1=  $row->reference_name;
                  $total_status = $totalDuration < 10800 ? 'ontime':'late';

                  $data[] = [
                      'duration' => $totalDuration,
                      'for_approval' => $row->reference_name,
                      'approved ' => $row1->reference_name,
                      'date_approved' => $row1->communication_date,
                      'date_approval' => $row->communication_date,
                      'total_status' => $total_status
                  ];
              }
            }
        }   

        return $data;  
    }

    public function hrkpi1_stat(Request $request){
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $current= date('n');
        $current_year= date('Y');

        $data = [];
        foreach ($months as $i => $month) {
            $target=$this->target_kpi(49);
            $percentage= collect($target)->sum('target');
            $i = $i + 1;
            $data_inputs = DB::table('kpi_datainput_result')
            ->join('data_input','data_input.input_id','=','kpi_datainput_result.data_input_id')
            ->join('metrics','metrics.metric_id','=','data_input.metric_id')
            ->where('kpi_datainput_result.month', $i)
            ->where('kpi_datainput_result.year', $request->year)
            ->get();
                
            $implemented = collect($data_inputs)->where('kpi_id', 49)->where('data_input_id', 34)->sum('answer');
            $proposed = collect($data_inputs)->where('kpi_id', 49)->where('data_input_id', 93)->sum('answer');

            if($implemented == 0){
                $var=0;
            }else{
                $var=$implemented;
            }
            if ($proposed == 0) {
                $var1=1;
            }else{
                $var1=$proposed;
            }
            $computation= ($var/ $var1) * 100;

            if ($current_year == $request->year) {
                    if ($i <= $current) {
                        $data[] = [
                        'month' => $month,
                        'total' => round($computation,2),
                        'target'=>$percentage, 
                    ];
                }
            }else{
              $data[] = [
                    'month' => $month,
                    'total' => round($computation,2),
                    'target'=>$percentage,
                ];
            }
        }

        return $data;
    }

    public function salesOrder_timeliness(Request $request, $year){
        $data = [];
        $current= date('n');
        $current_year= date('Y');
        $target=$this->target_kpi(43);
        $percentage= collect($target)->sum('target');

        $SO_data= $this->salesOrder_data($year,$request->month);
        $SO_ontime = collect($SO_data)->where('total_status', 'ontime')->count();
        $SO_late = collect($SO_data)->where('total_status', 'late')->count();
        $SO_total = collect($SO_data)->count();
        $avg_submission = collect($SO_data)->sum('duration');

        if($SO_late == 0){
            $var2=0;
        }else{
            $var2=$SO_late;
        }
        if($SO_ontime == 0){
            $var=0;
        }else{
            $var=$SO_ontime;
        }
        if ($SO_total == 0) {
            $var1=1;
        }else{
            $var1=$SO_total;
        }
        $late_rate= ($var2/ $var1) * 100;
        $ontime_rate=($var/ $var1) * 100;
        $avr_hrs_submission=($avg_submission/ $var1) * 100;
        $converted = gmdate('H:i:s', $avr_hrs_submission);

        $data[] = [
            'late_rate' => round($late_rate, 2),
            'ontime_rate' => round($ontime_rate, 2),
            'avr_hrs_submission'=> round($converted, 2),
            'total_so_ontime'=> $SO_ontime,
            'total_so_late'=> $SO_late,
            
        ];

        return $data;
    }

    public function quality_assurance_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 5)
        ->get();
        return view('client.modules.evaluation.overview.department.qa.index', compact('designation', 'department','kpi'));
    }
    public function production_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 8)
        ->get();
        return view('client.modules.evaluation.overview.department.production.index', compact('designation', 'department','kpi'));
    }
    public function human_resource_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 6)
        ->get();
        return view('client.modules.evaluation.overview.department.hr.index', compact('designation', 'department','kpi'));
    }
    public function plant_services_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 7)
        ->get();
        return view('client.modules.evaluation.overview.department.plant_services.index', compact('designation', 'department','kpi'));
    }
    
    public function information_technology_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');
        
        $kpi = DB::table('kpi')->where('kpi.department_id', 9)->get();

        $data_inputs = DB::table('kpi_datainput_result')
            ->join('data_input','data_input.input_id','=','kpi_datainput_result.data_input_id')
            ->join('metrics','metrics.metric_id','=','data_input.metric_id')
            ->where('metrics.kpi_id', 61)->where('metrics.metric_id', 128)
            ->orderBy('kpi_datainput_result.year', 'desc')
            ->limit(4)
            ->get();

        return view('client.modules.evaluation.overview.department.it.index', compact('designation', 'department','kpi','data_inputs'));
    }

    public function materials_management_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi = DB::table('kpi')->where('kpi.department_id', 10)->get();

        $item_classification = DB::connection('mysql_erp')->table('tabItem Classification')->get();

        return view('client.modules.evaluation.overview.department.materials_management.index', compact('designation', 'department','kpi', 'item_classification'));
    }

    public function materials_management_totals(){
        $total_ste = DB::connection('mysql_erp')->table('tabStock Entry')
            ->where('docstatus', 1)->count();
        $open_ste = DB::connection('mysql_erp')->table('tabStock Entry')
            ->where('docstatus', 0)->count();
        $mr_ste = DB::connection('mysql_erp')->table('tabStock Entry')
            ->where('docstatus', 1)->where('purpose', 'Material Receipt')->count();
        $mtfm_ste = DB::connection('mysql_erp')->table('tabStock Entry')
            ->where('docstatus', 1)->where('purpose', 'Material Transfer for Manufacture')->count();
        $mi_ste = DB::connection('mysql_erp')->table('tabStock Entry')
            ->where('docstatus', 1)->where('purpose', 'Material Issue')->count();

        $totals = [
            'total_ste' => $total_ste,
            'open_ste' => $open_ste,
            'mr_ste' => $mr_ste,
            'mtfm_ste' => $mtfm_ste,
            'mi_ste' => $mi_ste,
        ];

        return response()->json($totals);
    }

    public function purchasing_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi = DB::table('kpi')->where('kpi.department_id', 10)->get();

        $item_classification = DB::connection('mysql_erp')->table('tabItem Classification')->get();

        return view('client.modules.evaluation.overview.department.materials_management.index2', compact('designation', 'department','kpi', 'item_classification'));
    }

    public function invAccuracyChart($year){
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
            $inv_audit = DB::connection('mysql_erp')
                ->table('tabMonthly Inventory Audit')
                ->select('name', 'item_classification', 'average_accuracy_rate', 'warehouse', 'percentage_sku')
                ->whereYear('from', $year)->whereMonth('from', $i)
                ->where('docstatus', '<', 2)->get();

            $average = collect($inv_audit)->avg('average_accuracy_rate');

            $chart_data[] = [
                'month_no' => $i,
                'month' => $months[$i],
                'audit_per_month' => $inv_audit,
                'average' => round($average, 2),
            ];
        }

        return response()->json($chart_data);
    }

    public function itemMovements(Request $request, $year){
        $month = $request->month;

        $qry = ($month) ?  'AND MONTH(posting_date) = '.$month : '';

        $stock_movements = DB::connection('mysql_erp')
                ->table('tabStock Ledger Entry')
                ->selectRaw('item_code AS code, (SELECT description FROM `tabItem` WHERE name = code) AS description, (SELECT item_classification FROM `tabItem` WHERE name = code) AS item_classification, (SELECT COUNT(DISTINCT voucher_no) FROM `tabStock Ledger Entry` WHERE item_code = code '.$qry.' AND YEAR(posting_date) = '.$year.') AS total_transactions')
                ->whereYear('posting_date', $year)
                ->when($month, function ($query, $month) {
                    return $query->whereMonth('posting_date', $month);
                })
                ->groupBy('item_code')
                ->orderBy('total_transactions', 'desc')
                ->limit(10)->get();

        return response()->json($stock_movements);
    }

    public function itemClassMovements(Request $request, $year){
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
            $month = $i;
            $stock_movements = DB::connection('mysql_erp')
                    ->table('tabStock Ledger Entry')
                    ->selectRaw('item_code AS code, (SELECT item_classification FROM `tabItem` WHERE name = code) AS item_classification, (SELECT COUNT(DISTINCT voucher_no) FROM `tabStock Ledger Entry` WHERE item_code = code AND MONTH(posting_date) = '.$month.' AND YEAR(posting_date) = '.$year.') AS total_transactions')
                    ->whereYear('posting_date', $year)
                    ->whereMonth('posting_date', $month)
                    ->groupBy('item_code')
                    ->orderBy('item_classification', 'asc')
                    ->get();

            $total_transactions = $stock_movements->groupBy('item_classification')->map(function ($row) {
                return $row->sum('total_transactions');
            });

            $chart_data[] = [
                'month_no' => $i,
                'month' => $months[$i],
                'total_transactions' => collect($stock_movements)->sum('total_transactions'),
                'transactions_per_class' => $total_transactions,
            ];
        }

        return response()->json($chart_data);
    }

    public function purchasesTimeliness($year, $supplier_group){
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
            $month = $i;
            $purchases = DB::connection('mysql_erp')
                ->table('tabPurchase Receipt AS pr')
                ->join('tabPurchase Receipt Item AS pri', 'pr.name', 'pri.parent')
                ->join('tabPurchase Order AS po', 'po.name', 'pri.purchase_order')
                ->selectRaw('distinct pri.purchase_order, po.supplier, po.transaction_date, po.required_date, po.arrival_date, pr.invoice_no, pr.posting_date, DATEDIFF(po.required_date, pr.posting_date) as on_time, po.supplier_group')
                ->where('pr.docstatus', 1)
                ->where('po.supplier_group', $supplier_group)
                ->whereYear('po.transaction_date', $year)
                ->whereMonth('po.transaction_date', $month)
                ->orderBy('po.name', 'desc')->get();

            $total_deliveries = collect($purchases)->count();
            $on_time_deliveries = collect($purchases)->where('on_time', '>=', 0)->count();

            $percentage = ($total_deliveries > 0) ? ($on_time_deliveries / $total_deliveries) * 100 : 0;

            $chart_data[] = [
                'month_no' => $i,
                'month' => $months[$i],
                'deliveries' => $total_deliveries,
                'lates' => $on_time_deliveries,
                'percentage' => round($percentage, 2),
            ];
        }

        return response()->json($chart_data);
    }

    public function purchasing_totals(){
        $total_po = DB::connection('mysql_erp')->table('tabPurchase Order')
            ->where('docstatus', 1)->count();
        $upcoming_deliveries = DB::connection('mysql_erp')->table('tabPurchase Order')
            ->where('status', 'To Receive and Bill')->orWhere('status', 'To Receive')->count();
        $open_po = DB::connection('mysql_erp')->table('tabPurchase Order')
            ->where('docstatus', 0)->count();
        $late_deliveries = DB::connection('mysql_erp')->table('tabPurchase Receipt AS pr')
            ->join('tabPurchase Receipt Item AS pri', 'pr.name', 'pri.parent')
            ->join('tabPurchase Order AS po', 'po.name', 'pri.purchase_order')
            ->selectRaw('distinct pri.purchase_order')->where('pr.docstatus', 1)
            ->where(DB::raw('DATEDIFF(po.required_date, pr.posting_date)'), '<', 0)->count();

        $totals = [
            'total_po' => $total_po,
            'upcoming_deliveries' => $upcoming_deliveries,
            'open_po' => $open_po,
            'late_deliveries' => $late_deliveries,
        ];

        return response()->json($totals);
    }

    public function management_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 12)
        ->get();
        return view('client.modules.evaluation.overview.department.management.index', compact('designation', 'department','kpi'));
    }
    public function marketing_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 13)
        ->get();

        return view('client.modules.evaluation.overview.department.marketing.index', compact('designation', 'department','kpi'));
    }
    public function assembly_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 14)
        ->get();

        return view('client.modules.evaluation.overview.department.assembly.index', compact('designation', 'department','kpi'));
    }
    public function fabrication_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
            ->where('kpi.department_id', 15)
            ->get();

        return view('client.modules.evaluation.overview.department.fabrication.index', compact('designation', 'department','kpi'));
    }
    public function traffic_and_distribution_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 16)
        ->get();

        return view('client.modules.evaluation.overview.department.traffic_and_distribution.index', compact('designation', 'department','kpi'));
    }
    public function painting_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 17)
        ->get();

        return view('client.modules.evaluation.overview.department.painting.index', compact('designation', 'department','kpi'));
    }
    public function filunited_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 19)
        ->get();

        return view('client.modules.evaluation.overview.department.filunited.index', compact('designation', 'department','kpi'));
    }
    public function production_planning_index(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $kpi=DB::table('kpi')
        ->where('kpi.department_id', 20)
        ->get();

        return view('client.modules.evaluation.overview.department.production_planning.index', compact('designation', 'department','kpi'));
    }
    // kpi per department end

    public function accountingKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.accounting.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function sinvPerMonthChart($year){
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $sinvs = DB::connection('mysql_erp')->table('tabSales Invoice')
                ->where('docstatus', 1)->whereYear('posting_date', $year)
                ->where('company', 'FUMACO Inc.')
                ->selectRaw('tc_name, MONTH(posting_date) as m')->get();

        $chart_data = [];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
            $sinv_per_month = collect($sinvs)->where('m', $i)->count();
            $cash_sinv = collect($sinvs)->where('m', $i)->where('tc_name', 'Cash')->count();
            $other_sinv = collect($sinvs)->where('m', $i)->where('tc_name', '!=', 'Cash')->count();

            $chart_data[] = [
                'month' => $months[$i],
                'total' => $sinv_per_month,
                'cash_sinv' => $cash_sinv,
                'other_sinv' => $other_sinv,
            ];
        }

        return response()->json($chart_data);
    }

    public function creditCollectionChart($year){
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $sales_invoices = DB::table('kpi_datainput_result')
            ->where('user_id', null)->where('year', $year)
            ->whereIn('data_input_id', [17, 18])->get();

        $collectible_invoices = DB::table('kpi_datainput_result')
            ->where('user_id', null)->where('year', $year)
            ->whereIn('data_input_id', [19, 20, 21])->get();

        $chart_data = [];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
            $sinv_per_month = collect($sales_invoices)->where('month', $i)->sum('answer');
            $collectible_per_month = collect($collectible_invoices)->where('month', $i)->sum('answer');

            $percentage = $sinv_per_month > 0 ? ($collectible_per_month / $sinv_per_month) * 100 : 0;
            $chart_data[] = [
                'month' => $months[$i],
                'total' => $sinv_per_month,
                'total_coll' => $collectible_per_month,
                'percentage' => round($percentage, 2)
            ];
        }

        return $chart_data;
    }

    public function salesKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.sales.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function csKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.customer_service.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function qaKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.qa.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function plantServicesKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.plant_services.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function productionKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.production.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function materialsManagementKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.materials_management.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function managementKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.management.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function marketingKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.marketing.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function assemblyKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.assembly.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function fabricationKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.fabrication.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function trafficDistributionKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.traffic_and_distribution.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function deliveryCompletionChart($year){
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $chart_data = [];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
            $total_deliveries_accepted = DB::table('kpi_datainput_result')
                ->where('user_id', null)->where('data_input_id', 22)
                ->where('month', $i)->where('year', $year)->sum('answer');

            $total_delivery_invs = DB::table('kpi_datainput_result')
                ->where('user_id', null)->where('data_input_id', 23)
                ->where('month', $i)->where('year', $year)->sum('answer');

            $cust_rel_causes = DB::table('kpi_datainput_result')
                ->where('user_id', null)->whereIn('data_input_id', [28, 29, 30 ,31 , 32, 33])
                ->where('month', $i)->where('year', $year)->sum('answer');

            $total_deliveries_accepted = $total_deliveries_accepted + $cust_rel_causes;

            $percentage = $total_delivery_invs > 0 ? ($total_deliveries_accepted / $total_delivery_invs) * 100 : 0;

            $chart_data[] = [
                'month' => $months[$i],
                'total_deliveries_accepted' => $total_deliveries_accepted,
                'total_delivery_invs' => $total_delivery_invs,
                'percentage' => round($percentage, 1)
            ];
        }

        return response()->json($chart_data);
    }

    public function deliveryGoodConditionChart($year){
        $chart_data = [];
        $months = ['0', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $chart_data = [];
        $month_no = $year == date('Y') ? date('m') : 12;
        for ($i = 1; $i <= $month_no; $i++) {
            $total_deliveries = DB::table('kpi_datainput_result')
                ->where('user_id', null)->whereIn('data_input_id', [94, 154])
                ->where('month', $i)->where('year', $year)->sum('answer');

            $total_delivery_good = DB::table('kpi_datainput_result')
                ->where('user_id', null)->where('data_input_id', 94)
                ->where('month', $i)->where('year', $year)->sum('answer');

            // $total_delivery_not_good = DB::table('kpi_datainput_result')
            //     ->where('user_id', null)->where('data_input_id', 154)
            //     ->where('month', $i)->where('year', $year)->sum('answer');

            $percentage = $total_deliveries > 0 ? ($total_delivery_good / $total_deliveries) * 100 : 0;

            $chart_data[] = [
                'month' => $months[$i],
                'total_delivery_good' => $total_deliveries,
                'percentage' => round($percentage, 2)
            ];
        }

        return response()->json($chart_data);
    }

    public function nonDeliveryDeptCausesChart(Request $request, $year){
        $month = $request->month;
        $total_delivery_invs = DB::table('kpi_datainput_result')
                ->where('user_id', null)->where('data_input_id', 23)
                ->when($month, function ($query, $month) {
                    return $query->where('month', $month);
                })
                ->where('year', $year)->sum('answer');

        $ids = [24, 25, 26, 27];
        $data_inputs = DB::table('kpi_datainput_result')
            ->whereIn('data_input_id', $ids)
            ->where('year', $year)->get();

        $inputs = [];
        foreach ($ids as $id) {
            $cause = DB::table('data_input')->where('input_id', $id)->first();
            $input_result = collect($data_inputs)->where('data_input_id', $id)->sum('answer');
            $percentage = ($total_delivery_invs > 0) ? ($input_result / $total_delivery_invs) * 100 : 0;
            $inputs[] = [
                'cause' => $cause->data_input,
                'percentage' => round($percentage, 1)
            ];
        }

        return response()->json($inputs);
    }

    public function nonDeliveryCustCausesChart(Request $request, $year){
        $month = $request->month;
        $total_delivery_invs = DB::table('kpi_datainput_result')
                ->where('user_id', null)->where('data_input_id', 23)
                ->when($month, function ($query, $month) {
                    return $query->where('month', $month);
                })
                ->where('year', $year)->sum('answer');

        $ids = [28, 29, 30, 31, 32, 33];
        $data_inputs = DB::table('kpi_datainput_result')
            ->whereIn('data_input_id', $ids)
            ->where('year', $year)->get();

        $inputs = [];
        foreach ($ids as $id) {
            $cause = DB::table('data_input')->where('input_id', $id)->first();
            $input_result = collect($data_inputs)->where('data_input_id', $id)->sum('answer');
            $percentage = ($total_delivery_invs > 0) ? ($input_result / $total_delivery_invs) * 100 : 0;
            $inputs[] = [
                'cause' => $cause->data_input,
                'percentage' => round($percentage, 1)
            ];
        }

        return response()->json($inputs);
    }

    public function paintingKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.painting.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function filunitedKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.filunited.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function productionPlanningKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.production_planning.kpi_result', compact('year_list', 'designation', 'department'));
    }

    public function hrKpiResult(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $year_list = DB::table('fiscal_year')->get();

        return view('client.modules.evaluation.overview.department.hr.kpi_result', compact('year_list', 'designation', 'department'));
    }
}