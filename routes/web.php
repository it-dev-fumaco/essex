<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/local', 'TestingEnvironmentController@local_login');
Route::post('/updateExamineeStatus', 'ExamineesController@updateExamineeStatus');

// E M P L O Y E E  P O R T A L
Route::get('/', 'PortalController@index')->name('portal');
Route::get('/tbl_manuals', 'PortalController@load_manuals');
Route::get('/email_logs', 'PortalController@email_logs');
Route::get('/resend_email/{id}', 'PortalController@resend_email');

// G A L L E R Y
Route::get('/gallery', 'PortalController@showGallery');
Route::get('/gallery/fetchAlbums', 'PortalController@fetchAlbums');

// M A N U A L S
Route::get('/manuals', 'PortalController@showManuals');
Route::get('/article/{slug}', 'PortalController@showArticle');
Route::get('/services/directory', 'PortalController@phoneEmailDirectory');
Route::get('/services/internet', 'PortalController@showInternet');
Route::get('/services/email', 'PortalController@email');
Route::get('/services/system', 'PortalController@system');
Route::get('/gallery/album/{id}', 'PortalController@showAlbum');
Route::get('/historical_milestones', 'PortalController@showHistoricalMilestones');
// Route::get('/manuals', 'PortalController@showManuals');
Route::get('/policies', 'PortalController@showMemorandum');
Route::get('/updates', 'PortalController@showUpdates');
Route::get('/itguidelines', 'PortalController@showitGuidelines');

Route::get('/search', 'SearchController@search')->name('search');

Route::get('/exam', 'HomeController@takeExam');

Route::get('/userLogout', 'Auth\LoginController@userLogout')->name('user.logout');
Route::post('/userLogin', 'Auth\LoginController@userLogin');

    // //Applicant Examination
    // Route::get('/applicant','ApplicantExaminationsController@index')->name('client.applicant');
    // Route::post('/applicant/examinee','ApplicantExaminationsController@show')->name('client.appli_examinee');
    // Route::get('/applicant/takeExam/{id}','ApplicantExaminationsController@takeExam')->name('applicant.take_exam');
    // Route::post('/applicant/saveExam','ApplicantExaminationsController@saveExam')->name('applicant.save_exam');
    Route::get('/applicant/examSubmitted/{id}','ApplicantExaminationsController@examSuccess')->name('applicant.exam_success');

Auth::routes();
Route::group(['prefix' => 'admin'], function(){
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
    Route::get('/logout', 'Auth\AdminLoginController@adminLogout')->name('admin.logout');
});
Route::post('/notice_slip/updateStatus', 'AbsentNoticesController@updateStatus');

