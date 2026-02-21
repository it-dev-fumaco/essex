@if(in_array($designation, ['HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President', 'HR Assistant']))
<div class="row" style="padding: 0; margin: -50px 0 0 0;">
  <div class="col-md-5">
    <table style="width: 80%; margin: 0; padding: 0" border="0" id="nav-menu">
      <tr>
        <td class="hover-image">
          <a href="/module/attendance/history">
            <img src="{{ asset('storage/img/attendance.png') }}"/>
          </a>
        </td>
        <td class="hover-image">
          <img src="{{ asset('storage/img/loan.png') }}"/>
        </td>
        <td class="hover-image">
          <a href="/evaluation/objectives">
            <img src="{{ asset('storage/img/evaluation.png') }}"/>
          </a>
        </td>
        <td class="hover-image">
          <a href="/examPanel">
            <img src="{{ asset('storage/img/exam.png') }}"/>
          </a>
        </td>
        <td class="hover-image">
          <a href="/module/absent_notice_slip/history">
            <img src="{{ asset('storage/img/notice_slip.png') }}"/>
          </a>
        </td>
        <td class="hover-image">
          <a href="/client/gatepass/history">
            <img src="{{ asset('storage/img/pass.png') }}"/>
          </a>
        </td>
        <td class="hover-image">
          <a href="/module/hr/applicants">
            <img src="{{ asset('storage/img/hr.png') }}"/>
          </a>
        </td>
        <td class="hover-image">
          <a href="/client/analytics/attendance">
            <img src="{{ asset('storage/img/analytics.png') }}"/>
          </a>
        </td>
      </tr>
    </table>
  </div>
</div>
@endif

<style type="text/css">
  #nav-menu img{
    width: 30px;
    height: 30px;
  }

  #nav-menu td{
    padding: 0;
    margin: 0;
    z-index: 1;
    /*height: 70px;*/
    /*width: 14.285714285714285714285714285714%;*/
  } 
</style>
{{-- 

<div class="row" style="text-align: center; margin-top: -40px; margin-bottom: -25px;">
	<div class="row seven-cols">
    	<div class="col-md-1">
    		<div class="hover-image" data-toggle="modal" data-target="#attendanceModal" id="attendance-modal"><h4>Attendance</h4>
			<p><img src="{{ asset('storage/img/attendance.png') }}" width="120" height="120"/></p></div>
			<a href="/module/attendance/history"><button>Manage</button></a>
		</div>
    	<div class="col-md-1 hover-image" data-toggle="modal" data-target="#regLoan">
    		<h4>Loan</h4>
			<p><img src="{{ asset('storage/img/loan.png') }}" width="120" height="120"/></p>
			<button>Manage</button>
		</div>
    	<div class="col-md-1">
        <div class="hover-image" data-toggle="modal" data-target="#evaluationModal" id="evaluation-modal">
    		<h4>Evaluation</h4>
			<p><img src="{{ asset('storage/img/evaluation.png') }}" width="120" height="120"/></p></div>
      <a href="/evaluation/objectives">
			<button>Manage</button></a>
    	</div>
    	<div class="col-md-1">
        <div class="hover-image" data-toggle="modal" data-target="#examModal" id="exam-modal">
    		<h4>Exam</h4>
			<p><img src="{{ asset('storage/img/exam.png') }}" width="120" height="120"/></p></div>
      <a href="/examPanel">
			<button>Manage</button></a>
    	</div>
    	<div class="col-md-1">
        <div class="hover-image" data-toggle="modal" data-target="#absentNoticeModal" id="notice-modal">
    		<h4>Absent Notice</h4>
			<p><img src="{{ asset('storage/img/notice_slip.png') }}" width="120" height="120"/></p></div>
      <a href="/module/absent_notice_slip/history">
			<button>Manage</button></a>
    	</div>
    	<div class="col-md-1">
    		<div class="hover-image" data-toggle="modal" data-target="#gatepassModal" id="gatepass-modal"><h4>Gatepass</h4>
			<p><img src="{{ asset('storage/img/pass.png') }}" width="120" height="120"/></p></div>
			<a href="/client/gatepass/history"><button>Manage</button></a>
    	</div>
    	<div class="col-md-1 hover-image">
    		<a href="/module/hr/applicants">
    		<h4>HR</h4>
			<p><img src="{{ asset('storage/img/hr.png') }}" width="120" height="120"/></p>
			<button>Manage</button></a>
    	</div>
  </div>
</div>

<style type="text/css">
  .btn-analytics{
    font-size: 12pt;
    border-radius: 4px;
    background-color: #239B56;
    color: #fff;
  }
.seven-cols button  {
    /*height: 60%;*/
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

 --}}