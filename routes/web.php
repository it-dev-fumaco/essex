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

use App\Http\Controllers\AbsentNoticesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ApplicantExaminationsController;
use App\Http\Controllers\ApplicantExamineesController;
use App\Http\Controllers\ApplicantsController;
use App\Http\Controllers\ApproversController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BackgroundCheckController;
use App\Http\Controllers\BiometricLogsController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CalendarViewController;
use App\Http\Controllers\PortalCalendarController;
use App\Http\Controllers\ClientExamsController;
use App\Http\Controllers\DepartmentHeadListController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\DesignationsController;
use App\Http\Controllers\EmployeeLeavesController;
use App\Http\Controllers\EmployeeProfilesController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ExamGroupsController;
use App\Http\Controllers\ExaminationReportsController;
use App\Http\Controllers\ExaminationSchedulesController;
use App\Http\Controllers\ExamineesController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\ExamTypesController;
use App\Http\Controllers\GatepassesController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HumanResourcesController;
use App\Http\Controllers\ItemAccountabilityController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\LeaveTypesController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\PromotionalEvaluationsController;
use App\Http\Controllers\PromotionalExamsController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShiftsController;
use App\Http\Controllers\TestingEnvironmentController;
use Illuminate\Support\Facades\Route;

Route::get('/local', [TestingEnvironmentController::class, 'local_login']);
Route::post('/updateExamineeStatus', [ExamineesController::class, 'updateExamineeStatus']);

// E M P L O Y E E  P O R T A L
Route::get('/', [PortalController::class, 'index'])->name('portal');
Route::get('/tbl_manuals', [PortalController::class, 'load_manuals']);
Route::get('/email_logs', [PortalController::class, 'email_logs']);
Route::get('/resend_email/{id}', [PortalController::class, 'resend_email']);

// G A L L E R Y
Route::get('/gallery', [PortalController::class, 'showGallery']);
Route::get('/gallery/fetchAlbums', [PortalController::class, 'fetchAlbums']);

// M A N U A L S
Route::get('/manuals', [PortalController::class, 'showManuals']);
Route::get('/article/{slug}', [PortalController::class, 'showArticle']);
Route::get('/services/directory', [PortalController::class, 'phoneEmailDirectory'])
    ->withoutMiddleware('auth');
Route::get('/services/internet', [PortalController::class, 'showInternet']);
Route::get('/services/email', [PortalController::class, 'email']);
Route::get('/services/system', [PortalController::class, 'system']);
Route::get('/gallery/album/{id}', [PortalController::class, 'showAlbum']);
Route::get('/historical_milestones', [PortalController::class, 'showHistoricalMilestones']);
// Route::get('/manuals', [PortalController::class, 'showManuals']);
Route::get('/policies', [PortalController::class, 'showMemorandum']);
Route::get('/updates', [PortalController::class, 'showUpdates']);
Route::get('/itguidelines', [PortalController::class, 'showitGuidelines']);

Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/exam', [HomeController::class, 'takeExam']);

Route::get('/userLogout', [LoginController::class, 'userLogout'])->name('user.logout');
Route::post('/userLogin', [LoginController::class, 'userLogin']);

// //Applicant Examination
// Route::get('/applicant',[ApplicantExaminationsController::class, 'index'])->name('client.applicant');
// Route::post('/applicant/examinee',[ApplicantExaminationsController::class, 'show'])->name('client.appli_examinee');
// Route::get('/applicant/takeExam/{id}',[ApplicantExaminationsController::class, 'takeExam'])->name('applicant.take_exam');
// Route::post('/applicant/saveExam',[ApplicantExaminationsController::class, 'saveExam'])->name('applicant.save_exam');
Route::get('/applicant/examSubmitted/{id}', [ApplicantExaminationsController::class, 'examSuccess'])->name('applicant.exam_success');

// Auth routes (Laravel 12: no laravel/ui; define explicitly to preserve route names)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'userLogin']);
Route::post('logout', [LoginController::class, 'userLogout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [AdminLoginController::class, 'adminLogout'])->name('admin.logout');
});
Route::post('/notice_slip/updateStatus', [AbsentNoticesController::class, 'updateStatus']);

// Public Calendar page (role-aware; shows OOO only when logged in)
Route::get('/calendar', [PortalCalendarController::class, 'index']);
Route::get('/calendar/events', [PortalCalendarController::class, 'events']);

