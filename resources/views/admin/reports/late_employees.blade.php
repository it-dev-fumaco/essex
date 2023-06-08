@extends('admin.app')
@section('content')
<div class="row">
	<div class="col-sm-12 col-md-8 col-md-offset-2">
      <div class="inner-box featured">
         <h2 class="title-2">Late Employee(s) exceeding 300 min(s)</h2>
         <div class="row">
            <div class="col-md-12">
               <div class="pull-right">
                  <label>Select Month:</label>
                  <select class="filters" style="width: 150px; height: 30px;" id="selectMonth">
                     <option value="January">January</option>
                     <option value="February">February</option>
                     <option value="March">March</option>
                     <option value="April">April</option>
                     <option value="May">May</option>
                     <option value="June">June</option>
                     <option value="July">July</option>
                     <option value="August">August</option>
                     <option value="September">September</option>
                     <option value="October">October</option>
                     <option value="November">November</option>
                     <option value="December">December</option>
                  </select>
                  <label>Select Year:</label>
                  <select class="filters" style="width: 150px; height: 30px;" id="selectYear">
                     <option value="2015" {{ now()->year == '2015' ? 'selected' : '' }}>2015</option>
                     <option value="2016" {{ now()->year == '2016' ? 'selected' : '' }}>2016</option>
                     <option value="2017" {{ now()->year == '2017' ? 'selected' : '' }}>2017</option>
                     <option value="2018" {{ now()->year == '2018' ? 'selected' : '' }}>2018</option>
                     <option value="2019" {{ now()->year == '2019' ? 'selected' : '' }}>2019</option>
                     <option value="2020" {{ now()->year == '2020' ? 'selected' : '' }}>2020</option>
                     <option value="2021" {{ now()->year == '2021' ? 'selected' : '' }}>2021</option>
                     <option value="2022" {{ now()->year == '2022' ? 'selected' : '' }}>2022</option>
                     <option value="2023" {{ now()->year == '2023' ? 'selected' : '' }}>2023</option>
                     <option value="2024" {{ now()->year == '2024' ? 'selected' : '' }}>2024</option>
                  </select>
               </div>
            </div>
            <div class="col-md-12">
               <table class="table late-employees-table" id="late-employees-report-table">
                  <thead>
                     <tr>
                        <th style="width: 30%;">Access ID</th>
                        <th style="width: 40%;">Employee Name</th>
                        <th style="width: 30%;">Total Late (in mins)</th>
                     </tr>
                  </thead>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>

<style type="text/css">
   .late-employees-table thead th, tbody td{
      text-align: center;
   }
</style>
@endsection

@section('script')
<script>
$(document).ready(function() {
   $('#late-employees-report-table').DataTable({
      "bLengthChange": false,
      "order": [[ 2, "desc" ]],
      "searching": false,
      "columnDefs": [{
          targets: "_all",
          orderable: false
       }]
   });

   $('.filters').on('change', function(){
      loadLateEmployees();
   });

   loadLateEmployees();
   function loadLateEmployees(){
      var data = {
         'month': $('#selectMonth').val(),
         'year': $('#selectYear').val()
      }

      $.ajax({
         type: 'GET',
         url: "/admin/getLateEmployees",
         data: data,
         dataType: 'json',
         cache: false,
         success: function(data){
            var table = $('#late-employees-report-table').DataTable();
            table.clear();
            if (data != '') {
               $.each(data, function(i, d){
                  table.row.add([ data[i].access_id, data[i].employee_name, data[i].total_lates ]);
               });
            }
            table.draw();
         }
      });
   }
});
</script>
@endsection