@extends('client.app')
@section('content')
@include('client.modules.nav_menu')
<div class="col-md-12">
   <h2 class="section-title center">Absent Notice Slip</h2>
   <a href="/home">
      <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-bottom: -50px; float: left;"></i>
   </a>
</div>

<div id="tabs">
   <ul class="nav nav-tabs" style="text-align: center;">
      {{-- <li><a href="/module/absent_notice_slip/analytics">Analytics</a></li> --}}
      <li class="active"><a href="/module/absent_notice_slip/history">Absent Notice History</a></li>
      {{-- <li><a href="/module/absent_notice_slip/leave_analytics/{{ Carbon\Carbon::parse('first day of this month')->format('Y-m-d') }}/{{ Carbon\Carbon::parse('last day of this month')->format('Y-m-d') }}">Employee Leave Analytics</a></li> --}}
      <li><a href="/module/absent_notice_slip/leave_approvers">Leave Approver(s)</a></li>
      <li><a href="/module/absent_notice_slip/leave_balances">Leave Balance(s)</a></li>
   </ul>
   <div class="tab-content">
      <div class="tab-pane in active">
         <div class="row">
            <div class="col-sm-7">
               <select id="selectEmpNotice" class="manageNoticeFltr" style="margin-right: 10px; width: 210px;">
                  <option value="">All Employee(s)</option>
                  @forelse(in_array($designation, ['Human Resources Head', 'HR Payroll Assistant']) ? $employees : $employees_per_dept as $employee)
                  <option value="{{ $employee->user_id }}">{{ $employee->employee_name }}</option>
                  @empty
                  <option disabled>No Records Found.</option>
                  @endforelse
               </select>
               <select id="selectLeave" class="manageNoticeFltr" style="margin-right: 10px; width: 150px;">
                  <option value="">All Leave Type(s)</option>
                  @forelse($absent_type_list as $absence_type)
                  <option value="{{ $absence_type->leave_type_id }}">{{ $absence_type->leave_type }}</option>
                  @empty
                  <option disabled>No Records Found.</option>
                  @endforelse
               </select>
               <select id="selectDept" class="manageNoticeFltr" style="width: 210px;">
                  <option value="">All Department(s)</option>
                  @forelse($department_list as $row)
                  <option value="{{ $row->department_id }}">{{ $row->department }}</option>
                  @empty
                  <option disabled>No Records Found.</option>
                  @endforelse
               </select>
               
            </div>
            <div class="col-md-5" id="datepairExample">
               <label style="margin-left: 5px;">From</label>
               <input type="text" class="date manageNoticeFltr" autocomplete="off" id="manageNotice_start" value="{{ Carbon\Carbon::parse('first day of this month')->format('Y-m-d') }}">
               <label style="margin-left: 10px;">To</label>
               <input type="text" class="date manageNoticeFltr" autocomplete="off" id="manageNotice_end" value="{{ Carbon\Carbon::parse('last day of this month')->format('Y-m-d') }}">
            </div>
            
            <div class="col-md-12">
               <div id="notice-history-table"></div>
            </div>
         </div>
      </div>
   </div>
</div>
@include('client.modals.view_notice')

