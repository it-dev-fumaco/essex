<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExamineeAnswersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct()
    {
        $this->middleware('auth');
    }


    public function save(){}
}