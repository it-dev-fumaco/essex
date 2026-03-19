@extends('client.app')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
@include('client.modals.employee_pending_transactions')
@include('client.modals.edit_employee')
@include('client.modals.employee_reset_password')
@include('client.modals.employee_reset_leaves')
@include('client.modals.update_personal_details')
<div class="row" style="margin-top: -5%; padding-bottom: 1px;">
	<div class="col-md-12">
    	<h2 class="section-title center">Employee Profile</h2>
    	<a href="/home">
        	<i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 0; margin-top: -5%; float: left;"></i>
    	</a>
	</div>
	<div class="col-md-8" style="padding: 2% 1% 1% 1%;">
		<div style="float: left; padding: 0.5% 0.5% 0.5% 2%;">
			<img src="{{ asset('storage/img/user.png') }}" width="80" height="80">
		</div>
        <div style="float: left; padding: 1.5% 0;">
        	<span style="font-size: 18pt; display: block;">{{ $employee_profile->employee_name }}</span>

			@php
				$joiningDateRaw = $employee_profile->date_joined ?? $employee_profile->joining_date ?? null;
				$tenureText = 'Tenure: N/A';

				if (! empty($joiningDateRaw)) {
					try {
						$joinDate = \Carbon\Carbon::parse($joiningDateRaw);
						$now = \Carbon\Carbon::now();

						if ($joinDate->lte($now)) {
							$diff = $joinDate->diff($now);

							$years = (int) $diff->y;
							$months = (int) $diff->m;
							$days = (int) $diff->d;

							$yearsLabel = $years.' year'.($years === 1 ? '' : 's');
							$monthsLabel = $months.' month'.($months === 1 ? '' : 's');
							$daysLabel = $days.' day'.($days === 1 ? '' : 's');

							if ($years < 1) {
								if ($months > 0 && $days > 0) {
									$tenureText = $monthsLabel.' and '.$daysLabel;
								} elseif ($months > 0) {
									$tenureText = $monthsLabel;
								} else {
									$tenureText = $daysLabel;
								}
							} else {
								$parts = [$yearsLabel];
								if ($months > 0) {
									$parts[] = $monthsLabel;
								}
								if ($days > 0) {
									$parts[] = $daysLabel;
								}

								$tenureText = implode(' and ', $parts);
							}
						}
					} catch (\Throwable $e) {
						// fall back to Tenure: N/A
					}
				}
			@endphp

        	<span style="font-size: 13pt; padding-top: 1%; display: block;">{{ $employee_profile->designation }}</span>
        	<span style="font-size: 12pt; letter-spacing: 0.5px; display: block; text-transform: uppercase;">{{ $employee_profile->department }}</span>
        	<span style="font-size: 11pt; color: #777; display: block;"><em>{{ $tenureText }}</em></span>
        	<span id="employee-id" hidden>{{ $employee_profile->user_id }}</span>
        	<span id="user-id" hidden>{{ $employee_profile->id }}</span>
        	<span id="today" hidden>{{ date('Y-m-d') }}</span>
        </div>   
	</div>
	<div class="col-md-4" style="padding: 2% 1% 1% 1%;">
        <div style="float: left; padding: 1.5% 0;">
        	<span style="display: block;">Birthdate: <b>{{ date('F d, Y',strtotime($employee_profile->birth_date)) }}</b></span>
        	<span style="display: block;">Access ID: <b>{{ $employee_profile->user_id }}</b></span>
       </div>   
	</div>
	@if(session("message"))
	<div class="col-md-12">
		<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert'>&times;</button>
			<center>{!! session("message") !!}</center>
		</div>
	</div>
	@endif
