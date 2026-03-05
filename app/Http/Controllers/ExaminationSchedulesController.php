<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Exam;
use App\Models\Examinee;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Http\Request;

class ExaminationSchedulesController extends Controller
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

    public function index()
    {
        $exams = Exam::orderBy('department_id')->get();
        // $users = User::get();
        $examinees = Examinee::join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
            ->join('users', 'examinee.user_id', '=', 'users.id')
            ->select('examinee.*', 'exams.exam_title', 'users.employee_name')
            ->orderBy('examinee.exam_id')
            ->orderBy('date_of_exam', 'desc')
            ->orderBy('employee_name')->get();

        $examDepts = Exam::select('department_id')->distinct('department_id')->get();

        // dd($examDepts);
        $deptIDs = [null];
        foreach ($examDepts as $edept) {
            $hasUsers = User::where('department_id', $edept->department_id)->get();
            if (count($hasUsers) > 0) {
                array_push($deptIDs, $edept->department_id);
            }
        }
        // dd($deptIDs);
        // dd($deptIDs);
        $users = User::whereIn('department_id', $deptIDs)->orWhereNull('department_id')->orderBy('department_id')->get();
        $departments = Department::whereIn('department_id', $deptIDs)->orderBy('department_id')->get();

        $data = [
            'exams' => $exams,
            'users' => $users,
            'examinees' => $examinees,
            'departments' => $departments,
        ];

        return view('exam.examination_schedule_index')->with($data);
    }

    public function save(Request $request)
    {
        // dd($request->all());
        // list($duration, $exam_id) = explode(',', $request->exam_id);
        // $examinee = new Examinee;
        // $examinee->user_id = $request->user_id;
        // $examinee->exam_id = $exam_id;
        // $examinee->date_of_exam = $request->date_of_exam;
        // $examinee->duration = $duration;
        // $examinee->validity_date = $request->validity_date;
        // $examinee->save();

        // return redirect()->route('admin.examination_schedules_index')->with(["message" => "Succesfully Added New Examinee"]);

        try {
            // dd($request->all());
            [$duration, $exam_id] = explode(',', $request->exam_id);
            $tookExam = Examinee::where('user_id', $request->user_id)->where('exam_id', $exam_id)->whereNotNull('date_taken')->first();
            if ($tookExam) {
                return response()->json(['error' => 'Examinee has already taken the exam!!', 'rec' => $tookExam]);
            } else {
                $willExam = Examinee::where('user_id', $request->user_id)->where('exam_id', $exam_id)->whereNull('date_taken')->first();
                if ($willExam) {
                    // return redirect()->route('admin.examinees_index')->with(["error" => "Examinee has pending exam!"]);
                    return response()->json(['error' => 'Examinee has pending exam!', 'rec' => $willExam]);
                } else {
                    $examinee = new Examinee;
                    $examinee->user_id = $request->user_id;
                    $examinee->exam_id = $exam_id;
                    $examinee->date_of_exam = $request->date_of_exam;
                    $examinee->duration = $duration;
                    $examinee->validity_date = $request->validity_date;
                    $examinee->save();

                    $examinees = Examinee::join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
                        ->join('users', 'examinee.user_id', '=', 'users.id')
                        ->select('examinee.*', 'exams.exam_title', 'users.employee_name')
                        ->orderBy('examinee.exam_id')
                        ->orderBy('date_of_exam', 'desc')
                        ->orderBy('employee_name')->get();

                    return response()->json(['message' => 'Succesfully Added New Examinee', 'examinees' => $examinees]);
                }
            }

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());
        [$duration, $exam_id] = explode(',', $request->exam_id);
        $examinee = Examinee::find($request->examinee_id);
        $examinee->user_id = $request->user_id;
        $examinee->exam_id = $exam_id;
        $examinee->date_of_exam = $request->date_of_exam;
        $examinee->duration = $duration;
        $examinee->validity_date = $request->validity_date;
        $examinee->save();

        return redirect()->route('admin.examination_schedules_index')->with(['message' => 'Succesfully Updated Examinee']);
    }

    public function delete(Request $request)
    {
        // dd($request->all());
        $examinee = Examinee::find($request->examinee_id);
        DB::table('examinee')->where('examinee_id', '=', $request->examinee_id)->delete();

        return redirect()->route('admin.examination_schedules_index')->with(['message' => 'Succesfully Deleted Examinee']);
    }
}
