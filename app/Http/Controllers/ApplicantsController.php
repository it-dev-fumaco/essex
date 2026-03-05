<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Auth;
use App\Models\User;
use App\Models\Examinee;
use App\Models\BackgroundCheck;
use App\Models\Backgroundquestion;
use App\Models\Applicant;
use DB;

class ApplicantsController extends Controller
{
    public function index(){
        $applicants = DB::table("users")
                        ->where('users.user_type', '=', 'Applicant')
                        ->get(); 

        $departments = DB::table('departments')->get(); 
        $designation = DB::table('designation')->get(); 

        return view('admin.applicant.index', ["applicants" => $applicants, "departments" => $departments, "designation" => $designation]);
    }

    public function store(Request $request){
        $applicant = new User;
        $applicant->employee_name = $request->employee_name;
        $applicant->birth_date = $request->birthdate;
        $applicant->address = $request->address;
        $applicant->contact_no = $request->contact_no;
        $applicant->sss_no = $request->sss_no;
        $applicant->gender= $request->gender;
        $applicant->tin_no = $request->tin_no;
        $applicant->civil_status = $request->civil_status;
        $applicant->nick_name = $request->nickname;
        $applicant->user_type = 'Applicant';
        $applicant->position_applied_for1 = $request->position_applied_for1;
        $applicant->position_applied_for2 = $request->position_applied_for2;
        $applicant->save();

        return redirect()->back()->with(["message" => "Applicant <b>" . $applicant->employee_name . "</b> has been successfully added!"]);
    }

    public function update(Request $request){
        $applicant = User::find($request->id);
        $applicant->employee_name = $request->employee_name;
        $applicant->birth_date = $request->birthdate;
        $applicant->address = $request->address;
        $applicant->contact_no = $request->contact_no;
        $applicant->sss_no = $request->sss_no;
        $applicant->tin_no = $request->tin_no;
        $applicant->gender= $request->gender;
        $applicant->civil_status = $request->civil_status;
        $applicant->nick_name = $request->nickname;
        $applicant->position_applied_for1 = $request->position_applied_for1;
        $applicant->position_applied_for2 = $request->position_applied_for2;
        $applicant->last_modified_by = Auth::user()->employee_name;
        $applicant->save();

        return redirect()->back()->with(["message" => "Applicant <b>" . $applicant->employee_name . "</b> has been successfully updated!"]);
    }

    public function delete(Request $request){
        $applicant = User::find($request->id);
        $applicant->delete();
        
        return redirect()->back()->with(['message' => 'Applicant <b>' . $applicant->employee_name . '</b>  has been successfully deleted!']);
    }

