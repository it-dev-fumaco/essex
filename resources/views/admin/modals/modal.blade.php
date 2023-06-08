<!-- The Modal -->
<div class="modal fade" id="addEmployee">
   <div class="modal-dialog modal-lg" style="width: 50%;">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Employee</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/employees/create" method="POST">
               @csrf
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Access ID:</label>
                     <input type="text" class="form-control" name="user_id" placeholder="Enter Access ID" required>
                  </div>
                  <div class="form-group">
                     <label>Employee Name:</label>
                     <input type="text" class="form-control" name="employee_name" placeholder="Enter Employee Name">
                  </div>
                  <div class="form-group">
                     <label>Birthdate:</label>
                     <input type="date" class="form-control" name="birthdate" placeholder="Enter Birthdate">
                  </div>
                  <div class="form-group">
                     <label>Address:</label>
                     <textarea class="form-control" name="address" placeholder="Enter Address"></textarea>
                  </div>
                  <div class="form-group">
                     <label>Contact No.:</label>
                     <input type="text" class="form-control" name="contact_no" placeholder="Contact No.">
                  </div>
                  <div class="form-group">
                     <label>SSS No.:</label>
                     <input type="text" class="form-control" name="sss_no" placeholder="Enter SSS No.">
                  </div>
                  <div class="form-group">
                     <label>TIN No.:</label>
                     <input type="text" class="form-control" name="tin_no" placeholder="Enter TIN No.">
                  </div>
                  <div class="form-group">
                     <label>Civil Status:</label>
                     <select class="form-control" name="civil_status">
                        <option value="">Select Civil Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                     </select>
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Nickname:</label>
                     <input type="text" class="form-control" name="nickname" placeholder="Enter Nickname">
                  </div>
                  <div class="form-group">
                     <label>Designation:</label>
                     @if(isset($designations))
                     <select class="form-control" name="designation" required>
                        <option value="">Select Designation</option>
                        @forelse($designations as $designation)
                        <option value="{{ $designation->des_id }}">{{ $designation->designation }}</option>
                        @empty
                        <option>No Departments Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Department:</label>
                     @if(isset($departments))
                     <select class="form-control" name="department" required>
                        <option value="">Select Department</option>
                        @forelse($departments as $department)
                        <option value="{{ $department->department_id }}">{{ $department->department }}</option>
                        @empty
                        <option>No Departments Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Employment Status:</label>
                     <select class="form-control" name="employment_status">
                        <option value="">Select Employment Status</option>
                        <option value="Regular">Regular</option>
                        <option value="Contractual">Contractual</option>
                        <option value="Probationary">Probationary</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Telephone (Local No.):</label>
                     <input type="text" class="form-control" name="telephone" placeholder="Enter Local No.">
                  </div>
                  <div class="form-group">
                     <label>User Group:</label>
                     <select class="form-control" name="user_group">
                        <option value="">Select User Group</option>
                        <option value="Employee">Employee</option>
                        <option value="Manager">Manager</option>
                        <option value="HR Personnel">HR Personnel</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Email:</label>
                     <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                  </div>
                  <div class="form-group">
                     <label>Password:</label>
                     <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div></form>
      </div>
   </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="addDepartment">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Department</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/saveDepartment" method="POST">
               @csrf
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Department Name:</label>
                     <input type="text" class="form-control" name="department" id="department" placeholder="Enter Department" required>
                  </div>
               </div>
               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div></form>
      </div>
   </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="addAdmin">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Admin</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/users/create" method="POST">
               @csrf
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Access ID:</label>
                     <input type="text" class="form-control" name="access_id" id="access_id" required>
                  </div>
               </div>
                <div class="col-sm-12">
                  <div class="form-group">
                     <label>Name:</label>
                     <input type="text" class="form-control" name="username" id="username" required>
                  </div>
               </div>
                <div class="col-sm-12">
                  <div class="form-group">
                     <label>Email:</label>
                     <input type="email" class="form-control" name="email" id="email" required>
                  </div>
               </div>
                <div class="col-sm-12">
                  <div class="form-group">
                     <label>Password:</label>
                     <input type="password" class="form-control" name="password" id="password" required>
                  </div>
               </div>
               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div></form>
      </div>
   </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="addDesignation">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Designation</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/saveDesignation" method="POST">
               @csrf
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Department:</label>
                     @if(isset($departments))
                     <select class="form-control" name="department" id="department" required>
                        <option value="">Select Department</option>
                        @forelse($departments as $department)
                        <option value="{{ $department->department_id }}">{{ $department->department }}</option>
                        @empty
                        <option>No Departments Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Designation Name:</label>
                     <input type="text" class="form-control" name="designation" id="designation" placeholder="Enter Designation" required>
                  </div>
                  <div class="form-group">
                     <label>Remarks:</label>
                     <textarea cols="10" rows="4" name="remarks" class="form-control"></textarea>
                  </div>
               </div>
               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div></form>
      </div>
   </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="addApplicant">
   <div class="modal-dialog modal-lg" style="width: 50%;">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Applicant</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/applicants/create" method="POST">
               @csrf
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Applicant ID:</label>
                     <input type="text" class="form-control" name="user_id" placeholder="Enter Applicant ID" required>
                  </div>
                  <div class="form-group">
                     <label>Applicant Name:</label>
                     <input type="text" class="form-control" name="employee_name" placeholder="Enter Applicant Name">
                  </div>
                  <div class="form-group">
                     <label>Birthdate:</label>
                     <input type="date" class="form-control" name="birthdate" placeholder="Enter Birthdate">
                  </div>
                  <div class="form-group">
                     <label>Address:</label>
                     <textarea class="form-control" name="address" placeholder="Enter Address" rows="4"></textarea>
                  </div>
                  <div class="form-group">
                     <label>Contact No.:</label>
                     <input type="text" class="form-control" name="contact_no" placeholder="Enter Contact No.">
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Nickname:</label>
                     <input type="text" class="form-control" name="nickname" placeholder="Enter Nickname">
                  </div>
                  <div class="form-group">
                     <label>SSS No.:</label>
                     <input type="text" class="form-control" name="sss_no" placeholder="Enter SSS No.">
                  </div>
                  <div class="form-group">
                     <label>TIN No.:</label>
                     <input type="text" class="form-control" name="tin_no" placeholder="Enter TIN No.">
                  </div>
                  <div class="form-group">
                     <label>Civil Status:</label>
                     <select class="form-control" name="civil_status">
                        <option value="">Select Civil Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Position applied for (1st choice):</label>
                     <input type="text" class="form-control" name="position_applied_for1" placeholder="Enter Position applied for (1st choice)">
                  </div>
                  <div class="form-group">
                     <label>Position applied for (2nd choice):</label>
                     <input type="text" class="form-control" name="position_applied_for2" placeholder="Enter Position applied for (2nd choice)">
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div></form>
      </div>
   </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="addShift">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Shift</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/saveShift" method="POST">
               @csrf
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Schedule Date:</label>
                     <input type="date" class="form-control" name="shiftSchedule" id="shiftSchedule" required>
                  </div>
                  <div class="form-group">
                     <label>Time In:</label>
                     <input type="text" class="form-control" name="timeIn" id="timeIn" placeholder="Enter Time In" required>
                  </div>
                  <div class="form-group">
                     <label>Time Out:</label>
                     <input type="text" class="form-control" name="timeOut" id="timeOut" placeholder="Enter Time Out" required>
                  </div>
                 
               </div>
               <div class="col-sm-6">
                   <div class="form-group">
                     <label>Breaktime (hrs):</label>
                     <input type="number" class="form-control" name="breaktime" id="breaktime" required>
                  </div>
                  <div class="form-group">
                     <label>Grace Period (mins):</label>
                     <input type="number" class="form-control" name="gracePeriod" id="gracePeriod" required>
                  </div>
                  <div class="form-group">
                     <label>Remarks:</label>
                     <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Enter Remarks" required>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div></form>
      </div>
   </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="addLeaveType">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Leave Type</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/saveLeaveType" method="POST">
               @csrf
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Leave Type:</label>
                     <input type="text" class="form-control" name="leave_type" id="leave_type" placeholder="Enter Leave Type" required>
                  </div>
                  <div class="form-group">
                     <label>Color Legend:</label>
                     <input type="color" class="form-control" name="color_legend" id="color_legend" placeholder="Enter Color Legend" required>
                  </div>
                  <div class="form-group">
                     <label>Description:</label>
                     <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description" required>
                  </div>
                  <div class="form-group">
                     <label class="checkbox-inline">
                        <input type="checkbox" name="applied_to_all" checked="unchecked"> Apply to all Employees?
                     </label>
                  </div>
               </div>
               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div></form>
      </div>
   </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="addApprover">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Approver</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/saveApprover" method="POST">
               @csrf
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Department:</label>
                     @if(isset($departments))
                     <select class="form-control" name="department" id="department" required>
                        <option value="">Select Department</option>
                        @forelse($departments as $department)
                        <option value="{{ $department->department_id }}">{{ $department->department }}</option>
                        @empty
                        <option>No Departments Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Employee:</label>
                     @if(isset($employees))
                     <select class="form-control" name="employee" id="employee" required>
                        <option value="">Select Employee</option>
                        @forelse($employees as $employee)
                        <option value="{{ $employee->user_id }}">{{ $employee->employee_name }}</option>
                        @empty
                        <option>No Employees Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
               </div>
               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div></form>
      </div>
   </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="addEmployeeLeave">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Employee Leave</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/saveEmployeeLeave" method="POST">
               @csrf
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Employee:</label>
                     @if(isset($employees))
                     <select class="form-control" name="employee" id="employee" required>
                        <option value="">Select Employee</option>
                        @forelse($employees as $employee)
                        <option value="{{ $employee->user_id }}">{{ $employee->employee_name }}</option>
                        @empty
                        <option>No Employees Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Leave Type:</label>
                     @if(isset($leave_types))
                     <select class="form-control" name="leave_type" id="leave_type" required>
                        <option value="">Select Leave Type</option>
                        @forelse($leave_types as $leave_type)
                        <option value="{{ $leave_type->leave_type_id }}">{{ $leave_type->leave_type }}</option>
                        @empty
                        <option>No Leave Types Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Total no. of Leave(s):</label>
                     <input type="number" class="form-control" name="total" id="total" placeholder="Enter Total no. of Leave(s)" required>
                  </div>
                  <div class="form-group">
                     <label>Year:</label>
                     <input type="text" class="form-control" name="year" id="year" placeholder="Enter Year" required>
                  </div>
               </div>
               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div></form>
      </div>
   </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="addItem">
   <div class="modal-dialog modal-lg" style="width: 50%;">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Item</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/saveItem" method="POST">
               @csrf
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Item Name:</label>
                     <input type="text" class="form-control" name="item_name" id="item_name" placeholder="Enter Item Name" required>
                  </div>
                  <div class="form-group">
                     <label>Description:</label>
                     <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description">
                  </div>
                  <div class="form-group">
                     <label>Brand:</label>
                     <input type="text" class="form-control" name="brand" id="brand" placeholder="Enter Brand">
                  </div>
                  <div class="form-group">
                     <label>Model:</label>
                     <input type="text" class="form-control" name="model" id="model" placeholder="Enter Model">
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Item Type:</label>
                     <select class="form-control" name="item_type" id="item_type">
                        <option value="">Select Item Type</option>
                        <option value="Mobile Phone">Mobile Phone</option>
                        <option value="Vehicle">Vehicle</option>
                        <option value="Laptop">Laptop</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Others">Others</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Serial No:</label>
                     <input type="text" class="form-control" name="serial_no" id="serial_no" placeholder="Enter Serial No.">
                  </div>
                  <div class="form-group">
                     <label>MAC Address:</label>
                     <input type="text" class="form-control" name="mac_address" id="mac_address" placeholder="Enter MAC Address">
                  </div>
                  <div class="form-group">
                     <label>Other References:</label>
                     <input type="text" class="form-control" name="references" id="references" placeholder="Enter References">
                  </div>
                  
                  <div class="form-group">
                     <label>Remarks:</label>
                     <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Enter Remarks">
                  </div>
               </div>
               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div></form>
      </div>
   </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="addEmpItem">
   <div class="modal-dialog modal-lg" style="width: 50%;">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Issue Item to Employee</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/items_issued/create" method="POST">
               @csrf
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Employee Name:</label>
                      @if(isset($employees))
                     <select class="form-control" name="employee" id="employee">
                        <option value="">Select Employee</option>
                        @forelse($employees as $employee)
                        <option value="{{ $employee->user_id }}">{{ $employee->employee_name }}</option>
                        @empty
                        <option>No Employees Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Item Name:</label>
                     @if(isset($items))
                     <select class="form-control" name="item" id="item">
                        <option value="">Select Item</option>
                        @forelse($items as $item)
                        <option value="{{ $item->item_id }}">{{ $item->item_name }}</option>
                        @empty
                        <option>No Item(s) Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Status:</label>
                     <input type="text" class="form-control" name="status" id="status">
                  </div>
                  <div class="form-group">
                     <label>Date Issued:</label>
                     <input type="date" class="form-control" name="date_issued" id="date_issued" placeholder="Enter Model">
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Issued by:</label>
                     @if(isset($employees))
                     <select class="form-control" name="issued_by" id="issued_by">
                        <option value="">Select Employee</option>
                        @forelse($employees as $employee)
                        <option value="{{ $employee->user_id }}">{{ $employee->employee_name }}</option>
                        @empty
                        <option>No Employees Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Valid Until:</label>
                     <input type="date" class="form-control" name="valid_until" id="valid_until" placeholder="Enter Serial No.">
                  </div>
                  <div class="form-group">
                     <label>Revoke Reason:</label>
                     <input type="text" class="form-control" name="revoke_reason" id="revoke_reason" placeholder="Enter MAC Address">
                  </div>
                 
                  
                  <div class="form-group">
                     <label>Remarks:</label>
                     <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Enter Remarks">
                  </div>
               </div>
               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div></form>
      </div>
   </div>
</div>