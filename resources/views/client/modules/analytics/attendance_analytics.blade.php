@extends('client.app')
@section('content')
@include('client.modules.nav_menu')
<div class="col-md-12">
   <h2 class="section-title center">Analytics</h2>
   <a href="/home">
      <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-bottom: -50px; float: left;"></i>
   </a>
</div>

<div id="tabs">
   <ul class="nav nav-tabs" style="text-align: center;">
      <li class="active"><a href="/client/analytics/attendance">Attendance</a></li>
      <li><a href="/client/analytics/notice_slip">Absent Notice</a></li>
      <li><a href="/client/analytics/gatepass">Gatepass</a></li>
      <li><a href="/client/analytics/hr">Human Resource</a></li>
      <li><a href="/client/analytics/exam">Online Exam</a></li>
   </ul>
   <div class="tab-content">
      <div class="tab-pane in active">
         <div class="row">
            <div class="col-md-12" style="text-align: center; text-transform: uppercase; margin: 0 0 1% 0;">
               <span style="font-size: 16pt; font-weight: bold;">Daily Attendance Monitoring</span>
            </div>
            <div class="col-md-3" style="text-align: center;">
               <span class="span-title">Active Employee(s)</span>
               <span class="span-value">{{ $totals['active_employees'] }}</span>
            </div>
            <div class="col-md-3" style="text-align: center;">
               <span class="span-title">Present</span>
               <span class="span-value">{{ $totals['present_today'] }}</span>
            </div>
            <div class="col-md-3" style="text-align: center;">
               <span class="span-title">Planned Leave/Absence</span>
               <span class="span-value">{{ $totals['out_today'] }}</span>
            </div>
            <div class="col-md-3" style="text-align: center;">
               <span class="span-title">Late</span>
               <span class="span-value">{{ $totals['late_today'] }}</span>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div style="text-align: center; font-size: 13pt; padding-bottom: 20px;" id="monthly-stats-filters">Monthly Attendance Statistics:
                  <select style="width: 10%;" class="month filters">
                     <option value="1" {{ date('m') == 1 ? 'selected' : '' }}>January</option>
                     <option value="2" {{ date('m') == 2 ? 'selected' : '' }}>February</option>
                     <option value="3" {{ date('m') == 3 ? 'selected' : '' }}>March</option>
                     <option value="4" {{ date('m') == 4 ? 'selected' : '' }}>April</option>
                     <option value="5" {{ date('m') == 5 ? 'selected' : '' }}>May</option>
                     <option value="6" {{ date('m') == 6 ? 'selected' : '' }}>June</option>
                     <option value="7" {{ date('m') == 7 ? 'selected' : '' }}>July</option>
                     <option value="8" {{ date('m') == 8 ? 'selected' : '' }}>August</option>
                     <option value="9" {{ date('m') == 9 ? 'selected' : '' }}>September</option>
                     <option value="10" {{ date('m') == 10 ? 'selected' : '' }}>October</option>
                     <option value="11" {{ date('m') == 11 ? 'selected' : '' }}>November</option>
                     <option value="12" {{ date('m') == 12 ? 'selected' : '' }}>December</option>
                  </select>
                  <select style="width: 8%;" class="year filters">
                     <option value="2015" {{ date('Y') == 2015 ? 'selected' : '' }}>2015</option>
                     <option value="2016" {{ date('Y') == 2016 ? 'selected' : '' }}>2016</option>
                     <option value="2017" {{ date('Y') == 2017 ? 'selected' : '' }}>2017</option>
                     <option value="2018" {{ date('Y') == 2018 ? 'selected' : '' }}>2018</option>
                     <option value="2019" {{ date('Y') == 2019 ? 'selected' : '' }}>2019</option>
                     <option value="2020" {{ date('Y') == 2020 ? 'selected' : '' }}>2020</option>
                     <option value="2021" {{ date('Y') == 2021 ? 'selected' : '' }}>2021</option>
                     <option value="2022" {{ date('Y') == 2022 ? 'selected' : '' }}>2022</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="inner-box featured">
                  <div class="widget property-agent">
                     <h3 class="widget-title">Absences</h3>
                  </div>
                  <table class="table fixed_header" id="absences-list">
                     <thead>
                     <tr>
                        <th style="width: 230px;">Name</th>
                        <th style="width: 80px; text-align: center;">Day(s)</th>
                     </tr>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
                  <div class="alert">
                     <i class="fa fa-info-circle"></i> Employee(s) with absences on this period: <span id="total-absent" style="font-weight: bold;">0</span> out of <b>{{ $totals['active_employees'] }}</b> employees.
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="inner-box featured">
                  <div class="widget property-agent">
                     <h3 class="widget-title">Lates</h3>
                  </div>
                   <table class="table fixed_header" id="lates-list">
                     <thead>
                     <tr>
                        <th style="width: 230px;">Name</th>
                        <th style="width: 80px; text-align: center;">Minute(s)</th>
                     </tr>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
                  <div class="alert">
                     <i class="fa fa-info-circle"></i> Late employee(s) on this period.
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="inner-box featured">
                  <div class="widget property-agent">
                     <h3 class="widget-title">Perfect Attendance</h3>
                  </div>
                  <table class="table fixed_header" id="perfect-attendance-list">
                     <thead>
                     <tr>
                        <th style="width: 230px;">Name</th>
                        <th style="width: 80px; text-align: center;">Day(s)</th>
                     </tr>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
                  <div class="alert">
                     <i class="fa fa-info-circle"></i> Employee(s) with perfect attendance on this period with no late(s) and absence(s).
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<style type="text/css">
   .span-title{
      display: block;
      font-size: 13pt;
      text-align: center;
   }
   .span-value{
      display: block;
      font-size: 24pt;
      padding: 4%;
   }
   #tabs .nav-tabs > li {
      float: none;
      display: inline-block;
      /*zoom: 1;*/
   }

   .fixed_header{
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
}

