@extends('client.app')
@section('content')
	@include('client.modules')
	@include('client.modals.notice_slip_modal')
	@include('client.modals.gatepass_modal')
	@include('client.modals.attendance_modal')
	@include('client.modals.evaluation_modal')
	@include('client.modals.add_evaluation_file')
	@include('client.modals.edit_evaluation_file')
	@include('client.modals.delete_evaluation_file')
	@include('client.modals.exam_modal')
<div class="row">
	<div class="col-md-9">
		<div class="inner-box featured">
			<div class="tabs-section">
				<h2 class="title-2">Dashboard </h2>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-overview" data-toggle="tab"> Overview</a></li>
					<li><a href="#tab-my-shifts" data-toggle="tab">My Shift(s)</a></li>
					<li><a href="#tab-my-attendance" data-toggle="tab">My Attendance</a></li>
					<li><a href="#tab-my-leaves" data-toggle="tab">My Leave(s)</a></li>
					<li><a href="#tab-my-gatepasses" data-toggle="tab">My Gatepasses</a></li>
					<li><a href="#tab-my-itinerary" data-toggle="tab">My Itinerary</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane in active" id="tab-overview">
						<div class="row">
							<div class="col-sm-12" id="overview-tab">
								@include('client.overview_tab')
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-my-attendance">
						<div class="row">
							<div class="col-md-12">
								<div class="pull-right">
									<button type="button" class="btn btn-primary" id="refreshAttendance" style="font-size: 9pt;">
										<i class="fa fa-refresh"></i> Refresh
									</button>
								</div>
								<div id="datepairExample">

									<label>From</label>
									<input type="text" class="date attendanceFilter" autocomplete="off" id="attendanceFilter_start" value="{{ Carbon\Carbon::parse('this week -7 days')->format('Y-m-d') }}">
									<label>To</label>
									<input type="text" class="date attendanceFilter" autocomplete="off" id="attendanceFilter_end" value="{{ Carbon\Carbon::parse('now')->format('Y-m-d') }}">
								</div>
							</div>
							<div class="col-md-12">
								<div id="my-attendance"></div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-my-leaves">
						<div class="row">
							<div class="col-sm-12">
								<div id="my-absent-notice"></div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-my-gatepasses">
						<div class="row">
							<div class="col-sm-12">
								<div id="my-gatepasses"></div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-my-itinerary">
							<div class="row">
								<div class="col-sm-12">
									<div id="my-itinerary"></div>
                        		</div>
							</div>
						</div>
					<div class="tab-pane" id="tab-my-shifts">
						<div class="row">
							<div class="col-sm-12">
								<table class="table">
									<thead>
										<tr>
											<th>Day of Week</th>
											<th>Time In</th>
											<th>Time Out</th>
											<th>Breaktime</th>
											<th>Grace Period</th>
										</tr>
									</thead>
									<tbody>
										@foreach($regular_shift as $row)
										<tr>
											<td>{{ $row->day_of_week }}</td>
											<td>{{ date("g:i a", strtotime($row->time_in)) }}</td>
											<td>{{ date("g:i a", strtotime($row->time_out)) }}</td>
											<td>{{ $row->breaktime_by_hour }} hr(s)</td>
											<td>{{ $row->grace_period_in_mins }} min(s)</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane in active" id="tab-memorandum">
						<div class="row">
							<div class="col-sm-12" id="memo-tab"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="alert alert-danger blink" id="lateWarning" hidden>
			<i class="fa fa-info-circle" style="font-size: 15pt; "></i><span> You have reached the maximum late allowed (300 mins.)</span>
		</div>
		@if($kpi_schedules)
		<div class="alert alert-info blink">
			<i class="fa fa-info-circle"></i>
			<span> Schedule for KPI report submission:</span>
			<ul>
				@foreach($kpi_schedules as $sched)
				<li><b>{{ $sched[0] }}:</b> {{ $sched[1] }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<div class="inner-box featured">
			<div class="widget property-agent">
				<h3 class="widget-title">Today's Memo</h3>
				<div class="agent-info">
					<p><b>Upcoming 2019 Holiday/Events </b></p>
					@foreach($getholiday as $holiday)
					<p>{{ $holiday->description }} — {{ \Carbon\Carbon::parse($holiday->holiday_date)->format('F d')}}</p>
					@endforeach
				</div>
			</div>
		</div>
		<div class="inner-box featured">
			<div class="widget property-agent">
				<h3 class="widget-title">Calendar</h3>
				<div class="agent-info">
					<div class="calendar calendar-first" id="calendar_first">
						<div class="calendar_header">
							<button class="switch-month switch-left"> <i class="icon-arrow-left" style="color: #87b633;"></i></button>
							<a href="/calendar"><h2></h2></a>
							<button class="switch-month switch-right"> <i class="icon-arrow-right" style="color: #87b633;"></i></button>
						</div>
						<div class="calendar_weekdays"></div>
						<div class="calendar_content"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<iframe id="iframe-print" hidden></iframe>

<style type="text/css">
@-webkit-keyframes blinker {
	from {opacity: 1.0;}
	to {opacity: 0.0;}
}
.blink{
	text-decoration: blink;
	-webkit-animation-name: blinker;
	-webkit-animation-duration: 0.8s;
	-webkit-animation-iteration-count:infinite;
	-webkit-animation-timing-function:ease-in-out;
	-webkit-animation-direction: alternate;
}
#evaluationModal{ overflow-y:scroll }
</style>

@include('client.modals.add_datainput')
@include('client.modals.edit_notice_slip')
@include('client.modals.edit_gatepass')
@include('client.modals.on_leave_today')
@include('client.modals.pending_requests')

<!-- The Birthday Notification Modal -->
<div class="modal fade" id="birthday-notifcation-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Today is your birthday!</h5>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="row">
					<form></form>
					<div class="col-md-12">
						<center><img src="{{ asset('storage/animated-birthday-image-0015.gif') }}"></center>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

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
	@if (session()->has('success'))
		$.bootstrapGrowl("<center><i class=\"fa fa-check-square-o\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">{{ session()->get('message') }}</span></center>", {
			type: 'success',
			align: 'center',
			delay: 4000,
			width: 450,
			stackup_spacing: 20
		});
	@endif

	@if (session()->has('error'))
		$.bootstrapGrowl("<center><i class=\"fa fa-info\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">{{ session()->get('message') }}</span></center>", {
			type: 'danger',
			align: 'center',
			delay: 4000,
			width: 450,
			stackup_spacing: 20
		});
	@endif
	
	// initialize input widgets first
   $('.time').timepicker({
      'timeFormat': 'g:i A'
   });

   $('#datepairExample .date').datepicker({
      'format': 'yyyy-mm-dd',
      'autoclose': true
   });

   // initialize datepair
   $('#datepairExample').datepair();

   $.ajaxSetup({
  		headers: {
    		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$("#reg_L7").show();
	$("#reg_L8").show();
	$("#reg_L9").show();
	$("#reg_L10").show();

	var remain_sick = $('#remain_L2').val();
	var remain_vaca = $('#remain_L1').val();

	if(remain_vaca <= 0){
	 	$("#emp_L1").hide();
	 	$(".remain_L1").hide();
	}

	if(remain_sick <= 0){
	 	$("#emp_L2").hide();
	 	$(".remain_L2").hide();
	}

	showBirthdayNotif();
	function showBirthdayNotif(){
		var user_id = '{{ Auth::user()->user_id }}';
		$.ajax({
			url: "/showBirthdaysToday",
			data: {user_id: user_id},
			success: function(data){
				if(data.length > 0){
					if (sessionStorage.getItem('showModal') !== 'true') {
						$('#birthday-notifcation-modal').modal('show');
						sessionStorage.setItem('showModal','true');
					}

					$('#birthday-notif-div').html('<div class="alert alert-success" style="font-size: 20pt; text-align: center;"><img src="{{ asset('storage/bday.png') }}" width="150"><span> Today is your birthday! Happy Birthday {{ Auth::user()->nick_name }}!</span></div>');
				}
			}
		});
	}

	function getFirstDayOfMonth(){
		var today = new Date();
		return new Date(today.getFullYear(),today.getMonth(), 1);
	}

	getDeductions();
	loadAttendance();
	loadAbsentNotices();
	loadGatepasses();
	loadItinerary();

	$(document).on('click', '#attendance-modal', function(event){
		loadAttendanceHistory();
	});

	$(document).on('click', '#onLeaveToday', function(event){
		event.preventDefault();
		$('#onLeaveModal').modal('show'); 
	});

	$(document).on('click', '#pendingRequests', function(event){
		event.preventDefault();
		$('#pendingRequestsModal').modal('show'); 
	});

	$(document).on('click', '#attendance_pagination a', function(event){
		event.preventDefault();
		var page = $(this).attr('href').split('page=')[1];
		loadAttendance(page);
	});

	$(document).on('click', '#datainput_pagination a', function(event){
		event.preventDefault();
		var page = $(this).attr('href').split('page=')[1];
		loadEmployeedataInput(page);
	});

	$(document).on('click', '#notices_pagination a', function(event){
		event.preventDefault();
		var page = $(this).attr('href').split('page=')[1];
		loadAbsentNotices(page);
	});

	$(document).on('click', '#gatepass_pagination a', function(event){
		event.preventDefault();
		var page = $(this).attr('href').split('page=')[1];
		loadGatepasses(page);
	});

	$(document).on('click', '.viewItinerary', function(e){
		e.preventDefault();
		var id = $(this).data('idnum');
		data = {
			id : id
		}	
	
		$.ajax({
			url: "/itinerary/fetch/companion",
			type: 'get',
			data:data,
			success: function(data){
				$('.companiondiv').html(data);
			}
		});	
	});

	function loadItinerary(page){
		$.ajax({
			url: "/itinerary/fetch?page="+page,
			success: function(data){
				$('#my-itinerary').html(data);
			}
		});
	}

	$(document).on('click', '#itinerary_pagination a', function(event){
		event.preventDefault();
		var page = $(this).attr('href').split('page=')[1];
		loadItinerary(page);
	});

	function loadAttendance(page){
		var start = $('#attendanceFilter_start').val();
		var end = $('#attendanceFilter_end').val();
		var user_id = '{{ Auth::user()->user_id }}';
		
		data = {
			start : start,
			end : end,
		}
		
		$.ajax({
			url: "/attendance/dashboard/"+ user_id +"?page="+page,
			data: data,
			success: function(data){
				$('#my-attendance').html(data);
			}
		});
	}

	$(document).on('click', '#edit-shift-schedule-btn', function(event){
		event.preventDefault();		
		$('#edit-shift-schedule-form .schedule_id').val($(this).data('id'));
		$('#edit-shift-schedule-form .schedule_date').val($(this).data('schedule'));
		$('#edit-shift-schedule-form .shift_schedule').val($(this).data('shift'));
		$('#edit-shift-schedule-form .branch').val($(this).data('branch'));
		$('#edit-shift-schedule-form .department').val($(this).data('department'));
		$('#edit-shift-schedule-form .remarks').val($(this).data('remarks'));
		$('#edit-shift-schedule-modal').modal('show'); 
	});

	$(document).on('click', '#delete-shift-schedule-btn', function(event){
		event.preventDefault();		
		$('#delete-shift-schedule-form .schedule_id').val($(this).data('id'));
		$('#delete-shift-schedule-form .schedule_date').val($(this).data('schedule'));
		$('#delete-shift-schedule-form .schedule').text($(this).data('schedule'));
		$('#delete-shift-schedule-modal').modal('show'); 
	});

	$(document).on('click', '#edit-shift-btn', function(event){
		event.preventDefault();
		var id = $(this).data('id');
		data = {'id' : id }
		$.ajax({
			url: "/getShiftDetails",
			data: data,
			success: function(data){
				$('#edit-shift-form .shift_id').val(data.shift_id);
				$('#edit-shift-form .schedule_name').val(data.shift_schedule);
				$('#edit-shift-form .time_in').val(data.time_in);
				$('#edit-shift-form .time_out').val(data.time_out);
				$('#edit-shift-form .breaktime').val(data.breaktime_by_hour);
				$('#edit-shift-form .grace_period').val(data.grace_period_in_mins);
				$('#edit-shift-modal').modal('show'); 
			}
		});
	});

	$(document).on('click', '#delete-shift-btn', function(event){
		event.preventDefault();		
		$('#delete-shift-form .shift_id').val($(this).data('id'));
		$('#delete-shift-form .shift_name').val($(this).data('name'));
		$('#delete-shift-form .shift_name').text($(this).data('name'));
		$('#delete-shift-modal').modal('show'); 
	});

	function loadAbsentNotices(page){
		$.ajax({
			url: "/notice_slip/fetch?page="+page,
			success: function(data){
				$('#my-absent-notice').html(data);
			}
		});
	}

	function loadGatepasses(page){
		$.ajax({
			url: "/gatepass/fetch?page="+page,
			success: function(data){
				$('#my-gatepasses').html(data);
			}
		});
	}

	function loadAttendanceHistory(){
		var start = $('#attendanceHistoryFilter_start').val();
		var end = $('#attendanceHistoryFilter_end').val();
		var user_id = $('#attendanceHistoryFilter_employee').val();

		data = {
			start : start,
			end : end,
		}

		$.ajax({
			url: "/attendance/history/" + user_id,
			data: data,
			success: function(data){
				$('#attendance-history').html(data);
			}
		});
	}

	$('#refreshAttendance').on('click', function(){
		var employee = '{{ Auth::user()->user_id }}';
		$.ajax({
			type: 'POST',
			url: '/attendance/update/' + employee,
			beforeSend: function(){
				$("#refreshAttendance").text("Updating...");
			},
			success: function(response){
				loadAttendance();
			},
			complete: function(){
				$("#refreshAttendance").html("<i class=\"fa fa-refresh\"></i> Refresh");
			}
		});
	});


	$(document).on('click', '#view-notice', function(event){
		event.preventDefault();
		var id = $(this).data('id');
		data = {'id' : id}

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

	$(document).on('click', '#edit-unreturned-gatepass', function(event){
		event.preventDefault();
		var id = $(this).data('id');
		data = {'id' : id}
		$.ajax({  
			url:"/gatepass/getDetails",  
			data:data,  
			success:function(data){
				$('#unreturnedGatepassModal .gatepass_id').val(data.gatepass_id);
				$('#unreturnedGatepassModal .gatepass-id').text(data.gatepass_id);
				$('#unreturnedGatepassModal .employee-name').text(data.employee_name);
				$('#unreturnedGatepassModal .date-filed').text(data.date_filed);
				$('#unreturnedGatepassModal .time').text(data.time);
				$('#unreturnedGatepassModal .items').text(data.item_description);
				$('#unreturnedGatepassModal .purpose').text(data.purpose);
				$('#unreturnedGatepassModal .returned-on').text(data.returned_on);
				$('#unreturnedGatepassModal .company-name').text(data.company_name);
				$('#unreturnedGatepassModal .address').text(data.address);
				$('#unreturnedGatepassModal .tel-no').text(data.tel_no);
				$('#unreturnedGatepassModal .remarks').text(data.remarks);
				$('#unreturnedGatepassModal .item-type').text(data.item_type);
				$('#unreturnedGatepassModal .status').text(data.status);
				$('#unreturnedGatepassModal .approved-by').text(data.approved_by);
				$('#unreturnedGatepassModal .date-approved').text(data.approved_date);
				var status = data.status;
				switch (status.toLowerCase()){
					case 'approved': 
						$('#unreturnedGatepassModal .hidden-row').attr('hidden', false);
						$("#unreturnedGatepassModal .status").html("<span class=\"label label-primary\"><i class=\"fa fa-thumbs-o-up\"></i> Approved</span>");
						break;
					case 'cancelled':
						$('#unreturnedGatepassModal .hidden-row').attr('hidden', true);
						$("#unreturnedGatepassModal .status").html("<span class=\"label label-danger\"><i class=\"fa fa-ban\"></i> Cancelled</span>");
						break;
					case 'disapproved': 
						$('#unreturnedGatepassModal .hidden-row').attr('hidden', true);
						$("#unreturnedGatepassModal .status").html("<span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Disapproved</span>");
						break;
					case 'deferred': 
						$('#unreturnedGatepassModal .hidden-row').attr('hidden', true);
						$("#unreturnedGatepassModal .status").html("<span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Deferred</span>");
						break;
					default:
						$('#unreturnedGatepassModal .hidden-row').attr('hidden', true);
						$("#unreturnedGatepassModal .status").html("<span class=\"label label-warning\"><i class=\"fa fa-clock-o\"></i> For Approval</span>");
				}
				$('#unreturnedGatepassModal').modal('show');
			}
		});
	});

	$(document).on('click', '#view-gatepass', function(event){
		event.preventDefault();
		var id = $(this).data('id');
		data = {'id' : id}
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

	$(document).on('show.bs.modal', '.modal', function (event) {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});

	$(document).on('click', '#print-notice', function(event){
		event.preventDefault();		
		$("#iframe-print").get(0).contentWindow.print();
	});

	$(document).on('click', '#print-gatepass', function(event){
		event.preventDefault();		
		$("#iframe-print").get(0).contentWindow.print();
	});

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

	$(document).on('change', '#filterDateStart', function(e){
		var type = $('#filterDateStart').val();
		data = {type : type}
		$.ajax({
      	url: '/kiosk/notice/getusershift',
      	type: 'get',
      	data: data,
      	success: function(data){
      		$('#starttime').val(data.shift_in);
     			$('#endtime').val(data.shift_out);
      			SumHours();
   		}
   	});
   });

	$(document).on('click', '#editAbsent', function(event){
		event.preventDefault();
		var id = $(this).data('id');
		data = {'id' : id }
		$.ajax({  
            url:"/notice_slip/getDetails",  
            data:data,  
            success:function(data){
               	var leave_type = "leave_type_id" + data.leave_type_id;
               	$('#edit-notice-form .' + leave_type).prop('checked',true);
               	$('#edit-notice-form .notice_id').val(data.notice_id);
               	$('#edit-notice-form .from_date').val(data.date_from);
               	$('#edit-notice-form .from_time').val(data.time_from);
               	$('#edit-notice-form .to_date').val(data.date_to);
               	$('#edit-notice-form .to_time').val(data.time_to);
               	$('#edit-notice-form .means').val(data.means);
               	$('#edit-notice-form .time_reported').val(data.time_reported);
               	$('#edit-notice-form .info_by').val(data.info_by);
               	$('#edit-notice-form .approved_by').text(data.approved_by);
               	$('#edit-notice-form .leave_type').val(data.leave_type_id);
               	$('#edit-notice-form .date_approved').text(data.approved_date);
               	$("#print-notice").hide();

               	var remain_sick = $('#notice_remain_L2').val();
				var remain_vaca = $('#notice_remain_L1').val();
		
				SumHours_notice();

				var start= new Date($("#startdate_notice").val());
				var end= new Date($("#enddate_notice").val());
				var totaldays = workingDaysBetweenDates(new Date(start), new Date(end));

				console.log(totaldays);

               	var status = data.status;
               	if (status.toLowerCase() != 'for approval') {
               		$("#cancel-notice").hide();
               		$("#update-notice").hide();
               	}else{
               		$("#cancel-notice").show();
               		$("#update-notice").show();
               	}
               	if (status.toLowerCase() != 'cancelled') {
         
               		$("#amend-notice").hide();
               	}else{
               		
               		$("#amend-notice").show();
               		
					if(remain_vaca > 0){
						if(totaldays > remain_vaca){
					 		$("#notice_emp_L1").hide();
					 		$(".notice_remain_L1").hide();
						}else{
							$("#notice_emp_L1").show();
					 		$(".notice_remain_L1").show();
						}
					}
					if(remain_sick > 0){
						if(totaldays > remain_sick){
					 		$("#notice_emp_L2").hide();
					 		$(".notice_remain_L2").hide();
						}else{
							$("#notice_emp_L2").show();
					 		$(".notice_remain_L2").show();
						}
					}
               	}
               	
               	switch (status.toLowerCase()){
               		case 'approved': 
               			$('#edit-notice-form :input').attr('disabled', true);
               			$("#edit-notice-form .status").html("<h3><span class=\"label label-primary\"><i class=\"fa fa-thumbs-o-up\"></i> Approved</span></h3>");
               			$("#iframe-print").attr("src", "/printNotice/" + data.notice_id);
               			$("#edit-notice-form .divStatus").show();
               			$("#print-notice").show();
               			break;
               		case 'cancelled':
               			$('#edit-notice-form :input').attr('disabled', false);
	               		$("#edit-notice-form .status").html("<h3><span class=\"label label-danger\"><i class=\"fa fa-ban\"></i> Cancelled</span></h3>");
	               		$("#edit-notice-form .divStatus").hide();
	               		break;
	               	case 'disapproved': 
	               		$('#edit-notice-form :input').attr('disabled', true);
	               		$("#edit-notice-form .status").html("<h3><span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Disapproved</span></h3>");
	               		$("#edit-notice-form .divStatus").hide();
	               		break;
	               	case 'deferred': 
	               		$('#edit-notice-form :input').attr('disabled', true);
	               		$("#edit-notice-form .status").html("<h3><span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Deferred</span></h3>");
	               		$("#edit-notice-form .divStatus").hide();
	               		break;
	               	default:
	               		$('#edit-notice-form :input').attr('disabled', false);
	               		$("#edit-notice-form .divStatus").hide();
	               		$("#edit-notice-form .status").html("<h3><span class=\"label label-warning\"><i class=\"fa fa-clock-o\"></i> For Approval</span></h3>");
               	}

               	$('#edit-notice-form .reason').text(data.reason);
				$('#editNoticeModal').modal('show'); 
            }  
        });  
	});

	$(document).on('click', '#editGatepass', function(event){
		event.preventDefault();
		var id = $(this).data('id');
		data = {'id' : id }
		$.ajax({  
			url:"/gatepass/getDetails",  
			data:data,  
			success:function(data){  
				$('#edit-gatepass-form .gatepass_id').val(data.gatepass_id);
				$('#edit-gatepass-form .status').val(data.status);
				$('#edit-gatepass-form .item_type').val(data.item_type);
				$('#edit-gatepass-form .date_filed').val(data.date_filed);
				$('#edit-gatepass-form .returned_on').val(data.returned_on);
				$('#edit-gatepass-form .company_name').val(data.company_name);
				$('#edit-gatepass-form .time').val(data.time);
				$('#edit-gatepass-form .address').val(data.address);
				$('#edit-gatepass-form .purpose').val(data.purpose);
				$('#edit-gatepass-form .tel_no').val(data.tel_no);
				$('#edit-gatepass-form .item_description').text(data.item_description);
				$('#edit-gatepass-form .remarks').text(data.remarks);
				$('#edit-gatepass-form .approved_by').text(data.approved_by);
				$('#edit-gatepass-form .date_approved').text(data.approved_date);
				$('#edit-gatepass-form .purpose_type').val(data.purpose_type);
				$("#print-gatepass").hide();

				var status = data.status;
				if (status.toLowerCase() != 'for approval') {
					$("#cancel-gatepass").hide();
					$("#update-gatepass").hide();
				}else{
					$("#cancel-gatepass").show();
					$("#update-gatepass").show();
				}

				switch (status.toLowerCase()){
					case 'approved': 
						$('#edit-gatepass-form :input').attr('disabled', true);
						$("#edit-gatepass-form .status").html("<h3><span class=\"label label-primary\"><i class=\"fa fa-thumbs-o-up\"></i> Approved</span></h3>");
						$("#edit-gatepass-form .divStatus").show();
						$("#iframe-print").attr("src", "/printGatepass/" + data.gatepass_id);
						$("#print-gatepass").show();
						break;
					case 'cancelled': 
						$("#edit-gatepass-form .divStatus").hide();
						$('#edit-gatepass-form :input').attr('disabled', true);
						$("#edit-gatepass-form .status").html("<h3><span class=\"label label-danger\"><i class=\"fa fa-ban\"></i> Cancelled</span></h3>");
						break;
					case 'disapproved': 
						$("#edit-gatepass-form .divStatus").hide();
						$('#edit-gatepass-form :input').attr('disabled', true);
						$("#edit-gatepass-form .status").html("<h3><span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Disapproved</span></h3>");
						break;
					default:
						$('#edit-gatepass-form :input').attr('disabled', false);
						$("#edit-gatepass-form .status").html("<h3><span class=\"label label-warning\"><i class=\"fa fa-clock-o\"></i> For Approval</span></h3>");
						$("#edit-gatepass-form .divStatus").hide();
				}
				$('#editGatepassModal').modal('show'); 
			}
		});
	});

	$('#edit-gatepass-form').on("submit", function(event){
		event.preventDefault();
		$.ajax({
			url:"/gatepass/updateDetails",
			type:"POST",
			data:$(this).serialize(),
			success:function(data){
				loadGatepasses();
				$.bootstrapGrowl("<center><i class=\"fa fa-check-square-o\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + data.message + "</span></center>", {
				type: 'success',
				align: 'center',
				delay: 4000,
				width: 450,
				offset: {from: 'top', amount: 300},
				stackup_spacing: 20
				});

				$('#editGatepassModal').modal('hide');
			}
		});
	});

	$('#edit-notice-form').on("submit", function(event){
		event.preventDefault();
		$.ajax({
			url:"/notice_slip/updateDetails",
			type:"POST",
			data:$(this).serialize(),
			success:function(data){
				loadAbsentNotices();
				$.bootstrapGrowl("<center><i class=\"fa fa-check-square-o\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + data.message + "</span></center>", {
				type: 'success',
				align: 'center',
				delay: 4000,
				width: 450,
				offset: {from: 'top', amount: 300},
				stackup_spacing: 20
				});
				$('#editNoticeModal').modal('hide');
			}
		});  
	});

	$('#cancel-notice').on("click", function(event){
		event.preventDefault();
		var id = $('#edit-notice-form .notice_id').val();
		var leave_id = $('#edit-notice-form .leave_type').val();
		var from_date = $('#edit-notice-form .from_date').val();
		var to_date = $('#edit-notice-form .to_date').val();
		var user_id = $('#edit-notice-form .user_id').val();
		data = { 'notice_id' : id,
			'leave_id': leave_id,
			'date_from': from_date,
			'date_to': to_date,
			'user_id': user_id
		}

		$.ajax({
			url:"/notice_slip/cancelNotice_per_employee",
			type:"POST",
			data: data,
			success:function(data){
				loadAbsentNotices();
				$.bootstrapGrowl("<center><i class=\"fa fa-ban\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + data.message + "</span></center>", {
					type: 'danger',
					align: 'center',
					delay: 4000,
					width: 450,
					offset: {from: 'top', amount: 300},
					stackup_spacing: 20
				});
				$('#editNoticeModal').modal('hide');
			}
		});  
	});

	$('#cancel-gatepass').on("click", function(event){
		event.preventDefault();
		var id = $('#edit-gatepass-form .gatepass_id').val();

		data = {'id' : id}
		
		$.ajax({
			url:"/gatepass/cancelGatepass",
			type:"POST",
			data: data,
			success:function(data){
				loadGatepasses();
				$.bootstrapGrowl("<center><i class=\"fa fa-ban\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + data.message + "</span></center>", {
				type: 'danger',
				align: 'center',
				delay: 4000,
				width: 450,
				offset: {from: 'top', amount: 300},
				stackup_spacing: 20
				});
				$('#editGatepassModal').modal('hide');
			}
		});  
	});

	$('#add-gatepass-form').on("submit", function(event){
		event.preventDefault();
		$.ajax({
			url:"/gatepass/create",
			type:"POST",
			data:$(this).serialize(),
			success:function(data){
				loadGatepasses();
				$.bootstrapGrowl("<i class=\"fa fa-check-circle-o\" style=\"font-size: 60pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 16pt; font-weight: bold; padding-top: 5px;\">Request sent to Managers.</span><span style=\"font-size: 11pt;\">Please wait for the approved gatepass form.<br>" + data.message + "</span>", {
				type: 'success',
				align: 'center',
				delay: 8000,
				width: 450,
				offset: {from: 'top', amount: 300},
				stackup_spacing: 20
				});
				$('#gatepassModal').modal('hide');            	
			}
		});
	});

	$('#add-notice-form').on("submit", function(event){
		event.preventDefault();
		$('#notice-slip-submit-btn').attr('disabled', true);
		$.ajax({
			url:"/notice_slip/create",
			type:"POST",
			data:$(this).serialize(),
			success:function(data){
				$('#notice-slip-submit-btn').attr('disabled', false);
				if(data.success){
					loadAbsentNotices();
					$.bootstrapGrowl("<i class=\"fa fa-check-circle-o\" style=\"font-size: 60pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 16pt; font-weight: bold; padding-top: 5px;\">Request sent to Managers.</span><span style=\"font-size: 10pt;\">Please wait for the approved absent notice form.<br>" + data.message + "</span>", {
						type: 'success',
						align: 'center',
						delay: 8000,
						width: 450,
						offset: {from: 'top', amount: 300},
						stackup_spacing: 20
					});
					$('#absentNoticeModal').modal('hide');
				}else{
					$.bootstrapGrowl("<i class=\"fa fa-info\" style=\"font-size: 20pt; float: left; padding-right: 10px;\"></i><span style=\"font-size: 10pt;\">" + data.message + "</span>", {
						type: 'danger',
						align: 'center',
						delay: 8000,
						width: 450,
						stackup_spacing: 20
					});
				}
			},
			error: function (response){
				$('#notice-slip-submit-btn').attr('disabled', false);
				$.bootstrapGrowl("<i class=\"fa fa-info\" style=\"font-size: 20pt; float: left; padding-right: 10px;\"></i><span style=\"font-size: 10pt;\">An error occured. Please try again.</span>", {
					type: 'danger',
					align: 'center',
					delay: 8000,
					width: 450,
					stackup_spacing: 20
				});
			}
		});
	});

	$('.absentTodayFilter').on('change', function(){
		loadAbsentToday();
	});

	function loadAbsentToday(){
		var start = $('#filterDateStart').val();
		var end = $('#filterDateEnd').val();

		data = {
			start : start,
			end : end
		}

		$.ajax({
			url: "/notice_slip/absentToday",
			data: data,
			success:function(data){
				$('#out-of-office-table').html(data);
			}
		});
	}

	$('.attendanceFilter').on('change', function(){
		loadAttendance();
	});

	$('.attendanceHistoryFilter').on('change', function(){
		loadAttendanceHistory();
	});

	$('.modal').on('hidden.bs.modal', function(){
		$(this).find('form')[0].reset();
		$("#iframe-print").removeAttr("src");
	});

	$('#absentNoticeModal').on('hidden.bs.modal', function(){
		$('#out-of-office-table').html("");
		$('#notice-slip-submit-btn').removeAttr('disabled');
	});

	function getDeductions(){
		var employee = '{{ Auth::user()->user_id }}';

		$.ajax({
			url: "/attendance/deductions/" + employee,
			success: function(data){
				if(data >= 300){
					$("#lateWarning").removeAttr("hidden");
				}
			}
		});
	}
});

$(document).ready(function(){
	$(document).on('click', '#evaluation-files-pagination a', function(event){
		event.preventDefault();
		var page = $(this).attr('href').split('page=')[1];
		loadEvaluations(page);
	});

	$(document).on('click', '#evaluation-modal', function(e){
		e.preventDefault();
		loadEvaluations();
	});

	$(document).on('click', '#add-evaluation-file-btn', function(e){
		e.preventDefault();
		$('#add-evaluation-file-modal').modal('show');
	});

	$(document).on('click', '#datainputmodal', function(e){
		e.preventDefault();
		$('#data_inputmodal').modal('show');
	});

	$(document).on('click', '#evaluation-files-pagination a', function(event){
		event.preventDefault();
		var page = $(this).attr('href').split('page=')[1];
		loadEvaluations(page);
	});

	$(document).on('click', '#edit-evaluation-file-btn', function(event){
		event.preventDefault();
		$('#edit-evaluation-file-modal .id').val($(this).data('id'));
		$('#edit-evaluation-file-modal .title').val($(this).data('title'));
		$('#edit-evaluation-file-modal .employee').val($(this).data('employee'));
		$('#edit-evaluation-file-modal .eval-date').val($(this).data('eval-date'));
		$('#edit-evaluation-file-modal .eval-file').val($(this).data('file'));
		$('#edit-evaluation-file-modal .remarks').val($(this).data('remarks'));
		$('#edit-evaluation-file-modal .modified_date').text($(this).data('modifieddate'));
		$('#edit-evaluation-file-modal .modified_name').text($(this).data('modifiedname'));
		$('#edit-evaluation-file-modal').modal('show');
	});

	$(document).on('click', '#delete-evaluation-file-btn', function(event){
		event.preventDefault();
		$('#delete-evaluation-file-modal .id').val($(this).data('id'));
		$('#delete-evaluation-file-modal .eval-title').val($(this).data('title'));
		$('#delete-evaluation-file-modal .title').text($(this).data('title'));
		$('#delete-evaluation-file-modal .employee').text($(this).data('employee'));
		$('#delete-evaluation-file-modal').modal('show');
	});

	$(document).on('submit', '#delete-evaluation-file-form', function(e){
		e.preventDefault();
		$.ajax({
			url:"/deleteEvaluation",
			method:"POST",
			data:$(this).serialize(),
			success:function(data){
				$.bootstrapGrowl("<center><i class=\"fa " + data.icon + "\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><div style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + data.message + "</div></center>", {
				type: data.class_name,
				align: 'center',
				delay: 4000,
				width: 450,
				offset: {from: 'top', amount: 300},
				stackup_spacing: 20
				});
				loadEvaluations();
				$('#delete-evaluation-file-modal').modal('hide');
			}
		});
	});

	$(document).on("submit", '#submitform', function(event){
		event.preventDefault();
		$.ajax({
			url:"/savedatainput",
			type:"POST",
			data: $('#submitform').serialize(),
			dataType: "json",
			success:function(data){
				if('1' == data.message){
					$.bootstrapGrowl("<i class=\"fa fa-info-circle\" style=\"font-size: 60pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 16pt; font-weight: bold; padding-top: 5px;\"> Data is already exist! </span><span style=\"font-size: 10pt;\"><br></span>", {
					type: 'danger',
					align: 'center',
					delay: 4000,
					width: 450,
					offset: {from: 'top', amount: 300},
					stackup_spacing: 20
					});
				} else {
					$.bootstrapGrowl("<i class=\"fa fa-check-circle-o\" style=\"font-size: 60pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 16pt; font-weight: bold; padding-top: 5px;\">" + data.message + "</span><span style=\"font-size: 10pt;\"><br></span>", {
					type: 'success',
					align: 'center',
					delay: 4000,
					width: 450,
					offset: {from: 'top', amount: 300},
					stackup_spacing: 20
					});
					$('#data_inputmodal').modal('hide');
					$('#submitform')[0].reset(); 

					loadEmployeedataInput(); 
					loadKpiResult();
				}
			},
			error: function(data) {
				alert('error');
			}
		});
	});

	loadAppraisal();
	function loadAppraisal(page){
		var user_id = {{ Auth::user()->user_id }};

		$.ajax({
			url: "/getEmpAppraisal/"+user_id+"?page="+page,
			success: function(data){
				$('#appraisal-table').html(data);
			}
		});
	}

$(document).on('submit', '#edit-evaluation-file-form', function(e){
e.preventDefault();
$.ajax({
url:"/editEvaluation",
method:"POST",
data:new FormData(this),
// dataType:'JSON',
contentType: false,
cache: false,
processData: false,
success:function(data){
msg = '<ul>';
$.each(data.message, function(d, i){
msg += '<li>' + i + '</li>';
});
msg += '</ul>';
$.bootstrapGrowl("<center><i class=\"fa " + data.icon + "\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><div style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + msg + "</div></center>", {
type: data.class_name,
align: 'center',
delay: 4000,
width: 450,
offset: {from: 'top', amount: 300},
stackup_spacing: 20
});

if(data.class_name == 'success'){
loadEvaluations();
$('#edit-evaluation-file-modal').modal('hide');
}
}
});
});

$(document).on('submit', '#add-evaluation-file-form', function(e){
e.preventDefault();
$.ajax({
url:"/addEvaluation",
method:"POST",
data:new FormData(this),
// dataType:'JSON',
contentType: false,
cache: false,
processData: false,
success:function(data){
msg = '<ul>';
$.each(data.message, function(d, i){
msg += '<li>' + i + '</li>';
});
msg += '</ul>';
$.bootstrapGrowl("<center><i class=\"fa " + data.icon + "\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><div style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + msg + "</div></center>", {
type: data.class_name,
align: 'center',
delay: 4000,
width: 450,
offset: {from: 'top', amount: 300},
stackup_spacing: 20
});

if(data.class_name == 'success'){
loadEvaluations();
$('#add-evaluation-file-modal').modal('hide');
}
}
});
});

