<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function takeExam()
    {
        return view('client.exam_sheet');
    }

    public function test(Request $request)
    {

        $employee = new Admin;
        $employee->name = $request->get('testing');

        foreach ($request->quantity as $key => $quantity) {
            $data = [
                'quantity' => $request->quantity[$key], ];

            // insert here
        }

        return $data;
    }

    public function test1(Request $request)
    {

        $employee = new Admin;
        $employee->name = $request->get('testing');
        $data = [];
        foreach ($request->quantity as $key => $quantity) {
            $data[] = [
                'quantity' => $request->quantity[$key]];
        }

        return $data;
    }
}