// C L I E N T
Route::middleware('auth')->group(function () {

    // HR (Client Designations)
    Route::get('/module/hr/designation', [DesignationsController::class, 'hr_desig_index']);
    Route::post('/module/hr/designation/create', [DesignationsController::class, 'store']);
    Route::post('/module/hr/designation/update', [DesignationsController::class, 'update']);
    Route::post('/module/hr/designation/delete', [DesignationsController::class, 'delete']);

    // calendar (legacy leave calendar endpoints)
    Route::post('/addEvent', [CalendarViewController::class, 'store']);
    Route::get('/calendar/fetch', [HomeController::class, 'getLeaves']);
    Route::get('/holidays', [CalendarViewController::class, 'getholidays']);
    Route::get('/bday', [CalendarViewController::class, 'employeeBirthdates']);

    Route::post('/updateAllBiologs', [AttendanceController::class, 'updateEmployeesLogs']);

    Route::post('/employee/update', [EmployeesController::class, 'update']);
    Route::post('/employee/reset_password', [EmployeesController::class, 'reset_password']);
    Route::post('/employee/reset_leaves', [EmployeesController::class, 'reset_leaves']);

    // Gallery
    Route::post('/addAlbum', [PortalController::class, 'addAlbum']);
    Route::post('/editAlbum', [PortalController::class, 'editAlbum']);
    Route::post('/deleteAlbum', [PortalController::class, 'deleteAlbum']);
    // Posts
    Route::post('/addPost', [PortalController::class, 'addPost']);
    Route::post('/updatePost', [PortalController::class, 'updatePost']);
    Route::post('/deletePost', [PortalController::class, 'deletePost']);
    // Policy
    Route::post('/addPolicy', [PortalController::class, 'addPolicy']);
    Route::post('/editPolicy', [PortalController::class, 'editPolicy']);
    Route::post('/deletePolicy', [PortalController::class, 'deletePolicy']);

    Route::post('/gallery/album/uploadImages', [PortalController::class, 'uploadImage']);
    Route::delete('/image/delete/{id}', [PortalController::class, 'deleteImage']);
    Route::post('/setAsFeatured', [PortalController::class, 'setAsFeatured']);

    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home'); // comment this line on maintenance

    // Under Maintenance
    // Route::get('/home1', [HomeController::class, 'index'])->name('home1');
    // Route::get('/home', [HomeController::class, 'systemUnderMaitenance'])->name('home');

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::post('/attendance/refresh', [AttendanceController::class, 'refreshAttendance']);
    Route::get('/attendance/fetch/{user_id}', [BiometricLogsController::class, 'employeeAttendance']);
    Route::get('/getDeductions', [AttendanceController::class, 'getDeductions']);
    Route::get('/getBioAdjustments', [AttendanceController::class, 'getBioAdjustments']);
    // Route::post('/addAdjustment', [AttendanceController::class, 'addAdjustment']);
    Route::post('/deleteAdjustment', [AttendanceController::class, 'deleteAdjustment']);
    // Attendance History
    Route::get('/attendance_history/fetch', [AttendanceController::class, 'attendance_history']);
    // Absent Notice
    Route::get('/notice_slip/fetch', [AbsentNoticesController::class, 'fetchNotices']);
    Route::get('/notice_slip/getDetails', [AbsentNoticesController::class, 'getNoticeDetails']);
    Route::post('/notice_slip/create', [AbsentNoticesController::class, 'store']);
    Route::post('/notice_slip/resend-manager-notification', [AbsentNoticesController::class, 'resendManagerNotification']);
    Route::post('/notice_slip/updateDetails', [AbsentNoticesController::class, 'updateNoticeDetails']);
    Route::post('/notice_slip/cancelNotice', [AbsentNoticesController::class, 'cancelNotice']);
    Route::get('/notice_slip/absentToday', [AbsentNoticesController::class, 'getAbsentToday']);
    Route::get('/notice_slip/forApproval/fetch', [AbsentNoticesController::class, 'noticesForApproval']);
    Route::get('/getAbsentNotices', [AbsentNoticesController::class, 'getAbsentNotices']);
    Route::get('/printNotice/{id}', [AbsentNoticesController::class, 'printNotice']);
    Route::get('/countPendingNotices', [AbsentNoticesController::class, 'countPendingNotices']);
    Route::post('/notice_slip/cancelNotice_per_employee', [AbsentNoticesController::class, 'cancelNotice_per_employee']);
    // Gatepass
    Route::get('/gatepass/fetch', [GatepassesController::class, 'fetchGatepasses']);
    Route::get('/gatepass/getDetails', [GatepassesController::class, 'getGatepassDetails']);
    Route::post('/gatepass/create', [GatepassesController::class, 'store']);
    Route::post('/gatepass/updateDetails', [GatepassesController::class, 'updateGatepassDetails']);
    Route::post('/gatepass/cancelGatepass', [GatepassesController::class, 'cancelGatepass']);
    Route::get('/getGatepasses', [GatepassesController::class, 'getGatepasses']);
    Route::get('/printGatepass/{id}', [GatepassesController::class, 'printGatepass']);
    Route::get('/getUnreturnedGatepass', [GatepassesController::class, 'getUnreturnedGatepass']);
    Route::post('/updateUnreturnedGatepass', [GatepassesController::class, 'updateUnreturnedGatepass']);
    Route::get('/countPendingGatepass', [GatepassesController::class, 'countPendingGatepass']);

    Route::get('/gatepass/forApproval/fetch', [GatepassesController::class, 'gatepassesForApproval']);
    Route::post('/gatepass/updateStatus', [GatepassesController::class, 'updateStatus']);
    Route::get('/forApproval', [HomeController::class, 'showForApproval']);

    Route::get('/getShiftSchedules', [ShiftsController::class, 'getShiftSchedules']);
    Route::post('/addShiftSchedule', [ShiftsController::class, 'addShiftSchedule']);
    Route::post('/editShiftSchedule', [ShiftsController::class, 'editShiftSchedule']);
    Route::post('/deleteShiftSchedule', [ShiftsController::class, 'deleteShiftSchedule']);

    Route::get('/getShifts', [ShiftsController::class, 'getShifts']);
    Route::get('/getShiftDetails', [ShiftsController::class, 'getShiftDetails']);
    Route::post('/addShift', [ShiftsController::class, 'addShift']);
    Route::post('/editShift', [ShiftsController::class, 'editShift']);
    Route::post('/deleteShift', [ShiftsController::class, 'deleteShift']);
    // Applicants
    Route::get('/tabApplicants', [ApplicantsController::class, 'showApplicants']);
    Route::post('/tabAddApplicant', [ApplicantsController::class, 'store']);
    Route::post('/tabUpdateApplicant', [ApplicantsController::class, 'update']);
    Route::post('/tabDeleteApplicant', [ApplicantsController::class, 'delete']);
    // EXAMINATION
    Route::get('/examPanel', [HomeController::class, 'showExamPanel']);
    Route::get('/tabExams', [HomeController::class, 'showExams'])->name('client.tabExams');
    Route::get('/tabviewExamDetails/{id}', [ExamsController::class, 'tabviewExamDetails']);
    Route::post('/updateInstruction', [ExamTypesController::class, 'editInstructions']);
    Route::post('/tabAddExam', [ExamsController::class, 'tabAddExam']);
    Route::post('/examinee/updateStatus', [ExamineesController::class, 'updateExamineeStatus']);

    Route::post('/tabUpdateExam', [ExamsController::class, 'tabUpdateExam']);
    Route::post('/tabDeleteExam', [ExamsController::class, 'tabDeleteExam']);
    Route::get('/tabExaminees', [HomeController::class, 'showExaminees'])->name('client.tabExaminees');
    Route::get('/cancel_ongoing_exam/{id}', [HomeController::class, 'cancelOnGoingExam']);
    Route::post('/tabAddExaminee', [ExamineesController::class, 'tabAddExaminee']);
    Route::post('/tabUpdateExaminee', [ExamineesController::class, 'tabUpdateExaminee']);
    Route::post('/tabDeleteExaminee', [ExamineesController::class, 'tabDeleteExaminee']);
    Route::get('/tabExamReport', [HomeController::class, 'showExaminationReport']);
    Route::get('/viewExamResult/{examinee_id}/{exam_id}', [ExaminationReportsController::class, 'showExamResults'])->name('viewAnswers');
    Route::get('/printExamResult/{examinee_id}/{exam_id}', [ExaminationReportsController::class, 'printExamResults']);
    Route::get('/viewAnswers/{examinee_id}/{exam_id}/{exam_type_id}', [ExaminationReportsController::class, 'showExamineeAnswers']);
    Route::get('/checkAnswers/{examinee_id}/{exam_id}/{exam_type_id}', [ExaminationReportsController::class, 'showAnswersForChecking']);
    Route::post('/saveScore/{examinee_id}/{exam_id}', [ExaminationReportsController::class, 'saveScore']);

    // ADD QUESTION
    Route::post('/tabAddQuestion', [QuestionsController::class, 'tabAddQuestion']);
    Route::post('/tabUpdateQuestion', [QuestionsController::class, 'tabUpdateQuestion']);
    Route::post('/tabDeleteQuestion', [QuestionsController::class, 'tabDeleteQuestion']);

    // AJAX
    Route::get('/getQuestions', [QuestionsController::class, 'getQuestions']);
    Route::get('/getQuestionDetails', [QuestionsController::class, 'getQuestionDetails']);
    Route::get('/getExaminees', [ExamineesController::class, 'getExaminees']);
    Route::get('/getExams', [ExamsController::class, 'getExams']);
    Route::post('/addExam', [ExamsController::class, 'addExam']);
    Route::post('/addQuestion', [QuestionsController::class, 'addQuestion']);
    Route::post('/editQuestion', [QuestionsController::class, 'editQuestion']);
    Route::post('/deleteQuestion', [QuestionsController::class, 'deleteQuestion']);

    Route::post('/addExaminee', [ExamineesController::class, 'addExaminee']);
    Route::post('/editExaminee', [ExamineesController::class, 'editExaminee']);
    Route::post('/deleteExaminee', [ExamineesController::class, 'deleteExaminee']);

    // Route::get('/calendar', [HomeController::class, 'showCalendar']);
    // Route::get('/calendar/fetch', [HomeController::class, 'getLeaves']);

    // EVALUATION MODULE
    Route::get('/getEvaluations', [HomeController::class, 'getEvaluations']);
    Route::post('/addEvaluation', [EvaluationController::class, 'addEvaluation']);
    Route::post('/editEvaluation', [EvaluationController::class, 'editEvaluation']);
    Route::post('/deleteEvaluation', [EvaluationController::class, 'deleteEvaluation']);

    // Exam
    Route::get('/exam/take/{id}', [ClientExamsController::class, 'takeExam'])->name('client.take_exam');
    Route::post('/exam/save', [ClientExamsController::class, 'saveExam'])->name('client.save_exam');
    Route::get('/exam_success/{examinee_id}', [ClientExamsController::class, 'examSuccess'])->name('client.exam_success');

    // Employee Profiles
    Route::get('/profiles/fetch', [EmployeeProfilesController::class, 'fetchProfiles'])->name('admin.fetch_employee_profiles');
    Route::get('/view_profile/{id}', [EmployeeProfilesController::class, 'viewProfile'])->name('client.view_employee_profile');
    Route::get('/reset_password/{id}', [EmployeeProfilesController::class, 'resetEmployeePassword'])->name('client.reset_password');
    Route::post('/update_profile', [EmployeeProfilesController::class, 'updateEmployeeProfile'])->name('client.update_profile');
    Route::post('/update_password', [EmployeeProfilesController::class, 'changePassword'])->name('client.updatePassword');
    Route::post('/profile/personal-details', [EmployeeProfilesController::class, 'updatePersonalDetails'])->name('client.profile.update_personal_details');

    // Employee Directory (profile modal)
    Route::get('/services/directory/profile/{user_id}', [PortalController::class, 'directoryProfile'])
        ->whereNumber('user_id')
        ->withoutMiddleware('auth');

    // Emp Profile
    Route::post('/refreshAttendance/{id}', [EmployeeProfilesController::class, 'refreshAttendance']);
    Route::get('/employeeAttendance', [EmployeeProfilesController::class, 'getAttendance']);
    Route::get('/employeeNotices/{employee_id}', [EmployeeProfilesController::class, 'getNotices']);
    Route::get('/employeeGatepass/{employee_id}', [EmployeeProfilesController::class, 'getGatepass']);
    Route::get('/employeeLeaves/{employee_id}', [EmployeeProfilesController::class, 'getLeaves']);
    Route::get('/employeeExams/{employee_id}', [EmployeeProfilesController::class, 'getExams']);
    Route::get('/employeeEvaluations/{employee_id}', [EmployeeProfilesController::class, 'getEvaluations']);

    // Employee Profile Notice Slip
    Route::get('/approve_notice_slip/{notice_id}/{user_id}', [EmployeeProfilesController::class, 'approveAbsentNotice'])->name('client.approve_notice_slip');

    // Background Check Form
    Route::get('/backgroundcheck/{id}', [BackgroundCheckController::class, 'backcheck']);
    Route::get('/addbackquestion', [BackgroundCheckController::class, 'addbackquestion']);
    Route::post('/savequestion', [BackgroundCheckController::class, 'savequestion']);
    Route::post('/saveexam', [BackgroundCheckController::class, 'saveexam']);
    Route::get('/background_check/view_exam_panel/{id}', [BackgroundCheckController::class, 'view_panel']);
    Route::get('/background_check/tblQuestions', [BackgroundCheckController::class, 'showquestiontable']);
    Route::post('/crudAddQuestion', [BackgroundCheckController::class, 'store']);
    Route::post('/crudEditQuestion', [BackgroundCheckController::class, 'update']);
    Route::post('/crudDeleteQuestion', [BackgroundCheckController::class, 'delete']);

});

