<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Examinee;
use App\Exam;
use App\User;
use DB;

use App\Department;
use App\ExamGroup;

use App\Question;
use App\ExamType;

use Exception;

class ExamineesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //  public function __construct()
    // {
    //     $this->middleware('auth:admin');
    // }

    public function index(){
    	$exams = Exam::orderBy('department_id')->get();
    	// $users = User::get();
    	$examinees = Examinee::join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
    					->join('users', 'examinee.user_id', '=', 'users.id')
    					->select('examinee.*', 'exams.exam_title', 'users.employee_name')
                        ->orderBy('examinee.exam_id')
                        ->orderBy('date_of_exam','desc')
                        ->orderBy('employee_name')->get();

        $examDepts = Exam::select('department_id')->distinct('department_id')->get();
        
        // dd($examDepts);
        $deptIDs = [null];
        foreach($examDepts as $edept){
            $hasUsers = User::where('department_id',$edept->department_id)->get();
            if(count($hasUsers) > 0){
                array_push($deptIDs, $edept->department_id);
            }
        }
        // dd($deptIDs);
        // dd($deptIDs);
        $users = User::whereIn('department_id',$deptIDs)->orWhereNull('department_id')->orderBy('department_id')->get();
        $departments = Department::whereIn('department_id',$deptIDs)->orderBy('department_id')->get();

    	$data = [
    		'exams' => $exams,
    		'users' => $users,
    		'examinees' => $examinees,
            'departments' => $departments
    	];

    	return view('exam.examinee_index')->with($data);
    }

    public function save(Request $request){
        try{
            // dd($request->all());
            list($duration, $exam_id) = explode(',', $request->exam_id);
            $tookExam = Examinee::where('user_id',$request->user_id)->where('exam_id',$exam_id)->whereNotNull('date_taken')->first();
            if($tookExam){
                return response()->json(["error" => "Examinee has already taken the exam!!", 'rec' => $tookExam]);
            }
            else{
                $willExam = Examinee::where('user_id',$request->user_id)->where('exam_id',$exam_id)->whereNull('date_taken')->first();
                if($willExam){
                    // return redirect()->route('admin.examinees_index')->with(["error" => "Examinee has pending exam!"]);
                    return response()->json(["error" => "Examinee has pending exam!", 'rec' => $willExam]);
                }
                else{
                    $examinee = new Examinee;
                    $examinee->exam_code = date('Y',strtotime($request->date_of_exam)) . '-' . rand(10000,99999);
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
                            ->orderBy('date_of_exam','desc')
                            ->orderBy('employee_name')->get();

                    return response()->json(["message" => "Succesfully Added New Examinee", 'examinees' => $examinees]);
                }
            }
            
        }catch(Exception $e){
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    public function update(Request $request){
        // list($duration, $exam_id) = explode(',', $request->exam_id);
            $exam = Exam::find($request->exam_id);
        
        try{
            // dd($request->all());
            $record = Examinee::where('user_id',$request->user_id)
                            ->where('exam_id',$request->exam_id)
                            ->whereDate('date_of_exam', '>=', $request->date_of_exam)
                            ->whereDate('validity_date', '>=', $request->validity_date)
                            ->get();
            if(count($record) > 0){
                // dd($record);
                return redirect()->route('admin.examinees_index')->with(["error" => $request->employee_name . " has pending exam!"]);
            }
            else{
                $examinee = Examinee::find($request->examinee_id);
                $examinee->exam_code = date('Y',strtotime($request->date_of_exam)) . '-' . rand(10000,99999);
                $examinee->user_id = $request->user_id;
                $examinee->exam_id = $request->exam_id;
                $examinee->date_of_exam = $request->date_of_exam;
                $examinee->duration = $exam->duration;
                $examinee->validity_date = $request->validity_date;
                $examinee->save();
                return redirect()->route('admin.examinees_index')->with(["message" => "Succesfully Updated Examinee"]);
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }


    public function delete(Request $request){
        // dd($request->all());
        $examinee = Examinee::find($request->examinee_id);
        DB::table('examinee')->where('examinee_id', '=', $request->examinee_id)->delete();
        return redirect()->route('admin.examinees_index')->with(["message" => "Succesfully Deleted Examinee"]);
    }

    public function testSheetIndex(){
        $exams = Exam::join('exam_group','exams.exam_group_id','=','exam_group.exam_group_id')
                    ->join('departments','exams.department_id','=', 'departments.department_id')
                    ->select('exams.*','departments.department','exam_group.exam_group_description')
                    ->get();
        $departments = Department::get();
        $examgroups = ExamGroup::get();

        $data = [
            'exams' => $exams,
            'departments' => $departments,
            'examgroups' => $examgroups
        ];
        return view('exam.testsheet_index')->with(['exams' => $exams,'departments' => $departments,'examgroups' => $examgroups]);
    }

    public function examineeTestSheet($examineeid){
        $examinee = Examinee::find($examineeid)
                        ->join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
                        ->join('users', 'examinee.user_id', '=', 'users.id')
                        ->select('examinee.*', 'exams.exam_title', 'users.employee_name', 'users.user_type')
                        ->first();
        $multiplechoices = Question::where('questions.exam_id','=',$examinee->exam_id)
                                ->join('exam_type', 'questions.exam_type_id', '=', 'exam_type.exam_type_id')
                                ->join('exams', 'questions.exam_id', '=', 'exams.exam_id')
                                ->where('exam_type','Multiple Choice')
                                ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                                ->get();
        $truesfalses = Question::where('questions.exam_id','=',$examinee->exam_id)
                                ->join('exam_type', 'questions.exam_type_id', '=', 'exam_type.exam_type_id')
                                ->join('exams', 'questions.exam_id', '=', 'exams.exam_id')
                                ->where('exam_type','True or False')
                                ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                                ->get();
        $essays = Question::where('questions.exam_id','=',$examinee->exam_id)
                                ->join('exam_type', 'questions.exam_type_id', '=', 'exam_type.exam_type_id')
                                ->join('exams', 'questions.exam_id', '=', 'exams.exam_id')
                                ->where('exam_type','Essays')
                                ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                                ->get();
        $numericals = Question::where('questions.exam_id','=',$examinee->exam_id)
                                ->join('exam_type', 'questions.exam_type_id', '=', 'exam_type.exam_type_id')
                                ->join('exams', 'questions.exam_id', '=', 'exams.exam_id')
                                ->where('exam_type','Numerical Exam')
                                ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                                ->get();
        $exam = Exam::find($examinee->exam_id);
        $examtypes = ExamType::get();
        $mcins = Question::join('exam_type', 'questions.exam_type_id', 'exam_type.exam_type_id')
                        ->select('exam_type.instruction')
                        ->first();
        $data = [
            'exam' => $exam,
            'examinee' => $examinee,
            'multiplechoices' => $multiplechoices,
            'truesfalses' => $truesfalses,
            'essays' => $essays,
            'numericals' => $numericals,
            'examtypes' => $examtypes,
            'mcins' => $mcins
        ];

        return view('exam.examinee_testsheet')->with($data);
    }

    public function saveExamineeTestSheet(Request $request){
        $endtime = date('H:i');
        $data = [
            'request' => $request->all(),
            'endtime' => $endtime
        ];
        dd($data);
    }

    public function getUserByDepartment($id){
       if ($id == -1) {
            $usersByDept = User::where('user_type', 'Employee')->get();
        }else{
            $id > 0 ? $usersByDept = User::where('department_id',$id)->get() : $usersByDept = User::whereNull('department_id')->get();
        }

        $examsByDept = Exam::where('department_id',$id)->get();
        return response()->json(['users' => $usersByDept, 'exams' => $examsByDept, 'id' => $id]);
    }

    //AJAX
    public function getExaminees(Request $request){
        if ($request->ajax()) {
            $examinees = DB::table('examinee')
                        ->join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
                        ->join('users', 'examinee.user_id', '=', 'users.id')
                        ->where('examinee.exam_id', $request->exam_id)
                        ->get();

            return view('client.tables.examinees_table', compact('examinees'))->render();
        }
    }
    //AJAX
    public function addExaminee(Request $request){
        $examinee = new Examinee;
        $examinee->user_id = $request->user_id;
        $examinee->exam_id = $request->exam_id;
        $examinee->date_of_exam = $request->date_of_exam;
        $examinee->validity_date = $request->validity_date;
        $examinee->save();

        return response()->json(['message' => 'Examinee has been added.']);
    }

    public function tabAddExaminee(Request $request){
        try{
            list($duration, $exam_id) = explode(',', $request->exam_id);
            $tookExam = Examinee::join('users as u', 'u.id', 'examinee.user_id')
                ->where('examinee.user_id', $request->user_id)->where('examinee.exam_id', $exam_id)->whereNotIn('u.user_type', ['Employee'])->whereNotNull('examinee.date_taken')->first();
            if($tookExam){
                return response()->json(["error" => "Examinee has already taken the exam!"]);
            }

            $willExam = Examinee::where('user_id',$request->user_id)->where('exam_id',$exam_id)->whereNull('date_taken')->first();
            if($willExam){
                return response()->json(["error" => "Examinee has pending exam!"]);
            }

            $examinee = new Examinee;
            $examinee->user_id = $request->user_id;
            $examinee->exam_id = $exam_id;
            $examinee->exam_code = date('Y',strtotime($request->date_of_exam)) . '-' . rand(10000,99999);
            $examinee->date_of_exam = $request->date_of_exam;
            $examinee->duration = $duration;
            $examinee->validity_date = $request->validity_date;
            $examinee->save();

            $examinees = Examinee::join('exams', 'examinee.exam_id', '=', 'exams.exam_id')
                ->join('users', 'examinee.user_id', '=', 'users.id')
                ->select('examinee.*', 'exams.exam_title', 'users.employee_name')
                ->orderBy('examinee.exam_id')
                ->orderBy('date_of_exam','desc')
                ->orderBy('employee_name')->get();

            return response()->json(["message" => "Succesfully Added New Examinee", 'examinees' => $examinees]);
        }catch(Exception $e){
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    public function tabUpdateExaminee(Request $request){
        dd($request->all());
        try{
            $record = Examinee::where('user_id',$request->user_id)
                            ->where('exam_id',$request->user_id)
                            ->whereDate('date_of_exam', '>=', $request->date_of_exam)
                            ->whereDate('validity_date', '>=', $request->validity_date)
                            ->get();
            if(count($record) > 0){
                return redirect()->route('client.tabExaminees')->with(["error" => $request->employee_name . " has pending exam!"]);
            }
            else{
                list($duration, $exam_id) = explode(',', $request->exam_id);
                $examinee = Examinee::find($request->examinee_id);
                $examinee->user_id = $request->user_id;
                $examinee->exam_id = $exam_id;
                $examinee->date_of_exam = $request->date_of_exam;
                $examinee->duration = $duration;
                $examinee->validity_date = $request->validity_date;
                $examinee->save();
                return redirect()->route('client.tabExaminees')->with(["message" => "Succesfully Updated Examinee"]);
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }


    public function tabDeleteExaminee(Request $request){
        $examinee = Examinee::find($request->examinee_id);
        DB::table('examinee')->where('examinee_id', '=', $request->examinee_id)->delete();
        return redirect()->route('client.tabExaminees')->with(["message" => "Succesfully Deleted Examinee"]);
    }

    public function updateExamineeStatus(Request $request){
        $examinee = Examinee::find($request->id);
        $examinee->status = $request->status;
        $examinee->save();

        return redirect('/applicant/takeExam/' . $request->id);
    }
}