<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Carbon\Carbon;
use App\User;
use DB;
use App\Examinee;
use App\Question;
use App\Exam;
use App\ExamType;
use App\ExaminationResult;
use App\ExamineeAnswer;
use Auth;
class ClientExamsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

        public function validateExamCode(Request $request){
        $examinee = Examinee::where('exam_code',$request->excode)
            ->join('exams','examinee.exam_id','exams.exam_id')
            ->join('users','examinee.user_id','users.id')
            ->select('users.user_type', 'examinee.status', 'examinee.examinee_id', 'examinee.exam_id')
            ->first();

        if (!$examinee) {
            return response()->json(['message' => 'Invalid Exam Code1.', 'status' => 'danger1']);
        }

        $is_applicant = ($examinee->user_type == 'Employee') ? true : false;
        if (!$is_applicant) {
            return response()->json(['message' => 'Invalid Exam Code2.', 'status' => 'danger2']);
        }

        $took_exam = ($examinee->status == 2) ? true : false;
        if ($took_exam) {
            return response()->json(['message' => 'Employee has already took this exam.', 'status' => 'danger']);
        }

        $questions_saved = $this->saveQuest($examinee->examinee_id, $examinee->exam_id);
        if ($questions_saved == 1) {
            return response()->json(['message' => 'Welcome to Essex Online Exam', 'status' => 'success', 'examinee_id' => $examinee->examinee_id]);
        }
    }

    public function saveQuest($examinee_id, $exam_id){
        try {
            $exists = ExamineeAnswer::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->first();
            if ($exists === null) {
                $exam_types = [4, 5, 6, 7, 12, 13, 14, 15, 16];
                $e_types = ['Multiple Choice', 'Essay', 'Numerical Exam', 'True or False', 'Identification', 'Abstract', 'Dexterity & Accuracy 1', 'Dexterity & Accuracy 2', 'Dexterity & Accuracy 3'];
                $exam_type_list = [];
                foreach ($exam_types as $i => $type) {
                    $questions = Question::where('questions.exam_id','=', $exam_id)
                                ->where('questions.exam_type_id', $type)->inRandomOrder()->get();

                    $exam_type_details = ExamType::where('exam_type_id', $type)->first();

                    $exam_type_list[] = [
                        'exam_type_id' => $type,
                        'exam_type' => $e_types[$i],
                        'instructions' => $exam_type_details->instruction,
                        'questions' => $questions,
                    ];
                }

                $active_q = [];
                foreach ($exam_type_list as $row) {
                    if (count($row['questions']) > 0) {
                        foreach ($row['questions'] as $i => $q) {
                            $i = $i + 1;
                            $active_q[] = [
                                'examinee_id' => $examinee_id,
                                'exam_id' => $exam_id,
                                'exam_type_id' => $row['exam_type_id'],
                                'question_id' => $q->question_id,
                                'q_no' => $i,
                                'isCorrect' => "False",
                            ];
                        }
                    }
                }

                ExamineeAnswer::insert($active_q);
            }
        
            return 1;
            
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage(), "success" => 0]);
        }
    }

    public function checkOngoingStatus($id){
        $checker = DB::table('examinee')->where('examinee_id', $id)->where('status', 'On Going')->exists();

        return response()->json(["success" => true, "status" => $checker ? 1 : 0]);
    }

    public function takeExam($examineeid){
        
        $examinee = Examinee::where('examinee.examinee_id',$examineeid)
                        ->join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
                        ->join('users', 'examinee.user_id', '=', 'users.id')
                        ->select('examinee.*', 'exams.exam_title', 'users.employee_name', 'users.user_type', 'exams.duration_in_minutes')
                        ->first();

        $exam_types = [4, 5, 6, 7, 12, 13, 14, 15, 16];
        $e_types = ['Multiple Choice', 'Essay', 'Numerical Exam', 'True or False', 'Identification', 'Abstract', 'Dexterity & Accuracy 1', 'Dexterity & Accuracy 2', 'Dexterity & Accuracy 3'];
        $exam_type_list = [];
        foreach ($exam_types as $i => $type) {
            $questions = ExamineeAnswer::
                join('questions', 'questions.question_id', 'examinee_answers.question_id')
                ->where('examinee_answers.exam_id', $examinee->exam_id)
                ->where('examinee_answers.examinee_id', $examineeid)
                ->where('examinee_answers.exam_type_id', $type)
                ->select('questions.*', 'examinee_answers.examinee_answer_id', 'examinee_answers.examinee_answer')
                ->orderBy('q_no', 'asc')->get();

            $exam_type_details = ExamType::where('exam_type_id', $type)->first();

            $exam_type_list[] = [
                'exam_type_id' => $type,
                'exam_type' => $e_types[$i],
                'instructions' => $exam_type_details->instruction,
                'questions' => $questions,
            ];
        }

        $active_exam_types  = [];
        foreach ($exam_type_list as $row) {
            if (count($row['questions']) > 0) {
                $active_exam_types [] = [
                    'title' => $row['exam_type'],
                    'type_id' => $row['exam_type_id'],
                    'instruction' => $row['instructions'],
                    'questions' => $row['questions'],
                ];
            }
        }
        $lastElement = last($active_exam_types);
        // dd($lastElement);
        return view('online_exam.employee.index', compact('active_exam_types', 'examinee','lastElement'));
    }

    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
    }

    
    public function examSuccess($examinee_id){
        $examinee_stat = Examinee::find($examinee_id);
        $examinee_stat->status = 'Completed';
        $examinee_stat->save();

        $examinee = Examinee::where('examinee.examinee_id',$examinee_id)
                        ->join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
                        ->join('users', 'examinee.user_id', '=', 'users.id')
                        ->select('examinee.*', 'exams.exam_title', 'users.employee_name', 'users.user_type', 'exams.duration_in_minutes')
                        ->first();
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        list($min,$sec) = explode(':',$examinee->time_spent);
        $min = (int)$min;
        $sec = (int)$sec;
        $time_spent = $min . ' minute/s and ' . $sec . ' second/s';

        return view('online_exam.employee.exam_success', compact('examinee','department','designation','time_spent'));
    }
        public function updateAnswer(Request $request){
        try {
            $examinee = Examinee::find($request->examinee_id);
            $examinee->time_spent = $request->spent_time;
            $examinee->remaining_time = $request->remaining_time;
            $examinee->save();
            
            $is_correct = $this->checkAnswer($request->question_id, $request->examinee_answer);

            $examinee_answer = isset($request->examinee_answer) ? $request->examinee_answer : null;
            $answer = ExamineeAnswer::find($request->examinee_answer_id);
            $answer->examinee_answer = $examinee_answer;
            $answer->isCorrect = $is_correct;
            $answer->save();
            
            return response()->json($answer->examinee_answer_id);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function checkAnswer($question_id, $examinee_answer){
        $question = Question::find($question_id);
        $correct_answer = strtolower($question->correct_answer);
        $examinee_answer = strtolower($examinee_answer);
        $is_correct = "Pending";
        if($question->exam_type_id != 5 && $question->exam_type_id != 12 && $question->exam_type_id != 6){
            if (in_array($question->exam_type_id, [14, 15, 16])) {
                $examinee_answer = str_replace(' ', '-', $examinee_answer);
                $examinee_answer = str_replace('-', '', $examinee_answer);
                $examinee_answer = preg_replace('/[^A-Za-z0-9\-]/', '', $examinee_answer);
                $examinee_answer = preg_replace('/-+/', '-', $examinee_answer);

                $is_correct = ($correct_answer == $examinee_answer) ? "True" : "False";
            }else{
                $is_correct = ($correct_answer == $examinee_answer) ? "True" : "False";
            }
        }

        return $is_correct;
    }

    public function updateExamineeStatus(Request $request){
        $examinee = Examinee::find($request->examinee_id);
        $examinee->status = $request->status;
        $examinee->start_time = $request->start_time;
        $examinee->date_taken = $request->date_taken;
        $examinee->save();

        return response()->json(['message' => 'success']);
    }
    
    function exam_name($id){
        $exam_name = DB::table('exam_type')->where('exam_type_id', $id)
            ->select('exam_type')->orderBy('exam_type_id', 'desc')->first();

        return $exam_name->exam_type;
    }

    public function preview_answers(Request $request){
        $examinee = Examinee::where('examinee.examinee_id',$request->examineeId)
            ->join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
            ->join('users', 'examinee.user_id', '=', 'users.id')
            ->select('examinee.*', 'exams.exam_title', 'users.employee_name', 'users.user_type', 'exams.duration_in_minutes')->first();

        $question_id = DB::table('examinee_answers')
            ->join('exam_type', 'exam_type.exam_type_id','=','examinee_answers.exam_type_id')
            ->where('examinee_answers.examinee_id', $request->examineeId)
            ->where('examinee_answers.exam_id',$request->examId)
            ->select('examinee_answers.exam_type_id')
            ->groupBy('examinee_answers.exam_type_id')
            ->orderBy('q_no', 'desc')->get();

        $answer=[];
        foreach ($question_id as $row ) {
            $examinee_ans = ExamineeAnswer::join('questions','examinee_answers.question_id','questions.question_id')
                ->where('examinee_answers.examinee_id', $request->examineeId)
                ->where('examinee_answers.exam_id',$request->examId)
                ->where('examinee_answers.exam_type_id',$row->exam_type_id)
                ->select('examinee_answers.*','questions.questions')
                ->orderBy('q_no', 'asc')->get();
    
            $answer[] = [
                'question_id' => $row->exam_type_id,
                'exam_name' => $this->exam_name($row->exam_type_id),
                'nodess' => $examinee_ans,                
            ];
        }

        return view('online_exam.applicant.preview-answer', compact('answer','examinee'));
    }

    public function save_examresult(Request $request, $examineeId){
        $date_taken= date('Y-m-d');
        $time_in_24_hour_format  = date("H:i:s", strtotime($request->start_time));
        $end_time  = date("H:i:s", strtotime($request->end_time));
        $examinee = Examinee::find($examineeId);
        $examinee->status = 'Completed';
        $examinee->start_time = $time_in_24_hour_format;
        $examinee->date_taken = $date_taken;
        $examinee->time_spent = $request->time_spent;
        $examinee->remaining_time = $request->time_remaining;
        $examinee->end_time = $end_time;
        $examinee->save();

        $total_items= DB::table('examinee_answers')->where('examinee_id', $examineeId)
            ->where('exam_id', $request->examId)->count();

        $total_score= DB::table('examinee_answers')->where('examinee_id', $examineeId)
            ->where('exam_id', $request->examId)->where('isCorrect', 'True')->count();

        $record = ExaminationResult::where('examinee_id', $examineeId)
            ->where('exam_id',$request->examId)->first();

        if(!empty($record)){
            $examres = ExaminationResult::where('examinee_id',$examineeId)
                ->where('exam_id',$request->examId)->first();
            $examres->exam_id = $request->examId;
            $examres->examinee_id = $examineeId;
            $examres->exam_items = $total_items;
            $examres->examinee_score = $total_score;
            $examres->save();
        }else{
            $examres = new ExaminationResult;
            $examres->exam_id = $request->examId;
            $examres->examinee_id = $examineeId;
            $examres->exam_items = $total_items;
            $examres->examinee_score = $total_score;
            $examres->save();
        }
            
        return redirect()->route('applicant.exam_success',['examinee_id' => $examineeId]);
    }
    public function update_no_answer(Request $request, $examineeId){
        $questions = ExamineeAnswer::
                join('questions', 'questions.question_id', 'examinee_answers.question_id')
                ->where('examinee_answers.exam_id', $request->exam_id)
                ->where('examinee_answers.examinee_id', $examineeId)
                ->where('examinee_answers.q_no', $request->question_no)
                ->where('examinee_answers.exam_type_id', $request->exam_type)
                ->select('questions.*', 'examinee_answers.examinee_answer_id', 'examinee_answers.examinee_answer','examinee_answers.q_no')
                ->first();
        $exam_type_details = ExamType::where('exam_type_id', $request->exam_type)->first();
        $examinee_id =$examineeId;

        // dd($questions);
        return view('online_exam.applicant.update_no_answer_table', compact('questions','exam_type_details','examinee_id'));
                // return $questions;
    }
}