loadKpiResult();
createFunction();
loadEmployeedataInput();
entryvalidation();
getemployeeperdept();

function loadEvaluations(page){
$.ajax({
url: "/getEvaluations?page="+page,
success: function(data){
$('#evaluation-table').html(data);
}
});
}

loadKpiResult();

});
</script>
<script type="text/javascript">
function createFunction(){
var dept = document.getElementById('dept').value;
var employeelist = document.getElementById('employeelist').value;
var entry = document.getElementById('entry').value;
var departmentvalidate = document.getElementById('departmentvalidate').value;
var user_id = {{ Auth::user()->user_id }};
var eval_period = document.getElementById('entrysched').value;
data = {
employeelist : employeelist,
dept : dept,
entry : entry,
eval_period : eval_period
}	
$.ajax({
url: '/getdatainput',
type: 'get',
data:data,
success: function(data){
$('#viewdatainput').html(data);
$('#entry_val').val(entry);
$('#user_id').val(employeelist);
$('#depart_id').val(dept);
$('#eval-period').val(eval_period);
show_schedule_date();


if(entry == 'per_employee'){
$("#employeelistdiv").show();

}else{
$("#employeelistdiv").hide();
}
}

});

}
</script>
<script type="text/javascript">
function loadKpiResult(page){
var user_id = {{ Auth::user()->user_id }};
var filmonth = document.getElementById('monthfilterresult').value;
var filyear = document.getElementById('yearfilterresult').value;
data = {
filmonth : filmonth,
filyear : filyear

}
$.ajax({
url: "/getEmpKpiResult/"+user_id+"?page="+page,
data: data,
success: function(data){
$('#kpi-result-table').html(data);
}
});
}
</script>
<script type="text/javascript">
function loadEmployeedataInput(page){

var schedentry = document.getElementById('schedentry').value;
var filmonths = document.getElementById('monthfilter').value;
var filyears = document.getElementById('yearfilter').value;
var user_id = {{ Auth::user()->user_id }};
data = {
schedentry : schedentry,
filmonths : filmonths,
filyears : filyears,
user_id : user_id
}
$.ajax({
url: "/tblDatainput?page="+ page,
data:data,
success: function(data){
$('#tblDatainput').html(data);
getemployeeperdept();
}
});
}
</script>

