<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Exam;
use App\Models\Department;
use App\Models\ExamGroup;
use App\Models\Question;
use App\Models\ExamType;
use App\Models\Examinee;
use Input;
use Route;
class PromotionalEvaluationsController extends Controller
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
                    ->join('examinee','exams.exam_id','=', 'examinee.exam_id')
                    ->where('exam_group_description', 'Promotional Exam')
                    ->select('exams.*','departments.department','exam_group.exam_group_description')
                    ->get();
        $departments = Department::get();
        $examgroups = ExamGroup::where('exam_group_description', 'Promotional Exam')->first();

        $data = [
        	'promexams' => $promexams,
        	'departments' => $departments,
        	'examgroups' => $examgroups
        ];

        // dd($data);

        return view('exam.promotional_evaluation_index')->with($data);
    }

}