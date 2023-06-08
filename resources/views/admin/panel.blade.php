@extends('admin.app')
@section('content')
                  <div class="col-md-4">
                     <div class="panel-box panel-box-default">
                        <div class="panel-box-heading">Manage Employee</div>
                        <div class="panel-box-body">
                           <ul class="panel-box-list">
                              <li><a href="/admin/employees">Employee List</a></li>
                              <li><a href="/admin/departments">Department List</a></li>
                              <li><a href="/admin/designations">Designation List</a></li>
                              <li><a href="/admin/applicants">Applicant List</a></li>
                              <li><a href="/admin/shifts">Shift List</a></li>
                              <li><a href="/admin/admins">Admin List</a></li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="panel-box panel-box-default">
                        <div class="panel-box-heading">Manage Attendance</div>
                        <div class="panel-box-body">
                           <ul class="panel-box-list">
                              <li><a href="/admin/leave_calendar">Leave Calendar</a></li>
                              <li><a href="/admin/branches">Branch List</a></li>
                              <li><a href="/admin/holidays">Holiday List</a></li>
                              <li><a href="/admin/lateEmployees">Late Employees Report</a></li>
                              <li><a href="/admin/statistical_report/{{ Carbon\Carbon::parse('first day of this month')->format('Y-m-d') }}/{{ Carbon\Carbon::parse('last day of this month')->format('Y-m-d') }}/">Absences/Leaves Statistical Report</a></li>
                              <li><a href="/admin/attendance_adjustments">Attendance Adjustment Monitoring</a></li>
                              {{-- <li>Add Department</li>
                              <li>Add Applicants</li>
                              <li>Employee List</li>
                              <li>Employee Shift</li> --}}
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="panel-box panel-box-default">
                        <div class="panel-box-heading">Manage Absent Notices</div>
                        <div class="panel-box-body">
                           <ul class="panel-box-list">
                              <li><a href="/admin/leave_types">Leave Types</a></li>
                              <li><a href="/admin/approvers">Approvers List</a></li>
                              <li><a href="/admin/employee_leaves">Employee Leaves List</a></li>
                              <li>Employee Leave Analytics</li>
                              <li><a href="/admin/leave_balances">Employee Leave Balances</a></li>
                              <li><a href="/admin/notices_for_approval">Absent Notices for Approval</a></li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="panel-box panel-box-default">
                        <div class="panel-box-heading">Manage Gatepasses</div>
                        <div class="panel-box-body">
                           <ul class="panel-box-list">
                              <li><a href="/admin/items">Item List</a></li>
                              <li><a href="/admin/gatepasses">Gatepass List</a></li>
                              <li><a href="/admin/gatepass/forApproval">Gatepass for Approval</a></li>
                              <li><a href="/admin/gatepass/unreturned">Unreturned Items</a></li>
                              <li><a href="/admin/items_issued">Items issued to Employee</a></li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="panel-box panel-box-default">
                        <div class="panel-box-heading">Manage Employee Loans</div>
                        <div class="panel-box-body">
                           <ul class="panel-box-list">
                              {{-- <li>Add Employee</li>
                              <li>Add Shift</li>
                              <li>Add Department</li>
                              <li>Add Applicants</li>
                              <li>Employee List</li>
                              <li>Employee Shift</li> --}}
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="panel-box panel-box-default">
                        <div class="panel-box-heading">Manage Exam</div>
                        <div class="panel-box-body">
                           <ul class="panel-box-list">
                              <li><a href="{{route('admin.questions_index')}}">Add Question</a></li>
                              <li><a href="{{route('admin.exams_index')}}">Add Exam</a></li>
                              <li><a href="{{route('admin.exam_groups_index')}}">Add Exam Group</a></li>
                              <li><a href="{{route('admin.exam_types_index')}}">Add Exam Type</a></li>
                              {{--<li><a href="{{route('admin.testsheets.index')}}">Create Test Sheet</a></li>--}}
                              <li><a href="{{route('admin.examinees_index')}}">Enroll Examinee</a></li>
                              <li><a href="{{route('admin.applicant_examinees_index')}}">Applicant Examinees</a></li>
                              <li><a href="{{route('admin.promotional_exams_index')}}">Promotional Exam List</a></li>
                              <li><a href="{{route('admin.examination_schedules_index')}}">Examination Schedule List</a></li>
                              <li><a href="{{route('admin.promotional_evaluations_index')}}">Promotional and Evaluation</a></li>
                              <li><a href="{{route('admin.examination_reports_index')}}">Examination Report</a></li>
                              <li><a>Applicants Examination Report</a></li>
                              <li><a>Test Analysis</a></li>
                           </ul>
                        </div>
                     </div>
                  </div>
       @endsection

<style>
.panel-box {
    margin-bottom: 20px;
    background-color: #fff;
    border: 1px solid transparent;
    border-radius: 4px;
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
    box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.panel-box-default {
    border-color: #ddd;
}
.panel-box-default>.panel-box-heading {
    color: #333;
    background-color: #f5f5f5;
    border-color: #ddd;
}
.panel-box-heading {
    padding: 10px 15px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
}

.panel-box-body {
    padding: 15px;
}

.panel-box-list li{
   padding: 5px 5px;
   border-bottom: 1px solid #F5F5F5;
}

.panel-box-list li:last-child{
   border-bottom: 0px;
}

</style>