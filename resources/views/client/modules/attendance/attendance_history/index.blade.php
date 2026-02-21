@extends('client.app')
@section('content')
@include('client.modules.nav_menu')
<div class="col-md-12">
   <h2 class="section-title center">Attendance</h2>
   <a href="/home">
      <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-bottom: -50px; float: left;"></i>
   </a>
</div>

<div id="tabs">
   <ul class="nav nav-tabs" style="text-align: center;">
      {{-- <li><a href="/module/attendance/analytics">Analytics</a></li> --}}
      <li class="active"><a href="/module/attendance/history">Attendance History</a></li>
      <li><a href="/module/attendance/employee_shifts">Shift(s)</a></li>
      <li><a href="/module/attendance/biometric_adjustments">Attendance Adjustment(s)</a></li>
      <li><a href="/module/attendance/late_employees">Late Employee(s) Monitoring</a></li>
      <li><a href="/module/attendance/holiday_entry">Holiday/Events List</a></li>
   </ul>
   <div class="tab-content">
      <div class="tab-pane in active">
         <div class="row">
            <div class="inner-box featured">
               <h2 class="title-2">Attendance History</h2>
               <div class="row">
                  <div id="alert-message" class="alert-message"></div>
                  <div class="col-md-4">
                     <label>Employee:</label>
                     <select class="employee attendanceHistoryFilter" id="attendanceHistoryFilter_employee" style="width: 70%;">
                        @forelse(in_array($designation, ['Human Resources Head', 'HR Payroll Assistant', 'HR Assistant']) ? $employees : $employees_per_dept as $employee)
                        <option value="{{ $employee->user_id }}" {{ Auth::user()->user_id == $employee->user_id ? 'selected' : '' }}>{{ $employee->employee_name }}</option>
                        @empty
                        <option disabled>No Records Found.</option>
                        @endforelse
                     </select>
                  </div>
                  
                  <div class="col-md-5" id="datepairExample">
                     <label style="">From:</label>
                     <input type="text" class="date attendanceHistoryFilter" autocomplete="off" id="attendanceHistoryFilter_start" value="{{ Carbon\Carbon::parse('this week -7 days')->format('Y-m-d') }}" style="width: 40%;">
                     
                   
                      <label style="">To:</label>
                     <input type="text" class="date attendanceHistoryFilter" autocomplete="off" id="attendanceHistoryFilter_end" value="{{ Carbon\Carbon::parse('now')->format('Y-m-d') }}" style="width: 40%;">
                  </div>
                  <div class="col-md-3" style="text-align: center;">
                     <a href="#" class="btn btn-primary" style="padding: 6px 12px;" id="refresh-attendance-btn" ><i class="fa fa-refresh"></i> Update Attendance</a>
                  </div>
                  <div class="col-md-12">
                     <div id="attendance-history"></div>
                  </div>
               </div>
               </div>
            </div>
               </div>
            </div>
         </div>
      </div>
</div>
<div class="loading" id="loading" align="center">Loading&#8230;</div>

@endsection

@section('script')
<script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.timepicker.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/jquery.timepicker.css') }}" />
<script type="text/javascript" src="{{ asset('css/js/datepicker/datepair.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.datepair.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/datepicker/bootstrap-datepicker.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/bootstrap-datepicker.css') }}" />
<script>
   $(document).ready(function(){
      loadAttendanceHistory();
       $('#loading').hide();
      console.log('ready');

    $('#datepairExample .date').datepicker({
        'format': 'yyyy-mm-dd',
        'autoclose': true
    });


    $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
      

      $('.attendanceHistoryFilter').on('change', function(){
         loadAttendanceHistory();
      });

      function loadAttendanceHistory(){
         var start = $('#attendanceHistoryFilter_start').val();
         var end = $('#attendanceHistoryFilter_end').val();
         var user_id = $('#attendanceHistoryFilter_employee').val();
         
         data = {
            start : start,
            end : end
         }
         console.log(data);
         $.ajax({
            url: "/attendance/history/" + user_id,
            data: data,
            success: function(data){
               $('#attendance-history').html(data);

            }
         });
      }

         $('#refresh-attendance-btn').on('click', function(e){
         e.preventDefault();
         // var employee = $('#employee-id').text();
         var from = $('#attendanceHistoryFilter_start').val();
         var to = $('#attendanceHistoryFilter_end').val();

         data = {
            from: from,
            to: to
         }
            $.ajax({
            type: 'POST',
            url: '/updateAllBiologs',
            data: data,

            beforeSend: function(){
               $("#refresh-attendance-btn").text("Updating...");
               $('#loading').show();
            },
            success: function(data){
               loadAttendanceHistory();
               console.log(data);
                $('.alert-message').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><center>' + data.message + '</center></div>');
                $('#loading').hide();
            },
            complete: function(){
              $('#loading').hide();
               $("#refresh-attendance-btn").html("<i class=\"fa fa-refresh\"></i> Update Attendance");
            }
         });
      });



   });
</script>

@endsection

<style>
#tabs .nav-tabs > li {
   float: none;
   display: inline-block;
   /*zoom: 1;*/
}
</style>

<style type="text/css">
   /* Absolute Center Spinner */
  .loading {
    position: fixed;
    z-index: 999;
    height: 2em;
    width: 2em;
    overflow: visible;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
  }

  /* Transparent Overlay */
  .loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  margin-left: -4em;
    background-color: rgba(0,0,0,0.3);
  }

  /* :not(:required) hides these rules from IE9 and below */
  .loading:not(:required) {
    /* hide "loading..." text */
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
  }

  .loading:not(:required):after {
    content: '';
    display: block;
    font-size: 10px;
    width: 1em;
    height: 1em;
    margin-top: -0.5em;
    -webkit-animation: spinner 1500ms infinite linear;
    -moz-animation: spinner 1500ms infinite linear;
    -ms-animation: spinner 1500ms infinite linear;
    -o-animation: spinner 1500ms infinite linear;
    animation: spinner 1500ms infinite linear;
    border-radius: 0.5em;
    -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
    box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
  }

/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
</style>