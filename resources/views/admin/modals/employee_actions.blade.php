<!-- The Modal -->
<div class="modal fade" id="editEmployee{{ $employee->id }}">
   <div class="modal-dialog modal-lg" style="width: 50%;">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Employee</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/employees/{{ $employee->id }}" method="POST">
               @csrf
               @method('PUT')
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Access ID:</label>
                     <input type="text" class="form-control" name="user_id" placeholder="Enter Access ID" value="{{ $employee->user_id }}" disabled>
                  </div>
                  <div class="form-group">
                     <label>Employee Name:</label>
                     <input type="text" class="form-control" name="employee_name" placeholder="Enter Employee Name" value="{{ $employee->employee_name }}">
                  </div>
                  <div class="form-group">
                     <label>Birthdate:</label>
                     <input type="date" class="form-control" name="birthdate" placeholder="Enter Birthdate" value="{{ $employee->birth_date }}">
                  </div>
                  <div class="form-group">
                     <label>Address:</label>
                     <textarea class="form-control" name="address" placeholder="Enter Address">{{ $employee->address }}</textarea>
                  </div>
                  <div class="form-group">
                     <label>Contact No.:</label>
                     <input type="text" class="form-control" name="contact_no" placeholder="Contact No." value="{{ $employee->contact_no }}">
                  </div>
                  <div class="form-group">
                     <label>SSS No.:</label>
                     <input type="text" class="form-control" name="sss_no" placeholder="Enter SSS No." value="{{ $employee->sss_no }}">
                  </div>
                  <div class="form-group">
                     <label>TIN No.:</label>
                     <input type="text" class="form-control" name="tin_no" placeholder="Enter TIN No." value="{{ $employee->tin_no }}">
                  </div>
                  <div class="form-group">
                     <label>Civil Status:</label>
                     <select class="form-control" name="civil_status">
                        <option value="" disabled>Select Civil Status</option>
                        <option value="Single" {{ $employee->civil_status === "Single" ? "selected" : "" }}>Single</option>
                        <option value="Married" {{ $employee->civil_status === "Married" ? "selected" : "" }}>Married</option>
                        <option value="Widowed" {{ $employee->civil_status === "Widowed" ? "selected" : "" }}>Widowed</option>
                     </select>
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Nickname:</label>
                     <input type="text" class="form-control" name="nickname" placeholder="Enter Nickname" value="{{ $employee->nick_name }}">
                  </div>
                  <div class="form-group">
                     <label>Designation:</label>
                     <input type="text" class="form-control" name="designation" placeholder="Enter Designation" value="{{ $employee->designation }}" required>
                  </div>
                  <div class="form-group">
                     <label>Department:</label>
                     @if(isset($departments))
                     <select class="form-control" name="department" required>
                        <option value="">Select Department</option>
                        @forelse($departments as $department)
                        <option value="{{ $department->department_id }}" {{ $department->department_id === $employee->department_id ? "selected" : "" }}>{{ $department->department }}</option>
                        @empty
                        <option>No Departments Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Employment Status:</label>
                     <select class="form-control" name="employment_status">
                        <option disabled>Select Employment Status</option>
                        <option value="Regular" {{ $employee->employment_status === 'Regular' ? 'selected' : '' }}>Regular</option>
                        <option value="Contractual" {{ $employee->employment_status === 'Contractual' ? 'selected' : '' }}>Contractual</option>
                        <option value="Probationary" {{ $employee->employment_status === 'Probationary' ? 'selected' : '' }}>Probationary</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Telephone (Local No.):</label>
                     <input type="text" class="form-control" name="telephone" placeholder="Enter Local No." value="{{ $employee->telephone }}">
                  </div>
                  <div class="form-group">
                     <label>User Group:</label>
                     <select class="form-control" name="user_group">
                        <option disabled>Select User Group</option>
                        <option value="Employee" {{ $employee->user_group === 'Employee' ? 'selected' : '' }}>Employee</option>
                        <option value="Manager" {{ $employee->user_group === 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="HR Personnel" {{ $employee->user_group === 'HR Personnel' ? 'selected' : '' }}>HR Personnel</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Email:</label>
                     <input type="email" class="form-control" name="email" placeholder="Enter Email" value="{{ $employee->email }}" required>
                  </div>
                  <div class="form-group">
                     <label>Status:</label>
                     <select class="form-control" name="status">
                        <option value="Active" {{ $employee->status === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ $employee->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                     </select>
                  </div>
               </div>

            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
         </form>
      </div>
   </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="deleteEmployee{{ $employee->id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Employee</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
              <form action="/admin/employees/delete/{{ $employee->id }}" method="POST">
               @csrf
               @method("DELETE")
               <div class="col-sm-12">
                 Delete Employee <b>{{ $employee->employee_name }}</b> ?
               </div>               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> Delete</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
         </form>
      </div>
   </div>
</div>


                  