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
      <li ><a href="/module/attendance/history">Attendance History</a></li>
      <li><a href="/module/attendance/employee_shifts">Shift(s)</a></li>
      <li><a href="/module/attendance/biometric_adjustments">Attendance Adjustment(s)</a></li>
      <li><a href="/module/attendance/late_employees">Late Employee(s) Monitoring</a></li>
      <li class="active"><a href="/module/attendance/holiday_entry">Holiday/Events List</a></li>
   </ul>
   <div class="tab-content" id="datepairExample">
    @include('client.modules.attendance.holiday_entry.modals.add')
      <div class="tab-pane in active">
         <div class="row">
            <div class="col-sm-12 col-md-8 col-md-offset-2">
               <div class="inner-box featured">
                  <h2 class="title-2">Holiday/Events List</h2>
                  <div class="row">
                     <div class="col-md-12">
                      @if(session("message"))
                         <div class='alert alert-success alert-dismissible'>
                            <button type='button' class='close' data-dismiss='alert'>&times;</button>
                           <center>{!! session("message") !!}</center>
                        </div>
                         @endif
                         <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-holiday-modal" style="float: left; margin-bottom: -55px; z-index: 1;">
                              <i class="fa fa-plus"></i> Holiday
                         </a>
                     </div>
                     <div class="col-md-12">
                        <div class="table-responsive-wrapper">
                           <table class="table" id="example">
                              <thead>
                                 <tr>
                                    <th>ID</th>
                                    <th>Holiday Date</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                 </tr>
                              </thead>
                              <tbody class="table-body">
                                 @forelse($holidays as $holiday)
                                 <tr>
                                    <td>{{ $holiday->id }}</td>
                                    <td>{{ date('F d, Y', strtotime($holiday->holiday_date)) }}</td>
                                    <td>{{ $holiday->description }}</td>
                                    <td>{{ $holiday->category }}</td>
                                    <td>
                                       <a href="#" data-toggle="modal" data-target="#edit-holiday-{{ $holiday->id }}">
                                 <i class="fa fa-pencil icon-edit"></i>
                                       </a>
                                       <a href="#" data-toggle="modal" data-target="#delete-holiday-{{ $holiday->id }}">
                                 <i class="fa fa-trash icon-delete"></i> 
                                       </a>
                                    </td>
                                </tr>
                                 @include('client.modules.attendance.holiday_entry.modals.edit')
                                 @include('client.modules.attendance.holiday_entry.modals.delete')
                                 @empty
                                 <tr>
                                    <td colspan="3">No Holiday(s) Found.</td>
                                 </tr>
                                 @endforelse
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
       </div>
   </div>
</div>

@endsection
@section('script')
<script type="text/javascript" src="{{ asset('css/js/datepicker/bootstrap-datepicker.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/bootstrap-datepicker.css') }}" />
<script>
$(document).ready(function() {
$('#example').DataTable({
      "bLengthChange": false,
      "ordering": false,
      "dom": '<"top"f>rt<"bottom"ip><"clear">'
   });

$('#datepairExample .date').datepicker({
        'format': 'yyyy-mm-dd',
        'autoclose': true
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

input, select{
   height: 35px;
   width: 100%;
}
</style>