<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Question;
use App\ExamType;
use App\Exam;
use Route;

use Exception;
use Storage;

class QuestionsController extends Controller
{
    public function index(){
        $questions = Question::leftJoin('exams','questions.exam_id','=','exams.exam_id')
                        ->leftJoin('exam_type','questions.exam_type_id','=','exam_type.exam_type_id')
                        ->select('questions.*','exams.exam_title','exam_type.exam_type')
                        ->orderBy('exam_id')
                        ->orderBy('question_id', 'desc')
                        ->get();

        $examtypes = ExamType::get();
        $exams = Exam::get();

        return view('exam.question_index')->with(['questions' => $questions, 'examtypes' => $examtypes, 'exams' => $exams]);
    }
    public function save(Request $request){
        try{
            $request->validate([
                'option1_img' => 'nullable|image|mimes:jpeg,jpg,png,PNG,JPEG,JPG|max:2048',
                'option2_img' => 'nullable|image|mimes:jpeg,jpg,png,PNG,JPEG,JPG|max:2048',
                'option3_img' => 'nullable|image|mimes:jpeg,jpg,png,PNG,JPEG,JPG|max:2048',
                'option4_img' => 'nullable|image|mimes:jpeg,jpg,png,PNG,JPEG,JPG|max:2048',
                'qimage.*' => 'nullable|image|mimes:jpeg,jpg,png,PNG,JPEG,JPG|max:2048',
            ]);

            $question = new Question;
            $question->exam_id = $request->exam_id;
            $question->exam_type_id = $request->exam_type_id;
            $question->questions = $request->questions;
            $question->option1 = $request->option1;
            $question->option2 = $request->option2;
            $question->option3 = $request->option3;
            $question->option4 = $request->option4;
            $question->correct_answer = $request->correct_answer;

            if($request->hasFile('qimage')){
                $qimgs = '';
                foreach($request->qimage as $que){
                    $filename = 'question' . str_random(5) . '_' .$que->getClientOriginalName();
                    $que->storeAs('public/questions',$filename);
                    $qimgs .= $filename . ',';
                }
                $question->question_img = substr($qimgs,0,-1);
            }

            if($request->hasFile('option1_img')){
                $filename = 'option1-'  . rand(10000,99999) . '_' . $request->option1_img->getClientOriginalName();
                $request->option1_img->storeAs('public/options',$filename);
                $question->option1_img = $filename;
            }

            if($request->hasFile('option2_img')){
                $filename = 'option2-'  . rand(10000,99999) . '_' . $request->option2_img->getClientOriginalName();
                $request->option2_img->storeAs('public/options',$filename);
                $question->option2_img = $filename;
            }

            if($request->hasFile('option3_img')){
                $filename = 'option3-'  . rand(10000,99999) . '_' . $request->option3_img->getClientOriginalName();
                $request->option3_img->storeAs('public/options',$filename);
                $question->option3_img = $filename;
            }

            if($request->hasFile('option4_img')){
                $filename = 'option4-'  . rand(10000,99999) . '_' . $request->option4_img->getClientOriginalName();
                $request->option4_img->storeAs('public/options',$filename);
                $question->option4_img = $filename;
            }

            $question->save();
            
            return redirect()->route('admin.questions_index')->with(["message" => "Succesfully!!! Added New Question '".$request->questions."'"]);
        }catch(Exception $e){
            return redirect()->route('admin.questions_index')->with(["message" => $e->getMessage()]);
        }
    }
    public function update(Request $request){
        try{
            $question = Question::find($request->question_id);
            $question->exam_id = $request->exam_id;
            $question->questions = $request->questions;
            $question->option1 = $request->option1;
            $question->option2 = $request->option2;
            $question->option3 = $request->option3;
            $question->option4 = $request->option4;
            $request->exam_type == "True or False" ? $question->correct_answer = $request->catf : $question->correct_answer = $request->correct_answer;

            if($request->hasFile('qimage')){ 
                if($question->question_img){
                    $parts = explode(",",$question->question_img);
                    foreach($parts as $part){
                        $filepath = storage_path() . '\\app\\public\\questions\\' . $part;
                        unlink($filepath);
                    }   
                }

                $qimgs = '';
                foreach($request->qimage as $que){
                    $filename = 'question' .  str_random(5) . '_' .$que->getClientOriginalName();
                    $que->storeAs('public/questions',$filename);
                    $qimgs .= $filename . ',';
                }
                $question->question_img = substr($qimgs,0,-1);
            }

            if($request->hasFile('option1_img')){
                if($question->option1_img){
                    $filepath = storage_path() . '\\app\\public\\options\\' . $question->option1_img;
                    unlink($filepath);
                    $question->option1_img = null;
                }
                $filename = 'option1-'  . rand(10000,99999) . '_' . $request->option1_img->getClientOriginalName();
                $request->option1_img->storeAs('public/options',$filename);
                $question->option1_img = $filename;
            }

            if($request->hasFile('option2_img')){
                if($question->option2_img){
                    $filepath = storage_path() . '\\app\\public\\options\\' . $question->option2_img;
                    unlink($filepath);
                    $question->option2_img = null;
                }
                $filename = 'option2-'  . rand(10000,99999) . '_' . $request->option2_img->getClientOriginalName();
                $request->option2_img->storeAs('public/options',$filename);
                $question->option2_img = $filename;
            }

            if($request->hasFile('option3_img')){
                if($question->option3_img){
                    $filepath = storage_path() . '\\app\\public\\options\\' . $question->option3_img;
                    unlink($filepath);
                    $question->option3_img = null;
                }
                $filename = 'option3-'  . rand(10000,99999) . '_' . $request->option3_img->getClientOriginalName();
                $request->option3_img->storeAs('public/options',$filename);
                $question->option3_img = $filename;
            }


            if($request->hasFile('option4_img')){
                if($question->option4_img){
                    $filepath = storage_path() . '\\app\\public\\options\\' . $question->option4_img;
                    unlink($filepath);
                    $question->option4_img = null;
                }
                $filename = 'option4-'  . rand(10000,99999) . '_' . $request->option4_img->getClientOriginalName();
                $request->option4_img->storeAs('public/options',$filename);
                $question->option4_img = $filename;
            }

            $question->save();

            return redirect()->route('admin.questions_index')->with(["message" => "Succesfully Updated Question '".$request->questions."'"]);
        }catch(Exception $e){
            return redirect()->route('admin.questions_index')->with(["message" => $e->getMessage()]);
        }
    }
    public function delete(Request $request){
        try{
            $question = Question::find($request->question_id);
            if($question->question_img){
                $parts = explode(",",$question->question_img);
                foreach($parts as $part){
                    $filepath = storage_path() . '\\app\\public\\questions\\' . $part;
                    unlink($filepath);
                }
            }
            if($question->option1_img){
                $filepath = storage_path() . '\\app\\public\\options\\' . $question->option1_img;
                unlink($filepath);
            }
            if($question->option2_img){
                $filepath = storage_path() . '\\app\\public\\options\\' . $question->option2_img;
                unlink($filepath);
            }
            if($question->option3_img){
                $filepath = storage_path() . '\\app\\public\\options\\' . $question->option3_img;
                unlink($filepath);
            }
            if($question->option4_img){
                $filepath = storage_path() . '\\app\\public\\options\\' . $question->option4_img;
                unlink($filepath);
            }

            DB::table('questions')->where('question_id', '=', $request->question_id)->delete();
            
            return redirect()->route('admin.questions_index')->with(["message" => "Succesfully Deleted Question '".$question->questions."'"]);
        }catch(Exception $e){
            return redirect()->route('admin.questions_index')->with(["message" => $e->getMessage()]);
        }
    }

