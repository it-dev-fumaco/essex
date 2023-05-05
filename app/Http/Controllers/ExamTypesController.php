<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\ExamType;
use Auth;

use Exception;
class ExamTypesController extends Controller
{
    public function index(){
        $examtypes = ExamType::get();
        return view('exam.examtype_index')->withExamtypes($examtypes);
    }
    public function save(Request $request){
        try{
            $record = ExamType::where('exam_type',$request->exam_type)->first();
            if($record){
                return response()->json(["error" => $request->exam_type." already exists!"]);
            }
            else{
                $examtype = new ExamType;
                $examtype->exam_type = $request->exam_type;
                $examtype->instruction = $request->instruction;
                $examtype->save();
                
                return redirect()->route('admin.exam_types_index')->with(["message" => "Succesfully Added New Exam Type '".$request->exam_type."'"]);
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function update(Request $request){
        $examtype = ExamType::find($request->exam_type_id);
        $examtype->exam_type = $request->exam_type;
        $examtype->instruction = $request->instruction;
        $examtype->save();

        return redirect()->route('admin.exam_types_index')->with(["message" => "Succesfully Updated Exam Type '".$request->exam_type."'"]);
    }
    public function delete(Request $request){
        $examtype = ExamType::find($request->exam_type_id);
        DB::table('exam_type')->where('exam_type_id', '=', $request->exam_type_id)->delete();
        return redirect()->route('admin.exam_types_index')->with(["message" => "Succesfully Deleted Exam Type '".$examtype->exam_type."'"]);
    }

    public function editInstructions(Request $request){
        $examtype = ExamType::find($request->exam_type_id);
        $examtype->instruction = $request->instruction;
        $examtype->last_modified_by = Auth::user()->employee_name;
        $examtype->save();

        return redirect()->back()->with(["message" => "Instructions for '".$request->exam_type."' has been successfully updated."]);
    }
}