<script type="text/javascript">
function getemployeeperdept(){
createFunction();

var dept = document.getElementById('dept').value;
var deptvalidate = document.getElementById('departmentvalidate').value;
data = {
dept : dept,
deptvalidate : deptvalidate,
}

$.ajax({
url: '/getemployeeperdept',
type: 'get',
dataType: 'JSON',
data: data,
success: function(result) {
$('#employeelist').html(result);
createFunction();

},
error: function(result) {
alert('Error fetching data!');
}
});
}
</script>

<script type="text/javascript">
function entryvalidation(){
	createFunction();
	$('#entry').on('change', function() {
		if ( this.value == 'per_employee'){
			$("#employeelistdiv").show();
			getemployeeperdept();
		}else{
			$("#employeelistdiv").hide();
		}
	});
}
</script>

<script type="text/javascript">
	function sumofday() {
	    var start= $("#filterDateStart").datepicker("getDate");
	    var end= $("#filterDateEnd").datepicker("getDate");

	   	var totaldays = workingDaysBetweenDates(new Date(start), new Date(end));
	   	var remain_sick = $('#remain_L2').val();
		var remain_vaca = $('#remain_L1').val();

		SumHours();
	}

	function workingDaysBetweenDates(startDate, endDate) {
	    // Validate input
	    if (endDate < startDate)
	        return 0;
	    
	    // Calculate days between dates
	    var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
	    startDate.setHours(0,0,0,1);  // Start just after midnight
	    endDate.setHours(23,59,59,999);  // End just before midnight
	    var diff = endDate - startDate;  // Milliseconds between datetime objects    
	    var days = Math.ceil(diff / millisecondsPerDay);
	    
	    // Subtract two weekend days for every week in between
	    var weeks = Math.floor(days / 7);
	    days = days - (weeks * 1);

	    // Handle special cases
	    var startDay = startDate.getDay();
	    var endDay = endDate.getDay();
	    
	    // Remove weekend not previously removed.   
	    if (startDay - endDay > 1)         
	        days = days - 1;      
	    
	    // Remove start day if span starts on Sunday but ends before Saturday
	    if (startDay == 0 && endDay != 6) {
	        days = days - 1;  
	    }
	    
	    return days;
	}