// C L I E N T
Route::group(['middleware' => 'auth'], function(){

    //HR (Client Designations)
Route::get('/module/hr/designation', 'DesignationsController@hr_desig_index');
Route::post('/module/hr/designation/create', 'DesignationsController@store');
Route::post('/module/hr/designation/update', 'DesignationsController@update');
Route::post('/module/hr/designation/delete', 'DesignationsController@delete');
    

//calendar
    Route::post('/addEvent', 'CalendarViewController@store');
    Route::get('/calendar/fetch', 'HomeController@getLeaves'); 
    Route::get('/holidays', 'CalendarViewController@getholidays'); 
    Route::get('/bday', 'CalendarViewController@employeeBirthdates');

    Route::post('/updateAllBiologs', 'AttendanceController@updateEmployeesLogs');



    
    Route::post('/employee/update', 'EmployeesController@update');
    Route::post('/employee/reset_password', 'EmployeesController@reset_password');
    Route::post('/employee/reset_leaves', 'EmployeesController@reset_leaves');


    // Gallery
    Route::post('/addAlbum', 'PortalController@addAlbum');
    Route::post('/editAlbum', 'PortalController@editAlbum');
    Route::post('/deleteAlbum', 'PortalController@deleteAlbum');
    // Posts
    Route::post('/addPost', 'PortalController@addPost');
    Route::post('/updatePost', 'PortalController@updatePost');
    Route::post('/deletePost', 'PortalController@deletePost');
    // Policy
    Route::post('/addPolicy', 'PortalController@addPolicy');
    Route::post('/editPolicy', 'PortalController@editPolicy');
    Route::post('/deletePolicy', 'PortalController@deletePolicy');

    Route::post('/gallery/album/uploadImages', 'PortalController@uploadImage');
    Route::delete('/image/delete/{id}', 'PortalController@deleteImage');
    Route::post('/setAsFeatured', 'PortalController@setAsFeatured');

    // Dashboard
    Route::get('/home', 'HomeController@index')->name('home'); // comment this line on maintenance

    // Under Maintenance
    // Route::get('/home1', 'HomeController@index')->name('home1');
    // Route::get('/home', 'HomeController@systemUnderMaitenance')->name('home');

    // Attendance
    Route::get('/attendance', 'AttendanceController@index');
    Route::post('/attendance/refresh', 'AttendanceController@refreshAttendance');
    Route::get('/attendance/fetch/{user_id}', 'BiometricLogsController@employeeAttendance');
    Route::get('/getDeductions', 'AttendanceController@getDeductions');
    Route::get('/getBioAdjustments', 'AttendanceController@getBioAdjustments');
    // Route::post('/addAdjustment', 'AttendanceController@addAdjustment');
    Route::post('/deleteAdjustment', 'AttendanceController@deleteAdjustment');
    // Attendance History
    Route::get('/attendance_history/fetch', 'AttendanceController@attendance_history');
    // Absent Notice
    Route::get('/notice_slip/fetch', 'AbsentNoticesController@fetchNotices');
    Route::get('/notice_slip/getDetails', 'AbsentNoticesController@getNoticeDetails');
    Route::post('/notice_slip/create', 'AbsentNoticesController@store');
    Route::post('/notice_slip/updateDetails', 'AbsentNoticesController@updateNoticeDetails');
    Route::post('/notice_slip/cancelNotice', 'AbsentNoticesController@cancelNotice');
    Route::get('/notice_slip/absentToday', 'AbsentNoticesController@getAbsentToday');
    Route::get('/notice_slip/forApproval/fetch', 'AbsentNoticesController@noticesForApproval');
    Route::get('/getAbsentNotices', 'AbsentNoticesController@getAbsentNotices');
    Route::get('/printNotice/{id}', 'AbsentNoticesController@printNotice');
    Route::get('/countPendingNotices', 'AbsentNoticesController@countPendingNotices');
    Route::post('/notice_slip/cancelNotice_per_employee', 'AbsentNoticesController@cancelNotice_per_employee');
    // Gatepass
    Route::get('/gatepass/fetch', 'GatepassesController@fetchGatepasses');
    Route::get('/gatepass/getDetails', 'GatepassesController@getGatepassDetails');
    Route::post('/gatepass/create', 'GatepassesController@store');
    Route::post('/gatepass/updateDetails', 'GatepassesController@updateGatepassDetails');
    Route::post('/gatepass/cancelGatepass', 'GatepassesController@cancelGatepass');
    Route::get('/getGatepasses', 'GatepassesController@getGatepasses');
    Route::get('/printGatepass/{id}', 'GatepassesController@printGatepass');
    Route::get('/getUnreturnedGatepass', 'GatepassesController@getUnreturnedGatepass');
    Route::post('/updateUnreturnedGatepass', 'GatepassesController@updateUnreturnedGatepass');
    Route::get('/countPendingGatepass', 'GatepassesController@countPendingGatepass');

    Route::get('/gatepass/forApproval/fetch', 'GatepassesController@gatepassesForApproval');
    Route::post('/gatepass/updateStatus', 'GatepassesController@updateStatus');
    Route::get('/forApproval', 'HomeController@showForApproval');

    Route::get('/getShiftSchedules', 'ShiftsController@getShiftSchedules');
    Route::post('/addShiftSchedule', 'ShiftsController@addShiftSchedule');
    Route::post('/editShiftSchedule', 'ShiftsController@editShiftSchedule');
    Route::post('/deleteShiftSchedule', 'ShiftsController@deleteShiftSchedule');

    Route::get('/getShifts', 'ShiftsController@getShifts');
    Route::get('/getShiftDetails', 'ShiftsController@getShiftDetails');
    Route::post('/addShift', 'ShiftsController@addShift');
    Route::post('/editShift', 'ShiftsController@editShift');
    Route::post('/deleteShift', 'ShiftsController@deleteShift');
    // Applicants
    Route::get('/tabApplicants', 'ApplicantsController@showApplicants');
    Route::post('/tabAddApplicant', 'ApplicantsController@store');
    Route::post('/tabUpdateApplicant', 'ApplicantsController@update');
    Route::post('/tabDeleteApplicant', 'ApplicantsController@delete');
    //EXAMINATION
    Route::get('/examPanel', 'HomeController@showExamPanel');
    Route::get('/tabExams', 'HomeController@showExams')->name('client.tabExams');
    Route::get('/tabviewExamDetails/{id}', 'ExamsController@tabviewExamDetails');
    Route::post('/updateInstruction', 'ExamTypesController@editInstructions');
    Route::post('/tabAddExam', 'ExamsController@tabAddExam');
    Route::post('/examinee/updateStatus', 'ExamineesController@updateExamineeStatus');
    
    Route::post('/tabUpdateExam', 'ExamsController@tabUpdateExam');
    Route::post('/tabDeleteExam', 'ExamsController@tabDeleteExam');
    Route::get('/tabExaminees', 'HomeController@showExaminees')->name('client.tabExaminees');
    Route::get('/cancel_ongoing_exam/{id}', 'HomeController@cancelOnGoingExam');
    Route::post('/tabAddExaminee', 'ExamineesController@tabAddExaminee');
    Route::post('/tabUpdateExaminee', 'ExamineesController@tabUpdateExaminee');
    Route::post('/tabDeleteExaminee', 'ExamineesController@tabDeleteExaminee');
    Route::get('/tabExamReport', 'HomeController@showExaminationReport');
    Route::get('/viewExamResult/{examinee_id}/{exam_id}','ExaminationReportsController@showExamResults')->name('viewAnswers');
    Route::get('/printExamResult/{examinee_id}/{exam_id}','ExaminationReportsController@printExamResults');
    Route::get('/viewAnswers/{examinee_id}/{exam_id}/{exam_type_id}','ExaminationReportsController@showExamineeAnswers');
    Route::get('/checkAnswers/{examinee_id}/{exam_id}/{exam_type_id}','ExaminationReportsController@showAnswersForChecking');
    Route::post('/saveScore/{examinee_id}/{exam_id}','ExaminationReportsController@saveScore');

    //ADD QUESTION
    Route::post('/tabAddQuestion', 'QuestionsController@tabAddQuestion');
    Route::post('/tabUpdateQuestion', 'QuestionsController@tabUpdateQuestion');
    Route::post('/tabDeleteQuestion', 'QuestionsController@tabDeleteQuestion');

    //AJAX
    Route::get('/getQuestions', 'QuestionsController@getQuestions');
    Route::get('/getQuestionDetails', 'QuestionsController@getQuestionDetails');
    Route::get('/getExaminees', 'ExamineesController@getExaminees');
    Route::get('/getExams', 'ExamsController@getExams');
    Route::post('/addExam', 'ExamsController@addExam');
    Route::post('/addQuestion', 'QuestionsController@addQuestion');
    Route::post('/editQuestion', 'QuestionsController@editQuestion');
    Route::post('/deleteQuestion', 'QuestionsController@deleteQuestion');

    Route::post('/addExaminee', 'ExamineesController@addExaminee');
    Route::post('/editExaminee', 'ExamineesController@editExaminee');
    Route::post('/deleteExaminee', 'ExamineesController@deleteExaminee');

    Route::get('/calendar', 'HomeController@showCalendar');
    Route::get('/calendar/fetch', 'HomeController@getLeaves');  

    //EVALUATION MODULE
    Route::get('/getEvaluations', 'HomeController@getEvaluations');
    Route::post('/addEvaluation', 'EvaluationController@addEvaluation');
    Route::post('/editEvaluation', 'EvaluationController@editEvaluation');
    Route::post('/deleteEvaluation', 'EvaluationController@deleteEvaluation');



    // Exam
    Route::get('/exam/take/{id}','ClientExamsController@takeExam')->name('client.take_exam');
    Route::post('/exam/save','ClientExamsController@saveExam')->name('client.save_exam');
    Route::get('/exam_success/{examinee_id}','ClientExamsController@examSuccess')->name('client.exam_success');

    // Employee Profiles
    Route::get('/profiles/fetch','EmployeeProfilesController@fetchProfiles')->name('admin.fetch_employee_profiles');
    Route::get('/view_profile/{id}','EmployeeProfilesController@viewProfile')->name('client.view_employee_profile');
    Route::get('/reset_password/{id}','EmployeeProfilesController@resetEmployeePassword')->name('client.reset_password');
    Route::post('/update_profile','EmployeeProfilesController@updateEmployeeProfile')->name('client.update_profile');
    Route::post('/update_password','EmployeeProfilesController@changePassword')->name('client.updatePassword');

    //Emp Profile
    Route::post('/refreshAttendance/{id}', 'EmployeeProfilesController@refreshAttendance');
    Route::get('/employeeAttendance', 'EmployeeProfilesController@getAttendance');
    Route::get('/employeeNotices/{employee_id}', 'EmployeeProfilesController@getNotices');
    Route::get('/employeeGatepass/{employee_id}', 'EmployeeProfilesController@getGatepass');
    Route::get('/employeeLeaves/{employee_id}', 'EmployeeProfilesController@getLeaves');
    Route::get('/employeeExams/{employee_id}', 'EmployeeProfilesController@getExams');
    Route::get('/employeeEvaluations/{employee_id}', 'EmployeeProfilesController@getEvaluations');

    // Employee Profile Notice Slip
    Route::get('/approve_notice_slip/{notice_id}/{user_id}','EmployeeProfilesController@approveAbsentNotice')->name('client.approve_notice_slip');

    //Background Check Form
    Route::get('/backgroundcheck/{id}', 'BackgroundCheckController@backcheck');
    Route::get('/addbackquestion', 'BackgroundCheckController@addbackquestion');
    Route::post('/savequestion', 'BackgroundCheckController@savequestion');
    Route::post('/saveexam', 'BackgroundCheckController@saveexam');
    Route::get('/background_check/view_exam_panel/{id}', 'BackgroundCheckController@view_panel');
    Route::get('/background_check/tblQuestions', 'BackgroundCheckController@showquestiontable');
    Route::post('/crudAddQuestion', 'BackgroundCheckController@store');
    Route::post('/crudEditQuestion', 'BackgroundCheckController@update');
    Route::post('/crudDeleteQuestion', 'BackgroundCheckController@delete');
        
});

