@extends('client.app')
@section('content')
@include('client.modules.nav_menu')
<div class="col-md-12">
   <h2 class="section-title center">Online Examination System</h2>
   <a href="/home">
      <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-bottom: -50px; float: left;"></i>
   </a>
</div>

<div id="online-exam-tab">
   @include('client.tab_header_admin', ['current_page' => '/tabExamReport'])
   <div class="tab-content">
      <div class="tab-pane in active" id="tab-exam-report">
         <div class="row">
            <div class="col-md-12">
               <div class="inner-box featured">
                  <h2 class="title-2">Examination Frequent Mistakes Report</h2>
                  <div style="float: left; z-index: 1;" id="datepairExample">
                     <span>Date of Exam: <input type="text" class="exam-date date filters"{{--  value="{{ date('Y-m-d') }}" --}}></span>
                     <span>User Type: 
                        <select class="user-type filters">
                           <option value="">Select User Type</option>
                           <option value="Employee">Employee</option>
                           <option value="Applicant">Applicant</option>
                        </select>
                     </span>
                  </div>
                  <table id="exam-report-table" class="table">
                     <thead>
                        <tr>
                           <th>Examinee</th>
                           <th>Exam Title</th>
                           <th>Exam Group</th>
                           <th>Date Taken</th>
                           <th>No. of Items</th>
                           <th>Total Score</th>
                           <th>Average Score</th>
                           <th>Actions</th>
                        </tr>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<style>
#online-exam-tab .nav-tabs > li {
   float: none;
   display: inline-block;
}
</style>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.timepicker.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/jquery.timepicker.css') }}" />
<script type="text/javascript" src="{{ asset('css/js/datepicker/datepair.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.datepair.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/datepicker/bootstrap-datepicker.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/bootstrap-datepicker.css') }}" />

<script>
   $(document).ready(function() {


      $('#datepairExample .date').datepicker({
        'format': 'yyyy-mm-dd',
        'autoclose': true
    });

    // initialize datepair
    $('#datepairExample').datepair();


    $('#exam-report-table').DataTable({
         "bLengthChange": false,
         "ordering": false,
         // "pageLength": 2,
         "dom": '<"top"f>rt<"bottom"ip><"clear">'
      });

     $('.filters').on('change', function(){
      loadExamReport();
   });

    loadExamReport();

      function loadExamReport(){
         var exam_date = $('#datepairExample .exam-date').val();
         var user_type = $('#datepairExample .user-type').val();
      
      data = {
         exam_date : exam_date,
         user_type : user_type
      }
         $.ajax({
            url: "/tabExamReport",
            method: "GET",
            data: data,
            success: function(data) {
               var table = $('#exam-report-table').DataTable();
               table.clear();
               if (data != '') {
                  $.each(data, function(i, d){
                     var average = (100*(data[i].examinee_score / data[i].exam_items)).toFixed(2);
                     table.row.add([ 
                        data[i].employee_name, 
                        data[i].exam_title, 
                        data[i].exam_group_description,
                        data[i].date_taken,
                        data[i].exam_items,
                        data[i].examinee_score,
                        average +  ' %',
                        '<a href=\"/viewExamResult/' + data[i].examinee_id + '/' + data[i].exam_id + '\" class=\"hover-icon\"><i class=\"fa fa-search\" style=\"font-size: 15pt; color: #2980B9;\"></i> View Result</a>'
                     ]);
                  });
               }
               table.draw();
            },
            error: function(data) {
               alert('Error fetching data!');
            }
         });
      }
   });
</script>
@endsection