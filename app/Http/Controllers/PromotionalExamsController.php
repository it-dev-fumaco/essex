<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Exam;
use App\Models\Department;
use App\Models\ExamGroup;
use App\Models\Question;
use App\Models\ExamType;
use Input;
use Route;
class PromotionalExamsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $promexams = Exam::join('exam_group','exams.exam_group_id','=','exam_group.exam_group_id')
                    ->join('departments','exams.department_id','=', 'departments.department_id')
                    ->where('exam_group_description', 'Promotional Exam')
                    ->select('exams.*','departments.department','exam_group.exam_group_description')
                    ->get();
        $departments = Department::get();
        $examgroup = ExamGroup::where('exam_group_description', 'Promotional Exam')->first();
        $examgroups = ExamGroup::get();

        $data = [
        	'promexams' => $promexams,
        	'departments' => $departments,
        	'examgroup' => $examgroup,
            'examgroups' => $examgroups
        ];

        // dd($data);

        return view('exam.promotional_exam_index')->with($data);
    }

    public function save(Request $request){
    	// dd($request->all());
    	$promexam = new Exam;
    	$promexam->exam_group_id = $request->exam_group_id;
    	$promexam->department_id = $request->department_id;
    	$promexam->exam_title = $request->exam_title;
    	$promexam->duration_in_minutes = $request->duration_in_minutes;
    	$promexam->status = $request->status;
    	$promexam->passing_mark = $request->passing_mark;
    	$promexam->remarks = $request->remarks;
    	$promexam->save();
    	return redirect()->route('admin.promotional_exams_index')->with(['message' => 'Successfully updated Promotional Exam']);
    }


    public function update(Request $request){
        // dd($request->all());
        $promexam = Exam::find($request->exam_id);
        $promexam->exam_group_id = $request->exam_group_id;
        $promexam->department_id = $request->department_id;
        $promexam->exam_title = $request->exam_title;
        $promexam->duration_in_minutes = $request->duration_in_minutes;
        $promexam->status = $request->status;
        $promexam->passing_mark = $request->passing_mark;
        $promexam->remarks = $request->remarks;
        $promexam->save();
        return redirect()->route('admin.promotional_exams_index')->with(['message' => 'Successfully updated Promotional Exam']);
    }


    public function delete(Request $request){
        $exam = Exam::find($request->exam_id);
        DB::table('exams')->where('exam_id', '=', $request->exam_id)->delete();
        return redirect()->route('admin.promotional_exams_index')->with(["message" => "Succesfully Deleted New Exam '".$exam->exam_title."'"]);
    }

}