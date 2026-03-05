<?php

namespace App\Http\Controllers;

use App\Models\ExaminationResult;
use App\Models\ExamineeAnswer;
use App\Models\Question;
use Auth;
use DB;
use Illuminate\Http\Request;

class ExaminationReportsController extends Controller
{
    public function index()
    {
        $exam_results = ExaminationResult::join('examinee', 'examination_result.examinee_id', 'examinee.examinee_id')
            ->join('users', 'examinee.user_id', 'users.id')
            ->join('exams', 'examinee.exam_id', 'exams.exam_id')
            ->join('exam_group', 'exams.exam_group_id', 'exam_group.exam_group_id')
            ->select('examination_result.*', 'exam_group.exam_group_description', 'exams.exam_title', 'users.employee_name', 'examinee.date_taken')->get();

        return view('exam.examination_reports_index')->withExamresults($exam_results);
    }

    public function show($examinee_id, $exam_id)
    {
        $exam_res = ExaminationResult::join('examinee', 'examination_result.examinee_id', 'examinee.examinee_id')
            ->join('users', 'examinee.user_id', 'users.id')
            ->join('exams', 'examinee.exam_id', 'exams.exam_id')
            ->join('exam_group', 'exams.exam_group_id', 'exam_group.exam_group_id')
            ->select('examination_result.*', 'exam_group.exam_group_description', 'exams.exam_title', 'users.employee_name', 'examinee.date_taken', 'examinee.start_time', 'examinee.end_time')
            ->where('examination_result.examinee_id', $examinee_id)->where('examination_result.exam_id', $exam_id)->first();

        $examinee_ans = ExamineeAnswer::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->get();

        $questions = Question::where('exam_id', $exam_id)
            ->join('exam_type', 'questions.exam_type_id', 'exam_type.exam_type_id')
            ->select('questions.*', 'exam_type.exam_type')->get();

        $countsByTypeAndCorrect = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)
            ->selectRaw('exam_type_id, isCorrect, count(*) as cnt')
            ->groupBy('exam_type_id', 'isCorrect')
            ->get();

        $correctCount = fn ($typeId) => (int) (optional($countsByTypeAndCorrect->where('exam_type_id', $typeId)->where('isCorrect', 'True')->first())->cnt ?? 0);
        $count_mc = $correctCount(4);
        $count_es = $correctCount(5);
        $count_ne = $correctCount(6);
        $count_tf = $correctCount(7);
        $count_dex = $correctCount(8);
        $count_abs = $correctCount(11);
        $count_id = $correctCount(12);
        $items_mc = (int) $countsByTypeAndCorrect->where('exam_type_id', 4)->sum('cnt');
        $items_es = (int) $countsByTypeAndCorrect->where('exam_type_id', 5)->sum('cnt');
        $items_ne = (int) $countsByTypeAndCorrect->where('exam_type_id', 6)->sum('cnt');
        $items_tf = (int) $countsByTypeAndCorrect->where('exam_type_id', 7)->sum('cnt');
        $items_dex = (int) $countsByTypeAndCorrect->where('exam_type_id', 8)->sum('cnt');
        $items_abs = (int) $countsByTypeAndCorrect->where('exam_type_id', 11)->sum('cnt');
        $items_id = (int) $countsByTypeAndCorrect->where('exam_type_id', 12)->sum('cnt');

        $data = [
            'examres' => $exam_res,
            'count_mc' => $count_mc,
            'count_es' => $count_es,
            'count_ne' => $count_ne,
            'count_tf' => $count_tf,
            'count_dex' => $count_dex,
            'count_abs' => $count_abs,
            'count_id' => $count_id,

            'items_mc' => $items_mc,
            'items_es' => $items_es,
            'items_ne' => $items_ne,
            'items_tf' => $items_tf,
            'items_dex' => $items_dex,
            'items_abs' => $items_abs,
            'items_id' => $items_id,
        ];

        // dd($exam_res->employee_name);