// A D M I N
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function(){

    // Attendance Adjustments
    Route::get('/getBioAdjustments', 'AttendanceController@getBioAdjustments');
    // Route::post('/addAdjustment', 'AttendanceController@addAdjustment');
    Route::post('/deleteAdjustment', 'AttendanceController@deleteAdjustment');
    Route::get('/attendance_adjustments', 'BiometricLogsController@showAttendanceAdjustments');
    // Route::get('/adj_monitoring', 'BiometricLogsController@attendanceAdjMonitoring');

    
    // Statistical Report
    Route::get('/statistical_report/{from_date}/{to_date}', 'BiometricLogsController@statisticalReport');
    Route::get('/report_date_filters', 'BiometricLogsController@reportDateFilter');
    Route::post('/updateEmployeesLogs', 'AttendanceController@updateEmployeesLogs');
    
    //Late Employee Report
    Route::get('/lateEmployees', 'AttendanceController@showLateEmployeeReport');
    Route::get('/getLateEmployees', 'AttendanceController@getLateEmployees');
    //Employees
    Route::get('/employees', 'EmployeesController@index');
    Route::post('/employee/create', 'EmployeesController@store');
    Route::post('/employee/update', 'EmployeesController@update');
    Route::post('/employee/delete', 'EmployeesController@delete');
    Route::post('/employee/reset_password', 'EmployeesController@reset_password');
    //Departments
    Route::get('/departments', 'DepartmentsController@index');
    Route::post('/department/create', 'DepartmentsController@store');
    Route::post('/department/update', 'DepartmentsController@update');
    Route::post('/department/delete', 'DepartmentsController@delete');
    //Designations
    Route::get('/designations', 'DesignationsController@index');
    Route::post('/designation/create', 'DesignationsController@store');
    Route::post('/designation/update', 'DesignationsController@update');
    Route::post('/designation/delete', 'DesignationsController@delete');
    //Branch
    Route::get('/branches', 'BranchController@index');
    Route::post('/branch/create', 'BranchController@store');
    Route::post('/branch/update', 'BranchController@update');
    Route::post('/branch/delete', 'BranchController@delete');
    //Holidays
    Route::get('/holidays', 'HolidayController@index');
    Route::post('/holiday/create', 'HolidayController@store');
    Route::post('/holiday/update', 'HolidayController@update');
    Route::post('/holiday/delete', 'HolidayController@delete');
    //Admins
    Route::get('/admins', 'EmployeesController@adminList');
    Route::post('/admin/create', 'EmployeesController@storeAdmin');
    Route::post('/admin/update', 'EmployeesController@updateAdmin');
    Route::post('/admin/delete', 'EmployeesController@deleteAdmin');
    Route::post('/admin/reset_password', 'EmployeesController@reset_admin_password');
    //Applicants
    Route::resource('/applicants', 'ApplicantsController');
    Route::post('/applicant/create', 'ApplicantsController@store');
    Route::post('/applicant/update', 'ApplicantsController@update');
    Route::post('/applicant/delete', 'ApplicantsController@delete');
    

    Route::get('/leave_calendar', 'AbsentNoticesController@showLeaveCalendar');
    Route::get('/leave_calendar/load', 'AbsentNoticesController@employeeLeaves');

    //Gatepasses
    Route::resource('/gatepasses', 'GatepassesController');
    Route::get('/gatepass/forApproval', 'GatepassesController@gatepassesForApproval')->name('admin.gatepasses_for_approval');
    Route::get('/gatepass/unreturned', 'GatepassesController@unreturnedItems')->name('admin.unreturned_items');

    Route::get('/notices_for_approval', 'AbsentNoticesController@noticesForApproval');

    //Shifts
    Route::resource('/shifts', 'ShiftsController');
    Route::post('/saveShift', 'ShiftsController@store')->name('admin.shift.create');
    Route::patch('/shifts/{id}', 'ShiftsController@update')->name('admin.shift.update');
    Route::delete('/shifts/delete/{id}', 'ShiftsController@destroy')->name('admin.shift.delete');

    //Leave Types
    Route::resource('/leave_types', 'LeaveTypesController');
    Route::post('/saveLeaveType', 'LeaveTypesController@store')->name('admin.leave_type.create');
    Route::patch('/leave_types/{id}', 'LeaveTypesController@update')->name('admin.leave_type.update');
    Route::delete('/leave_types/delete/{id}', 'LeaveTypesController@destroy')->name('admin.department.delete');

    //Approvers
    Route::resource('/approvers', 'ApproversController');
    Route::post('/saveApprover', 'ApproversController@store')->name('admin.approver.create');
    Route::patch('/approvers/{id}', 'ApproversController@update')->name('admin.approver.update');
    Route::delete('/approvers/delete/{id}', 'ApproversController@destroy')->name('admin.approver.delete');

    //Employee Leaves
    Route::resource('/employee_leaves', 'EmployeeLeavesController');
    Route::post('/saveEmployeeLeave', 'EmployeeLeavesController@store')->name('admin.employee_leave.create');
    Route::patch('/employee_leaves/{id}', 'EmployeeLeavesController@update')->name('admin.employee_leave.update');
    Route::delete('/employee_leaves/delete/{id}', 'EmployeeLeavesController@destroy')->name('admin.employee_leave.delete');
    Route::get('/leave_balances', 'EmployeeLeavesController@leaveBalances')->name('admin.leave_balances');

    //Absent Notices
    Route::resource('/absent_notices', 'AbsentNoticesController');
    
    //Items
    Route::resource('/items', 'ItemsController');
    Route::post('/saveItem', 'ItemsController@store')->name('admin.item.create');
    Route::patch('/items/{id}', 'ItemsController@update')->name('admin.item.update');
    Route::delete('/items/delete/{id}', 'ItemsController@destroy')->name('admin.item.delete');
    Route::get('/items_issued', 'ItemsController@issuedItems')->name('admin.items_issued');
    Route::post('/items_issued/create', 'ItemsController@issueItems')->name('admin.items_issued.create');
    Route::patch('/items_issued/update/{id}', 'ItemsController@updateIssuedItems')->name('admin.items_issued.update');

    //Exams
    Route::get('/exams/index','ExamsController@index')->name('admin.exams_index');
    Route::get('/exam/view/{id}','ExamsController@view')->name('admin.exam_view');
    Route::post('/exam/save','ExamsController@save')->name('admin.exam_save');
    Route::post('/exam/update','ExamsController@update')->name('admin.exam_update');
    Route::post('/exam/delete','ExamsController@delete')->name('admin.exam_delete');

    //Applicant Examinees
    Route::get('/applicant_examinees/index','ApplicantExamineesController@index')->name('admin.applicant_examinees_index');
    Route::post('/applicant_examinee/save','ApplicantExamineesController@save')->name('admin.applicant_examinee_save');
    Route::post('/applicant_examinee/update','ApplicantExamineesController@update')->name('admin.applicant_examinee_update');
    Route::post('/applicant_examinee/delete','ApplicantExamineesController@delete')->name('admin.applicant_examinee_delete');
});


Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function(){
    //Exam Types
    Route::get('/exam_types/index','ExamTypesController@index')->name('admin.exam_types_index');
    Route::post('/exam_type/save','ExamTypesController@save')->name('admin.exam_type_save');
    Route::post('/exam_type/update','ExamTypesController@update')->name('admin.exam_type_update');
    Route::post('/exam_type/delete','ExamTypesController@delete')->name('admin.exam_type_delete');

    //Exam Groups
    Route::get('/exam_groups/index','ExamGroupsController@index')->name('admin.exam_groups_index');
    Route::post('/exam_group/save','ExamGroupsController@save')->name('admin.exam_group_save');
    Route::post('/exam_group/update','ExamGroupsController@update')->name('admin.exam_group_update');
    Route::post('/exam_group/delete','ExamGroupsController@delete')->name('admin.exam_group_delete');
    
    // //Exams
    // Route::get('/exams/index','ExamsController@index')->name('admin.exams_index');
    // Route::get('/exam/view/{id}','ExamsController@view')->name('admin.exam_view');
    // Route::post('/exam/save','ExamsController@save')->name('admin.exam_save');
    // Route::post('/exam/update','ExamsController@update')->name('admin.exam_update');
    // Route::post('/exam/delete','ExamsController@delete')->name('admin.exam_delete');

    //Questions
    Route::get('/questions/index','QuestionsController@index')->name('admin.questions_index');
    Route::post('/question/save','QuestionsController@save')->name('admin.question_save');
    Route::post('/question/update','QuestionsController@update')->name('admin.question_update');
    Route::post('/question/delete','QuestionsController@delete')->name('admin.question_delete');

    //Exam Multiple Choice
    Route::post('/exam/multiple_choice/save', 'ExamsController@saveMultipleChoice')->name('admin.exam_multiplechoice_save');
    Route::post('/exam/multiple_choice/update', 'ExamsController@updateMultipleChoice')->name('admin.exam_multiplechoice_update');
    Route::post('/exam/multiple_choice/delete', 'ExamsController@deleteExamQuestion')->name('admin.exam_multiplechoice_delete');

    //Exam True or False
    Route::post('/exam/true_false/save', 'ExamsController@saveTrueFalse')->name('admin.exam_truefalse_save');
    Route::post('/exam/true_false/update', 'ExamsController@updateTrueFalse')->name('admin.exam_truefalse_update');
    Route::post('/exam/true_false/delete', 'ExamsController@deleteExamQuestion')->name('admin.exam_truefalse_delete');

    //Exam Essay
    Route::post('/exam/essay/save','ExamsController@saveEssay')->name('admin.exam_essay_save');
    Route::post('/exam/essay/update','ExamsController@updateEssay')->name('admin.exam_essay_update');
    Route::post('/exam/essay/delete','ExamsController@deleteExamQuestion')->name('admin.exam_essay_delete');

    //Exam Numerical
    //copt Multiple Choice

    //Exam Identification
    Route::post('/exam/identif/save','ExamsController@saveIdentif')->name('admin.exam_identif_save');
    Route::post('/exam/identif/update','ExamsController@updateIdentif')->name('admin.exam_identif_update');
    Route::post('/exam/identif/delete','ExamsController@didentifeleteExamQuestion')->name('admin.exam_essay_delete');


    //Examinees
    Route::get('/examinees/index', 'ExamineesController@index')->name('admin.examinees_index');
    Route::post('/examinee/save', 'ExamineesController@save')->name('admin.examinee_save');
    Route::post('/examinee/update', 'ExamineesController@update')->name('admin.examinee_update');
    Route::post('/examinee/delete', 'ExamineesController@delete')->name('admin.examinee_delete');
    Route::get('/get_users/{id}','ExamineesController@getUserByDepartment')->name('admin.get_user_by_dept');

    //Examinee Test Sheet
    Route::get('/examinee/test_sheet/{id}', 'ExamineesController@examineeTestSheet')->name('admin.examinee_testsheet');
    Route::post('/examinee/test_sheet/save', 'ExamineesController@saveExamineeTestSheet')->name('admin.examinee_testsheet_save');



    

    //Promotional Exams
    Route::get('/promotional_exams/index', 'PromotionalExamsController@index')->name('admin.promotional_exams_index');
    Route::post('/promotional_exams/save', 'PromotionalExamsController@save')->name('admin.promotional_exam_save');
    Route::post('/promotional_exams/update', 'PromotionalExamsController@update')->name('admin.promotional_exam_update');
    Route::post('/promotional_exams/delete', 'PromotionalExamsController@delete')->name('admin.promotional_exam_delete');

    //Examination Schedules
    Route::get('/examination_schedules/index','ExaminationSchedulesController@index')->name('admin.examination_schedules_index');
    Route::post('/examination_schedule/save','ExaminationSchedulesController@save')->name('admin.examination_schedule_save');
    Route::post('/examination_schedule/update','ExaminationSchedulesController@update')->name('admin.examination_schedule_update');
    Route::post('/examination_schedule/delete','ExaminationSchedulesController@delete')->name('admin.examination_schedule_delete');

    //Promotional Evaluation
    Route::get('/promotional_evaluation/index','PromotionalEvaluationsController@index')->name('admin.promotional_evaluations_index');

    //Examination Reports
    Route::get('/examination_reports/index','ExaminationReportsController@index')->name('admin.examination_reports_index');
    Route::get('/examination_report/show/{examinee_id}/{exam_id}','ExaminationReportsController@show')->name('admin.examination_report_show');
    Route::get('/examination_report/view/{examinee_id}/{exam_id}/{exam_type_id}','ExaminationReportsController@viewByExamType')->name('admin.exam_result_by_type_view');
    Route::get('/examination_report/update_score/{examinee_id}/{exam_id}/{exam_type_id}','ExaminationReportsController@updateScore')->name('admin.exam_result_score_update');

    Route::post('/examination_report/save_updated_score/{examinee_id}/{exam_id}','ExaminationReportsController@saveUpdatedScore')->name('admin.exam_result_save_updated_score');
});