</script>

<script type="text/javascript">
	function sumofday_refillnotice() {
	    var start= new Date($("#startdate_notice").val());
		var end= new Date($("#enddate_notice").val());
	   	var totaldays = workingDaysBetweenDates(new Date(start), new Date(end));

	   	var remain_sick = $('#notice_remain_L2').val();
		var remain_vaca = $('#notice_remain_L1').val();

		if(remain_vaca > 0){
			if(totaldays > remain_vaca){
				$("#notice_emp_L1").hide();
				$(".notice_remain_L1").hide();
			}else{
				$("#notice_emp_L1").show();
				$(".notice_remain_L1").show();
			}
		}
		if(remain_sick > 0){
			if(totaldays > remain_sick){
				$("#notice_emp_L2").hide();
				$(".notice_remain_L2").hide();
			}else{
				$("#notice_emp_L2").show();
				$(".notice_remain_L2").show();
			}
		}
	}
		function SumHours_notice() {
		  var smon = $('#notice_from_time').val();
		  var fmon = $('#notice_end_time').val();
		  var convertedFrom = (convertTime12to24(smon));
		  var convertedTo = (convertTime12to24(fmon));
		  console.log(convertedFrom);
		  console.log(convertedTo);
		  var remain_sick = $('#notice_remain_L2').val();
		  var remain_vaca = $('#notice_remain_L1').val();
		  var diff = 0 ;

		  var start= new Date($("#startdate_notice").val());
		  var end= new Date($("#enddate_notice").val());
   		  var totaldays = workingDaysBetweenDates(new Date(start), new Date(end));

		  if (smon && fmon) {
		    smon = ConvertToSeconds(convertedFrom);
		    fmon = ConvertToSeconds(convertedTo);
		    diff = Math.abs( fmon - smon ) ;
		    if (secondsTohhmmss(diff) <= 3) {
		    	$("#notice_reg_L8").hide();
		    	$("#notice_reg_L9").show();
		    	$("#notice_reg_L10").show();
		    	$("#notice_reg_L7").hide();

		    	$("#notice_emp_L1").hide();
		 		$(".notice_remain_L1").hide();
		 		$("#notice_emp_L2").hide();
		 		$(".notice_remain_L2").hide();
		   	}else if(secondsTohhmmss(diff) <=4){
		    	$("#notice_reg_L7").hide();
		    	$("#notice_reg_L8").show();
		    	$("#notice_reg_L9").hide();
		    	$("#notice_reg_L10").hide();

		 		$("#notice_emp_L1").hide();
		 		$(".notice_remain_L1").hide();
		 		$("#notice_emp_L2").hide();
		 		$(".notice_remain_L2").hide();
		 	}else if(secondsTohhmmss(diff) <=8){
		    	$("#notice_reg_L7").hide();
		    	$("#notice_reg_L8").show();
		    	$("#notice_reg_L9").hide();
		    	$("#notice_reg_L10").hide();

		 		$("#notice_emp_L1").hide();
		 		$(".notice_remain_L1").hide();
		 		$("#notice_emp_L2").hide();
		 		$(".notice_remain_L2").hide();

		    }else if(secondsTohhmmss(diff) >= 9){
		    	$("#notice_reg_L7").show();
		    	$("#notice_reg_L8").hide();
		    	$("#notice_reg_L9").hide();
		    	$("#notice_reg_L10").hide();

				if(remain_vaca > 0){
					if(totaldays > remain_vaca){
				 		$("#notice_emp_L1").hide();
				 		$(".notice_remain_L1").hide();
					}else{
						$("#notice_emp_L1").show();
				 		$(".notice_remain_L1").show();
					}
				}
				if(remain_sick > 0){
					if(totaldays > remain_sick){
				 		$("#notice_emp_L2").hide();
				 		$(".notice_remain_L2").hide();
					}else{
						$("#notice_emp_L2").show();
				 		$(".notice_remain_L2").show();
					}
				}

		    }

		  }
		}


	
