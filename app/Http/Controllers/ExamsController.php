<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Exam;
use App\Department;
use App\ExamGroup;
use App\Question;
use App\ExamType;

use Input;
use Route;
use Exception;

class ExamsController extends Controller
{
    public function index(){
        $exams = Exam::join('exam_group','exams.exam_group_id','=','exam_group.exam_group_id')
                    ->join('departments','exams.department_id','=', 'departments.department_id')
                    ->select('exams.*','departments.department','exam_group.exam_group_description')
                    ->orderBy('exams.exam_id','desc')
                    ->get();
        $departments = Department::get();
        $examgroups = ExamGroup::get();
        return view('exam.exam_index')->with(['exams' => $exams,'departments' => $departments,'examgroups' => $examgroups]);
    }
    public function save(Request $request){
        try{
            $exam = new Exam;
            $exam->exam_group_id = $request->exam_group_id;
            $exam->department_id = $request->department_id;
            $exam->exam_title = $request->exam_title;
            $exam->duration_in_minutes = $request->duration_in_minutes;
            $exam->passing_mark = $request->passing_mark;
            $exam->status = $request->status;
            $exam->remarks = $request->remarks;
            $exam->save();

            return redirect()->route('admin.exams_index')->with(["message" => "Succesfully Added New Exam '".$request->exam_title."'"]);
        }catch(Exception $e){
            return redirect()->route('admin.exams_index')->with(["message" => $e->getMessage()]);
        }
    }
    public function update(Request $request){
        try{
            $exam = Exam::find($request->exam_id);
            $exam->exam_group_id = $request->exam_group_id;
            $exam->department_id = $request->department_id;
            $exam->exam_title = $request->exam_title;
            $exam->duration_in_minutes = $request->duration_in_minutes;
            $exam->passing_mark = $request->passing_mark;
            $exam->status = $request->status;
            $exam->remarks = $request->remarks;
            $exam->last_modified_by = Auth::user()->employee_name;
            $exam->save();

            return redirect()->route('admin.exams_index')->with(["message" => "Succesfully Updated New Exam '".$request->exam_title."'"]);
        }catch(Exception $e){
            return redirect()->route('admin.exams_index')->with(["message" => $e->getMessage()]);
        }
    }
    public function delete(Request $request){
        try{
            $exam = Exam::find($request->exam_id);
            DB::table('exams')->where('exam_id', '=', $request->exam_id)->delete();
            return redirect()->route('admin.exams_index')->with(["message" => "Succesfully Deleted New Exam '".$exam->exam_title."'"]);
        }catch(Exception $e){
            return redirect()->route('admin.exams_index')->with(["message" => $e->getMessage()]);
        }
    }
    public function view($exam_id){
        try{
            $examtypes = ExamType::get();
            $exams = Exam::get();
            $exam = Exam::where('exam_id',$exam_id)
                        ->join('exam_group','exams.exam_group_id','=','exam_group.exam_group_id')
                        ->join('departments','exams.department_id','=', 'departments.department_id')
                        ->select('exams.*','departments.department','exam_group.exam_group_description')
                        ->first();
            $examquestions = Question::where('questions.exam_id','=',$exam_id)
                                    ->join('exam_type', 'questions.exam_type_id', '=', 'exam_type.exam_type_id')
                                    ->join('exams', 'questions.exam_id', '=', 'exams.exam_id')
                                    ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                                    ->get();
                                    
            $multiplechoices = Question::where('questions.exam_id','=',$exam_id)
                                    ->join('exam_type', 'questions.exam_type_id', '=', 'exam_type.exam_type_id')
                                    ->join('exams', 'questions.exam_id', '=', 'exams.exam_id')
                                    ->where('exam_type','Multiple Choice')
                                    ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                                    ->get();

            $truesfalses = Question::where('questions.exam_id','=',$exam_id)
                                    ->join('exam_type', 'questions.exam_type_id', '=', 'exam_type.exam_type_id')
                                    ->join('exams', 'questions.exam_id', '=', 'exams.exam_id')
                                    ->where('exam_type','True or False')
                                    ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                                    ->get();

            $essays = Question::where('questions.exam_id','=',$exam_id)
                                    ->join('exam_type', 'questions.exam_type_id', '=', 'exam_type.exam_type_id')
                                    ->join('exams', 'questions.exam_id', '=', 'exams.exam_id')
                                    ->where('exam_type','Essay')
                                    ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                                    ->get();

            $numericals = Question::where('questions.exam_id','=',$exam_id)
                                    ->join('exam_type', 'questions.exam_type_id', '=', 'exam_type.exam_type_id')
                                    ->join('exams', 'questions.exam_id', '=', 'exams.exam_id')
                                    ->where('exam_type','Numerical Exam')
                                    ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                                    ->get();

            $data = [
                'exams' => $exams,
                'examtypes' => $examtypes,
                'exam' => $exam,
                'examquestions' => $examquestions,
                'multiplechoices' => $multiplechoices,
            'truesfalses' => $truesfalses,
                'essays' => $essays,
                'numericals' => $numericals
            ];

            // dd($data);
            return view('exam.exam_view_data')->with($data);
        }catch(Exception $e){
            return redirect()->route('admin.exams_index')->with(["message" => $e->getMessage()]);
        }

    }
    //generic func for all exam types
    public function deleteExamQuestion(Request $request){
        $question = Question::find($request->question_id);
        DB::table('questions')->where('question_id', '=', $request->question_id)->delete();
        return redirect()->route('admin.exam_view',$request->exam_id)->with(["message" => "Succesfully Deleted Question '".$question->questions."'"]);
    }