Route::group(['middleware' => 'auth'], function(){
    Route::get('/getprofile', 'HomeController@getprofile');
    Route::get('/leave_analytics_filter', 'AbsentNoticesController@filterEmployeeLeaveAnalytics');
    Route::get('/leaveAllocationChart', 'AbsentNoticesController@leaveAllocationChart');
    Route::post('/updateEmployeesLogs', 'AttendanceController@updateEmployeesLogs');
    Route::get('/module/absent_notice_slip/leave_types_stats','AbsentNoticesController@leaveTypeStats');
    Route::get('/module/absent_notice_slip/absence_rate/{year}','AbsentNoticesController@absenceRate');
    Route::get('/module/absent_notice_slip/analytics','AbsentNoticesController@showAnalytics');
    Route::get('/module/absent_notice_slip/history','AbsentNoticesController@showNoticeHistory');
    Route::get('/module/absent_notice_slip/leave_analytics/{from_date}/{to_date}','AttendanceController@showStatisticalReport');

    // CLIENT LEAVE APPROVER CRUD
    Route::get('/module/absent_notice_slip/leave_approvers','ApproversController@showLeaveApprovers');
    Route::post('/client/leave_approver/create','ApproversController@approverCreate');
    Route::post('/client/leave_approver/update/{id}','ApproversController@approverUpdate');
    Route::post('/client/leave_approver/delete/{id}','ApproversController@approverDelete');
    // END CLIENT LEAVE APPROVER CRUD

    // CLIENT LEAVE BALANCE CRUD
    Route::get('/module/absent_notice_slip/leave_balances','EmployeeLeavesController@showLeaveBalances');
    Route::post('/client/leave_balance/create','EmployeeLeavesController@leaveBalanceCreate');
    Route::post('/client/leave_balance/update/{id}','EmployeeLeavesController@leaveBalanceUpdate');
    Route::post('/client/leave_balance/delete/{id}','EmployeeLeavesController@leaveBalanceDelete');
    Route::post('/client/employee_leave_balances/create','EmployeeLeavesController@employeeLeaveBalanceCreate');
    // END CLIENT LEAVE BALANCE CRUD

    // HR RECRUITMENT MODULE
    Route::get('/module/hr/analytics','HumanResourcesController@showAnalytics');
    Route::get('/module/hr/hiring_rate','HumanResourcesController@hiringRate');
    Route::get('/module/hr/applicants_chart','HumanResourcesController@applicantsChart');
    Route::get('/module/hr/employees_per_dept_chart','HumanResourcesController@employeesPerDeptChart');
    Route::get('/module/hr/job_source_chart','HumanResourcesController@jobSourceChart');

    // CLIENT APPLICANTS
    Route::get('/module/hr/applicants','ApplicantsController@showApplicantList');
    Route::get('/client/applicant/profile/{id}','ApplicantsController@showApplicantProfile');
    Route::get('/client/applicant/backgound_check/{id}','BackgroundCheckController@showBackGroundCheckForm');
    Route::post('/client/applicant/create','ApplicantsController@applicantCreate');
    Route::post('/client/applicant/update/{id}','ApplicantsController@applicantUpdate');
    Route::post('/client/applicant/delete/{id}','ApplicantsController@applicantDelete');

    Route::post('/updateApplicantStatus/{id}','ApplicantsController@updateApplicantStatus');
    Route::post('/hireApplicant/{id}','EmployeesController@hireApplicant');

    Route::get('/client/exams/applicant_exams','ExamsController@getExamList');
    Route::get('/client/hr/applicant_exam_details/{applicant_id}','ApplicantsController@getApplicantExamDetails');
    Route::post('/client/applicant/submitWizard','ApplicantsController@submitWizard');
    // END CLIENT APPLICANTS

    // CLIENT EMPLOYEES
    Route::get('/module/hr/employees','EmployeesController@showEmployees');
    Route::get('/getEmployeeDetails/{id}','EmployeesController@getEmployeeDetails');
    Route::post('/client/employee/create','EmployeesController@employeeCreate');
    Route::post('/client/employee/update/{id}','EmployeesController@employeeUpdate');
    Route::post('/client/employee/delete/{id}','EmployeesController@employeeDelete');
    Route::post('/client/employee/reset_password', 'EmployeesController@reset_password');
    Route::get('/client/employee/profile/{id}','EmployeesController@employeeProfile');
    Route::get('/showBirthdaysToday','EmployeesController@checkEmployeeBirthday');
    // END CLIENT EMPLOYEES

    // CLIENT BACKGROUND INVESTIGATION
    Route::get('/module/hr/background_check','BackgroundCheckController@showBackgroundInvQuestions');
    Route::post('/client/background_check/crudAddQuestion', 'BackgroundCheckController@store');
    Route::post('/client/background_check/crudEditQuestion', 'BackgroundCheckController@update');
    Route::post('/client/background_check/crudDeleteQuestion', 'BackgroundCheckController@delete');
    // END CLIENT BACKGROUND INVESTIGATION

    // CLIENT APPLICANT EXAMS
    Route::get('/module/hr/applicant_exams','ExamsController@showApplicantExams');
    Route::get('/client/hr/applicant_exams/{id}', 'ExamsController@showApplicantExamDetails');
    Route::get('/client/hr/applicant_exams/add_exam', 'ExamsController@addApplicantExam');
    Route::get('/client/hr/applicant_exams/update_exam', 'ExamsController@updateApplicantExam');
    Route::get('/client/hr/applicant_exams/delete_exam', 'ExamsController@deleteApplicantExam');
    Route::post('/client/hr/applicant_exams/insturctions/update', 'ExamTypesController@editInstructions');
    Route::post('/client/applicant_exams/add_question', 'QuestionsController@tabAddQuestion');
    Route::post('/client/applicant_exams/update_question', 'QuestionsController@tabUpdateQuestion');
    Route::post('/client/applicant_exams/delete_question', 'QuestionsController@tabDeleteQuestion');
    // END CLIENT APPLICANT EXAMS

    // CLIENT EXAM RESULTS
    Route::get('/module/hr/exam_results','ExaminationReportsController@showApplicantExamResult');
    Route::get('/client/exam_results/{examinee_id}/{exam_id}','ExaminationReportsController@showApplicantExamResults');
    Route::get('/client/exam_results/answers/{examinee_id}/{exam_id}/{exam_type_id}','ExaminationReportsController@showApplicantExamAnswers');
    Route::get('/client/exam_results/check_answers/{examinee_id}/{exam_id}/{exam_type_id}','ExaminationReportsController@showApplicantAnswersForChecking');
    Route::post('/client/exam_results/check_answers/update_score/{examinee_id}/{exam_id}','ExaminationReportsController@updateApplicantScore');
    // END CLIENT EXAM RESULTS

    //  DEPARTMENT HEAD LIST
    Route::get('/module/hr/department_head_list','DepartmentHeadListController@showlist');
    Route::post('/client/modules/human_resource/department_head/create','DepartmentHeadListController@store');
    Route::post('/client/modules/human_resource/department_head/update/{id}','DepartmentHeadListController@update');
    Route::post('/client/modules/human_resource/department_head/delete/{id}','DepartmentHeadListController@delete');

     // ATTENDANCE MODULE
    Route::get('/module/attendance/analytics','AttendanceController@showAnalytics');
    Route::get('/module/attendance/biometric_adjustments','AttendanceController@showAdjustmentMonitoring');
    Route::get('/module/attendance/history','AttendanceController@showAttendanceHistory');
    Route::get('/module/attendance/holiday_entry','HolidayController@indexholiday');//Holiday 
    Route::post('/module/attendance/holiday/create', 'HolidayController@storeholiday');//Holiday 
    Route::post('/module/attendance/holiday/update', 'HolidayController@updateholiday');//Holiday 
    Route::post('/module/attendance/holiday/delete', 'HolidayController@deleteholiday');//Holiday 
    Route::get('/module/attendance/late_employees','AttendanceController@showLateEmployees');
    Route::get('/getAbsentEmployees','AttendanceController@getAbsentEmployees');
    Route::get('/getPerfectAttendance','AttendanceController@getPerfectAttendance');
    Route::get('/adj_monitoring', 'BiometricLogsController@attendanceAdjustmentMonitoring');
    // Route::get('/adj_history', 'AttendanceController@attendanceAdjHistory');
    Route::get('/attendance_history', 'BiometricLogsController@attendanceHistory');
    Route::get('/lateEmployees', 'AttendanceController@getLateEmployees');
    Route::get('/getBioAdjustments', 'AttendanceController@getBioAdjustments');
    
    Route::post('/deleteAdjustment', 'AttendanceController@deleteAdjustment');
    // CLIENT SHIFTS
    Route::get('/module/attendance/employee_shifts','ShiftsController@showEmployeeShifts');
    Route::get('/client/attendance/employee_shifts/details/{group_id}','ShiftsController@getEmployeeShiftDetails');
    Route::post('/client/attendance/employee_shifts/create','ShiftsController@createShiftSchedule');
    Route::post('/client/attendance/employee_shifts/update','ShiftsController@updateShiftSchedule');
    Route::post('/client/attendance/employee_shifts/delete/{id}','ShiftsController@deleteShiftSchedule');
    Route::post('/client/attendance/special_shift/create','ShiftsController@createSpecialShift');
    Route::post('/client/attendance/special_shift/update/{id}','ShiftsController@updateSpecialShift');
    Route::post('/client/attendance/special_shift/delete/{id}','ShiftsController@deleteSpecialShift');
    
    // GATEPASS MODULE
    Route::get('/module/gatepass/analytics','GatepassesController@showAnalytics');
    Route::get('/module/gatepass/gatepass_per_dept_chart','GatepassesController@gatepassPerDeptChart');
    Route::get('/module/gatepass/purpose_rate_chart','GatepassesController@purposeRateChart');
    Route::get('/module/gatepass/gatepass_per_dept_chart','GatepassesController@gatepassPerDeptChart');
    Route::get('/getItemsIssuedtoEmployee/{user_id}','GatepassesController@getItemsIssuedtoEmployee');

    Route::get('/client/gatepass/history','GatepassesController@showGatepassHistory');
    Route::get('/client/gatepass/unreturned_gatepass','GatepassesController@showUnreturnedItems');
    Route::get('/client/gatepass/employee_accountability','GatepassesController@showEmployeeAccountability');
    Route::get('/client/gatepass/company_asset','GatepassesController@showCompanyAsset');
    Route::post('/addAsset', 'ItemAccountabilityController@storeAsset');
    Route::get('/getupdateItemsIssuedtoEmployee/{user_id}','GatepassesController@getupdateItemsIssuedtoEmployee');
    Route::post('/deleteAsset', 'GatepassesController@deleteAsset');

    // ANALYTICS
    Route::get('/client/analytics/attendance','AnalyticsController@showAttendanceAnalytics');
    Route::get('/client/analytics/hr','AnalyticsController@showHrAnalytics');
    Route::get('/client/analytics/notice_slip','AnalyticsController@showNoticesAnalytics');
    Route::get('/client/analytics/gatepass','AnalyticsController@showGatepassAnalytics');
    Route::get('/client/analytics/exam','ExamsController@showExamAnalytics');

    // ITEM ACCOUNTABILITY
    Route::get('/itemAccountability/{id}', 'ItemAccountabilityController@index');
    Route::post('/addItem', 'ItemAccountabilityController@store');
    Route::post('/editItem/{id}', 'ItemAccountabilityController@updateAsset');
    Route::post('/deleteItem', 'ItemAccountabilityController@delete');
    Route::get('/printItem/{id}', 'ItemAccountabilityController@print');
    Route::get('/getinfoeditmodal/{id}', 'ItemAccountabilityController@getinfoeditmodal');
    Route::get('/getInfo', 'GatepassesController@showAccountability');
    Route::get('/getCateg', 'GatepassesController@showCateg');

    // EVALUATION MODULE
    Route::get('/evaluation/department', 'EvaluationController@kpiPerDept');
    Route::get('/evaluation/employee_inputs', 'EvaluationController@showEmployeeInputsDept');
    Route::get('/evaluation/employee_inputs/form/{department_id}', 'EvaluationController@showEmployeeInputsForm');
    Route::get('/evaluation/employee_inputs/view/{department_id}', 'EvaluationController@viewEmployeeInputs');
    Route::get('/evaluation/setup/{department_id}', 'EvaluationController@setupKPI');
    Route::get('/evaluation/objectives', 'EvaluationController@showObjectives');
    Route::get('/evaluation/objective/view/{objective_id}', 'EvaluationController@viewObjectiveTree');
    Route::get('/evaluation/kpi', 'EvaluationController@showKPI');
    Route::get('/evaluation/appraisal', 'EvaluationController@showAppraisal');
    Route::get('/evaluation/appraisal/form/{user_id}/{from_month}/{from_year}/{to_month}/{to_year}/{purpose}', 'EvaluationController@showAppraisalForm');
    Route::get('/evaluation/appraisal/view/{id}', 'EvaluationController@viewAppraisal');
    Route::get('/evaluation/appraisal/print/{id}', 'EvaluationController@printAppraisal');

    Route::get('/getEmployees', 'EvaluationController@getEmployees');

    // Route::get('/evaluationTree/{department_id}', 'EvaluationController@evaluationTree');
    Route::get('/kpiTree/{department}', 'EvaluationController@kpiTree');
    Route::get('/getObjectives', 'EvaluationController@getObjectives');
    Route::get('/getKPI', 'EvaluationController@getKPI');
    Route::get('/qualitativeKpi', 'EvaluationController@qualitativeKpi');

    Route::get('/getObjectiveDetails/{id}', 'EvaluationController@getObjectiveDetails');
    Route::get('/getKpiDetails/{id}', 'EvaluationController@getKpiDetails');
    Route::get('/getMetricDetails/{id}', 'EvaluationController@getMetricDetails');

    Route::get('/getDesignations', 'EvaluationController@getDesignations');

    Route::post('/createObjective', 'EvaluationController@createObjective');
    Route::post('/updateObjective', 'EvaluationController@updateObjective');
    Route::post('/deleteObjective', 'EvaluationController@deleteObjective');

    Route::post('/createKPI', 'EvaluationController@createKPI');
    Route::post('/updateKPI', 'EvaluationController@updateKPI');
    Route::post('/deleteKPI', 'EvaluationController@deleteKPI');

    Route::post('/createMetrics', 'EvaluationController@createMetrics');
    Route::post('/updateMetric', 'EvaluationController@updateMetric');
    Route::post('/deleteMetric', 'EvaluationController@deleteMetric');

    Route::post('/createAppraisal', 'EvaluationController@createAppraisal');
    Route::post('/saveAppraisal', 'EvaluationController@saveAppraisal');
    Route::post('/updateAppraisal', 'EvaluationController@updateAppraisal');
    Route::post('/deleteAppraisal/{id}', 'EvaluationController@deleteAppraisal');

    Route::post('/updateEmpInputs', 'EvaluationController@updateEmpInputs');

    Route::get('/getEmpAppraisal/{user}', 'EvaluationController@getEmpAppraisal');
    Route::get('/getEmpKpiResult/{user}', 'EvaluationController@getEmpKpiResult');
    Route::get('/appraisal_result/{id}/view', 'EvaluationController@viewEmpAppraisalResult');

    Route::get('/getdatainput', 'EvaluationController@dataInput');
    Route::get('/tblDatainput', 'EvaluationController@tbldatainput');
    Route::post('/savedatainput', 'EvaluationController@savedataInput');

    Route::get('/evaluation/schedules', 'EvaluationController@showEvalSchedules');
    Route::get('/evaluation/schedule/{id}/view', 'EvaluationController@viewEvalSchedule');
    Route::post('/evaluation/schedule/new', 'EvaluationController@addEvalSchedule');
    Route::post('/evaluation/schedule/{id}/update', 'EvaluationController@updateEvalSchedule');
    Route::post('/evaluation/schedule/{id}/delete', 'EvaluationController@deleteEvalSchedule');

    Route::post('/createDataInputs', 'EvaluationController@createDataInputs');
    Route::post('/updateDataInput', 'EvaluationController@updateDataInput');
    Route::post('/deleteDataInput', 'EvaluationController@deleteDataInput');
    

    Route::get('/evaluation/kpi_result', 'EvaluationController@showKpiResult');
    Route::get('/getKpiResult', 'EvaluationController@getKpiResult');
    Route::post('/updateDataInputResult', 'EvaluationController@updateDataInputResult');

    Route::get('/kpiTree/{department}', 'EvaluationController@kpiTree');
    Route::get('/getemployeeperdept', 'EvaluationController@getemployeeperdept');


    //Overview per Department
    Route::get('/kpi_stats/accounting/index', 'EvaluationController@accounting_index');
    Route::get('/kpi_stats/accounting/index2', 'EvaluationController@accounting_index2');
    Route::get('/kpi_stats/engineering/index', 'EvaluationController@engineering_index');
    Route::get('/kpi_stats/it/index', 'EvaluationController@information_technology_index');
    Route::get('/kpi_stats/sales/index', 'EvaluationController@sales_index');
    Route::get('/kpi_stats/customer_service/index', 'EvaluationController@customer_service_index');
    Route::get('/kpi_stats/qa/index', 'EvaluationController@quality_assurance_index');
    Route::get('/kpi_stats/hr/index', 'EvaluationController@human_resource_index');
    Route::get('/kpi_stats/plant_services/index', 'EvaluationController@plant_services_index');
    Route::get('/kpi_stats/production/index', 'EvaluationController@production_index');
    Route::get('/kpi_stats/material_management/index', 'EvaluationController@materials_management_index');
    Route::get('/kpi_stats/material_management/index2', 'EvaluationController@purchasing_index');
    Route::get('/kpi_stats/management/index', 'EvaluationController@management_index');
    Route::get('/kpi_stats/marketing/index', 'EvaluationController@marketing_index');
    Route::get('/kpi_stats/assembly/index', 'EvaluationController@assembly_index');
    Route::get('/kpi_stats/fabrication/index', 'EvaluationController@fabrication_index');
    Route::get('/kpi_stats/traffic_and_distribution/index', 'EvaluationController@traffic_and_distribution_index');
    Route::get('/kpi_stats/painting/index', 'EvaluationController@painting_index');
    Route::get('/kpi_stats/filunited/index', 'EvaluationController@filunited_index');
    Route::get('/kpi_stats/production_planning/index', 'EvaluationController@production_planning_index');

    Route::get('/kpi_stats/getdata_it/kpi1', 'EvaluationController@kpi1_stats');
    Route::get('/kpi_stats/getdata_it/kpi2', 'EvaluationController@kpi2_stats');
    Route::get('/kpi_stats/getdata_it/kpi3', 'EvaluationController@kpi3_stats');
    Route::get('/kpi_stats/technicalLevel', 'EvaluationController@technicalLevel_stats');
    Route::get('/kpi/result/InformationTechnologydepartment', 'EvaluationController@viewKPIresult_IT');
    Route::get('/ITKpiResult/{department}', 'EvaluationController@IT_departmentKpiResult');

    Route::get('/AttandanceAdjUpdateall', 'AttendanceController@AttendanceAdjUpdateall');

    Route::get('/departmentKpiResult/{department}', 'EvaluationController@departmentKpiResult');



    Route::get('/AttendanceAdjUpdateall', 'AttendanceController@AttendanceAdjUpdateall');
    Route::post('/addAdjustment', 'AttendanceController@addAdjustment');
    Route::get('/adj_history', 'AttendanceController@attendanceAdjHistory');
    Route::get('/adj_monitoring', 'BiometricLogsController@attendanceAdjustmentMonitoring');

    Route::get('/employeeStats/{employee_id}', 'EvaluationController@empStats');
    Route::get('/employee_erp_data_inputs/{employee_id}', 'EvaluationController@empDataInputsERP');
    Route::get('/employee_manual_data_inputs/{employee_id}', 'EvaluationController@empDataInputsManualEntry');
});