// A D M I N
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    // Attendance Adjustments
    Route::get('/getBioAdjustments', [AttendanceController::class, 'getBioAdjustments']);
    // Route::post('/addAdjustment', [AttendanceController::class, 'addAdjustment']);
    Route::post('/deleteAdjustment', [AttendanceController::class, 'deleteAdjustment']);
    Route::get('/attendance_adjustments', [BiometricLogsController::class, 'showAttendanceAdjustments']);
    // Route::get('/adj_monitoring', [BiometricLogsController::class, 'attendanceAdjMonitoring']);

    // Statistical Report
    Route::get('/statistical_report/{from_date}/{to_date}', [BiometricLogsController::class, 'statisticalReport']);
    Route::get('/report_date_filters', [BiometricLogsController::class, 'reportDateFilter']);
    Route::post('/updateEmployeesLogs', [AttendanceController::class, 'updateEmployeesLogs']);

    // Late Employee Report
    Route::get('/lateEmployees', [AttendanceController::class, 'showLateEmployeeReport']);
    Route::get('/getLateEmployees', [AttendanceController::class, 'getLateEmployees']);
    // Employees
    Route::get('/employees', [EmployeesController::class, 'index']);
    Route::post('/employee/create', [EmployeesController::class, 'store']);
    Route::post('/employee/update', [EmployeesController::class, 'update']);
    Route::post('/employee/delete', [EmployeesController::class, 'delete']);
    Route::post('/employee/reset_password', [EmployeesController::class, 'reset_password']);
    // Departments
    Route::get('/departments', [DepartmentsController::class, 'index']);
    Route::post('/department/create', [DepartmentsController::class, 'store']);
    Route::post('/department/update', [DepartmentsController::class, 'update']);
    Route::post('/department/delete', [DepartmentsController::class, 'delete']);
    // Designations
    Route::get('/designations', [DesignationsController::class, 'index']);
    Route::post('/designation/create', [DesignationsController::class, 'store']);
    Route::post('/designation/update', [DesignationsController::class, 'update']);
    Route::post('/designation/delete', [DesignationsController::class, 'delete']);
    // Branch
    Route::get('/branches', [BranchController::class, 'index']);
    Route::post('/branch/create', [BranchController::class, 'store']);
    Route::post('/branch/update', [BranchController::class, 'update']);
    Route::post('/branch/delete', [BranchController::class, 'delete']);
    // Holidays
    Route::get('/holidays', [HolidayController::class, 'index']);
    Route::post('/holiday/create', [HolidayController::class, 'store']);
    Route::post('/holiday/update', [HolidayController::class, 'update']);
    Route::post('/holiday/delete', [HolidayController::class, 'delete']);
    // Admins
    Route::get('/admins', [EmployeesController::class, 'adminList']);
    Route::post('/admin/create', [EmployeesController::class, 'storeAdmin']);
    Route::post('/admin/update', [EmployeesController::class, 'updateAdmin']);
    Route::post('/admin/delete', [EmployeesController::class, 'deleteAdmin']);
    Route::post('/admin/reset_password', [EmployeesController::class, 'reset_admin_password']);
    // Applicants
    Route::resource('/applicants', ApplicantsController::class);
    Route::post('/applicant/create', [ApplicantsController::class, 'store']);
    Route::post('/applicant/update', [ApplicantsController::class, 'update']);
    Route::post('/applicant/delete', [ApplicantsController::class, 'delete']);

    Route::get('/leave_calendar', [AbsentNoticesController::class, 'showLeaveCalendar']);
    Route::get('/leave_calendar/load', [AbsentNoticesController::class, 'employeeLeaves']);

    // Gatepasses
    Route::resource('/gatepasses', GatepassesController::class);
    Route::get('/gatepass/forApproval', [GatepassesController::class, 'gatepassesForApproval'])->name('admin.gatepasses_for_approval');
    Route::get('/gatepass/unreturned', [GatepassesController::class, 'unreturnedItems'])->name('admin.unreturned_items');

    Route::get('/notices_for_approval', [AbsentNoticesController::class, 'noticesForApproval']);

    // Shifts
    Route::resource('/shifts', ShiftsController::class);
    Route::post('/saveShift', [ShiftsController::class, 'store'])->name('admin.shift.create');
    Route::patch('/shifts/{id}', [ShiftsController::class, 'update'])->name('admin.shift.update');
    Route::delete('/shifts/delete/{id}', [ShiftsController::class, 'destroy'])->name('admin.shift.delete');

    // Leave Types
    Route::resource('/leave_types', LeaveTypesController::class);
    Route::post('/saveLeaveType', [LeaveTypesController::class, 'store'])->name('admin.leave_type.create');
    Route::patch('/leave_types/{id}', [LeaveTypesController::class, 'update'])->name('admin.leave_type.update');
    Route::delete('/leave_types/delete/{id}', [LeaveTypesController::class, 'destroy'])->name('admin.department.delete');

    // Approvers
    Route::resource('/approvers', ApproversController::class);
    Route::post('/saveApprover', [ApproversController::class, 'store'])->name('admin.approver.create');
    Route::patch('/approvers/{id}', [ApproversController::class, 'update'])->name('admin.approver.update');
    Route::delete('/approvers/delete/{id}', [ApproversController::class, 'destroy'])->name('admin.approver.delete');

    // Employee Leaves
    Route::resource('/employee_leaves', EmployeeLeavesController::class);
    Route::post('/saveEmployeeLeave', [EmployeeLeavesController::class, 'store'])->name('admin.employee_leave.create');
    Route::patch('/employee_leaves/{id}', [EmployeeLeavesController::class, 'update'])->name('admin.employee_leave.update');
    Route::delete('/employee_leaves/delete/{id}', [EmployeeLeavesController::class, 'destroy'])->name('admin.employee_leave.delete');
    Route::get('/leave_balances', [EmployeeLeavesController::class, 'leaveBalances'])->name('admin.leave_balances');

    // Absent Notices
    Route::resource('/absent_notices', AbsentNoticesController::class);

    // Items
    Route::resource('/items', ItemsController::class);
    Route::post('/saveItem', [ItemsController::class, 'store'])->name('admin.item.create');
    Route::patch('/items/{id}', [ItemsController::class, 'update'])->name('admin.item.update');
    Route::delete('/items/delete/{id}', [ItemsController::class, 'destroy'])->name('admin.item.delete');
    Route::get('/items_issued', [ItemsController::class, 'issuedItems'])->name('admin.items_issued');
    Route::post('/items_issued/create', [ItemsController::class, 'issueItems'])->name('admin.items_issued.create');
    Route::patch('/items_issued/update/{id}', [ItemsController::class, 'updateIssuedItems'])->name('admin.items_issued.update');

    // Exams
    Route::get('/exams/index', [ExamsController::class, 'index'])->name('admin.exams_index');
    Route::get('/exam/view/{id}', [ExamsController::class, 'view'])->name('admin.exam_view');
    Route::post('/exam/save', [ExamsController::class, 'save'])->name('admin.exam_save');
    Route::post('/exam/update', [ExamsController::class, 'update'])->name('admin.exam_update');
    Route::post('/exam/delete', [ExamsController::class, 'delete'])->name('admin.exam_delete');

    // Applicant Examinees
    Route::get('/applicant_examinees/index', [ApplicantExamineesController::class, 'index'])->name('admin.applicant_examinees_index');
    Route::post('/applicant_examinee/save', [ApplicantExamineesController::class, 'save'])->name('admin.applicant_examinee_save');
    Route::post('/applicant_examinee/update', [ApplicantExamineesController::class, 'update'])->name('admin.applicant_examinee_update');
    Route::post('/applicant_examinee/delete', [ApplicantExamineesController::class, 'delete'])->name('admin.applicant_examinee_delete');

    // Exam Types
    Route::get('/exam_types/index', [ExamTypesController::class, 'index'])->name('admin.exam_types_index');
    Route::post('/exam_type/save', [ExamTypesController::class, 'save'])->name('admin.exam_type_save');
    Route::post('/exam_type/update', [ExamTypesController::class, 'update'])->name('admin.exam_type_update');
    Route::post('/exam_type/delete', [ExamTypesController::class, 'delete'])->name('admin.exam_type_delete');

    // Exam Groups
    Route::get('/exam_groups/index', [ExamGroupsController::class, 'index'])->name('admin.exam_groups_index');
    Route::post('/exam_group/save', [ExamGroupsController::class, 'save'])->name('admin.exam_group_save');
    Route::post('/exam_group/update', [ExamGroupsController::class, 'update'])->name('admin.exam_group_update');
    Route::post('/exam_group/delete', [ExamGroupsController::class, 'delete'])->name('admin.exam_group_delete');

    // //Exams
    // Route::get('/exams/index',[ExamsController::class, 'index'])->name('admin.exams_index');
    // Route::get('/exam/view/{id}',[ExamsController::class, 'view'])->name('admin.exam_view');
    // Route::post('/exam/save',[ExamsController::class, 'save'])->name('admin.exam_save');
    // Route::post('/exam/update',[ExamsController::class, 'update'])->name('admin.exam_update');
    // Route::post('/exam/delete',[ExamsController::class, 'delete'])->name('admin.exam_delete');

    // Questions
    Route::get('/questions/index', [QuestionsController::class, 'index'])->name('admin.questions_index');
    Route::post('/question/save', [QuestionsController::class, 'save'])->name('admin.question_save');
    Route::post('/question/update', [QuestionsController::class, 'update'])->name('admin.question_update');
    Route::post('/question/delete', [QuestionsController::class, 'delete'])->name('admin.question_delete');

    // Exam Multiple Choice
    Route::post('/exam/multiple_choice/save', [ExamsController::class, 'saveMultipleChoice'])->name('admin.exam_multiplechoice_save');
    Route::post('/exam/multiple_choice/update', [ExamsController::class, 'updateMultipleChoice'])->name('admin.exam_multiplechoice_update');
    Route::post('/exam/multiple_choice/delete', [ExamsController::class, 'deleteExamQuestion'])->name('admin.exam_multiplechoice_delete');

    // Exam True or False
    Route::post('/exam/true_false/save', [ExamsController::class, 'saveTrueFalse'])->name('admin.exam_truefalse_save');
    Route::post('/exam/true_false/update', [ExamsController::class, 'updateTrueFalse'])->name('admin.exam_truefalse_update');
    Route::post('/exam/true_false/delete', [ExamsController::class, 'deleteExamQuestion'])->name('admin.exam_truefalse_delete');

    // Exam Essay
    Route::post('/exam/essay/save', [ExamsController::class, 'saveEssay'])->name('admin.exam_essay_save');
    Route::post('/exam/essay/update', [ExamsController::class, 'updateEssay'])->name('admin.exam_essay_update');
    Route::post('/exam/essay/delete', [ExamsController::class, 'deleteExamQuestion'])->name('admin.exam_essay_delete');

    // Exam Numerical
    // copt Multiple Choice

    // Exam Identification
    Route::post('/exam/identif/save', [ExamsController::class, 'saveIdentif'])->name('admin.exam_identif_save');
    Route::post('/exam/identif/update', [ExamsController::class, 'updateIdentif'])->name('admin.exam_identif_update');
    Route::post('/exam/identif/delete', [ExamsController::class, 'deleteExamQuestion'])->name('admin.exam_identif_delete');

    // Examinees
    Route::get('/examinees/index', [ExamineesController::class, 'index'])->name('admin.examinees_index');
    Route::post('/examinee/save', [ExamineesController::class, 'save'])->name('admin.examinee_save');
    Route::post('/examinee/update', [ExamineesController::class, 'update'])->name('admin.examinee_update');
    Route::post('/examinee/delete', [ExamineesController::class, 'delete'])->name('admin.examinee_delete');
    Route::get('/get_users/{id}', [ExamineesController::class, 'getUserByDepartment'])->name('admin.get_user_by_dept');

    // Examinee Test Sheet
    Route::get('/examinee/test_sheet/{id}', [ExamineesController::class, 'examineeTestSheet'])->name('admin.examinee_testsheet');
    Route::post('/examinee/test_sheet/save', [ExamineesController::class, 'saveExamineeTestSheet'])->name('admin.examinee_testsheet_save');

    // Promotional Exams
    Route::get('/promotional_exams/index', [PromotionalExamsController::class, 'index'])->name('admin.promotional_exams_index');
    Route::post('/promotional_exams/save', [PromotionalExamsController::class, 'save'])->name('admin.promotional_exam_save');
    Route::post('/promotional_exams/update', [PromotionalExamsController::class, 'update'])->name('admin.promotional_exam_update');
    Route::post('/promotional_exams/delete', [PromotionalExamsController::class, 'delete'])->name('admin.promotional_exam_delete');

    // Examination Schedules
    Route::get('/examination_schedules/index', [ExaminationSchedulesController::class, 'index'])->name('admin.examination_schedules_index');
    Route::post('/examination_schedule/save', [ExaminationSchedulesController::class, 'save'])->name('admin.examination_schedule_save');
    Route::post('/examination_schedule/update', [ExaminationSchedulesController::class, 'update'])->name('admin.examination_schedule_update');
    Route::post('/examination_schedule/delete', [ExaminationSchedulesController::class, 'delete'])->name('admin.examination_schedule_delete');

    // Promotional Evaluation
    Route::get('/promotional_evaluation/index', [PromotionalEvaluationsController::class, 'index'])->name('admin.promotional_evaluations_index');

    // Examination Reports
    Route::get('/examination_reports/index', [ExaminationReportsController::class, 'index'])->name('admin.examination_reports_index');
    Route::get('/examination_report/show/{examinee_id}/{exam_id}', [ExaminationReportsController::class, 'show'])->name('admin.examination_report_show');
    Route::get('/examination_report/view/{examinee_id}/{exam_id}/{exam_type_id}', [ExaminationReportsController::class, 'viewByExamType'])->name('admin.exam_result_by_type_view');
    Route::get('/examination_report/update_score/{examinee_id}/{exam_id}/{exam_type_id}', [ExaminationReportsController::class, 'updateScore'])->name('admin.exam_result_score_update');

    Route::post('/examination_report/save_updated_score/{examinee_id}/{exam_id}', [ExaminationReportsController::class, 'saveUpdatedScore'])->name('admin.exam_result_save_updated_score');
});