</script>
<script type="text/javascript">
	const convertTime12to24 = (time12h) => {
  	const [time, modifier] = time12h.split(' ');

	  let [hours, minutes] = time.split(':');

	  if (hours === '12') {
	    hours = '00';
	  }

	  if (modifier === 'PM') {
	    hours = parseInt(hours, 10) + 12;
	  }

	  return `${hours}:${minutes}`;
	}

	function SumHours() {
	  var smon = $('#starttime').val();
	  var fmon = $('#endtime').val();
	  var convertedFrom = (convertTime12to24(smon));
	  var convertedTo = (convertTime12to24(fmon));
	  // console.log(convertedFrom);
	  // console.log(convertedTo);
	  var remain_sick = $('#remain_L2').val();
	  var remain_vaca = $('#remain_L1').val();
	  var diff = 0 ;

	  var start= new Date($("#filterDateStart").val());
	  var end= new Date($("#filterDateEnd").val());
   	  var totaldays = workingDaysBetweenDates(new Date(start), new Date(end));

	  if (smon && fmon) {
	    smon = ConvertToSeconds(convertedFrom);
	    fmon = ConvertToSeconds(convertedTo);
	    diff = Math.abs( fmon - smon ) ;
	    console.log(secondsTohhmmss(diff));
	    if (secondsTohhmmss(diff) <= 3) {
	    	$("#reg_L8").hide();
	    	$("#reg_L9").show();
	    	$("#reg_L10").show();
	    	$("#reg_L7").hide();

	    	$("#emp_L1").hide();
	 		$(".remain_L1").hide();
	 		$("#emp_L2").hide();
	 		$(".remain_L2").hide();
	   	}else if(secondsTohhmmss(diff) <=4){
	    	$("#reg_L7").hide();
	    	$("#reg_L8").show();
	    	$("#reg_L9").hide();
	    	$("#reg_L10").hide();

	 		$("#emp_L1").hide();
	 		$(".remain_L1").hide();
	 		$("#emp_L2").hide();
	 		$(".remain_L2").hide();
	 	}else if(secondsTohhmmss(diff) <=8){
	    	$("#reg_L7").hide();
	    	$("#reg_L8").show();
	    	$("#reg_L9").hide();
	    	$("#reg_L10").hide();

	 		$("#emp_L1").hide();
	 		$(".remain_L1").hide();
	 		$("#emp_L2").hide();
	 		$(".remain_L2").hide();

	    }else if(secondsTohhmmss(diff) >= 9){
	    	$("#reg_L7").show();
	    	$("#reg_L8").hide();
	    	$("#reg_L9").hide();
	    	$("#reg_L10").hide();

	    	if(remain_vaca > 0){
				if(totaldays > remain_vaca){
			 		$("#emp_L1").hide();
					$(".remain_L1").hide();
					console.log('hide vaca');
				}else{
					$("#emp_L1").show();
					$(".remain_L1").show();
					console.log('show vaca');
				}
			}
			if(remain_sick > 0){
				if(totaldays > remain_sick){
					$("#emp_L2").hide();
					$(".remain_L2").hide();
					console.log('hide sick');
				}else{
					$("#emp_L2").show();
					$(".remain_L2").show();
					console.log('show sick');
				}
			}

	    }

	  }
	}
	 function ConvertToSeconds(time) {
	    var splitTime = time.split(":");
	    return splitTime[0] * 3600 + splitTime[1] * 60;
	  }

	 function secondsTohhmmss(secs) {
	    var hours = parseInt(secs / 3600);
	    var seconds = parseInt(secs % 3600);
	    var minutes = parseInt(seconds / 60) ;
	    return hours;
	  }
</script>
<script type="text/javascript">
	function show_schedule_date(){
		var get_val= $("#schedule_date").val();
		$('#show_scheduledDate').text(get_val);

	}
</script>
<script type="text/javascript">
	$(document).on('click', '#employee_submit', function(event){
		var excode= $(this).data('idcode');
         $.ajax({
           type: 'post',
           url: '/oem/employee/validateExamCode',
           data:{excode:excode},
           success: function(data){
              console.log(data);
               $.bootstrapGrowl('<center><span id="msg-alert">'+data.message+'</span></center>', {
                  type: data.status,
                  align: 'center',
                  offset: {from: 'top', amount: 170},
                  width: 400,
                  delay: 4000,
                  stackup_spacing: 10,
                  allow_dismiss: false
               });

               if (data.status == 'success') {
                  setTimeout(function() {
                     window.location.href = "/oem/employee/index/" + data.examinee_id;
                  }, 1000);
               }
            }
         });
     });
      
</script>
@endsection