        return view('exam.exam_result_view')->with($data);
    }

    public function viewByExamType($examinee_id, $exam_id, $exam_type_id)
    {
        $exam_res = ExaminationResult::join('examinee', 'examination_result.examinee_id', 'examinee.examinee_id')
            ->join('users', 'examinee.user_id', 'users.id')
            ->join('exams', 'examinee.exam_id', 'exams.exam_id')
            ->join('exam_group', 'exams.exam_group_id', 'exam_group.exam_group_id')
            ->select('examination_result.*', 'exam_group.exam_group_description', 'exams.exam_title', 'users.employee_name', 'examinee.date_taken', 'examinee.start_time', 'examinee.end_time')
            ->where('examination_result.examinee_id', $examinee_id)->where('examination_result.exam_id', $exam_id)->first();
        $examinee_ans = ExamineeAnswer::where('examinee_answers.examinee_id', $examinee_id)->where('examinee_answers.exam_id', $exam_id)->where('examinee_answers.exam_type_id', $exam_type_id)
            ->join('questions', 'examinee_answers.question_id', 'questions.question_id')
            ->select('examinee_answers.*', 'questions.questions', 'questions.correct_answer')->get();
        // $questions = Question::where('exam_type_id',$exam_type_id)->where('exam_id',$exam_id)->get();

        $data = [
            'examres' => $exam_res,
            'examans' => $examinee_ans,
        ];
        // dd($data);

        return view('exam.exam_result_by_type_view')->with($data);
    }

    public function updateScore($examinee_id, $exam_id, $exam_type_id)
    {
        $exam_res = ExaminationResult::join('examinee', 'examination_result.examinee_id', 'examinee.examinee_id')
            ->join('users', 'examinee.user_id', 'users.id')
            ->join('exams', 'examinee.exam_id', 'exams.exam_id')
            ->join('exam_group', 'exams.exam_group_id', 'exam_group.exam_group_id')
            ->select('examination_result.*', 'exam_group.exam_group_description', 'exams.exam_title', 'users.employee_name', 'examinee.date_taken', 'examinee.start_time', 'examinee.end_time')
            ->where('examination_result.examinee_id', $examinee_id)->where('examination_result.exam_id', $exam_id)->first();
        $examinee_ans = ExamineeAnswer::where('examinee_answers.examinee_id', $examinee_id)->where('examinee_answers.exam_id', $exam_id)->where('examinee_answers.exam_type_id', $exam_type_id)
            ->join('questions', 'examinee_answers.question_id', 'questions.question_id')
            ->select('examinee_answers.*', 'questions.questions', 'questions.correct_answer')->get();

        $data = [
            'examres' => $exam_res,
            'examans' => $examinee_ans,
        ];
        // dd($data);

        return view('exam.exam_result_update_score')->with($data);
    }

    public function saveUpdatedScore($examinee_id, $exam_id, Request $request)
    {
        // dd($request->all());

        $examinee_ans = ExamineeAnswer::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->get();

        foreach ($examinee_ans as $ans) {
            $qid = $ans->question_id;
            if ($request->$qid) {
                $ans->isCorrect = $request->$qid;
                $ans->save();
            }
        }

        $examres = ExaminationResult::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->first();
        $examres->examinee_score = ExamineeAnswer::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->where('isCorrect', 'True')->count();
        $examres->save();

        // dd($examinee_ans);

        return redirect()->route('admin.examination_report_show', [$examinee_id, $exam_id]);
    }

    public function showExamResults($examinee_id, $exam_id)
    {

        $details = DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', Auth::user()->user_id)
            ->first();

        $department = $details->department;
        $designation = $details->designation;

        $exam_res = ExaminationResult::join('examinee', 'examination_result.examinee_id', 'examinee.examinee_id')
            ->join('users', 'examinee.user_id', 'users.id')
            ->join('exams', 'examinee.exam_id', 'exams.exam_id')
            ->join('exam_group', 'exams.exam_group_id', 'exam_group.exam_group_id')
            ->select('exams.duration_in_minutes', 'exams.passing_mark', 'examination_result.*', 'exam_group.exam_group_description', 'exams.exam_title', 'users.employee_name', 'examinee.date_taken', 'examinee.start_time', 'examinee.end_time')
            ->where('examination_result.examinee_id', $examinee_id)->where('examination_result.exam_id', $exam_id)->first();

        $count_multiple_choice = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 4)->count();
        $count_essay = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 5)->count();
        $count_numerical_exam = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 6)->count();
        $count_true_or_false = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 7)->count();
        $count_identification = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 12)->count();
        $count_abstract = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 13)->count();
        $count_dexterity1 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 14)->count();
        $count_dexterity2 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 15)->count();
        $count_dexterity3 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 16)->count();

        $items_multiple_choice = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 4)->count();
        $items_essay = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 5)->count();
        $items_numerical_exam = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 6)->count();
        $items_true_or_false = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 7)->count();
        $items_identification = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 12)->count();
        $items_abstract = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 13)->count();
        $items_dexterity1 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 14)->count();
        $items_dexterity2 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 15)->count();
        $items_dexterity3 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 16)->count();

        $totalItems = ExamineeAnswer::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->count();
        $totalScore = ExamineeAnswer::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->where('isCorrect', 'True')->count();

        $average = number_format(100 * ($totalScore / $totalItems), 2);

        $data = [
            'examres' => $exam_res,

            'count_multiple_choice' => $count_multiple_choice,
            'count_essay' => $count_essay,
            'count_numerical_exam' => $count_numerical_exam,
            'count_true_or_false' => $count_true_or_false,
            'count_identification' => $count_identification,
            'count_abstract' => $count_abstract,
            'count_dexterity1' => $count_dexterity1,
            'count_dexterity2' => $count_dexterity2,
            'count_dexterity3' => $count_dexterity3,

            'items_multiple_choice' => $items_multiple_choice,
            'items_essay' => $items_essay,
            'items_numerical_exam' => $items_numerical_exam,
            'items_true_or_false' => $items_true_or_false,
            'items_identification' => $items_identification,
            'items_abstract' => $items_abstract,
            'items_dexterity1' => $items_dexterity1,
            'items_dexterity2' => $items_dexterity2,
            'items_dexterity3' => $items_dexterity3,

            'designation' => $designation,
            'department' => $department,
            'totalItems' => $totalItems,
            'totalScore' => $totalScore,
            'average' => $average,
        ];

        return view('client.tab_examination_result')->with($data);
    }

    public function printExamResults($examinee_id, $exam_id)
    {

        $details = DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', Auth::user()->user_id)
            ->first();

        $department = $details->department;
        $designation = $details->designation;

        $exam_res = ExaminationResult::join('examinee', 'examination_result.examinee_id', 'examinee.examinee_id')
            ->join('users', 'examinee.user_id', 'users.id')
            ->join('exams', 'examinee.exam_id', 'exams.exam_id')
            ->join('exam_group', 'exams.exam_group_id', 'exam_group.exam_group_id')
            ->select('exams.duration_in_minutes', 'exams.passing_mark', 'examination_result.*', 'exam_group.exam_group_description', 'exams.exam_title', 'users.employee_name', 'examinee.date_taken', 'examinee.start_time', 'examinee.end_time')
            ->where('examination_result.examinee_id', $examinee_id)->where('examination_result.exam_id', $exam_id)->first();

        $count_multiple_choice = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 4)->count();
        $count_essay = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 5)->count();
        $count_numerical_exam = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 6)->count();
        $count_true_or_false = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 7)->count();
        $count_identification = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 12)->count();
        $count_abstract = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 13)->count();
        $count_dexterity1 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 14)->count();
        $count_dexterity2 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 15)->count();
        $count_dexterity3 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 16)->count();

        $items_multiple_choice = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 4)->count();
        $items_essay = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 5)->count();
        $items_numerical_exam = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 6)->count();
        $items_true_or_false = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 7)->count();
        $items_identification = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 12)->count();
        $items_abstract = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 13)->count();
        $items_dexterity1 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 14)->count();
        $items_dexterity2 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 15)->count();
        $items_dexterity3 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 16)->count();

        $totalItems = ExamineeAnswer::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->count();
        $totalScore = ExamineeAnswer::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->where('isCorrect', 'True')->count();

        $average = number_format(100 * ($totalScore / $totalItems), 2);

        $data = [
            'examres' => $exam_res,

            'count_multiple_choice' => $count_multiple_choice,
            'count_essay' => $count_essay,
            'count_numerical_exam' => $count_numerical_exam,
            'count_true_or_false' => $count_true_or_false,
            'count_identification' => $count_identification,
            'count_abstract' => $count_abstract,
            'count_dexterity1' => $count_dexterity1,
            'count_dexterity2' => $count_dexterity2,
            'count_dexterity3' => $count_dexterity3,

            'items_multiple_choice' => $items_multiple_choice,
            'items_essay' => $items_essay,
            'items_numerical_exam' => $items_numerical_exam,
            'items_true_or_false' => $items_true_or_false,
            'items_identification' => $items_identification,
            'items_abstract' => $items_abstract,
            'items_dexterity1' => $items_dexterity1,
            'items_dexterity2' => $items_dexterity2,
            'items_dexterity3' => $items_dexterity3,

            'designation' => $designation,
            'department' => $department,
            'totalItems' => $totalItems,
            'totalScore' => $totalScore,
            'average' => $average,
        ];

        return view('client.print_exam_result')->with($data);
    }

    public function showExamineeAnswers($examinee_id, $exam_id, $exam_type_id)
    {

        $details = DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', Auth::user()->user_id)
            ->first();

        $department = $details->department;
        $designation = $details->designation;

        $exam_res = ExaminationResult::join('examinee', 'examination_result.examinee_id', 'examinee.examinee_id')
            ->join('users', 'examinee.user_id', 'users.id')
            ->join('exams', 'examinee.exam_id', 'exams.exam_id')
            ->join('exam_group', 'exams.exam_group_id', 'exam_group.exam_group_id')
            ->select('examination_result.*', 'exam_group.exam_group_description', 'exams.exam_title', 'users.employee_name', 'examinee.date_taken', 'examinee.start_time', 'examinee.end_time')
            ->where('examination_result.examinee_id', $examinee_id)
            ->where('examination_result.exam_id', $exam_id)
            ->first();

        $examinee_ans = ExamineeAnswer::join('questions', 'examinee_answers.question_id', 'questions.question_id')
            ->where('examinee_answers.examinee_id', $examinee_id)
            ->where('examinee_answers.exam_id', $exam_id)
            ->where('examinee_answers.exam_type_id', $exam_type_id)
            ->select('examinee_answers.*', 'questions.questions', 'questions.correct_answer', 'questions.question_img')
            ->orderBy('examinee_answers.q_no', 'asc')
            ->get();

        $exam_type = DB::table('exam_type')->where('exam_type_id', $exam_type_id)->first();

        $data = [
            'examres' => $exam_res,
            'examans' => $examinee_ans,
            'designation' => $designation,
            'department' => $department,
            'exam_type' => $exam_type,
        ];

        return view('client.tab_examinee_answers')->with($data);
    }

    public function showAnswersForChecking($examinee_id, $exam_id, $exam_type_id)
    {
        $details = DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', Auth::user()->user_id)
            ->first();

        $department = $details->department;
        $designation = $details->designation;

        $exam_res = ExaminationResult::join('examinee', 'examination_result.examinee_id', 'examinee.examinee_id')
            ->join('users', 'examinee.user_id', 'users.id')
            ->join('exams', 'examinee.exam_id', 'exams.exam_id')
            ->join('exam_group', 'exams.exam_group_id', 'exam_group.exam_group_id')
            ->where('examination_result.examinee_id', $examinee_id)
            ->where('examination_result.exam_id', $exam_id)
            ->select('examination_result.*', 'exam_group.exam_group_description', 'exams.exam_title', 'users.employee_name', 'examinee.date_taken', 'examinee.start_time', 'examinee.end_time')
            ->first();

        $examinee_ans = ExamineeAnswer::join('questions', 'examinee_answers.question_id', 'questions.question_id')
            ->where('examinee_answers.examinee_id', $examinee_id)
            ->where('examinee_answers.exam_id', $exam_id)
            ->where('examinee_answers.exam_type_id', $exam_type_id)
            ->select('examinee_answers.*', 'questions.questions', 'questions.correct_answer', 'questions.question_img', 'questions.question_img')
            ->get();

        $exam_type = DB::table('exam_type')->where('exam_type_id', $exam_type_id)->first();

        $data = [
            'examres' => $exam_res,
            'examans' => $examinee_ans,
            'designation' => $designation,
            'department' => $department,
            'exam_type' => $exam_type,
        ];

        return view('client.tab_examination_check_answers')->with($data);
    }

    public function saveScore($examinee_id, $exam_id, Request $request)
    {

        $examinee_ans = ExamineeAnswer::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->get();

        foreach ($examinee_ans as $ans) {
            $qid = $ans->question_id;
            if ($request->$qid) {
                $ans->isCorrect = $request->$qid;
                $ans->save();
            }
        }

        $examres = ExaminationResult::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->first();
        $examres->examinee_score = ExamineeAnswer::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->where('isCorrect', 'True')->count();
        $examres->save();

        return redirect()->route('viewAnswers', [$examinee_id, $exam_id]);
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

    public function showApplicantExamResult()
    {
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $departments = DB::table('departments')->get();
        $examgroups = DB::table('exam_group')->get();
        $exam_types = DB::table('exam_type')->get();

        $exam_results = ExaminationResult::join('examinee', 'examination_result.examinee_id', 'examinee.examinee_id')
            ->join('users', 'examinee.user_id', 'users.id')
            ->join('exams', 'examinee.exam_id', 'exams.exam_id')
            ->join('exam_group', 'exams.exam_group_id', 'exam_group.exam_group_id')
            ->where('exam_group.exam_group_description', 'LIKE', '%applicant%')
            ->select('examination_result.*', 'exam_group.exam_group_description', 'exams.exam_title', 'users.employee_name', 'examinee.date_taken')
            ->orderBy('examinee.date_taken', 'desc')->get();

        return view('client.modules.human_resource.exam_results.index', compact('designation', 'department', 'examgroups', 'departments', 'exam_types', 'exam_results'));
    }

    public function showApplicantExamResults($examinee_id, $exam_id)
    {

        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $exam_res = ExaminationResult::join('examinee', 'examination_result.examinee_id', 'examinee.examinee_id')
            ->join('users', 'examinee.user_id', 'users.id')
            ->join('exams', 'examinee.exam_id', 'exams.exam_id')
            ->join('exam_group', 'exams.exam_group_id', 'exam_group.exam_group_id')
            ->select('exams.duration_in_minutes', 'exams.passing_mark', 'examination_result.*', 'exam_group.exam_group_description', 'exams.exam_title', 'users.employee_name', 'examinee.date_taken', 'examinee.start_time', 'examinee.end_time')
            ->where('examination_result.examinee_id', $examinee_id)->where('examination_result.exam_id', $exam_id)->first();

        $count_multiple_choice = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 4)->count();
        $count_essay = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 5)->count();
        $count_numerical_exam = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 6)->count();
        $count_true_or_false = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 7)->count();
        $count_identification = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 12)->count();
        $count_abstract = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 13)->count();
        $count_dexterity1 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 14)->count();
        $count_dexterity2 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 15)->count();
        $count_dexterity3 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('isCorrect', 'True')
            ->where('exam_type_id', 16)->count();

        $items_multiple_choice = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 4)->count();
        $items_essay = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 5)->count();
        $items_numerical_exam = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 6)->count();
        $items_true_or_false = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 7)->count();
        $items_identification = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 12)->count();
        $items_abstract = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 13)->count();
        $items_dexterity1 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 14)->count();
        $items_dexterity2 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 15)->count();
        $items_dexterity3 = ExamineeAnswer::where('examinee_id', $examinee_id)
            ->where('exam_id', $exam_id)->where('exam_type_id', 16)->count();

        $totalItems = ExamineeAnswer::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->count();
        $totalScore = ExamineeAnswer::where('examinee_id', $examinee_id)->where('exam_id', $exam_id)->where('isCorrect', 'True')->count();

        $average = number_format(100 * ($totalScore / $totalItems), 2);

        $data = [
            'examres' => $exam_res,

            'count_multiple_choice' => $count_multiple_choice,
            'count_essay' => $count_essay,
            'count_numerical_exam' => $count_numerical_exam,
            'count_true_or_false' => $count_true_or_false,
            'count_identification' => $count_identification,
            'count_abstract' => $count_abstract,
            'count_dexterity1' => $count_dexterity1,
            'count_dexterity2' => $count_dexterity2,
            'count_dexterity3' => $count_dexterity3,

            'items_multiple_choice' => $items_multiple_choice,
            'items_essay' => $items_essay,
            'items_numerical_exam' => $items_numerical_exam,
            'items_true_or_false' => $items_true_or_false,
            'items_identification' => $items_identification,
            'items_abstract' => $items_abstract,
            'items_dexterity1' => $items_dexterity1,
            'items_dexterity2' => $items_dexterity2,
            'items_dexterity3' => $items_dexterity3,

            'designation' => $designation,
            'department' => $department,
            'totalItems' => $totalItems,
            'totalScore' => $totalScore,
            'average' => $average,
        ];

        return view('client.modules.human_resource.exam_results.exam_result')->with($data);
    }

    public function showApplicantExamAnswers($examinee_id, $exam_id, $exam_type_id)
    {

        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $exam_res = ExaminationResult::join('examinee', 'examination_result.examinee_id', 'examinee.examinee_id')
            ->join('users', 'examinee.user_id', 'users.id')
            ->join('exams', 'examinee.exam_id', 'exams.exam_id')
            ->join('exam_group', 'exams.exam_group_id', 'exam_group.exam_group_id')
            ->select('examination_result.*', 'exam_group.exam_group_description', 'exams.exam_title', 'users.employee_name', 'examinee.date_taken', 'examinee.start_time', 'examinee.end_time')
            ->where('examination_result.examinee_id', $examinee_id)
            ->where('examination_result.exam_id', $exam_id)
            ->first();

        $examinee_ans = ExamineeAnswer::join('questions', 'examinee_answers.question_id', 'questions.question_id')
            ->where('examinee_answers.examinee_id', $examinee_id)
            ->where('examinee_answers.exam_id', $exam_id)
            ->where('examinee_answers.exam_type_id', $exam_type_id)
            ->select('examinee_answers.*', 'questions.questions', 'questions.correct_answer', 'questions.question_img')
            ->get();

        $exam_type = DB::table('exam_type')->where('exam_type_id', $exam_type_id)->first();

        $data = [
            'examres' => $exam_res,
            'examans' => $examinee_ans,
            'designation' => $designation,
            'department' => $department,
            'exam_type' => $exam_type,
        ];

        return view('client.modules.human_resource.exam_results.view_answers')->with($data);
    }

    public function showApplicantAnswersForChecking($examinee_id, $exam_id, $exam_type_id)
    {
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $exam_res = ExaminationResult::join('examinee','examination_result.examinee_id','examinee.examinee_id')
            ->join('users','examinee.user_id','users.id')
            ->join('exams','examinee.exam_id','exams.exam_id')
            ->join('exam_group','exams.exam_group_id', 'exam_group.exam_group_id')
            ->where('examination_result.examinee_id',$examinee_id)
            ->where('examination_result.exam_id',$exam_id)
            ->select('examination_result.*','exam_group.exam_group_description','exams.exam_title','users.employee_name','examinee.date_taken','examinee.start_time','examinee.end_time')
            ->first();

        $examinee_ans = ExamineeAnswer::join('questions','examinee_answers.question_id','questions.question_id')
            ->where('examinee_answers.examinee_id',$examinee_id)
            ->where('examinee_answers.exam_id',$exam_id)
            ->where('examinee_answers.exam_type_id',$exam_type_id)
            ->select('examinee_answers.*','questions.questions','questions.correct_answer', 'questions.question_img')
            ->get();

        $exam_type = DB::table('exam_type')->where('exam_type_id', $exam_type_id)->first();

        $data = [
            'examres' => $exam_res,
            'examans' => $examinee_ans,
            'designation' => $designation,
            'department' => $department,
            'exam_type' => $exam_type,
        ];

        return view('client.modules.human_resource.exam_results.check_answers')->with($data);
    }

    public function updateApplicantScore($examinee_id, $exam_id, Request $request)
    {

        $examinee_ans = ExamineeAnswer::where('examinee_id',$examinee_id)->where('exam_id',$exam_id)->get();

        foreach ($examinee_ans as $ans) {
            $qid = $ans->question_id;
            if ($request->$qid) {
                $ans->isCorrect = $request->$qid;
                $ans->save();
            }
        }

        $examres = ExaminationResult::where('examinee_id',$examinee_id)->where('exam_id',$exam_id)->first();
        $examres->examinee_score = ExamineeAnswer::where('examinee_id',$examinee_id)->where('exam_id',$exam_id)->where('isCorrect','True')->count();
        $examres->save();

        return redirect('/client/exam_results/'.$examinee_id.'/'.$exam_id.'');
    }
}
