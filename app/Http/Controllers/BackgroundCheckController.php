<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicantBackgroundCheckRequest;
use App\Http\Requests\BackgroundCheckQuestionRequest;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\BackgroundCheck;
use App\Models\Backgroundquestion;
use App\Models\Applicant;


class BackgroundCheckController extends Controller
{
  public function backcheck($id){
        $applicant= Applicant::find($id);
        $question = DB::table('background_question')
                        ->select('question_id', 'question')
                        ->get();
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        $designation = $detail->designation;
        $department = $detail->department;

        return view('client.background_check.back_form', compact('designation', 'department', 'applicant','question'));
    }
    


  public function addbackquestion(){
  	$designation = $this->sessionDetails('designation');
    $department = $this->sessionDetails('department');
    return view('client.background_check.addquestion', compact('designation', 'department'));
  }


  public function sessionDetails($column){
       $detail = DB::table('users')
                   ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                   ->join('departments', 'users.department_id', '=', 'departments.department_id')
                   ->where('user_id', Auth::user()->user_id)
                   ->first();
       return $detail->$column;
    }



  public function savequestion(BackgroundCheckQuestionRequest $request){

    	$ques= new Backgroundquestion;
    	$ques->question=$request->question;
    	$ques->save();

    	return redirect('/backgroundcheck');

    }

public function saveexam(Request $request){
//   $this->validate($request, [
//             'examinee_name' => 'required', 
//             'name_interview' => 'required']);

// $validate_array = ['answer[]' => 'required'];

// $this->validate($request, $validate_array );

  $data = $request->all();

  $ques = $data['question_id'];
  $ans = $data['answer'];

  //insert using foreach loop
  foreach($ques as $key => $input) {
    $scores = new BackgroundCheck();
    $scores->evaluator_id=Auth::user()->user_id;
    $scores->examinee_id=$request->examinee_id;
    $scores->answer = isset($ans[$key]) ? $ans[$key] : ''; //add a default value here
    $scores->question_id = isset($ques[$key]) ? $ques[$key] : ''; //add a default value here
    $scores->nperson_interviewd=$request->nperson_interviewd;
    $scores->conducted_by=$request->conducted_by;
    $scores->remarks=$request->remarks;
    $scores->save();
    }


    return redirect('/tabApplicants');

}

	public function view_panel($id){
    $question_answer = DB::table('background_investigation')
                        ->select('background_investigation.examinee_id', 'background_investigation.question_id','background_investigation.answer','background_question.question')
                        ->join('background_question', 'background_question.question_id', '=', 'background_investigation.question_id')
                        ->where('background_investigation.examinee_id', '=', $id)
                        ->get();
            
                

    if ($u = BackgroundCheck::where('examinee_id', '=', $id)->first())
      {
         $message='exists';
      } else {
          $message='nope';
      }
        $applicant= Applicant::find($id);

        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        $designation = $detail->designation;
        $department = $detail->department;

        return view('client.background_check.view_exampanel', compact('designation', 'department', 'applicant','message','question_answer'));
    }



    public function showquestiontable(){

        $question = DB::table('background_question')
                        ->select('question_id', 'question')
                        ->get();
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        return view('client.background_check.crud_questions', compact('designation', 'department','question'));

    }


        public function store(Request $request){
        $questions = new Backgroundquestion;
        $questions->question = $request->question;
        $questions->save();

        return redirect()->back()->with(["message" => "Question - <b>" . $questions->question . "</b> has been successfully added!"]);
    }

    public function update(Request $request){
        $questions = Backgroundquestion::find($request->id);
        $questions->question = $request->question;
        $questions->save();

        return redirect()->back()->with(["message" => "Question - <b>" . $questions->question . "</b> has been successfully updated!"]);
    }

    public function delete(Request $request){
        $questions = Backgroundquestion::find($request->question_id);
        $questions->delete();
        
        return redirect()->back()->with(['message' => 'Question - <b>' . $questions->question . '</b>  has been successfully deleted!']);
    }

    public function showBackgroundInvQuestions(){
      $designation = $this->sessionDetails('designation');
      $department = $this->sessionDetails('department');

      $question = DB::table('background_question')->select('question_id', 'question')->get();

      return view('client.modules.human_resource.background_investigation.index', compact('designation', 'department','question'));
    }

    public function showBackGroundCheckForm($id){
        $applicant= Applicant::find($id);
        $question = DB::table('background_question')
                        ->select('question_id', 'question')
                        ->get();
                        
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        return view('client.modules.human_resource.applicants.background_check_form', compact('designation', 'department', 'applicant','question'));
    }

    public function ApplicantBackGroundCheckCreate(ApplicantBackgroundCheckRequest $request){
  $data = $request->all();

  $ques = $data['question_id'];
  $ans = $data['answer'];

  //insert using foreach loop
  foreach($ques as $key => $input) {
    $scores = new BackgroundCheck();
    $scores->evaluator_id=Auth::user()->user_id;
    $scores->examinee_id=$request->examinee_id;
    $scores->answer = isset($ans[$key]) ? $ans[$key] : ''; //add a default value here
    $scores->question_id = isset($ques[$key]) ? $ques[$key] : ''; //add a default value here
    $scores->nperson_interviewd=$request->nperson_interviewd;
    $scores->conducted_by=$request->conducted_by;
    $scores->save();
    }

      return redirect('/client/applicant/profile/' . $request->examinee_id);
    }
}