Route::middleware('auth')->group(function () {
    Route::get('/getprofile', [HomeController::class, 'getprofile']);
    Route::get('/leave_analytics_filter', [AbsentNoticesController::class, 'filterEmployeeLeaveAnalytics']);
    Route::get('/leaveAllocationChart', [AbsentNoticesController::class, 'leaveAllocationChart']);
    Route::post('/updateEmployeesLogs', [AttendanceController::class, 'updateEmployeesLogs']);
    Route::get('/module/absent_notice_slip/leave_types_stats', [AbsentNoticesController::class, 'leaveTypeStats']);
    Route::get('/module/absent_notice_slip/absence_rate/{year}', [AbsentNoticesController::class, 'absenceRate']);
    Route::get('/module/absent_notice_slip/analytics', [AbsentNoticesController::class, 'showAnalytics']);
    Route::get('/module/absent_notice_slip/history', [AbsentNoticesController::class, 'showNoticeHistory']);
    Route::get('/module/absent_notice_slip/leave_analytics/{from_date}/{to_date}', [AttendanceController::class, 'showStatisticalReport']);

    // CLIENT LEAVE APPROVER CRUD
    Route::get('/module/absent_notice_slip/leave_approvers', [ApproversController::class, 'showLeaveApprovers']);
    Route::post('/client/leave_approver/create', [ApproversController::class, 'approverCreate']);
    Route::post('/client/leave_approver/update/{id}', [ApproversController::class, 'approverUpdate']);
    Route::post('/client/leave_approver/delete/{id}', [ApproversController::class, 'approverDelete']);
    // END CLIENT LEAVE APPROVER CRUD

    // CLIENT LEAVE BALANCE CRUD
    Route::get('/module/absent_notice_slip/leave_balances', [EmployeeLeavesController::class, 'showLeaveBalances']);
    Route::post('/client/leave_balance/create', [EmployeeLeavesController::class, 'leaveBalanceCreate']);
    Route::post('/client/leave_balance/update/{id}', [EmployeeLeavesController::class, 'leaveBalanceUpdate']);
    Route::post('/client/leave_balance/delete/{id}', [EmployeeLeavesController::class, 'leaveBalanceDelete']);
    Route::post('/client/employee_leave_balances/create', [EmployeeLeavesController::class, 'employeeLeaveBalanceCreate']);
    // END CLIENT LEAVE BALANCE CRUD

    // HR RECRUITMENT MODULE
    Route::get('/module/hr/analytics', [HumanResourcesController::class, 'showAnalytics']);
    Route::get('/module/hr/hiring_rate', [HumanResourcesController::class, 'hiringRate']);
    Route::get('/module/hr/applicants_chart', [HumanResourcesController::class, 'applicantsChart']);
    Route::get('/module/hr/employees_per_dept_chart', [HumanResourcesController::class, 'employeesPerDeptChart']);
    Route::get('/module/hr/job_source_chart', [HumanResourcesController::class, 'jobSourceChart']);

    // CLIENT APPLICANTS
    Route::get('/module/hr/applicants', [ApplicantsController::class, 'showApplicantList']);
    Route::get('/client/applicant/profile/{id}', [ApplicantsController::class, 'showApplicantProfile']);
    Route::get('/client/applicant/backgound_check/{id}', [BackgroundCheckController::class, 'showBackGroundCheckForm']);
    Route::post('/client/applicant/create', [ApplicantsController::class, 'applicantCreate']);
    Route::post('/client/applicant/update/{id}', [ApplicantsController::class, 'applicantUpdate']);
    Route::post('/client/applicant/delete/{id}', [ApplicantsController::class, 'applicantDelete']);

    Route::post('/updateApplicantStatus/{id}', [ApplicantsController::class, 'updateApplicantStatus']);
    Route::post('/hireApplicant/{id}', [EmployeesController::class, 'hireApplicant']);

    Route::get('/client/exams/applicant_exams', [ExamsController::class, 'getExamList']);
    Route::get('/client/hr/applicant_exam_details/{applicant_id}', [ApplicantsController::class, 'getApplicantExamDetails']);
    Route::post('/client/applicant/submitWizard', [ApplicantsController::class, 'submitWizard']);
    // END CLIENT APPLICANTS

    // CLIENT EMPLOYEES
    Route::get('/module/hr/employees', [EmployeesController::class, 'showEmployees']);
    Route::get('/getEmployeeDetails/{id}', [EmployeesController::class, 'getEmployeeDetails']);
    Route::post('/client/employee/create', [EmployeesController::class, 'employeeCreate']);
    Route::post('/client/employee/update/{id}', [EmployeesController::class, 'employeeUpdate']);
    Route::post('/client/employee/delete/{id}', [EmployeesController::class, 'employeeDelete']);
    Route::post('/client/employee/reset_password', [EmployeesController::class, 'reset_password']);
    Route::get('/client/employee/profile/{id}', [EmployeesController::class, 'employeeProfile']);
    Route::post('/client/employee/profile/{id}/photo', [EmployeesController::class, 'updateEmployeeProfilePhoto'])->name('client.employee_profile.photo.upload');
    Route::get('/showBirthdaysToday', [EmployeesController::class, 'checkEmployeeBirthday']);
    // END CLIENT EMPLOYEES

    // CLIENT BACKGROUND INVESTIGATION
    Route::get('/module/hr/background_check', [BackgroundCheckController::class, 'showBackgroundInvQuestions']);
    Route::post('/client/background_check/crudAddQuestion', [BackgroundCheckController::class, 'store']);
    Route::post('/client/background_check/crudEditQuestion', [BackgroundCheckController::class, 'update']);
    Route::post('/client/background_check/crudDeleteQuestion', [BackgroundCheckController::class, 'delete']);
    // END CLIENT BACKGROUND INVESTIGATION

    // CLIENT APPLICANT EXAMS
    Route::get('/module/hr/applicant_exams', [ExamsController::class, 'showApplicantExams']);
    Route::get('/client/hr/applicant_exams/{id}', [ExamsController::class, 'showApplicantExamDetails']);
    Route::get('/client/hr/applicant_exams/add_exam', [ExamsController::class, 'addApplicantExam']);
    Route::get('/client/hr/applicant_exams/update_exam', [ExamsController::class, 'updateApplicantExam']);
    Route::get('/client/hr/applicant_exams/delete_exam', [ExamsController::class, 'deleteApplicantExam']);
    Route::post('/client/hr/applicant_exams/insturctions/update', [ExamTypesController::class, 'editInstructions']);
    Route::post('/client/applicant_exams/add_question', [QuestionsController::class, 'tabAddQuestion']);
    Route::post('/client/applicant_exams/update_question', [QuestionsController::class, 'tabUpdateQuestion']);
    Route::post('/client/applicant_exams/delete_question', [QuestionsController::class, 'tabDeleteQuestion']);
    // END CLIENT APPLICANT EXAMS

    // CLIENT EXAM RESULTS
    Route::get('/module/hr/exam_results', [ExaminationReportsController::class, 'showApplicantExamResult']);
    Route::get('/client/exam_results/{examinee_id}/{exam_id}', [ExaminationReportsController::class, 'showApplicantExamResults']);
    Route::get('/client/exam_results/answers/{examinee_id}/{exam_id}/{exam_type_id}', [ExaminationReportsController::class, 'showApplicantExamAnswers']);
    Route::get('/client/exam_results/check_answers/{examinee_id}/{exam_id}/{exam_type_id}', [ExaminationReportsController::class, 'showApplicantAnswersForChecking']);
    Route::post('/client/exam_results/check_answers/update_score/{examinee_id}/{exam_id}', [ExaminationReportsController::class, 'updateApplicantScore']);
    // END CLIENT EXAM RESULTS

    //  DEPARTMENT HEAD LIST
    Route::get('/module/hr/department_head_list', [DepartmentHeadListController::class, 'showlist']);
    Route::post('/client/modules/human_resource/department_head/create', [DepartmentHeadListController::class, 'store']);
    Route::post('/client/modules/human_resource/department_head/update/{id}', [DepartmentHeadListController::class, 'update']);
    Route::post('/client/modules/human_resource/department_head/delete/{id}', [DepartmentHeadListController::class, 'delete']);

    // ATTENDANCE MODULE
    Route::get('/module/attendance/analytics', [AttendanceController::class, 'showAnalytics']);
    Route::get('/module/attendance/biometric_adjustments', [AttendanceController::class, 'showAdjustmentMonitoring']);
    Route::get('/module/attendance/history', [AttendanceController::class, 'showAttendanceHistory']);
    Route::get('/module/attendance/holiday_entry', [HolidayController::class, 'indexholiday']); // Holiday
    Route::post('/module/attendance/holiday/create', [HolidayController::class, 'storeholiday']); // Holiday
    Route::post('/module/attendance/holiday/update', [HolidayController::class, 'updateholiday']); // Holiday
    Route::post('/module/attendance/holiday/delete', [HolidayController::class, 'deleteholiday']); // Holiday
    Route::get('/module/attendance/late_employees', [AttendanceController::class, 'showLateEmployees']);
    Route::get('/getAbsentEmployees', [AttendanceController::class, 'getAbsentEmployees']);
    Route::get('/getPerfectAttendance', [AttendanceController::class, 'getPerfectAttendance']);
    Route::get('/adj_monitoring', [BiometricLogsController::class, 'attendanceAdjustmentMonitoring']);
    // Route::get('/adj_history', [AttendanceController::class, 'attendanceAdjHistory']);
    Route::get('/attendance_history', [BiometricLogsController::class, 'attendanceHistory']);
    Route::get('/lateEmployees', [AttendanceController::class, 'getLateEmployees']);
    Route::get('/getBioAdjustments', [AttendanceController::class, 'getBioAdjustments']);

    Route::post('/deleteAdjustment', [AttendanceController::class, 'deleteAdjustment']);
    // CLIENT SHIFTS
    Route::get('/module/attendance/employee_shifts', [ShiftsController::class, 'showEmployeeShifts']);
    Route::get('/client/attendance/employee_shifts/details/{group_id}', [ShiftsController::class, 'getEmployeeShiftDetails']);
    Route::post('/client/attendance/employee_shifts/create', [ShiftsController::class, 'createShiftSchedule']);
    Route::post('/client/attendance/employee_shifts/update', [ShiftsController::class, 'updateShiftSchedule']);
    Route::post('/client/attendance/employee_shifts/delete/{id}', [ShiftsController::class, 'deleteShiftSchedule']);
    Route::post('/client/attendance/special_shift/create', [ShiftsController::class, 'createSpecialShift']);
    Route::post('/client/attendance/special_shift/update/{id}', [ShiftsController::class, 'updateSpecialShift']);
    Route::post('/client/attendance/special_shift/delete/{id}', [ShiftsController::class, 'deleteSpecialShift']);

    // GATEPASS MODULE
    Route::get('/module/gatepass/analytics', [GatepassesController::class, 'showAnalytics']);
    Route::get('/module/gatepass/gatepass_per_dept_chart', [GatepassesController::class, 'gatepassPerDeptChart']);
    Route::get('/module/gatepass/purpose_rate_chart', [GatepassesController::class, 'purposeRateChart']);
    Route::get('/module/gatepass/gatepass_per_dept_chart', [GatepassesController::class, 'gatepassPerDeptChart']);
    Route::get('/getItemsIssuedtoEmployee/{user_id}', [GatepassesController::class, 'getItemsIssuedtoEmployee']);

    Route::get('/client/gatepass/history', [GatepassesController::class, 'showGatepassHistory']);
    Route::get('/client/gatepass/unreturned_gatepass', [GatepassesController::class, 'showUnreturnedItems']);
    Route::get('/client/gatepass/employee_accountability', [GatepassesController::class, 'showEmployeeAccountability']);
    Route::get('/client/gatepass/company_asset', [GatepassesController::class, 'showCompanyAsset']);
    Route::post('/addAsset', [ItemAccountabilityController::class, 'storeAsset']);
    Route::get('/getupdateItemsIssuedtoEmployee/{user_id}', [GatepassesController::class, 'getupdateItemsIssuedtoEmployee']);
    Route::post('/deleteAsset', [GatepassesController::class, 'deleteAsset']);

    // ANALYTICS
    Route::get('/client/analytics/attendance', [AnalyticsController::class, 'showAttendanceAnalytics']);
    Route::get('/client/analytics/hr', [AnalyticsController::class, 'showHrAnalytics']);
    Route::get('/client/analytics/notice_slip', [AnalyticsController::class, 'showNoticesAnalytics']);
    Route::get('/client/analytics/gatepass', [AnalyticsController::class, 'showGatepassAnalytics']);
    Route::get('/client/analytics/exam', [ExamsController::class, 'showExamAnalytics']);

    // ITEM ACCOUNTABILITY
    Route::get('/itemAccountability/{id}', [ItemAccountabilityController::class, 'index']);
    Route::post('/addItem', [ItemAccountabilityController::class, 'store']);
    Route::post('/editItem/{id}', [ItemAccountabilityController::class, 'updateAsset']);
    Route::post('/deleteItem', [ItemAccountabilityController::class, 'delete']);
    Route::get('/printItem/{id}', [ItemAccountabilityController::class, 'print']);
    Route::get('/getinfoeditmodal/{id}', [ItemAccountabilityController::class, 'getinfoeditmodal']);
    Route::get('/getInfo', [GatepassesController::class, 'showAccountability']);
    Route::get('/getCateg', [GatepassesController::class, 'showCateg']);

    // EVALUATION MODULE
    Route::get('/evaluation/department', [EvaluationController::class, 'kpiPerDept']);
    Route::get('/evaluation/employee_inputs', [EvaluationController::class, 'showEmployeeInputsDept']);
    Route::get('/evaluation/employee_inputs/form/{department_id}', [EvaluationController::class, 'showEmployeeInputsForm']);
    Route::get('/evaluation/employee_inputs/view/{department_id}', [EvaluationController::class, 'viewEmployeeInputs']);
    Route::get('/evaluation/setup/{department_id}', [EvaluationController::class, 'setupKPI']);
    Route::get('/evaluation/objectives', [EvaluationController::class, 'showObjectives']);
    Route::get('/evaluation/objective/view/{objective_id}', [EvaluationController::class, 'viewObjectiveTree']);
    Route::get('/evaluation/kpi', [EvaluationController::class, 'showKPI']);
    Route::get('/evaluation/appraisal', [EvaluationController::class, 'showAppraisal']);
    Route::get('/evaluation/appraisal/form/{user_id}/{from_month}/{from_year}/{to_month}/{to_year}/{purpose}', [EvaluationController::class, 'showAppraisalForm']);
    Route::get('/evaluation/appraisal/view/{id}', [EvaluationController::class, 'viewAppraisal']);
    Route::get('/evaluation/appraisal/print/{id}', [EvaluationController::class, 'printAppraisal']);

    Route::get('/getEmployees', [EvaluationController::class, 'getEmployees']);

    // Route::get('/evaluationTree/{department_id}', [EvaluationController::class, 'evaluationTree']);
    Route::get('/kpiTree/{department}', [EvaluationController::class, 'kpiTree']);
    Route::get('/getObjectives', [EvaluationController::class, 'getObjectives']);
    Route::get('/getKPI', [EvaluationController::class, 'getKPI']);
    Route::get('/qualitativeKpi', [EvaluationController::class, 'qualitativeKpi']);

    Route::get('/getObjectiveDetails/{id}', [EvaluationController::class, 'getObjectiveDetails']);
    Route::get('/getKpiDetails/{id}', [EvaluationController::class, 'getKpiDetails']);
    Route::get('/getMetricDetails/{id}', [EvaluationController::class, 'getMetricDetails']);

    Route::get('/getDesignations', [EvaluationController::class, 'getDesignations']);

    Route::post('/createObjective', [EvaluationController::class, 'createObjective']);
    Route::post('/updateObjective', [EvaluationController::class, 'updateObjective']);
    Route::post('/deleteObjective', [EvaluationController::class, 'deleteObjective']);

    Route::post('/createKPI', [EvaluationController::class, 'createKPI']);
    Route::post('/updateKPI', [EvaluationController::class, 'updateKPI']);
    Route::post('/deleteKPI', [EvaluationController::class, 'deleteKPI']);

    Route::post('/createMetrics', [EvaluationController::class, 'createMetrics']);
    Route::post('/updateMetric', [EvaluationController::class, 'updateMetric']);
    Route::post('/deleteMetric', [EvaluationController::class, 'deleteMetric']);

    Route::post('/createAppraisal', [EvaluationController::class, 'createAppraisal']);
    Route::post('/saveAppraisal', [EvaluationController::class, 'saveAppraisal']);
    Route::post('/updateAppraisal', [EvaluationController::class, 'updateAppraisal']);
    Route::post('/deleteAppraisal/{id}', [EvaluationController::class, 'deleteAppraisal']);

    Route::post('/updateEmpInputs', [EvaluationController::class, 'updateEmpInputs']);

    Route::get('/getEmpAppraisal/{user}', [EvaluationController::class, 'getEmpAppraisal']);
    Route::get('/getEmpKpiResult/{user}', [EvaluationController::class, 'getEmpKpiResult']);
    Route::get('/appraisal_result/{id}/view', [EvaluationController::class, 'viewEmpAppraisalResult']);

    Route::get('/getdatainput', [EvaluationController::class, 'dataInput']);
    Route::get('/tblDatainput', [EvaluationController::class, 'tbldatainput']);
    Route::post('/savedatainput', [EvaluationController::class, 'savedataInput']);

    Route::get('/evaluation/schedules', [EvaluationController::class, 'showEvalSchedules']);
    Route::get('/evaluation/schedule/{id}/view', [EvaluationController::class, 'viewEvalSchedule']);
    Route::post('/evaluation/schedule/new', [EvaluationController::class, 'addEvalSchedule']);
    Route::post('/evaluation/schedule/{id}/update', [EvaluationController::class, 'updateEvalSchedule']);
    Route::post('/evaluation/schedule/{id}/delete', [EvaluationController::class, 'deleteEvalSchedule']);

    Route::post('/createDataInputs', [EvaluationController::class, 'createDataInputs']);
    Route::post('/updateDataInput', [EvaluationController::class, 'updateDataInput']);
    Route::post('/deleteDataInput', [EvaluationController::class, 'deleteDataInput']);

    Route::get('/evaluation/kpi_result', [EvaluationController::class, 'showKpiResult']);
    Route::get('/getKpiResult', [EvaluationController::class, 'getKpiResult']);
    Route::post('/updateDataInputResult', [EvaluationController::class, 'updateDataInputResult']);

    Route::get('/kpiTree/{department}', [EvaluationController::class, 'kpiTree']);
    Route::get('/getemployeeperdept', [EvaluationController::class, 'getemployeeperdept']);

    // Overview per Department
    Route::get('/kpi_stats/accounting/index', [EvaluationController::class, 'accounting_index']);
    Route::get('/kpi_stats/accounting/index2', [EvaluationController::class, 'accounting_index2']);
    Route::get('/kpi_stats/engineering/index', [EvaluationController::class, 'engineering_index']);
    Route::get('/kpi_stats/it/index', [EvaluationController::class, 'information_technology_index']);
    Route::get('/kpi_stats/sales/index', [EvaluationController::class, 'sales_index']);
    Route::get('/kpi_stats/customer_service/index', [EvaluationController::class, 'customer_service_index']);
    Route::get('/kpi_stats/qa/index', [EvaluationController::class, 'quality_assurance_index']);
    Route::get('/kpi_stats/hr/index', [EvaluationController::class, 'human_resource_index']);
    Route::get('/kpi_stats/plant_services/index', [EvaluationController::class, 'plant_services_index']);
    Route::get('/kpi_stats/production/index', [EvaluationController::class, 'production_index']);
    Route::get('/kpi_stats/material_management/index', [EvaluationController::class, 'materials_management_index']);
    Route::get('/kpi_stats/material_management/index2', [EvaluationController::class, 'purchasing_index']);
    Route::get('/kpi_stats/management/index', [EvaluationController::class, 'management_index']);
    Route::get('/kpi_stats/marketing/index', [EvaluationController::class, 'marketing_index']);
    Route::get('/kpi_stats/assembly/index', [EvaluationController::class, 'assembly_index']);
    Route::get('/kpi_stats/fabrication/index', [EvaluationController::class, 'fabrication_index']);
    Route::get('/kpi_stats/traffic_and_distribution/index', [EvaluationController::class, 'traffic_and_distribution_index']);
    Route::get('/kpi_stats/painting/index', [EvaluationController::class, 'painting_index']);
    Route::get('/kpi_stats/filunited/index', [EvaluationController::class, 'filunited_index']);
    Route::get('/kpi_stats/production_planning/index', [EvaluationController::class, 'production_planning_index']);

    Route::get('/kpi_stats/getdata_it/kpi1', [EvaluationController::class, 'kpi1_stats']);
    Route::get('/kpi_stats/getdata_it/kpi2', [EvaluationController::class, 'kpi2_stats']);
    Route::get('/kpi_stats/getdata_it/kpi3', [EvaluationController::class, 'kpi3_stats']);
    Route::get('/kpi_stats/technicalLevel', [EvaluationController::class, 'technicalLevel_stats']);
    Route::get('/kpi/result/InformationTechnologydepartment', [EvaluationController::class, 'viewKPIresult_IT']);
    Route::get('/ITKpiResult/{department}', [EvaluationController::class, 'IT_departmentKpiResult']);

    Route::get('/AttandanceAdjUpdateall', [AttendanceController::class, 'AttendanceAdjUpdateall']);

    Route::get('/departmentKpiResult/{department}', [EvaluationController::class, 'departmentKpiResult']);

    Route::get('/AttendanceAdjUpdateall', [AttendanceController::class, 'AttendanceAdjUpdateall']);
    Route::post('/addAdjustment', [AttendanceController::class, 'addAdjustment']);
    Route::get('/adj_history', [AttendanceController::class, 'attendanceAdjHistory']);
    Route::get('/adj_monitoring', [BiometricLogsController::class, 'attendanceAdjustmentMonitoring']);

    Route::get('/employeeStats/{employee_id}', [EvaluationController::class, 'empStats']);
    Route::get('/employee_erp_data_inputs/{employee_id}', [EvaluationController::class, 'empDataInputsERP']);
    Route::get('/employee_manual_data_inputs/{employee_id}', [EvaluationController::class, 'empDataInputsManualEntry']);
});

