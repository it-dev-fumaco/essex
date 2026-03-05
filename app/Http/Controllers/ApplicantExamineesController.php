<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examinee;
use App\Models\Exam;
use App\Models\User;
use DB;
use App\Models\Department;
use App\Models\ExamGroup;
use App\Models\Question;
use App\Models\ExamType;

class ApplicantExamineesController extends Controller
{
    public function index(){
        $users = User::where('user_type', 'Applicant')->get();
        $exams = Exam::join('exam_group', 'exams.exam_group_id', '=', 'exam_group.exam_group_id')
                    ->where('exam_group_description', 'Applicant Exam')
                    ->select('exams.*', 'exam_group.exam_group_description')
                    ->get();

        $appexaminees = Examinee::join('users', 'examinee.user_id', '=', 'users.id')
                                ->join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
                                ->where('user_type', 'Applicant')
                                ->select('examinee.*', 'exams.exam_title', 'users.employee_name')
                                ->orderBy('date_of_exam','desc')->get();
        $data = [
            'appexaminees' => $appexaminees,
            'exams' => $exams,
            'users' => $users
            ];

        return view('exam.applicant_examinee_index')->with($data);
    }

    public function save(Request $request){
        list($duration, $exam_id) = explode(',', $request->exam_id);
        $examinee = new Examinee;
        $examinee->exam_code = date('Y',strtotime($request->date_of_exam)) . '-' . rand(10000);
        $examinee->user_id = $request->user_id;
        $examinee->exam_id = $exam_id;
        $examinee->date_of_exam = $request->date_of_exam;
        $examinee->duration = $duration;
        $examinee->validity_date = $request->validity_date;
        $examinee->save();

        return redirect()->route('admin.applicant_examinees_index')->with(["message" => "Succesfully Added New Applicant Examinee"]);
    }

    public function update(Request $request){
        list($duration, $exam_id) = explode(',', $request->exam_id);
        $examinee = Examinee::find($request->examinee_id);
        $examinee->user_id = $request->user_id;
        $examinee->exam_id = $exam_id;
        $examinee->date_of_exam = $request->date_of_exam;
        $examinee->duration = $duration;
        $examinee->validity_date = $request->validity_date;
        $examinee->save();

        return redirect()->route('admin.applicant_examinees_index')->with(["message" => "Succesfully Updated Applicant Examinee"]);
    }

    public function delete(Request $request){
        $examinee = Examinee::find($request->examinee_id);

        DB::table('examinee')->where('examinee_id', '=', $request->examinee_id)->delete();
        
        return redirect()->route('admin.applicant_examinees_index')->with(["message" => "Succesfully Deleted Examinee"]);
    }

}