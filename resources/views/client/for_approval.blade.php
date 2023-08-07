@extends('client.app')
@section('content')
<div class="row">
   <div class="col-sm-12">
      <h2 class="section-title center" style="margin-top: -50px;">Awaiting for Approval</h2>
      <a href="/home">
         <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-top: -70px; float: left;"></i>
      </a>
      <div class="tabs-section">
         <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-notices-for-approval" data-toggle="tab"> Absent Notice Slip <span class="badge badge-error" style="background-color: #CB4335; font-size: 10pt;" id="badgePendingNotice">0</span></a></li>
            @if(count($handledDepts) > 0 || in_array($designation, ['Human Resources Head', 'HR Payroll Assistant']))
            <li><a href="#tab-notice-history" data-toggle="tab">Absent Notice History</a></li>
            @endif
            @if(in_array($designation, ['Operations Manager', 'President', 'Director of Operations', 'Product Manager', 'Human Resources Head', 'HR Payroll Assistant']))
            <li><a href="#tab-gatepass-for-approval" data-toggle="tab">Gatepass <span class="badge badge-error" style="background-color: #CB4335; font-size: 10pt;" id="badgePendingGatepass">0</span></a></li>
            <li><a href="#tab-gatepass-history" data-toggle="tab">Gatepass History</a></li>
             @endif
         </ul>
         <div class="tab-content">
            <div class="tab-pane in active" id="tab-notices-for-approval">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="inner-box featured">
                        <div id="pending-notice-message"></div>
                        <div id="notices-for-approval"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="tab-pane" id="tab-gatepass-for-approval">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="inner-box featured">
                        <div id="pending-gatepass-message"></div>
                        <div id="gatepasses-for-approval"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="tab-pane" id="tab-gatepass-history">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="inner-box featured">
                        <div class="row">
                              <div class="col-sm-6">
                                       <select style="width: 180px; margin-left: 3%;" id="selectEmpGatepass" class="manageGatepassFltr">
                                          <option value="">All Employees</option>
                                          @forelse($employees as $employee)
                                          <option value="{{ $employee->user_id }}">{{ $employee->employee_name }}</option>
                                          @empty
                                          <option disabled>No Records Found.</option>
                                          @endforelse
                                       </select>
                                       <select style="width: 180px; margin-left: 3%;" id="itemTypeGatepass" class="manageGatepassFltr">
                                          <option value="">All Item Types</option>
                                          <option value="Returnable">Returnable</option>
                                          <option value="Unreturnable">Unreturnable</option>
                                       </select>
                                    </div>
                              <div class="col-sm-12" style="margin-top: 1%;">
                                 <div id="gatepass-history-message"></div>
                        <div id="gatepass-history"></div>
                              </div>
                           </div>
                        
                     </div>
                  </div>
               </div>
            </div>
         
            <div class="tab-pane" id="tab-notice-history">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="inner-box featured">
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
                           <div class="col-sm-12" style="margin-top: 1%;">
                        <div id="notice-history-message"></div>
                        <div id="notice-history"></div>
                     </div>
                  </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<iframe id="iframe-print" hidden></iframe>
   
   @include('client.modals.gatepass_actions')
   @include('client.modals.notice_slip_actions')
   @include('client.modals.view_notice')
   @include('client.modals.view_gatepass')

@endsection

@section('script')
<script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.timepicker.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/jquery.timepicker.css') }}" />
<script type="text/javascript" src="{{ asset('css/js/datepicker/datepair.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.datepair.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/datepicker/bootstrap-datepicker.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/bootstrap-datepicker.css') }}" />