// Engineering Department
Route::prefix('kpi_overview/engineering')->middleware('auth')->group(function () {
    // charts
    Route::get('/rfd_per_month/{year}', [EvaluationController::class, 'rfdPerMonthChart']);
    Route::get('/rfd_distribution/{year}', [EvaluationController::class, 'rfdDistributionChart']);
    Route::get('/rfd_timeliness/{year}', [EvaluationController::class, 'rfdTimeliness']);
    Route::get('/rfd_completion/{year}', [EvaluationController::class, 'rfdCompletion']);
    Route::get('/rfd_quality/{year}', [EvaluationController::class, 'rfdQuality']);
    Route::get('/rfd_success_rate/{year}', [EvaluationController::class, 'rfdSuccessRate']);
    Route::get('/rfd_totals', [EvaluationController::class, 'rfdTotals']);

    Route::get('/emp_data_inputs', [EvaluationController::class, 'engineeringDataInputsERP']);

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'engineeringKpiResult']);
});

// Attendance
Route::prefix('attendance')->middleware('auth')->group(function () {
    Route::post('/update/{employee}', [AttendanceController::class, 'updateAttendanceLogs']);
    // Portal clock in/out — disabled temporarily (re-enable with homepage + HomeController)
    // Route::post('/clock-in', [AttendanceController::class, 'clockIn']);
    // Route::post('/clock-out', [AttendanceController::class, 'clockOut']);
    // Route::post('/resume', [AttendanceController::class, 'resumeClock']);
    Route::get('/history/{employee}', [AttendanceController::class, 'employeeAttendanceHistory']);
    Route::get('/dashboard/{employee}', [AttendanceController::class, 'employeeAttendanceDashboard']);
    Route::get('/deductions/{employee}', [AttendanceController::class, 'employeeLateDeductions']);
});