</div>
<div class="row" style="padding-top: 0">
	<div class="col-md-9">
		<div class="inner-box featured" style="min-height: 440px">
			<div class="tabs-section">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-employee-attendance" data-toggle="tab"> Attendance</a></li>
					<li><a href="#tab-employee-notices" data-toggle="tab">Absent Notice(s)</a></li>
					<li><a href="#tab-employee-gatepasses" data-toggle="tab">Gatepasses</a></li>
					<li><a href="#tab-employee-leaves" data-toggle="tab">Leave(s)</a></li>
					<li><a href="#tab-employee-exams" data-toggle="tab">Exam History</a></li>
					<li><a href="#tab-employee-evaluations" data-toggle="tab">Evaluation(s)</a></li>
					<li><a href="#tab-employee-itemaccountability" data-toggle="tab">Item Accountability</a></li>
					
					
				</ul>
				<div class="tab-content">
					<div class="tab-pane in active" id="tab-employee-attendance">
						<div class="row">
							<div class="col-md-4">
								
								<span>Regular Shift: <b>{{ isset($regular_shift->shift_schedule) ? $regular_shift->shift_schedule : 'None' }}</b></span>
								
							</div>
							<div class="col-md-6">
								<div id="filters" style="z-index: 1; position: absolute;">
									<label>From</label>
									<input type="text" name="from" class="date start att-filters" autocomplete="off">
									<label>To</label>
									<input type="text" name="to" class="date end att-filters" autocomplete="off">
								</div>
							</div>
							<div class="col-md-2">
								<div style="z-index: 1; position: absolute;">
								<a href="#" id="refreshAttendance" style="font-size: 15pt;">
									<i class="fa fa-refresh"></i> Refresh
								</a>
								</div>
							</div>
							<div class="col-md-12">
								<div style="margin-top: -40px;">
									@include('client.tables.employee_attendance_table')
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-employee-notices">
						<div class="row">
							<div class="col-sm-12">
								<div style="margin-top: -40px;">
									@include('client.tables.employee_notices_table')
								</div>
							</div>
						</div>											
					</div>
					<div class="tab-pane" id="tab-employee-gatepasses">
						<div class="row">
							<div class="col-sm-12">
								<div style="margin-top: -40px;">
									@include('client.tables.employee_gatepass_table')
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-employee-leaves">
						<div class="row">
							<div class="col-sm-12">
								<div style="margin-top: -40px;">
									@include('client.tables.employee_leaves_table')
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-employee-exams">
						<div class="row">
							<div class="col-sm-12">
								<div style="margin-top: -40px;">
									@include('client.tables.employee_exams_table')
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-employee-evaluations">
						<div class="row">
							<div class="col-sm-12">
								<div style="margin-top: -40px;">
									@include('client.tables.employee_evaluations_table')
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-employee-itemaccountability">
						<div class="row">
							<div class="col-sm-12">
								<div style="margin-top: -40px;">
									@include('client.tables.employee_itemaccountability_table')
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
      <div class="inner-box featured">
         <div class="widget property-agent">
            <h3 class="widget-title">Transactions</h3>
            <div class="agent-info">
               <ul class="options">
                  <li class="option-list">
                     <span class="option-name">
                        <a href="#" data-toggle="modal" data-target="#pending-notice-modal">
                           Pending Absent Notice Slip(s)
                        </a>
                     </span>
                     <span class="badge badge-warning" style="background-color: #E67E22;" id="emp-pending-notices-count">0</span>
                  </li>
                  <li class="option-list">
                     <span class="option-name">
                        <a href="#" data-toggle="modal" data-target="#pending-gatepass-modal">
                           Pending Gatepass Request(s)
                        </a>
                     </span>
                     <span class="badge badge-warning" style="background-color: #E67E22;" id="emp-pending-gatepass-count">0</span>
                  </li>
                  <li class="option-list">
                     <span class="option-name">
                        <a href="#" data-toggle="modal" data-target="#unreturned-items-modal">
                           Gatepass - Unreturned Item(s)
                        </a>
                     </span>
                     <span class="badge badge-warning" style="background-color: #E67E22;" id="emp-unreturned-items-count">0</span>
                  </li>
               </ul>
            </div>
         </div>
      </div>
      <div class="inner-box featured">
         <div class="widget property-agent">
            <h3 class="widget-title">Settings</h3>
            <div class="agent-info">
               <ul class="options">
                  <li class="option-list">
                     <span class="option-name">
                        <a href="#" data-toggle="modal" data-target="#update-personal-details">
                           <i class="fa fa-user"></i> Update Personal Details
                        </a>
                     </span>
                  </li>
                  <li class="option-list">
                     <span class="option-name">
                        <a href="#" data-toggle="modal" data-target="#edit-employee">
                           <i class="fa fa-pencil"></i> Edit Details
                        </a>
                     </span>
                  </li>
                  <li class="option-list">
                     <span class="option-name">
                        <a href="#" data-toggle="modal" data-target="#reset-employee-password"><i class="fa fa-lock"></i> Reset Password</a>
                     </span>
                  </li>
                  <li class="option-list">
                     <span class="option-name">
                        <a href="#" data-toggle="modal" data-target="#reset-employee-leaves"><i class="fa fa-repeat"></i> Reset No. of Leave(s)</a>
                     </span>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>