// Engineering Department
Route::group(['prefix' => 'kpi_overview/engineering', 'middleware' => 'auth'], function(){
    // charts
    Route::get('/rfd_per_month/{year}', 'EvaluationController@rfdPerMonthChart');
    Route::get('/rfd_distribution/{year}', 'EvaluationController@rfdDistributionChart');
    Route::get('/rfd_timeliness/{year}', 'EvaluationController@rfdTimeliness');
    Route::get('/rfd_completion/{year}', 'EvaluationController@rfdCompletion');
    Route::get('/rfd_quality/{year}', 'EvaluationController@rfdQuality');
    Route::get('/rfd_success_rate/{year}', 'EvaluationController@rfdSuccessRate');
    Route::get('/rfd_totals', 'EvaluationController@rfdTotals');

    Route::get('/emp_data_inputs', 'EvaluationController@engineeringDataInputsERP');

    // page
    Route::get('/kpi_result', 'EvaluationController@engineeringKpiResult');
});

// Attendance
Route::group(['prefix' => 'attendance', 'middleware' => 'auth'], function(){
    Route::post('/update/{employee}', 'AttendanceController@updateAttendanceLogs');
    Route::get('/history/{employee}', 'AttendanceController@employeeAttendanceHistory');
    Route::get('/dashboard/{employee}', 'AttendanceController@employeeAttendanceDashboard');
    Route::get('/deductions/{employee}', 'AttendanceController@employeeLateDeductions');
});