    public function showApplicants(){
        $applicants = DB::table("users")
                        ->select(DB::raw('(SELECT designation FROM designation WHERE des_id = users.position_applied_for1) as pos1, (SELECT designation FROM designation WHERE des_id = users.position_applied_for2) as pos2'), 'users.*')
                        ->where('users.user_type', '=', 'Applicant')
                        ->orderBy('id', 'desc')
                        ->get(); 

        $designation_list = DB::table('designation')->get();

        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        $designation = $detail->designation;
        $department = $detail->department;

        return view('client.tab_applicants', ["applicants" => $applicants, "department" => $department, "designation" => $designation, 'designation_list' => $designation_list]);
    }

    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
    }

    public function showApplicantProfile($id){
        $department_list = DB::table('departments')->get(); 
        $designation_list = DB::table('designation')->get(); 
        $shifts = DB::table('shift_groups')->get(); 
        $branch = DB::table('branch')->get();

        $question_answer = DB::table('background_investigation')
            ->select('background_investigation.examinee_id', 'background_investigation.question_id','background_investigation.answer','background_question.question','background_investigation.remarks')
            ->join('background_question', 'background_question.question_id', '=', 'background_investigation.question_id')
            ->where('background_investigation.examinee_id', '=', $id)->get();
        $remarks=DB::table('background_investigation')
                ->where('background_investigation.examinee_id', '=', $id)->first();
        if ($u = BackgroundCheck::where('examinee_id', '=', $id)->first()){
            $message='exists';
        } else {
          $message='nope';
        }

        $applicant = Applicant::select(DB::raw('(SELECT designation FROM designation WHERE des_id = users.position_applied_for1) as pos1, (SELECT designation FROM designation WHERE des_id = users.position_applied_for2) as pos2'), 'users.*')->where('id', $id)->first();

        $exams = DB::table('examinee')->join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
                        ->where('user_id', $id)->orderBy('examinee.examinee_id', 'desc')->get();

        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        return view('client.modules.human_resource.applicants.profile', compact('designation_list', 'department_list', 'shifts', 'branch', 'exams', 'designation', 'department', 'applicant','message','question_answer','remarks'));
    }

    public function updateApplicantStatus(Request $request, $id){
        $applicant = Applicant::find($id);
        $applicant->applicant_status = $request->applicant_status;
        $applicant->last_modified_by = Auth::user()->employee_name;
        $applicant->save();

        return redirect()->back()->with(['message' => 'Applicant updated.']); 
    }

    public function showApplicantList(){
        $applicants = DB::table("users")
                        ->select(DB::raw('(SELECT designation FROM designation WHERE des_id = users.position_applied_for1) as pos1, (SELECT designation FROM designation WHERE des_id = users.position_applied_for2) as pos2'), 'users.*')
                        ->where('users.user_type', '=', 'Applicant')
                        ->orderBy('id', 'desc')
                        ->get();

        $designation_list = DB::table('designation')->get();

        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        return view('client.modules.human_resource.applicants.index', compact('applicants', 'department', 'designation', 'designation_list'));
    }

    public function applicantCreate(Request $request){
        $applicant = new User;
        $applicant->employee_name = $request->employee_name;
        $applicant->birth_date = $request->birthdate;
        $applicant->address = $request->address;
        $applicant->contact_no = $request->contact_no;
        $applicant->sss_no = $request->sss_no;
        $applicant->gender = $request->gender;
        $applicant->tin_no = $request->tin_no;
        $applicant->civil_status = $request->civil_status;
        $applicant->job_source = $request->job_source;
        $applicant->nick_name = $request->nickname;
        $applicant->user_type = 'Applicant';
        $applicant->source = 'Applicant';
        $applicant->applicant_status = 'Submitted';
        $applicant->position_applied_for1 = $request->position_applied_for1;
        $applicant->position_applied_for2 = $request->position_applied_for2;
        $applicant->save();

        if ($request->ajax()) {
            return response()->json(['message' => 'Applicant <b>' . $applicant->employee_name . '</b> has been successfully added!', 'id' => $applicant->id]);
        }

        return redirect('/module/hr/applicants')->with(["message" => "Applicant <b>" . $applicant->employee_name . "</b> has been successfully added!"]);
    }

    public function applicantUpdate(Request $request, $id){
        $applicant = User::find($id);
        $applicant->employee_name = $request->employee_name;
        $applicant->birth_date = $request->birthdate;
        $applicant->address = $request->address;
        $applicant->contact_no = $request->contact_no;
        $applicant->sss_no = $request->sss_no;
        $applicant->tin_no = $request->tin_no;
        $applicant->gender= $request->gender;
        $applicant->civil_status = $request->civil_status;
        $applicant->job_source = $request->job_source;
        $applicant->nick_name = $request->nickname;
        $applicant->position_applied_for1 = $request->position_applied_for1;
        $applicant->position_applied_for2 = $request->position_applied_for2;
        $applicant->last_modified_by = Auth::user()->employee_name;
        $applicant->save();

        if ($request->ajax()) {
            return response()->json(['message' => 'Applicant <b>' . $applicant->employee_name . '</b> has been successfully updated!', 'id' => $applicant->id]);
        }

        return redirect('/module/hr/applicants')->with(["message" => "Applicant <b>" . $applicant->employee_name . "</b> has been successfully updated!"]);
    }

    public function applicantDelete(Request $request, $id){
        $applicant = User::find($id);
        $applicant->delete();
        
        return redirect('/module/hr/applicants')->with(['message' => 'Applicant <b>' . $applicant->employee_name . '</b>  has been successfully deleted!']);
    }

    public function submitWizard(Request $request){
        $dataset = [];
        foreach ($request->exam_id as $i => $row) {
            $dataset[] = [
                'user_id' => $request->applicant_id,
                'exam_id' => $row,
                'exam_code' => date('Y',strtotime($request->exam_date[$i])) . '-' . rand(10000,99999),
                'date_of_exam' => $request->exam_date[$i],
                'duration' => 0,
                'validity_date' => $request->validity_date[$i],
            ];
        }

        Examinee::insert($dataset);
        // DB::table('examinee')->insert($dataset);

        return response()->json(['message' => 'Applicant Exams has been set!']);
    }

    public function getApplicantExamDetails($applicant_id){
        $applicant = DB::table('users')->where('source', 'Applicant')->where('id', $applicant_id)
                ->select(DB::raw('(SELECT designation FROM designation WHERE des_id = users.position_applied_for1) as pos1, (SELECT designation FROM designation WHERE des_id = users.position_applied_for2) as pos2'), 'employee_name')
                ->first();

        $exams = DB::table('examinee')
                ->join('exams', 'exams.exam_id', 'examinee.exam_id')
                ->select('exams.exam_title', 'examinee.exam_code', 'examinee.validity_date', 'examinee.date_of_exam')
                ->where('user_id', $applicant_id)->get();

        return response()->json(['applicant' => $applicant, 'exams' => $exams]);
    }
}