<style type="text/css">
	.options{list-style-type: none; padding: 0; margin: 0;}
	.option-name{font-size: 10pt;}
	.option-list{border-bottom:1px solid #ddd; padding: 8px;}
</style>

@endsection

@section('script')

<script src="{{ asset('css/js/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('css/js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/datepicker/datepair.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.datepair.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/datepicker/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.timepicker.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/jquery.timepicker.css') }}" />

<script>
	$(document).ready(function(){
		$.ajaxSetup({
	  		headers: {
	    		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$('#filters .date').datepicker({
	        'format': 'yyyy-mm-dd',
	        'autoclose': true
	    });

	    $('#filters .end').datepicker('setDate', new Date());
		$('#filters .start').datepicker('setDate', getFirstDayOfMonth());

	   	$('.datatables').DataTable({
	      	"bLengthChange": false,
	      	"searching": false,
	      	"ordering": false,
	      	"bInfo": false,
	      	"sDom": '<"row view-filter"<"col-sm-12"<"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"p>>>'
	   	});
	  
		function getFirstDayOfMonth(){
	    	var today = new Date();
	    	return new Date(today.getFullYear(),today.getMonth(), 1);
		}

		$('.att-filters').on('change', function(){
			loadEmployeeAttendance();
	   	});

		loadEmployeeAttendance();
		loadEmployeeNotices();
		loadEmployeeGatepass();
		loadEmployeeLeaves();
		loadEmployeeExams();
		loadEmployeeEvaluations();
		loadEmployeePendingNotices();
		loadEmployeePendingGatepass();
		loadEmployeeUnreturnedItems();

		$('#refreshAttendance').on('click', function(e){
			e.preventDefault();
			var employee = $('#employee-id').text();
	      	$.ajax({
				type: 'POST',
				url: '/refreshAttendance/' + employee,
				beforeSend: function(){
					$("#refreshAttendance").text("Updating...");
				},
				success: function(response){
					loadEmployeeAttendance();
				},
				complete: function(){
					$("#refreshAttendance").html("<i class=\"fa fa-refresh\"></i> Refresh");
				}
			});
		});

		function loadEmployeeAttendance(){
			var employee = $('#employee-id').text();
			var data = {
		        'start': $('#filters .start').val(),
		        'end': $('#filters .end').val()
		    }
	      	$.ajax({
		        type: 'GET',
		        url: "/employeeAttendance/" + employee,
		        data: data,
		        dataType: 'json',
		        cache: false,
	         	success: function(data){
	            	var table = $('#employee-attendance-table').DataTable();
	           		table.clear();
		            if (data != '') {
		            	$.each(data, function(i, d){
		            		var status_color = (data[i].status == 'late') ? 'danger' : 'primary';
		            		var status = "<span class=\"label label-" + status_color + "\">" + data[i].status + "</span>";
		            		table.row.add([
		            			data[i].transaction_date,
		            			data[i].time_in,
		            			data[i].in_loc,
		            			data[i].time_out,
		            			data[i].out_loc,
		            			status,
		            			data[i].hrs_worked,
		            			data[i].overtime
		            		]);
		               });
		            }
	            	table.draw();
	         	}
	      	});
   		}

   		function loadEmployeeNotices(){
			var employee = $('#employee-id').text();
	      	$.ajax({
		        type: 'GET',
		        url: "/employeeNotices/" + employee,
		        // data: data,
		        dataType: 'json',
		        cache: false,
	         	success: function(data){
	            	var table = $('#employee-notice-table').DataTable();
	           		table.clear();
		            if (data != '') {
		               $.each(data, function(i, d){
		               	absence_period = data[i].date_from + " - " + data[i].date_to;
		               	switch(data[i].status.toLowerCase()){
               				case 'approved':
               					status = "<span class=\"label label-primary\">" + data[i].status.toUpperCase() + "</span>";
               					break;
               				case 'cancelled':
               					status = "<span class=\"label label-danger\">" + data[i].status.toUpperCase() + "</span>";
               					break;
               				case 'disapproved':
               					status = "<span class=\"label label-danger\">" + data[i].status.toUpperCase() + "</span>";
               					break;
               				default:
               					status = "<span class=\"label label-warning\">" + data[i].status.toUpperCase() + "</span>";
               			}
		                	table.row.add([
		                		data[i].notice_id, 
		                		data[i].leave_type, 
		                		absence_period, 
		                		data[i].reason, 
		                		status,
		                	]);
		               });
		            }
	            	table.draw();
	         	}
	      	});
   		}

   		function loadEmployeeGatepass(){
			var employee = $('#employee-id').text();
	      	$.ajax({
		        type: 'GET',
		        url: "/employeeGatepass/" + employee,
		        // data: data,
		        dataType: 'json',
		        cache: false,
	         	success: function(data){
	            	var table = $('#employee-gatepass-table').DataTable();
	           		table.clear();
		            if (data != '') {
		               $.each(data, function(i, d){
		               	switch(data[i].status.toLowerCase()){
               				case 'approved':
               					status = "<span class=\"label label-primary\">" + data[i].status.toUpperCase() + "</span>";
               					break;
               				case 'cancelled':
               					status = "<span class=\"label label-danger\">" + data[i].status.toUpperCase() + "</span>";
               					break;
               				case 'disapproved':
               					status = "<span class=\"label label-danger\">" + data[i].status.toUpperCase() + "</span>";
               					break;
               				default:
               					status = "<span class=\"label label-warning\">" + data[i].status.toUpperCase() + "</span>";
               			}
		                	table.row.add([
		                		data[i].gatepass_id, 
		                		data[i].item_description, 
		                		data[i].purpose, 
		                		data[i].returned_on, 
		                		data[i].item_type, 
		                		status,
		                	]);
		               });
		            }
	            	table.draw();
	         	}
	      	});
   		}

   		function loadEmployeeLeaves(){
			var employee = $('#employee-id').text();
	      	$.ajax({
		        type: 'GET',
		        url: "/employeeLeaves/" + employee,
		        // data: data,
		        dataType: 'json',
		        cache: false,
	         	success: function(data){
	            	var table = $('#employee-leaves-table').DataTable();
	           		table.clear();
		            if (data != '') {
		               $.each(data, function(i, d){
		                	table.row.add([
		                		data[i].leave_id, 
		                		data[i].leave_type, 
		                		data[i].total, 
		                		data[i].remaining, 
		                	]);
		               });
		            }
	            	table.draw();
	         	}
	      	});
   		}

   		function loadEmployeeExams(){
			var employee = $('#user-id').text();
			var today = $('#today').text();
	      	$.ajax({
		        type: 'GET',
		        url: "/employeeExams/" + employee,
		        // data: data,
		        dataType: 'json',
		        cache: false,
	         	success: function(data){
	            	var table = $('#employee-exams-table').DataTable();
	           		table.clear();
		            if (data != '') {
		               $.each(data, function(i, d){
		               	if (data[i].start_time != null) {
		               		status = 'Completed';
		               	}else if (new Date(today) > new Date(data[i].validity_date)) {
		               		status = 'Validity Expired';
		               	}else{
		               		status = 'Pending';
		               	}
		                	table.row.add([
		                		data[i].date_of_exam, 
		                		data[i].exam_title, 
		                		data[i].exam_group_description, 
		                		data[i].date_taken, 
		                		data[i].validity_date,
		                		status, 
		                	]);
		               });
		            }
	            	table.draw();
	         	}
	      	});
   		}

   		function loadEmployeeEvaluations(){
			var employee = $('#employee-id').text();
	      	$.ajax({
		        type: 'GET',
		        url: "/employeeEvaluations/" + employee,
		        // data: data,
		        dataType: 'json',
		        cache: false,
	         	success: function(data){
	            	var table = $('#employee-evaluations-table').DataTable();
	           		table.clear();
		            if (data != '') {
		               $.each(data, function(i, d){
		               	file_dir = "{{ Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->url('uploads/evaluations/') }}";
		               	eval_file = "<a href=\"" + file_dir +"/"+ data[i].evaluation_file + "\" target=\"_blank\"><i class=\"fa fa-search\"></i></a>";
		                	table.row.add([
		                		data[i].title, 
		                		data[i].evaluation_date, 
		                		data[i].evaluated_by, 
		                		eval_file, 
		                	]);
		               });
		            }
	            	table.draw();
	         	}
	      	});
   		}

   		function loadEmployeePendingNotices(){
			var employee = $('#employee-id').text();
			
	      	$.ajax({
		        type: 'GET',
		        url: "/employeeNotices/" + employee,
		        // data: data,
		        dataType: 'json',
		        cache: false,
	         	success: function(data){
	         		
	            	var table = $('#employee-pending-notices-table').DataTable();
	           		table.clear();
		            if (data != '') {
		            	var count = 0;
		               	$.each(data, function(i, d){
			               	absence_period = data[i].date_from + " - " + data[i].date_to;
			               	if (data[i].status.toLowerCase() == 'for approval') {
			               		count++;
			               		table.row.add([
			                		data[i].notice_id, 
			                		data[i].leave_type, 
			                		absence_period, 
			                		data[i].reason, 
			                	]);
			               	}
		               });
		               $('#emp-pending-notices-count').text(count);
		            }
	            	table.draw();
	         	}
	      	});
   		}

   		function loadEmployeePendingGatepass(){
			var employee = $('#employee-id').text();
	      	$.ajax({
		        type: 'GET',
		        url: "/employeeGatepass/" + employee,
		        // data: data,
		        dataType: 'json',
		        cache: false,
	         	success: function(data){
	            	var table = $('#employee-pending-gatepass-table').DataTable();
	           		table.clear();
		            if (data != '') {
		            	var count = 0;
		               $.each(data, function(i, d){
		               	if (data[i].status.toLowerCase() == 'for approval') {
		               		count++;
		                	table.row.add([
		                		data[i].gatepass_id, 
		                		data[i].item_description, 
		                		data[i].purpose, 
		                		data[i].returned_on, 
		                		data[i].item_type, 
		                		status,
		                	]);
		                }
		               });
		               $('#emp-pending-gatepass-count').text(count);
		            }
	            	table.draw();
	         	}
	      	});
   		}

   		function loadEmployeeUnreturnedItems(){
			var employee = $('#employee-id').text();
	      	$.ajax({
		        type: 'GET',
		        url: "/employeeGatepass/" + employee,
		        // data: data,
		        dataType: 'json',
		        cache: false,
	         	success: function(data){
	            	var table = $('#employee-unreturned-items-table').DataTable();
	           		table.clear();
		            if (data != '') {
		            	var count = 0;
		               $.each(data, function(i, d){
		               	if (data[i].status.toLowerCase() == 'approved' && data[i].item_status.toLowerCase() == 'unreturned') {
		               		count++;
		                	table.row.add([
		                		data[i].gatepass_id, 
		                		data[i].item_description, 
		                		data[i].purpose, 
		                		data[i].item_type, 
		                	]);
		                }
		               });
		               $('#emp-unreturned-items-count').text(count);
		            }
	            	table.draw();
	         	}
	      	});
   		}
	});
</script>
@endsection