Route::get('/kiosk/login', 'KioskController@loginForm');
Route::post('/kiosk/loguser', 'KioskController@kioskLogin');
Route::get('/kiosk/logoutuser', 'KioskController@kioskLogout');

Route::get('/kiosk/leave_calendar', 'KioskController@leaveCalendar');

Route::get('/kiosk/home', 'KioskController@index');
Route::get('/kiosk/notice', 'KioskController@noticeTransactSel');
Route::get('/kiosk/notice/leave_balance', 'KioskController@leaveBalance');
Route::get('/kiosk/notice/form', 'KioskController@noticeForm');
Route::get('/kiosk/notice/getnotice_table', 'KioskController@getnotice_history');
Route::get('/kiosk/notice/load_view_table', 'KioskController@notice_view_table');
Route::get('/kiosk/notice/cancel_slip', 'KioskController@cancel_notice');
Route::post('/kiosk/notice/form/insert', 'KioskController@storenotice');
Route::get('/kiosk/notice/view', 'KioskController@noticeView');
Route::get('/kiosk/notice/history', 'KioskController@noticeHistory');
Route::get('/kiosk/notice/getusershift', 'KioskController@user_shift');

Route::post('/kiosk/gatepass/form/insert', 'KioskController@storegatepass');
Route::get('/kiosk/gatepass', 'KioskController@gatepassTransactSel');
Route::get('/kiosk/gatepass/form', 'KioskController@gatepassForm');
Route::get('/kiosk/gatepass/view', 'KioskController@gatepassView');
Route::get('/kiosk/gatepass/history', 'KioskController@gatepassHistory');
Route::get('/kiosk/gatepass/load_view_table', 'KioskController@gatepass_view_table');
Route::get('/kiosk/gatepass/getgatepass_table', 'KioskController@getgatepass_history');
Route::get('/kiosk/gatepass/cancel_slip', 'KioskController@cancel_gatepass');
Route::get('/kiosk/gatepass/getUnreturned_gatepass_table', 'KioskController@getunreturned_history');
Route::get('/kiosk/gatepass/for_return', 'KioskController@gatepassUnreturned');