Route::get('/kiosk/login', [KioskController::class, 'loginForm']);
Route::post('/kiosk/loguser', [KioskController::class, 'kioskLogin']);
Route::get('/kiosk/logoutuser', [KioskController::class, 'kioskLogout']);

Route::get('/kiosk/leave_calendar', [KioskController::class, 'leaveCalendar']);

Route::get('/kiosk/home', [KioskController::class, 'index']);
Route::get('/kiosk/notice', [KioskController::class, 'noticeTransactSel']);
Route::get('/kiosk/notice/leave_balance', [KioskController::class, 'leaveBalance']);
Route::get('/kiosk/notice/form', [KioskController::class, 'noticeForm']);
Route::get('/kiosk/notice/getnotice_table', [KioskController::class, 'getnotice_history']);
Route::get('/kiosk/notice/load_view_table', [KioskController::class, 'notice_view_table']);
Route::get('/kiosk/notice/cancel_slip', [KioskController::class, 'cancel_notice']);
Route::post('/kiosk/notice/form/insert', [KioskController::class, 'storenotice']);
Route::get('/kiosk/notice/view', [KioskController::class, 'noticeView']);
Route::get('/kiosk/notice/history', [KioskController::class, 'noticeHistory']);
Route::get('/kiosk/notice/getusershift', [KioskController::class, 'user_shift']);