.fixed_header tbody{
  display:block;
  width: 100%;
  overflow: auto;
  height: 300px;
}

.fixed_header thead tr {
   display: block;
}

.fixed_header .td-name{
  width: 250px;
}
.fixed_header .td-days{
  width: 80px;
  text-align: center;
}
</style>


@endsection

@section('script')
<script>
   $(document).ready(function(){
      
      loadAbsences();
      // loadPerfectAttendance();
      // loadLates();

      $('#monthly-stats-filters .filters').on('change', function(){
         loadAbsences();
         loadPerfectAttendance();
         loadLates();
      });

      function loadAbsences(){
         var month = $('#monthly-stats-filters .month option:selected').text();
         var year = $('#monthly-stats-filters .year').val();

         $("#absences-list tbody").empty();

         $.ajax({
            url: "/getAbsentEmployees",
            method: "GET",
            data: {month: month, year:year},
            success: function(data) {
               console.log(data.length);
               $.each(data, function(i, d){
                  if (d.days_absent > 0) {
                  var row = '<tr>' +
                           '<td class="td-name">' + d.employee_name + '</td>' +
                           '<td class="td-days">' + d.days_absent + '</td>' +
                   '</tr>';
                }

                   $("#absences-list tbody").append(row);
                   $("#total-absent").html(data.length);
               });
            },
            error: function(data) {
               alert('Error fetching data!');
            }
         });
      }

      function loadLates(){
         var month = $('#monthly-stats-filters .month option:selected').text();
         var year = $('#monthly-stats-filters .year').val();

         $("#lates-list tbody").empty();

         $.ajax({
            url: "/lateEmployees",
            method: "GET",
            data: {month: month, year:year},
            success: function(data) {
               $.each(data, function(i, d){
                  if (d.total_lates > 0) {
                  var row = '<tr>' +
                           '<td class="td-name">' + d.employee_name + '</td>' +
                           '<td class="td-days">' + d.total_lates + '</td>' +
                   '</tr>';
                }

                   $("#lates-list tbody").append(row);  
               });
            },
            error: function(data) {
               alert('Error fetching data!');
            }
         });
      }

      function loadPerfectAttendance(){
         var month = $('#monthly-stats-filters .month option:selected').text();
         var year = $('#monthly-stats-filters .year').val();

         $("#perfect-attendance-list tbody").empty();

         $.ajax({
            url: "/getPerfectAttendance",
            method: "GET",
            data: {month: month, year:year},
            success: function(data) {
               $.each(data, function(i, d){
                  if (d.working_days > 0) {
                  var row = '<tr>' +
                           '<td class="td-name">' + d.employee_name + '</td>' +
                           '<td class="td-days">' + d.working_days + '</td>' +
                   '</tr>';
                }

                   $("#perfect-attendance-list tbody").append(row);  
               });
            },
            error: function(data) {
               alert('Error fetching data!');
            }
         });
      }

      function gatepass_per_dept_chart(){
         var purpose = $('#gatepass-per-dept-filters .purpose').val();
         var year = $('#gatepass-per-dept-filters .year').val();

         $.ajax({
            url: "/module/gatepass/gatepass_per_dept_chart",
            method: "GET",
            data: {purpose: purpose, year: year},
            success: function(data) {
               var departments = [];
               var totals = [];

               for(var i in data) {
                 departments.push(data[i].department);
                 totals.push(data[i].total);
               }

               var chartdata = {
                  labels: departments,
                  datasets : [{
                     backgroundColor: '#2e86c1',
                     data: totals,
                     label: "No. of Gatepass"
                  }]
               };

               var ctx = $("#gatepass-per-dept-chart");

               if (window.absenceGraph != undefined) {
                  window.absenceGraph.destroy();
               }

               window.absenceGraph = new Chart(ctx, {
                  type: 'bar',
                  data: chartdata,
                  options: {
                     responsive: true,
                     legend: {
                        display: true,
                        position: 'bottom',
                        labels:{
                           boxWidth: 13
                        }
                     }
                  }
               });
            },
            error: function(data) {
               console.log(data);
            }
         });
      }
   });
</script>

@endsection