    //multiple choice store update
    public function saveMultipleChoice(Request $request){

        // dd($request->all());
        $question = new Question;
        $question->exam_id = $request->exam_id;
        $question->exam_type_id = $request->exam_type_id;
        $question->questions = $request->questions;
        $question->option1 = $request->option1;
        $question->option2 = $request->option2;
        $question->option3 = $request->option3;
        $question->option4 = $request->option4;
        $question->correct_answer = $request->correct_answer;
        $question->save();
        
        return redirect()->route('admin.exam_view',$request->exam_id)->with(["message" => "Succesfully Added New Question '".$request->questions."'"]);
    }

    public function updateMultipleChoice(Request $request){
        $question = Question::find($request->question_id);
        $question->exam_id = $request->exam_id;
        $question->exam_type_id = $request->exam_type_id;
        $question->questions = $request->questions;
        $question->option1 = $request->option1;
        $question->option2 = $request->option2;
        $question->option3 = $request->option3;
        $question->option4 = $request->option4;
        $question->correct_answer = $request->correct_answer;
        $question->last_modified_by = Auth::user()->employee_name;
        $question->save();

        return redirect()->route('admin.exam_view',$request->exam_id)->with(["message" => "Succesfully Updated Question '".$request->questions."'"]);
    }

    //true false store update
    public function saveTrueFalse(Request $request){
        $question = new Question;
        $question->exam_id = $request->exam_id;
        $question->exam_type_id = $request->exam_type_id;
        $question->questions = $request->questions;
        $question->option1 = 'True';
        $question->option2 = 'False';
        $question->correct_answer = $request->correct_answer;
        $question->save();
        
        return redirect()->route('admin.exam_view',$request->exam_id)->with(["message" => "Succesfully Added New Question '".$request->questions."'"]);
    }

    public function updateTrueFalse(Request $request){
        $question = Question::find($request->question_id);
        $question->exam_id = $request->exam_id;
        $question->exam_type_id = $request->exam_type_id;
        $question->questions = $request->questions;
        $question->correct_answer = $request->correct_answer;
        $question->last_modified_by = Auth::user()->employee_name;
        $question->save();

        return redirect()->route('admin.exam_view',$request->exam_id)->with(["message" => "Succesfully Updated Question '".$request->questions."'"]);
    }

    //essay store update
    public function saveEssay(Request $request){
        $question = new Question;
        $question->exam_id = $request->exam_id;
        $question->exam_type_id = $request->exam_type_id;
        $question->questions = $request->questions;
        // $question->correct_answer = $request->correct_answer;
        $question->save();
        
        return redirect()->route('admin.exam_view',$request->exam_id)->with(["message" => "Succesfully Added New Question '".$request->questions."'"]);
    }

    public function updateEssay(Request $request){
        $question = Question::find($request->question_id);
        $question->exam_id = $request->exam_id;
        $question->exam_type_id = $request->exam_type_id;
        $question->questions = $request->questions;
        $question->last_modified_by = Auth::user()->employee_name;
        $question->save();

        return redirect()->route('admin.exam_view',$request->exam_id)->with(["message" => "Succesfully Updated Question '".$request->questions."'"]);
    }

