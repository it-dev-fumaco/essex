<div class="modal fade" id="data_inputmodal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">KPI - Data Input List</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-2 m-0">
				<div class="row p-0 m-0">
					<div class="col-7">
						<div class="row p-0 m-0">
							<div class="col-12 {{ ($depart == 'employee') ? 'd-none' : '' }}">
								<div class="d-flex flex-row">
									<label class="col-4">Entry By:</label>
									<select class="col-8" name="entry" id="entry" onchange="entryvalidation()">
										<option value="per_department" selected="selected">KPI per department</option>
										<option value="per_employee">Per employee</option>
									</select>
								</div>
							</div> 
							<div class="col-12 pt-2 {{ ($depart == 'employee') ? 'd-none' : '' }}">
								<div class="d-flex flex-row">
									<label class="col-4">Department:</label>
									<select class="col-8" name="dept" id="dept" onchange="getemployeeperdept()">
										@foreach($department_heads as $row)
										<option value="{{ $row->department_id }}">{{ $row->department }} </option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-12 pt-2" style="display:none;" id="employeelistdiv">
								<div class="d-flex flex-row">
									<label class="col-4">Employee Name</label>
									<select class="col-8" name="employeelist" id="employeelist" onchange="createFunction()">
										<option value=""></option>
									</select>
								</div>
							</div>
							<div class="col-12 pt-2">
								<div class="d-flex flex-row">
									<label class="col-4">Evaluation Period</label>
									<select class="col-8" name="entrysched" id="entrysched" onchange="createFunction()">
										<option value="Monthly">Monthly</option>
										<option value="Quarterly">Quarterly</option>
										<option value="Semi-Annual">Semi-Annual</option>
										<option value="Annual">Annual</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-5 p-2">
						<div class="p-3 border rounded border-secondary">
							<span class="d-block fw-bold">KPI Reporting Deadline:</span>
							<span class="d-block text-center fst-italic mt-2" id="show_scheduledDate"></span>
						</div>
					</div>
				</div>
				<input type="hidden" name="departmentvalidate" id="departmentvalidate" value="{{ $depart }}">
				<div class="row m-0 p-0" id="viewdatainput"></div>
			</div>
		</div>
	</div>
</div>