<script>

   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });


   // initialize input widgets first
    $('#datepairExample .time').timepicker({
        'showDuration': true,
        'timeFormat': 'g:ia'
    });

    $('#datepairExample .date').datepicker({
        'format': 'yyyy-mm-dd',
        'autoclose': true
    });

    // initialize datepair
    $('#datepairExample').datepair();

   loadPendingNotices();
   loadPendingGatepass();
   countPendingGatepass();
   countPendingNotices();

   $(document).on('click', '#view-gatepass', function(event){
      event.preventDefault();
      var id = $(this).data('id');
      data = {'id' : id }
      $.ajax({  
            url:"/gatepass/getDetails",  
            data:data,  
            success:function(data){  
               $('#viewGatepassModal .gatepass-id').text(data.gatepass_id);
               $('#viewGatepassModal .employee-name').text(data.employee_name);
                  $('#viewGatepassModal .date-filed').text(data.date_filed);
                  $('#viewGatepassModal .time').text(data.time);
                  $('#viewGatepassModal .items').text(data.item_description);
                  $('#viewGatepassModal .purpose').text(data.purpose);
                  $('#viewGatepassModal .returned-on').text(data.returned_on);
                  $('#viewGatepassModal .company-name').text(data.company_name);
                  $('#viewGatepassModal .address').text(data.address);
                  $('#viewGatepassModal .tel-no').text(data.tel_no);
                  $('#viewGatepassModal .remarks').text(data.remarks);
                  $('#viewGatepassModal .item-type').text(data.item_type);
                  $('#viewGatepassModal .status').text(data.status);
                  $('#viewGatepassModal .approved-by').text(data.approved_by);
                  $('#viewGatepassModal .date-approved').text(data.approved_date);
                  var status = data.status;
                  switch (status.toLowerCase()){
                     case 'approved': 
                        $('#viewGatepassModal .hidden-row').attr('hidden', false);
                        $("#viewGatepassModal .status").html("<span class=\"label label-primary\"><i class=\"fa fa-thumbs-o-up\"></i> Approved</span>");
                        break;
                     case 'cancelled':
                     $('#viewGatepassModal .hidden-row').attr('hidden', true);
                        $("#viewGatepassModal .status").html("<span class=\"label label-danger\"><i class=\"fa fa-ban\"></i> Cancelled</span>");
                        break;
                     case 'disapproved': 
                     $('#viewGatepassModal .hidden-row').attr('hidden', true);
                        $("#viewGatepassModal .status").html("<span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Disapproved</span>");
                        break;
                     case 'deferred': 
                     $('#viewGatepassModal .hidden-row').attr('hidden', true);
                        $("#viewGatepassModal .status").html("<span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Deferred</span>");
                        break;
                     default:
                     $('#viewGatepassModal .hidden-row').attr('hidden', true);
                        $("#viewGatepassModal .status").html("<span class=\"label label-warning\"><i class=\"fa fa-clock-o\"></i> For Approval</span>");
                  }
            $('#viewGatepassModal').modal('show');
            }
        });
      
   });

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

   $(document).on('click', '#manage_notices_pagination a', function(event){
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      loadFiledNotices(page);
   });

   $('.manageNoticeFltr').on('change', function(){
      loadFiledNotices();
   });

   function loadPendingGatepass(page){
      $.ajax({
         url: "/gatepass/forApproval/fetch?page="+page,
         success: function(data){
            $('#gatepasses-for-approval').html(data);
         }
      });
   }

   function loadPendingNotices(page){
      $.ajax({
         url: "/notice_slip/forApproval/fetch?page="+page,
         success: function(data){
            $('#notices-for-approval').html(data);
         }
      });
   }

   function countPendingGatepass(page){
      $.ajax({
         url: "/countPendingGatepass",
         success: function(data){
            $('#badgePendingGatepass').text(data);
         }
      });
   }

   function countPendingNotices(page){
      $.ajax({
         url: "/countPendingNotices",
         success: function(data){
            $('#badgePendingNotice').text(data);
         }
      });
   }

   $(document).on('click', '#pending_notices_pagination a', function(event){
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      loadPendingNotices(page);
   });

   $(document).on('click', '#pending_gatepass_pagination a', function(event){
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      loadPendingGatepass(page);
   });

   $(document).on('click', '#actionGatepass', function(event){
      event.preventDefault();
      var id = $(this).data('id');
      data = {'id' : id };
      $.ajax({  
         url:"/gatepass/getDetails",  
         data:data,  
         success:function(data){  
            $('#action-gatepass-form .gatepass_id').val(data.gatepass_id);
            $('#action-gatepass-form .status').val(data.status);
            $('#action-gatepass-form .item_type').val(data.item_type);
            $('#action-gatepass-form .gatepass_id_txt').text(data.gatepass_id);
            $('#action-gatepass-form .employee_name').text(data.employee_name);
            $('#action-gatepass-form .date_filed').text(data.date_filed);
            $('#action-gatepass-form .time').text(data.time);
            $('#action-gatepass-form .item_description').text(data.item_description);
            $('#action-gatepass-form .purpose').text(data.purpose);
            $('#action-gatepass-form .returned_on').text(data.returned_on);
            $('#action-gatepass-form .company_name').text(data.company_name);
            $('#action-gatepass-form .address').text(data.address);
            $('#action-gatepass-form .tel_no').text(data.tel_no);
            $('#action-gatepass-form .remarks').text(data.remarks);
            $('#actionGatepassModal').modal('show'); 
         }
      });
   });

   $(document).on('click', '#actionNotice', function(event){
      event.preventDefault();
      var id = $(this).data('id');
      data = {'id' : id };
      $.ajax({
         url:"/notice_slip/getDetails",  
         data:data,  
         success:function(data){  
            $('#action-notice-form .notice_id').val(data.notice_id);
            $('#action-notice-form .status').val(data.status);
            $('#action-notice-form .employee_name').text(data.employee_name);
            $('#action-notice-form .department').text(data.department);
            $('#action-notice-form .leave_type').text(data.leave_type);
            $('#action-notice-form .date_from_txt').text(data.date_from);
            $('#action-notice-form .date_to_txt').text(data.date_to);
            $('#action-notice-form .means').text(data.means);
            $('#action-notice-form .reason').text(data.reason);
            $('#action-notice-form .date_filed').text(data.date_filed);
            $('#action-notice-form .time_from').text(data.time_from);
            $('#action-notice-form .time_to').text(data.time_to);
            $('#action-notice-form .info_by').text(data.info_by);
            $('#action-notice-form .remarks').text(data.remarks);
            $('#action-notice-form .status_txt').text(data.status);
            $('#action-notice-form .notice_id_txt').text(data.notice_id);
            $('#action-notice-form .leave_type_id').val(data.leave_type_id);
            $('#action-notice-form .date_from').val(data.date_from);
            $('#action-notice-form .date_to').val(data.date_to);
            $('#action-notice-form .user_id').val(data.user_id);
            $('#actionNoticeModal').modal('show'); 
         }
      });
   });

   $('#action-notice-form').on("submit", function(event){
      event.preventDefault();
      $.ajax({
         url:"/notice_slip/updateStatus",
         type:"POST",
         data:$(this).serialize(),
         success:function(data){
            loadPendingNotices();
            $("#pending-notice-message").html("<div class='alert alert-success alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>" + data.message + "</center></div>");
            $('#actionNoticeModal').modal('hide');
         }
      });
   });

   $('#action-gatepass-form').on("submit", function(event){
      event.preventDefault();
      $.ajax({
         url:"/gatepass/updateStatus",
         type:"POST",
         data:$(this).serialize(),
         success:function(data){
            loadPendingGatepass();
            $("#pending-gatepass-message").html("<div class='alert alert-success alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>" + data.message + "</center></div>");
            $('#actionGatepassModal').modal('hide');
         }
      });
   });

   $('.modal').on('hidden.bs.modal', function(){
      $(this).find('form')[0].reset();
   });

   loadFiledNotices();

   $('#manager-cancel-notice-frm').on("submit", function(event){
      event.preventDefault();
      $.ajax({
         url:"/notice_slip/cancelNotice",
         type:"POST",
         data: $(this).serialize(),
            success:function(data){
               loadFiledNotices();
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

   function loadFiledNotices(page){
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
               $('#notice-history').html(data);
            }
      });
   }

   $(document).on('click', '#printAbsent', function(event){
      event.preventDefault();
      var id = $(this).data('id');
      $("#iframe-print").attr("src", "/printNotice/" + id);
      $('#iframe-print').load(function(){
         $(this).get(0).contentWindow.print();
      });
   });

   $(document).on('click', '#printGatepass', function(event){
      event.preventDefault();
      var id = $(this).data('id');
      $("#iframe-print").attr("src", "/printGatepass/" + id);
      $('#iframe-print').load(function(){
         $(this).get(0).contentWindow.print();
      });
   });

   $('.manageGatepassFltr').on('change', function(){
      loadFiledGatepasses();
   });

   $(document).on('click', '#manage_gatepass_pagination a', function(event){
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      loadFiledGatepasses(page);
   });

   loadFiledGatepasses();

   function loadFiledGatepasses(page){
      // var start = $('#manageNotice_start').val();
      // var end = $('#manageNotice_end').val();
      var employee = $('#selectEmpGatepass').val();
      var item_type = $('#itemTypeGatepass').val();
      
      data = {
      //    start : start,
      //    end : end,
         employee : employee,
         item_type : item_type,
      }

      $.ajax({
         url: "/getGatepasses?page="+page,
         data: data,
         success:function(data){
               $('#gatepass-history').html(data);
            }
      });
   }
</script>
@endsection