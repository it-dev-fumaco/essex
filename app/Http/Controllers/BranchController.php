<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = DB::table('branch')->get();

        return view('admin.branch.index')->with('branches', $branches);
    }

    public function store(Request $request)
    {
        $data = [
            'branch_name' => $request->branch_name,
            'address' => $request->address,
        ];

        $branch = DB::table('branch')->insert($data);

        return redirect()->back()->with(['message' => 'Branch <b>'.$request->branch_name.'</b>  has been added!']);
    }

    public function update(Request $request)
    {
        $data = [
            'branch_name' => $request->branch_name,
            'address' => $request->address,
        ];

        $branch = DB::table('branch')->where('branch_id', $request->id)->update($data);

        return redirect()->back()->with(['message' => 'Branch <b>'.$request->branch_name.'</b>  has been updated!']);
    }

    public function delete(Request $request)
    {
        DB::table('branch')->where('branch_id', $request->id)->delete();

        return redirect()->back()->with(['message' => 'Branch <b>'.$request->branch_name.'</b>  has been deleted!']);
    }
}