    //identification store update
    public function saveIdentif(Request $request){
        $question = new Question;
        $question->exam_id = $request->exam_id;
        $question->exam_type_id = $request->exam_type_id;
        $question->questions = $request->questions;
        $question->correct_answer = $request->correct_answer;
        $question->save();
        
        return redirect()->route('admin.exam_view',$request->exam_id)->with(["message" => "Succesfully Added New Question '".$request->questions."'"]);
    }

    public function updateIdentif(Request $request){
        $question = Question::find($request->question_id);
        $question->exam_id = $request->exam_id;
        $question->exam_type_id = $request->exam_type_id;
        $question->questions = $request->questions;
        $question->correct_answer = $request->correct_answer;
        $question->last_modified_by = Auth::user()->employee_name;
        $question->save();

        return redirect()->route('admin.exam_view',$request->exam_id)->with(["message" => "Succesfully Updated Question '".$request->questions."'"]);
    }

    // AJAX
    public function addExam(Request $request){
        $exam = new Exam;
        $exam->exam_group_id = $request->exam_group_id;
        $exam->department_id = $request->department_id;
        $exam->exam_title = $request->exam_title;
        $exam->duration_in_minutes = $request->duration_in_minutes;
        $exam->passing_mark = $request->passing_mark;
        $exam->status = $request->status;
        $exam->remarks = $request->remarks;
        $exam->save();

        return response()->json(["message" => "Succesfully Added New Exam '".$request->exam_title."'", 'exam_title' => $exam->exam_title, 'exam_id' => $exam->exam_id]);
    }

    public function tabAddExam(Request $request){
        try{
            $exam = new Exam;
            $exam->exam_group_id = $request->exam_group_id;
            $exam->department_id = $request->department_id;
            $exam->exam_title = $request->exam_title;
            $exam->duration_in_minutes = $request->duration_in_minutes;
            $exam->passing_mark = $request->passing_mark;
            $exam->status = $request->status;
            $exam->remarks = $request->remarks;
            $exam->save();

            return redirect()->route('client.tabExams')->with(["message" => "Succesfully Added New Exam '".$exam->exam_title."'"]);
        }catch(Exception $e){
            return redirect()->route('admin.exams_index')->with(["message" => $e->getMessage()]);
        }
    }

    public function tabUpdateExam(Request $request){
        try{
            $exam = Exam::find($request->exam_id);
            $exam->exam_group_id = $request->exam_group_id;
            $exam->department_id = $request->department_id;
            $exam->exam_title = $request->exam_title;
            $exam->duration_in_minutes = $request->duration_in_minutes;
            $exam->passing_mark = $request->passing_mark;
            $exam->status = $request->status;
            $exam->remarks = $request->remarks;
            $exam->last_modified_by = Auth::user()->employee_name;
            $exam->save();

            return redirect()->route('client.tabExams')->with(["message" => "Succesfully Updated New Exam '".$exam->exam_title."'"]);
        }catch(Exception $e){
            return redirect()->route('client.tabExams')->with(["message" => $e->getMessage()]);
        }
    }
    public function tabDeleteExam(Request $request){
        try{
            $exam = Exam::find($request->exam_id);
            DB::table('exams')->where('exam_id', '=', $request->exam_id)->delete();
            return redirect()->route('client.tabExams')->with(["message" => "Succesfully Deleted New Exam '".$exam->exam_title."'"]);
        }catch(Exception $e){
            return redirect()->route('client.tabExams')->with(["message" => $e->getMessage()]);
        }
    }