Route::get('/kiosk/attendance', 'KioskController@attendanceTransactSel');
Route::get('/kiosk/attendance/view', 'KioskController@attendanceView');
Route::get('/kiosk/attendance/summary', 'KioskController@attendanceSummary');

//ItineraryKiosk
Route::get('/kiosk/itinerary', 'KioskController@itineraryTransactSel');
Route::get('/kiosk/itinerary/form', 'KioskController@itineraryForm');
Route::get('/kiosk/itinerary/view/{id}', 'KioskController@itineraryView');
Route::get('/kiosk/itinerary/result/{id}', 'KioskController@itineraryResult');
Route::post('/kiosk/itinerary/cancel/{id}', 'KioskController@cancelItinerary');

Route::get('/kiosk/itinerary/history', 'KioskController@itineraryHistory');
Route::get('/kiosk/notice/get_Itinerary_table', 'KioskController@get_itineraryHistory');
Route::get('/kiosk/itinerary/result_table/{id}', 'KioskController@itineraryResult_table');

//ItineraryEssex
Route::get('/itinerary/fetch', 'ItineraryController@fetchItineraries');
Route::get('/itinerary/fetch/companion', 'ItineraryController@fetchItineraries_companion');

// AJAX
Route::get('/kiosk/attendance_logs/{employee}', 'KioskController@biometricLogs');
Route::get('/kiosk/employees/erp', 'KioskController@getEmployees');
Route::get('/kiosk/destinations/{doctype}', 'KioskController@getDocList');
Route::post('/kiosk/itinerary/save', 'KioskController@saveItinerary');

//Additional Cancel code per employee
Route::post('/notice_slip/cancelNotice_per_employee', 'AbsentNoticesController@cancelNotice_per_employee');

///Stepper
Route::get('/kiosk/stepper', 'KioskController@stepper_index');
Route::get('/stepper/notice', 'KioskController@stepper_notice');
Route::get('/stepper/gatepass', 'KioskController@stepper_gatepass');
Route::get('/stepper/itinerary', 'KioskController@stepper_itinerary');

Route::post('/kiosk/notice_employee/fetch', 'KioskController@fetch_employee_name');

// KPI DASHBOARD PER DEPARTMENT
// Engineering Department
Route::group(['prefix' => 'kpi_overview/engineering', 'middleware' => 'auth'], function(){
    // charts
    Route::get('/rfd_per_month/{year}', 'EvaluationController@rfdPerMonthChart');
    Route::get('/rfd_distribution/{year}', 'EvaluationController@rfdDistributionChart');
    Route::get('/rfd_timeliness/{year}', 'EvaluationController@rfdTimeliness');
    Route::get('/rfd_completion/{year}', 'EvaluationController@rfdCompletion');
    Route::get('/rfd_quality/{year}', 'EvaluationController@rfdQuality');
    Route::get('/rfd_success_rate/{year}', 'EvaluationController@rfdSuccessRate');
    Route::get('/rfd_totals', 'EvaluationController@rfdTotals');

    Route::get('/emp_data_inputs', 'EvaluationController@engineeringDataInputsERP');

    // page
    Route::get('/kpi_result', 'EvaluationController@engineeringKpiResult');
});

// Accounting Department
Route::group(['prefix' => 'kpi_overview/accounting', 'middleware' => 'auth'], function(){
    // chart
    Route::get('/sinv_per_month/{year}', 'EvaluationController@sinvPerMonthChart');
    Route::get('/pinv_per_month/{year}', 'EvaluationController@pinvPerMonthChart');
    Route::get('/top_expenses/{year}', 'EvaluationController@topExpenses');
    Route::get('/sinv_analysis/{year}', 'EvaluationController@salesInvAnalysis');
    Route::get('/pinv_analysis/{year}', 'EvaluationController@purchaseInvAnalysis');
    Route::get('/cash_receipt/{year}', 'EvaluationController@cashReceiptChart');
    Route::get('/cash_disbursement/{year}', 'EvaluationController@cashDisbursementChart');
    Route::get('/sinv_analysis_ctx/{year}', 'EvaluationController@salesInvAnalysisCtx');
    Route::get('/pinv_analysis_ctx/{year}', 'EvaluationController@purchaseInvAnalysisCtx');
    
    // page
    Route::get('/kpi_result', 'EvaluationController@accountingKpiResult');
});

