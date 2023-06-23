{{-- 
<div class="row" style="margin-top: -60px; margin-bottom: -25px;">
  <div class="col-md-12">
    <div id="birthday-notif-div"></div>
    @if(in_array($designation, ['HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President']))
    <div class="pull-right hover-image" style="padding-right: 2%;">
      <a href="/client/analytics/attendance" style="font-size: 12pt;">
        <button class="btn-analytics" style="text-transform: none; padding: 8px 15px;">
    <img src="{{ asset('storage/img/analytics.png') }}" width="30" height="30"> Analytics
    </button>
    </a>
    </div>
    @endif
  </div>
</div>

<div class="row" style="text-align: center; margin-top: -40px; margin-bottom: -25px;">
  <div class="row seven-cols">
      <div class="col-md-1">
        <div class="hover-image" data-toggle="modal" data-target="#attendanceModal" id="attendance-modal"><h4>Attendance</h4>
      <p><img src="{{ asset('storage/img/attendance.png') }}" width="120" height="120"/></p></div>
      @if(in_array($designation, ['HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President']))
      <a href="/module/attendance/history"><button>Manage</button></a>
      @endif
    </div>
      <div class="col-md-1 hover-image" data-toggle="modal" data-target="#regLoan">
        <h4>Loan</h4>
      <p><img src="{{ asset('storage/img/loan.png') }}" width="120" height="120"/></p>
      @if(in_array($designation, ['HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President']))
      <button>Manage</button>
      @endif
    </div>
      <div class="col-md-1">
        <div class="hover-image" data-toggle="modal" data-target="#evaluationModal" id="evaluation-modal">
        <h4>Evaluation</h4>
      <p><img src="{{ asset('storage/img/evaluation.png') }}" width="120" height="120"/></p></div>
        @if(in_array($designation, ['Operations Manager', 'HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President']))
        @if($depart == 'head' || Auth::user()->user_id == 8888)
      <a href="/evaluation/objectives">
      <button>Manage</button></a>
      @endif
      </div>
      <div class="col-md-1">
        <div class="hover-image" data-toggle="modal" data-target="#examModal" id="exam-modal">
        <h4>Exam</h4>
      <p><img src="{{ asset('storage/img/exam.png') }}" width="120" height="120"/></p></div>
      @if(in_array($designation, ['Product Manager', 'Operations Manager', 'HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President']))
      <a href="/examPanel">
      <button>Manage</button></a>
      @endif
      </div>
      <div class="col-md-1">
        <div class="hover-image" data-toggle="modal" data-target="#absentNoticeModal" id="notice-modal">
        <h4>Absent Notice</h4>
      <p><img src="{{ asset('storage/img/notice_slip.png') }}" width="120" height="120"/></p></div>
      @if(in_array($designation, ['HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President']))
      <a href="/module/absent_notice_slip/history">
      <button>Manage</button></a>
      @endif
      </div>
      <div class="col-md-1">
        <div class="hover-image" data-toggle="modal" data-target="#gatepassModal" id="gatepass-modal"><h4>Gatepass</h4>
      <p><img src="{{ asset('storage/img/pass.png') }}" width="120" height="120"/></p></div>
      @if(in_array($designation, ['HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President']))
      <a href="/client/gatepass/history"><button>Manage</button></a>
      @endif
      </div>
      <div class="col-md-1 hover-image">
        @if(in_array($designation, ['HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President']))
        <a href="/module/hr/applicants">
          @endif
        <h4>HR</h4>
      <p><img src="{{ asset('storage/img/hr.png') }}" width="120" height="120"/></p>
      
    @if(in_array($designation, ['HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President']))
  <button>Manage</button></a>@endif
      </div>
  </div>
</div> --}}

<style type="text/css">
   .btn-analytics{
    font-size: 12pt;
    border-radius: 4px;
    background-color: #239B56;
    color: #fff;
  }
.seven-cols button  {
    height: 60%;
    font-size: 12pt;
    width: 50%;
    border-radius: 4px;
    background-color: #239B56;
    color: #fff;
    margin-top: 15%;
  }
@media (min-width: 768px){
  .seven-cols .col-md-1,
  .seven-cols .col-sm-1,
  .seven-cols .col-lg-1  {
    width: 100%;
    *width: 100%;
  }
}

@media (min-width: 992px) {
  .seven-cols .col-md-1,
  .seven-cols .col-sm-1,
  .seven-cols .col-lg-1 {
    width: 14.285714285714285714285714285714%;
    *width: 14.285714285714285714285714285714%;
  }
}

/**
 *  The following is not really needed in this case
 *  Only to demonstrate the usage of @media for large screens
 */    
@media (min-width: 1200px) {
  .seven-cols .col-md-1,
  .seven-cols .col-sm-1,
  .seven-cols .col-lg-1 {
    width: 14.285714285714285714285714285714%;
    *width: 14.285714285714285714285714285714%;
  }
}
</style>