Route::post('/kiosk/gatepass/form/insert', [KioskController::class, 'storegatepass']);
Route::get('/kiosk/gatepass', [KioskController::class, 'gatepassTransactSel']);
Route::get('/kiosk/gatepass/form', [KioskController::class, 'gatepassForm']);
Route::get('/kiosk/gatepass/view', [KioskController::class, 'gatepassView']);
Route::get('/kiosk/gatepass/history', [KioskController::class, 'gatepassHistory']);
Route::get('/kiosk/gatepass/load_view_table', [KioskController::class, 'gatepass_view_table']);
Route::get('/kiosk/gatepass/getgatepass_table', [KioskController::class, 'getgatepass_history']);
Route::get('/kiosk/gatepass/cancel_slip', [KioskController::class, 'cancel_gatepass']);
Route::get('/kiosk/gatepass/getUnreturned_gatepass_table', [KioskController::class, 'getunreturned_history']);
Route::get('/kiosk/gatepass/for_return', [KioskController::class, 'gatepassUnreturned']);

Route::get('/kiosk/attendance', [KioskController::class, 'attendanceTransactSel']);
Route::get('/kiosk/attendance/view', [KioskController::class, 'attendanceView']);
Route::get('/kiosk/attendance/summary', [KioskController::class, 'attendanceSummary']);

// ItineraryKiosk
Route::get('/kiosk/itinerary', [KioskController::class, 'itineraryTransactSel']);
Route::get('/kiosk/itinerary/form', [KioskController::class, 'itineraryForm']);
Route::get('/kiosk/itinerary/view/{id}', [KioskController::class, 'itineraryView']);
Route::get('/kiosk/itinerary/result/{id}', [KioskController::class, 'itineraryResult']);
Route::post('/kiosk/itinerary/cancel/{id}', [KioskController::class, 'cancelItinerary']);

Route::get('/kiosk/itinerary/history', [KioskController::class, 'itineraryHistory']);
Route::get('/kiosk/notice/get_Itinerary_table', [KioskController::class, 'get_itineraryHistory']);
Route::get('/kiosk/itinerary/result_table/{id}', [KioskController::class, 'itineraryResult_table']);

// ItineraryEssex
Route::get('/itinerary/fetch', [ItineraryController::class, 'fetchItineraries']);
Route::get('/itinerary/fetch/companion', [ItineraryController::class, 'fetchItineraries_companion']);

// AJAX
Route::get('/kiosk/attendance_logs/{employee}', [KioskController::class, 'biometricLogs']);
Route::get('/kiosk/employees/erp', [KioskController::class, 'getEmployees']);
Route::get('/kiosk/destinations/{doctype}', [KioskController::class, 'getDocList']);
Route::post('/kiosk/itinerary/save', [KioskController::class, 'saveItinerary']);

// Additional Cancel code per employee
Route::post('/notice_slip/cancelNotice_per_employee', [AbsentNoticesController::class, 'cancelNotice_per_employee']);

// /Stepper
Route::get('/kiosk/stepper', [KioskController::class, 'stepper_index']);
Route::get('/stepper/notice', [KioskController::class, 'stepper_notice']);
Route::get('/stepper/gatepass', [KioskController::class, 'stepper_gatepass']);
Route::get('/stepper/itinerary', [KioskController::class, 'stepper_itinerary']);