<style>
#tabs .nav-tabs > li {
   float: none;
   display: inline-block;
   /*zoom: 1;*/
}
input, select{
   height: 35px;
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
   $(document).ready(function(){

       $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

    $('#datepairExample .date').datepicker({
        'format': 'yyyy-mm-dd',
        'autoclose': true
    });

    $('.manageNoticeFltr').on('change', function(){
      loadNoticeHistory();
      });
$(document).on('click', '#manage_notices_pagination a', function(event){
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      loadNoticeHistory(page);
   });

      loadNoticeHistory();
      function loadNoticeHistory(page){
         var start = $('#manageNotice_start').val();
         var end = $('#manageNotice_end').val();
         var employee = $('#selectEmpNotice').val();
         var department = $('#selectDept').val();
         var leave_type = $('#selectLeave').val();
         
         data = {
            start : start,
            end : end,
            employee : employee,
            department : department,
            leave_type : leave_type,
         }

         $.ajax({
            url: "/getAbsentNotices?page="+page,
            data: data,
            success:function(data){
               $('#notice-history-table').html(data);
            }
         });
      }

      $(document).on('click', '#view-notice', function(event){
      event.preventDefault();
      var id = $(this).data('id');
      data = {'id' : id }

      $.ajax({  
            url:"/notice_slip/getDetails",  
            data:data,  
            success:function(data){
               $('#viewNoticeModal .date-from-val').val(data.date_from);
               $('#viewNoticeModal .date-to-val').val(data.date_to);
               $('#viewNoticeModal .leave-type-id-val').val(data.leave_type_id);
               $('#viewNoticeModal .user-id-val').val(data.user_id);

               $('#viewNoticeModal .notice-id-val').val(data.notice_id);
                  $('#viewNoticeModal .notice-id').text(data.notice_id);
                  $('#viewNoticeModal .employee-name').text(data.employee_name);
                  $('#viewNoticeModal .department').text(data.department);
                  $('#viewNoticeModal .leave-type').text(data.leave_type);
                  $('#viewNoticeModal .date-from').text(data.date_from);
                  $('#viewNoticeModal .time-from').text(data.time_from);
                  $('#viewNoticeModal .date-to').text(data.date_to);
                  $('#viewNoticeModal .time-to').text(data.time_to);
                  $('#viewNoticeModal .reported-through').text(data.means);
                  $('#viewNoticeModal .time-reported').text(data.time_reported);
                  $('#viewNoticeModal .received-by').text(data.info_by);
                  $('#viewNoticeModal .approved-by').text(data.approved_by);
                  $('#viewNoticeModal .date-approved').text(data.approved_date);
                  $('#viewNoticeModal .reason').text(data.reason);
                  $('#viewNoticeModal .remarks').text(data.remarks);
                  $('#viewNoticeModal .date-filed').text(data.date_filed);

                  var status = data.status;
                  if (status.toLowerCase() == 'approved' || status.toLowerCase() == 'for approval') {
                     $("#manager-cancel-notice").show();
                  }else{
                     $("#manager-cancel-notice").hide();
                  }

                  switch (status.toLowerCase()){
                     case 'approved': 
                        $('#viewNoticeModal .hidden-row').attr('hidden', false);
                        $("#viewNoticeModal .status").html("<span class=\"label label-primary\"><i class=\"fa fa-thumbs-o-up\"></i> Approved</span>");
                        $("#iframe-print").attr("src", "/printNotice/" + data.notice_id);
                        break;
                     case 'cancelled':
                        $('#viewNoticeModal .hidden-row').attr('hidden', true);
                        $("#viewNoticeModal .status").html("<span class=\"label label-danger\"><i class=\"fa fa-ban\"></i> Cancelled</span>");
                        break;
                     case 'disapproved': 
                        $('#viewNoticeModal .hidden-row').attr('hidden', true);
                        $("#viewNoticeModal .status").html("<span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Disapproved</span>");
                        break;
                     case 'deferred': 
                        $('#viewNoticeModal .hidden-row').attr('hidden', true);
                        $("#viewNoticeModal .status").html("<span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Deferred</span>");
                        break;
                     default:
                        $('#viewNoticeModal .hidden-row').attr('hidden', true);
                        $("#viewNoticeModal .status").html("<span class=\"label label-warning\"><i class=\"fa fa-clock-o\"></i> For Approval</span>");
                  }

            $('#viewNoticeModal').modal('show'); 
            }  
        });  
   });

      $('#manager-cancel-notice-frm').on("submit", function(event){
      event.preventDefault();
      $.ajax({
         url:"/notice_slip/cancelNotice",
         type:"POST",
         data: $(this).serialize(),
            success:function(data){
               loadNoticeHistory();
               $.bootstrapGrowl("<center><i class=\"fa fa-ban\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + data.message + "</span></center>", {
                        type: 'danger',
                        align: 'center',
                        delay: 4000,
                        width: 450,
                        offset: {from: 'top', amount: 300},
                        stackup_spacing: 20
                    });
               $('#viewNoticeModal').modal('hide');
            }
        });  
   });
   });
</script>

@endsection