    // AJAX
    public function getQuestions(Request $request){
        if ($request->ajax()) {
            $questions = DB::table('questions')
                        ->where('questions.exam_id', '=', $request->exam_id)
                        ->join('exam_type', 'questions.exam_type_id', '=', 'exam_type.exam_type_id')
                        ->join('exams', 'questions.exam_id', '=', 'exams.exam_id')
                        ->where('exam_type',  $request->exam_type)
                        ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                        ->paginate(8);
                        
            return view($request->tableView, compact('questions'))->render();
        }
    }

    public function getQuestionDetails(Request $request){
        $questions = DB::table('questions')
                    ->where('questions.question_id', '=', $request->id)
                    ->join('exam_type', 'questions.exam_type_id', '=', 'exam_type.exam_type_id')
                    ->join('exams', 'questions.exam_id', '=', 'exams.exam_id')
                    ->select('questions.*', 'exam_type.exam_type', 'exams.exam_title')
                    ->first();
                        
        return response()->json($questions);
    }

    public function addQuestion(Request $request){
        DB::beginTransaction();
        try {
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

            DB::commit();
            return response()->json(['success' => 1, 'message' => 'Question no. <b>' . $question->question_id . '</b> has been added.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => 0, 'message' => 'An error occured. Please try again later.']);
            DB::rollback();
            throw $th;
        }
    }

    public function editQuestion(Request $request){
        $question = Question::find($request->question_id);
        $question->questions = $request->questions;
        $question->option1 = $request->option1;
        $question->option2 = $request->option2;
        $question->option3 = $request->option3;
        $question->option4 = $request->option4;
        $question->correct_answer = $request->correct_answer;
        $question->save();

        return response()->json(['message' => 'Question no. <b>' . $question->question_id . '</b> has been updated.']);
    }

    public function deleteQuestion(Request $request){
        Question::destroy($request->question_id);

        return response()->json(['message' => 'Question no. <b>' . $request->question_id . '</b> has been deleted.']);
    }

    public function tabAddQuestion(Request $request){
        $question = new Question;
        $question->exam_id = $request->exam_id;
        $question->exam_type_id = $request->exam_type_id;
        $question->questions = $request->questions;
        $question->option1 = $request->option1;
        $question->option2 = $request->option2;
        $question->option3 = $request->option3;
        $question->option4 = $request->option4;
        $question->correct_answer = $request->correct_answer;

        if($request->hasFile('qimage')){
            $qimgs = '';
            foreach($request->qimage as $que){
                $filename = 'question' . str_random(5) . '_' .$que->getClientOriginalName();
                $que->storeAs('public/questions',$filename);
                $qimgs .= $filename . ',';
            }
            $question->question_img = substr($qimgs,0,-1);
        }

        if($request->hasFile('option1_img')){
            $filename = 'option1-'  . rand(10000,99999) . '_' . $request->option1_img->getClientOriginalName();
            $request->option1_img->storeAs('public/options',$filename);
            $question->option1_img = $filename;
        }

        if($request->hasFile('option2_img')){
            $filename = 'option2-'  . rand(10000,99999) . '_' . $request->option2_img->getClientOriginalName();
            $request->option2_img->storeAs('public/options',$filename);
            $question->option2_img = $filename;
        }

        if($request->hasFile('option3_img')){
            $filename = 'option3-'  . rand(10000,99999) . '_' . $request->option3_img->getClientOriginalName();
            $request->option3_img->storeAs('public/options',$filename);
            $question->option3_img = $filename;
        }

        if($request->hasFile('option4_img')){
            $filename = 'option4-'  . rand(10000,99999) . '_' . $request->option4_img->getClientOriginalName();
            $request->option4_img->storeAs('public/options',$filename);
            $question->option4_img = $filename;
        }

        $question->save();

        $msg = ["message" => "Succesfully added new question ".$question->questions."' ('".$request->exam_type."')'"];
        
        return redirect()->back()->with($msg);
    }

    public function tabUpdateQuestion(Request $request){
        $question = Question::find($request->question_id);
        $question->exam_id = $request->exam_id;
        $question->exam_type_id = $request->exam_type_id;
        $question->questions = $request->questions;
        $question->option1 = $request->option1;
        $question->option2 = $request->option2;
        $question->option3 = $request->option3;
        $question->option4 = $request->option4;
        $question->correct_answer = $request->correct_answer;

        if($request->hasFile('qimage')){ 
            if($question->question_img){
                $parts = explode(",",$question->question_img);
                foreach($parts as $part){
                    $filepath = storage_path() . '\\app\\public\\questions\\' . $part;
                    unlink($filepath);
                }   
            }
            $qimgs = '';
            foreach($request->qimage as $que){
                $filename = 'question' .  str_random(5) . '_' .$que->getClientOriginalName();
                $que->storeAs('public/questions',$filename);
                $qimgs .= $filename . ',';
            }
            $question->question_img = substr($qimgs,0,-1);  
        }

        if($request->hasFile('option1_img')){
            if($question->option1_img){
                $filepath = storage_path() . '\\app\\public\\options\\' . $question->option1_img;
                unlink($filepath);
                $question->option1_img = null;
            }
            $filename = 'option1-'  . rand(10000,99999) . '_' . $request->option1_img->getClientOriginalName();
            $request->option1_img->storeAs('public/options',$filename);
            $question->option1_img = $filename;
        }

        if($request->hasFile('option2_img')){
            if($question->option2_img){
                $filepath = storage_path() . '\\app\\public\\options\\' . $question->option2_img;
                unlink($filepath);
                $question->option2_img = null;
            }
            $filename = 'option2-'  . rand(10000,99999) . '_' . $request->option2_img->getClientOriginalName();
            $request->option2_img->storeAs('public/options',$filename);
            $question->option2_img = $filename;
        }

        if($request->hasFile('option3_img')){
            if($question->option3_img){
                $filepath = storage_path() . '\\app\\public\\options\\' . $question->option3_img;
                unlink($filepath);
                $question->option3_img = null;
            }
            $filename = 'option3-'  . rand(10000,99999) . '_' . $request->option3_img->getClientOriginalName();
            $request->option3_img->storeAs('public/options',$filename);
            $question->option3_img = $filename;
        }

        if($request->hasFile('option4_img')){
            if($question->option4_img){
                $filepath = storage_path() . '\\app\\public\\options\\' . $question->option4_img;
                unlink($filepath);
                $question->option4_img = null;
            }
            $filename = 'option4-'  . rand(10000,99999) . '_' . $request->option4_img->getClientOriginalName();
            $request->option4_img->storeAs('public/options',$filename);
            $question->option4_img = $filename;
        }

        $question->save();

        $msg = ["message" => "Succesfully updated question ('".$request->exam_type."')'".$question->questions."'"];
        
        return redirect()->back()->with($msg);
    }

    public function tabDeleteQuestion(Request $request){
        $question = Question::find($request->question_id);

        if($question->question_img){
            $parts = explode(",",$question->question_img);
            foreach($parts as $part){
                $filepath = storage_path() . '\\app\\public\\questions\\' . $part;
                unlink($filepath);
            }   
        }

        if($question->option1_img){
            $filepath = storage_path() . '\\app\\public\\options\\' . $question->option1_img;
            unlink($filepath);
        }

        if($question->option2_img){
            $filepath = storage_path() . '\\app\\public\\options\\' . $question->option2_img;
            unlink($filepath);
        }

        if($question->option3_img){
            $filepath = storage_path() . '\\app\\public\\options\\' . $question->option3_img;
            unlink($filepath);
        }

        if($question->option4_img){
            $filepath = storage_path() . '\\app\\public\\options\\' . $question->option4_img;
            unlink($filepath);
        }

        DB::table('questions')->where('question_id', '=', $request->question_id)->delete();
        
        return redirect()->back()->with(["message" => "Succesfully delete question '".$question->questions."'"]);
    }
}