Route::post('/kiosk/notice_employee/fetch', [KioskController::class, 'fetch_employee_name']);

// Accounting Department
Route::prefix('kpi_overview/accounting')->middleware('auth')->group(function () {
    // chart
    Route::get('/sinv_per_month/{year}', [EvaluationController::class, 'sinvPerMonthChart']);
    Route::get('/pinv_per_month/{year}', [EvaluationController::class, 'pinvPerMonthChart']);
    Route::get('/top_expenses/{year}', [EvaluationController::class, 'topExpenses']);
    Route::get('/sinv_analysis/{year}', [EvaluationController::class, 'salesInvAnalysis']);
    Route::get('/pinv_analysis/{year}', [EvaluationController::class, 'purchaseInvAnalysis']);
    Route::get('/cash_receipt/{year}', [EvaluationController::class, 'cashReceiptChart']);
    Route::get('/cash_disbursement/{year}', [EvaluationController::class, 'cashDisbursementChart']);
    Route::get('/sinv_analysis_ctx/{year}', [EvaluationController::class, 'salesInvAnalysisCtx']);
    Route::get('/pinv_analysis_ctx/{year}', [EvaluationController::class, 'purchaseInvAnalysisCtx']);

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'accountingKpiResult']);
});

// Sales Department
Route::prefix('kpi_overview/sales')->middleware('auth')->group(function () {
    Route::get('/totals', [EvaluationController::class, 'sales_totals']);
    Route::get('/opty_stats/{year}', [EvaluationController::class, 'opportunityStats']);
    Route::get('/sales_chart/{year}', [EvaluationController::class, 'salesChart']);

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'salesKpiResult']);
});

// Traffic and Distribution Department
Route::prefix('kpi_overview/traffic_and_distribution')->middleware('auth')->group(function () {
    // chart
    Route::get('/delivery_completion/{year}', [EvaluationController::class, 'deliveryCompletionChart']);
    Route::get('/delivery_good_condition/{year}', [EvaluationController::class, 'deliveryGoodConditionChart']);
    Route::get('/non_delivery_dept_cause/{year}', [EvaluationController::class, 'nonDeliveryDeptCausesChart']);
    Route::get('/non_delivery_cust_cause/{year}', [EvaluationController::class, 'nonDeliveryCustCausesChart']);

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'trafficDistributionKpiResult']);
});

// Customer Service Department
Route::prefix('kpi_overview/customer_service')->middleware('auth')->group(function () {
    // page
    Route::get('/kpi_result', [EvaluationController::class, 'csKpiResult']);
    Route::get('/get_kpi_CsStat1', [EvaluationController::class, 'cskpi1_stat']);
    Route::get('/get_kpi_CsStat2', [EvaluationController::class, 'cskpi2_stat']);
    Route::get('/within_department_fault_chart/{year}', [EvaluationController::class, 'within_departmentfaultPie']);
    Route::get('/not_within_department_fault_chart/{year}', [EvaluationController::class, 'not_within_departmentfaultPie']);

    Route::get('/cs_performace_chart/{year}', [EvaluationController::class, 'csperformance_chart']);
    Route::get('/get_total_sales', [EvaluationController::class, 'salesTotal']);
    Route::get('/get_csTimeliness/{year}', [EvaluationController::class, 'salesOrder_timeliness']);
});

// Quality Assurance Department
Route::prefix('kpi_overview/qa')->middleware('auth')->group(function () {

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'qaKpiResult']);
});

// Plant Services Department
Route::prefix('kpi_overview/plant_services')->middleware('auth')->group(function () {

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'plantServicesKpiResult']);
});

// Production Department
Route::prefix('kpi_overview/production')->middleware('auth')->group(function () {

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'productionKpiResult']);
});

// Material Management Department
Route::prefix('kpi_overview/material_management')->middleware('auth')->group(function () {
    // Inventory
    Route::get('/inventory/totals', [EvaluationController::class, 'materials_management_totals']);
    Route::get('/inv_accuracy/{year}', [EvaluationController::class, 'invAccuracyChart']);
    Route::get('/item_movements/{year}', [EvaluationController::class, 'itemMovements']);
    Route::get('/item_class_movements/{year}', [EvaluationController::class, 'itemClassMovements']);

    // Purchasing
    Route::get('/purchase_timeliness/{year}/{supplier_group}', [EvaluationController::class, 'purchasesTimeliness']);
    Route::get('/purchasing/totals', [EvaluationController::class, 'purchasing_totals']);

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'materialsManagementKpiResult']);
});

// Management Department
Route::group(['prefix' => 'kpi_overview/management', 'middleware' => 'auth'], function () {

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'managementKpiResult']);
});

// Marketing Department
Route::prefix('kpi_overview/marketing')->middleware('auth')->group(function () {

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'marketingKpiResult']);
});

// Assembly Department
Route::prefix('kpi_overview/assembly')->middleware('auth')->group(function () {

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'assemblyKpiResult']);
});

// Fabrication Department
Route::group(['prefix' => 'kpi_overview/fabrication', 'middleware' => 'auth'], function () {

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'fabricationKpiResult']);
});

// Painting Department
Route::prefix('kpi_overview/painting')->middleware('auth')->group(function () {

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'paintingKpiResult']);
});

// Filunited Department
Route::prefix('kpi_overview/filunited')->middleware('auth')->group(function () {

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'filunitedKpiResult']);
});

// Production Planning Department
Route::prefix('kpi_overview/production_planning')->middleware('auth')->group(function () {

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'productionPlanningKpiResult']);
});

// Human Resource Department
Route::prefix('kpi_overview/hr')->middleware('auth')->group(function () {

    // page
    Route::get('/kpi_result', [EvaluationController::class, 'hrKpiResult']);
    Route::get('/get_kpiStat1', [EvaluationController::class, 'hrkpi1_stat']);
});

// ONLINE EXAM - APPLICANT
Route::get('/applicant', [ApplicantExaminationsController::class, 'enterExamCode']);
Route::get('/oem/index/{examineeid}', [ApplicantExaminationsController::class, 'applicantExamIndex']);
Route::post('/oem/validate_exam_code', [ApplicantExaminationsController::class, 'validateExamCode']);
Route::post('/oem/update_answer', [ApplicantExaminationsController::class, 'updateAnswer']);
Route::post('/oem/update_examinee_status', [ApplicantExaminationsController::class, 'updateExamineeStatus']);
Route::get('/oem/preview_examinee_answer', [ApplicantExaminationsController::class, 'preview_answers']);
Route::get('/oem/save_exam_result/{examineeid}', [ApplicantExaminationsController::class, 'save_examresult']);
Route::get('/oem/examSubmitted/{id}', [ApplicantExaminationsController::class, 'examSuccess']);
Route::get('/oem/update_no_answer/{examineeid}', [ApplicantExaminationsController::class, 'update_no_answer']);

// ONLINE EXAM - EMPLOYEE
Route::post('/oem/employee/validateExamCode', [ClientExamsController::class, 'validateExamCode']);
Route::get('/oem/employee/index/{id}', [ClientExamsController::class, 'takeexam']);
Route::get('/check_ongoing_exam/{id}', [ClientExamsController::class, 'checkOngoingStatus']);
Route::post('/oem/employee/update_answer', [ClientExamsController::class, 'updateAnswer']);
Route::post('/oem/employee/update_examinee_status', [ClientExamsController::class, 'updateExamineeStatus']);
Route::get('/oem/employee/preview_examinee_answer', [ClientExamsController::class, 'preview_answers']);
Route::get('/oem/employee/save_exam_result/{examineeid}', [ClientExamsController::class, 'save_examresult']);
Route::get('/oem/employee/examSubmitted/{id}', [ClientExamsController::class, 'examSuccess']);
Route::get('/oem/employee/update_no_answer/{examineeid}', [ClientExamsController::class, 'update_no_answer']);

// HR Training
Route::get('/module/hr/training', [HumanResourcesController::class, 'show_HR_training']);
Route::get('/module/hr/training_profile/{id}',[HumanResourcesController::class, 'training_profile']);
Route::post('/module/hr/add_training',[HumanResourcesController::class, 'add_HR_training']);
Route::post('/module/hr/edit_training',[HumanResourcesController::class, 'edit_HR_training']);
Route::post('/module/hr/delete_training',[HumanResourcesController::class, 'delete_HR_training']);
Route::get('/module/hr/training/employee_list',[HumanResourcesController::class, 'Employee_list']);
Route::get('/module/hr/training/employee_list_edit',[HumanResourcesController::class, 'Employee_list_edit']);
Route::get('/module/hr/training_details/{id}',[HumanResourcesController::class, 'edit_training_details']);

Route::get('/notice_slip/updateStatus', [AbsentNoticesController::class, 'updateStatus']);