// Sales Department
Route::group(['prefix' => 'kpi_overview/sales', 'middleware' => 'auth'], function(){
    Route::get('/totals', 'EvaluationController@sales_totals');
    Route::get('/opty_stats/{year}', 'EvaluationController@opportunityStats');
    Route::get('/sales_chart/{year}', 'EvaluationController@salesChart');
    
    // page
    Route::get('/kpi_result', 'EvaluationController@salesKpiResult');
});

// Traffic and Distribution Department
Route::group(['prefix' => 'kpi_overview/traffic_and_distribution', 'middleware' => 'auth'], function(){
    // chart
    Route::get('/delivery_completion/{year}', 'EvaluationController@deliveryCompletionChart');
    Route::get('/delivery_good_condition/{year}', 'EvaluationController@deliveryGoodConditionChart');
    Route::get('/non_delivery_dept_cause/{year}', 'EvaluationController@nonDeliveryDeptCausesChart');
    Route::get('/non_delivery_cust_cause/{year}', 'EvaluationController@nonDeliveryCustCausesChart');
    
    // page
    Route::get('/kpi_result', 'EvaluationController@trafficDistributionKpiResult');
});

// Customer Service Department
Route::group(['prefix' => 'kpi_overview/customer_service', 'middleware' => 'auth'], function(){
    // page
    Route::get('/kpi_result', 'EvaluationController@csKpiResult');
    Route::get('/get_kpi_CsStat1', 'EvaluationController@cskpi1_stat');
    Route::get('/get_kpi_CsStat2', 'EvaluationController@cskpi2_stat');
    Route::get('/within_department_fault_chart/{year}', 'EvaluationController@within_departmentfaultPie');
    Route::get('/not_within_department_fault_chart/{year}', 'EvaluationController@not_within_departmentfaultPie');

    Route::get('/cs_performace_chart/{year}', 'EvaluationController@csperformance_chart');
    Route::get('/get_total_sales', 'EvaluationController@salesTotal');
    Route::get('/get_csTimeliness/{year}', 'EvaluationController@salesOrder_timeliness');
});

// Quality Assurance Department
Route::group(['prefix' => 'kpi_overview/qa', 'middleware' => 'auth'], function(){
    
    // page
    Route::get('/kpi_result', 'EvaluationController@qaKpiResult');
});

// Plant Services Department
Route::group(['prefix' => 'kpi_overview/plant_services', 'middleware' => 'auth'], function(){
    
    // page
    Route::get('/kpi_result', 'EvaluationController@plantServicesKpiResult');
});

// Production Department
Route::group(['prefix' => 'kpi_overview/production', 'middleware' => 'auth'], function(){
    
    // page
    Route::get('/kpi_result', 'EvaluationController@productionKpiResult');
});

// Material Management Department
Route::group(['prefix' => 'kpi_overview/material_management', 'middleware' => 'auth'], function(){
    // Inventory
    Route::get('/inventory/totals', 'EvaluationController@materials_management_totals');
    Route::get('/inv_accuracy/{year}', 'EvaluationController@invAccuracyChart');
    Route::get('/item_movements/{year}', 'EvaluationController@itemMovements');
    Route::get('/item_class_movements/{year}', 'EvaluationController@itemClassMovements');    

    // Purchasing
    Route::get('/purchase_timeliness/{year}/{supplier_group}', 'EvaluationController@purchasesTimeliness');
    Route::get('/purchasing/totals', 'EvaluationController@purchasing_totals');
    
    // page
    Route::get('/kpi_result', 'EvaluationController@materialsManagementKpiResult');
});

// Management Department
Route::group(['prefix' => 'kpi_overview/management', 'middleware' => 'auth'], function(){
    
    // page
    Route::get('/kpi_result', 'EvaluationController@managementKpiResult');
});

// Marketing Department
Route::group(['prefix' => 'kpi_overview/marketing', 'middleware' => 'auth'], function(){
    
    // page
    Route::get('/kpi_result', 'EvaluationController@marketingKpiResult');
});

// Assembly Department
Route::group(['prefix' => 'kpi_overview/assembly', 'middleware' => 'auth'], function(){
    
    // page
    Route::get('/kpi_result', 'EvaluationController@assemblyKpiResult');
});

// Fabrication Department
Route::group(['prefix' => 'kpi_overview/fabrication', 'middleware' => 'auth'], function(){
    
    // page
    Route::get('/kpi_result', 'EvaluationController@fabricationKpiResult');
});

// Painting Department
Route::group(['prefix' => 'kpi_overview/painting', 'middleware' => 'auth'], function(){
    
    // page
    Route::get('/kpi_result', 'EvaluationController@paintingKpiResult');
});

// Filunited Department
Route::group(['prefix' => 'kpi_overview/filunited', 'middleware' => 'auth'], function(){
    
    // page
    Route::get('/kpi_result', 'EvaluationController@filunitedKpiResult');
});

// Production Planning Department
Route::group(['prefix' => 'kpi_overview/production_planning', 'middleware' => 'auth'], function(){
    
    // page
    Route::get('/kpi_result', 'EvaluationController@productionPlanningKpiResult');
});

// Human Resource Department
Route::group(['prefix' => 'kpi_overview/hr', 'middleware' => 'auth'], function(){
    
    // page
    Route::get('/kpi_result', 'EvaluationController@hrKpiResult');
    Route::get('/get_kpiStat1', 'EvaluationController@hrkpi1_stat');
});

// ONLINE EXAM - APPLICANT
Route::get('/applicant', 'ApplicantExaminationsController@enterExamCode');
Route::get('/oem/index/{examineeid}', 'ApplicantExaminationsController@applicantExamIndex');
Route::post('/oem/validate_exam_code', 'ApplicantExaminationsController@validateExamCode');
Route::post('/oem/update_answer', 'ApplicantExaminationsController@updateAnswer');
Route::post('/oem/update_examinee_status', 'ApplicantExaminationsController@updateExamineeStatus');
Route::get('/oem/preview_examinee_answer', 'ApplicantExaminationsController@preview_answers');
Route::get('/oem/save_exam_result/{examineeid}', 'ApplicantExaminationsController@save_examresult');
Route::get('/oem/examSubmitted/{id}','ApplicantExaminationsController@examSuccess');
Route::get('/oem/update_no_answer/{examineeid}', 'ApplicantExaminationsController@update_no_answer');

// ONLINE EXAM - EMPLOYEE
Route::post('/oem/employee/validateExamCode','ClientExamsController@validateExamCode');
Route::get('/oem/employee/index/{id}','ClientExamsController@takeexam');
Route::get('/check_ongoing_exam/{id}', 'ClientExamsController@checkOngoingStatus');
Route::post('/oem/employee/update_answer', 'ClientExamsController@updateAnswer');
Route::post('/oem/employee/update_examinee_status', 'ClientExamsController@updateExamineeStatus');
Route::get('/oem/employee/preview_examinee_answer', 'ClientExamsController@preview_answers');
Route::get('/oem/employee/save_exam_result/{examineeid}', 'ClientExamsController@save_examresult');
Route::get('/oem/employee/examSubmitted/{id}','ClientExamsController@examSuccess');
Route::get('/oem/employee/update_no_answer/{examineeid}', 'ClientExamsController@update_no_answer');

// HR Training
Route::get('/module/hr/training','HumanResourcesController@show_HR_training');
Route::get('/module/hr/training_profile/{id}','HumanResourcesController@training_profile');
Route::post('/module/hr/add_training','HumanResourcesController@add_HR_training');
Route::post('/module/hr/edit_training','HumanResourcesController@edit_HR_training');
Route::post('/module/hr/delete_training','HumanResourcesController@delete_HR_training');
Route::get('/module/hr/training/employee_list','HumanResourcesController@Employee_list');
Route::get('/module/hr/training/employee_list_edit','HumanResourcesController@Employee_list_edit');
Route::get('/module/hr/training_details/{id}','HumanResourcesController@edit_training_details');

Route::get('/notice_slip/updateStatus', 'AbsentNoticesController@updateStatus');