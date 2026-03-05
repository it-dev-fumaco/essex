<?php

namespace App\Http\Controllers;

use App\Models\ExamGroup;
use DB;
use Exception;
use Illuminate\Http\Request;

class ExamGroupsController extends Controller
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
    public function index()
    {
        $examgroups = ExamGroup::get();

        return view('exam.examgroup_index')->with(['examgroups' => $examgroups]);
    }

    public function save(Request $request)
    {
        try {
            $record = ExamGroup::where('exam_group_description', $request->exam_group_description)->first();
            if ($record) {
                // dd($record);
                // return redirect()->route('admin.exam_groups_index')->with(["error" => "Exam Group already exist in database!"]);
                return response()->json(['error' => $request->exam_group_description.' already exists!']);
            } else {
                $examgroup = new ExamGroup;
                $examgroup->exam_group_description = $request->exam_group_description;
                $examgroup->remarks = $request->remarks;
                $examgroup->save();

                return response()->json(['message' => "Succesfully Added New Exam Group '".$request->exam_group_description."'"]);
            }

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request)
    {
        $examgroup = ExamGroup::find($request->exam_group_id);
        $examgroup->exam_group_description = $request->exam_group_description;
        $examgroup->remarks = $request->remarks;
        $examgroup->save();

        return redirect()->route('admin.exam_groups_index')->with(['message' => "Succesfully Updated Exam Group '".$request->exam_group_description."'"]);
    }

    public function delete(Request $request)
    {
        $examgroup = ExamGroup::find($request->exam_group_id);
        DB::table('exam_group')->where('exam_group_id', '=', $request->exam_group_id)->delete();

        return redirect()->route('admin.exam_groups_index')->with(['message' => "Succesfully Deleted Exam Group '".$examgroup->exam_group_description."'"]);
    }
}