    public function tabviewExamDetails($exam_id){
        $examtypes = ExamType::get();

        $details = DB::table('users')
                ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                ->join('departments', 'users.department_id', '=', 'departments.department_id')
                ->where('user_id', Auth::user()->user_id)
                ->first();

        $department = $details->department;
        $designation = $details->designation;

        $exam = Exam::where('exam_id',$exam_id)
                    ->join('exam_group','exams.exam_group_id','=','exam_group.exam_group_id')
                    ->join('departments','exams.department_id','=', 'departments.department_id', 'left outer')
                    ->select('exams.*','departments.department','exam_group.exam_group_description')
                    ->first();

        $questions = Question::where('questions.exam_id', $exam_id)->get();

        $questions_count = collect($questions)->count();
        $questions = collect($questions)->groupBy('exam_type_id');

        $multipleChoice     = isset($questions[4]) ? $questions[4] : [];
        $essay              = isset($questions[5]) ? $questions[5] : [];
        $numericalExam      = isset($questions[6]) ? $questions[6] : [];
        $trueOrFalse        = isset($questions[7]) ? $questions[7] : [];
        $identification     = isset($questions[12]) ? $questions[12] : [];
        $abstract           = isset($questions[13]) ? $questions[13] : [];
        $dexterity1         = isset($questions[14]) ? $questions[14] : [];
        $dexterity2         = isset($questions[15]) ? $questions[15] : [];
        $dexterity3         = isset($questions[16]) ? $questions[16] : [];

        $frequent_mistakes_data = DB::table('examinee_answers as answer')
            ->join('questions as question', 'question.question_id', 'answer.question_id')
            ->where('answer.exam_id', $exam_id)->where('answer.isCorrect', 'False')->whereNotNull('answer.examinee_answer')
            ->select('question.question_id', 'question.questions', 'question.correct_answer', DB::raw('COUNT(answer.q_no) as wrong_answers_count'))
            ->groupBy('question.question_id', 'question.questions', 'question.correct_answer')
            ->orderByDesc('wrong_answers_count')
            ->get();

        $question_ids = collect($frequent_mistakes_data)->pluck('question_id');

        $examinee_answers = DB::table('examinee_answers as answer')
            ->join('questions as question', 'question.question_id', 'answer.question_id')
            ->where('answer.exam_id', $exam_id)->where('answer.isCorrect', 'False')->whereIn('question.question_id', $question_ids)->whereNotNull('answer.examinee_answer')
            ->select('question.question_id', 'question.questions', 'question.correct_answer', 'answer.examinee_answer', DB::raw('COUNT(answer.examinee_answer) as wrong_answers_count'))
            ->groupBy('question.question_id', 'question.questions', 'question.correct_answer', 'answer.examinee_answer')
            ->orderByDesc('wrong_answers_count')
            ->get();

        $examinee_answers = collect($examinee_answers)->groupBy('question_id');

        return view('client.tab_exam_view_details', compact('examtypes', 'exam', 'questions_count', 'department', 'designation', 'multipleChoice', 'essay', 'numericalExam', 'trueOrFalse', 'identification', 'abstract', 'dexterity1', 'dexterity2', 'dexterity3', 'frequent_mistakes_data', 'examinee_answers'));
    }

    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
    }
    public function showApplicantExams(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $exams = DB::table('exams')
                ->join('exam_group','exams.exam_group_id','=','exam_group.exam_group_id')
                ->join('departments','exams.department_id','=', 'departments.department_id', 'left outer')
                ->where('exam_group.exam_group_description', 'LIKE', "%applicant%")
                ->orWhere('exams.department_id', 0)
                ->select('exams.*','departments.department','exam_group.exam_group_description')
                ->orderBy('exams.exam_id','desc')
                ->get();

        $departments = DB::table('departments')->get();
        $examgroups = DB::table('exam_group')->get();
        $exam_types = DB::table('exam_type')->get();
        
        return view('client.modules.human_resource.applicant_exams.index', compact('designation', 'department', 'examgroups', 'departments', 'exam_types', 'exams'));
    }
    public function showApplicantExamDetails($exam_id){
        // try{
            $examtypes = ExamType::get();

            $designation = $this->sessionDetails('designation');
            $department = $this->sessionDetails('department');

            $exam = Exam::where('exam_id',$exam_id)
                        ->join('exam_group','exams.exam_group_id','=','exam_group.exam_group_id')
                        ->join('departments','exams.department_id','=', 'departments.department_id', 'left outer')
                        ->select('exams.*','departments.department','exam_group.exam_group_description')
                        ->first();

            $questions = Question::where('questions.exam_id', '=', $exam_id)
                        ->join('exam_type', 'questions.exam_type_id', '=', 'exam_type.exam_type_id')
                        ->join('exams', 'questions.exam_id', '=', 'exams.exam_id')
                        ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                        ->get();
                                    
            $multipleChoice = Question::where('questions.exam_id','=',$exam_id)
                        ->where('questions.exam_type_id', 4)
                        // ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                        ->get();
            $essay = Question::where('questions.exam_id','=',$exam_id)
                        ->where('questions.exam_type_id', 5)
                        // ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                        ->get();
            $numericalExam = Question::where('questions.exam_id','=',$exam_id)
                        ->where('questions.exam_type_id', 6)
                        // ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                        ->get();
            $trueOrFalse = Question::where('questions.exam_id','=',$exam_id)
                        ->where('questions.exam_type_id', 7)
                        // ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                        ->get();
            $identification = Question::where('questions.exam_id','=',$exam_id)
                        ->where('questions.exam_type_id', 12)
                        // ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                        ->get();
            $abstract = Question::where('questions.exam_id','=',$exam_id)
                        ->where('questions.exam_type_id', 13)
                        // ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                        ->get();
            $dexterity1 = Question::where('questions.exam_id','=',$exam_id)
                        ->where('questions.exam_type_id', 14)
                        // ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                        ->get();
            $dexterity2 = Question::where('questions.exam_id','=',$exam_id)
                        ->where('questions.exam_type_id', 15)
                        // ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                        ->get();

            $dexterity3 = Question::where('questions.exam_id','=',$exam_id)
                        ->where('questions.exam_type_id', 16)
                        // ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                        ->get();

            $data = [
                'examtypes' => $examtypes,
                'exam' => $exam,
                'questions' => $questions,
                'department' => $department,
                'designation' => $designation,

                'multipleChoice' => $multipleChoice,
                'essay' => $essay,
                'numericalExam' => $numericalExam,
                'trueOrFalse' => $trueOrFalse,
                'identification' => $identification,
                'abstract' => $abstract,
                'dexterity1' => $dexterity1,
                'dexterity2' => $dexterity2,
                'dexterity3' => $dexterity3,
            ];

            // dd($data);

            return view('client.modules.human_resource.applicant_exams.exam_details')->with($data);
        // }catch(Exception $e){
        //     return redirect()->route('client.tab_view_exam_details')->with(["message" => $e->getMessage()]);
        // }
    }
    public function addApplicantExam(Request $request){
        $exam = new Exam;
        $exam->exam_group_id = $request->exam_group_id;
        $exam->department_id = $request->department_id;
        $exam->exam_title = $request->exam_title;
        $exam->duration_in_minutes = $request->duration_in_minutes;
        $exam->passing_mark = $request->passing_mark;
        $exam->status = $request->status;
        $exam->remarks = $request->remarks;
        $exam->save();

        return redirect()->back()->with(["message" => "Succesfully Added New Exam '".$exam->exam_title."'"]);
    }
    public function updateApplicantExam(Request $request){
        $exam = Exam::find($request->exam_id);
        $exam->exam_group_id = $request->exam_group_id;
        $exam->department_id = $request->department_id;
        $exam->exam_title = $request->exam_title;
        $exam->duration_in_minutes = $request->duration_in_minutes;
        $exam->passing_mark = $request->passing_mark;
        $exam->status = $request->status;
        $exam->remarks = $request->remarks;
        $exam->last_modified_by = Auth::user()->employee_name;
        $exam->save();

        return redirect()->back()->with(["message" => "Succesfully Updated New Exam '".$exam->exam_title."'"]);
    }
    public function deleteApplicantExam(Request $request){
        $exam = Exam::find($request->exam_id);
        DB::table('exams')->where('exam_id', '=', $request->exam_id)->delete();
        return redirect()->back()->with(["message" => "Succesfully Deleted New Exam '".$exam->exam_title."'"]);
    }
    public function getExamList(){
        return DB::table('exams')
                ->join('exam_group','exams.exam_group_id','=','exam_group.exam_group_id')
                ->join('departments','exams.department_id','=', 'departments.department_id', 'left outer')
                ->where('exam_group.exam_group_description', 'LIKE', "%applicant%")
                ->orWhere('exams.department_id', 0)
                ->select('exams.*','departments.department','exam_group.exam_group_description')
                ->orderBy('exams.exam_id','desc')
                ->get();
    }

    public function showExamAnalytics(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $total_exams = DB::table('exams')->count();
        $total_exam_groups = DB::table('exam_group')->count();
        $total_examinees = DB::table('examinee')->count();
        $total_questions = DB::table('questions')->count();

        $totals = [
            'total_exams' => $total_exams,
            'total_exam_groups' => $total_exam_groups,
            'total_examinees' => $total_examinees,
            'total_questions' => $total_questions,
        ];

        return view('client.modules.analytics.exam_analytics', compact('designation', 'department', 'totals'));
    }
}