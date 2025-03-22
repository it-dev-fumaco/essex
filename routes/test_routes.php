<?php

use Illuminate\Support\Facades\Route;

Route::get('/test_db', 'TestingEnvironmentController@test_db'); 
Route::get('/test_mail', 'TestingEnvironmentController@test_mail'); 