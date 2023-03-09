<div class="modal fade" id="add-employee-modal">
   <div class="modal-dialog modal-lg" style="width: 80%;">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Employee</h4>
         </div>
         <div class="modal-body">
            <form action="/admin/employee/create" method="POST">
            @csrf
               <div class="row" style="margin: 7px;">
                  <div class="col-sm-4">
                     <div class="form-group">
                        <label>Access ID:</label>
                        <input type="text" class="form-control" name="user_id" placeholder="Enter Access ID" required>
                     </div>
                     <div class="form-group">
                        <label>Employee Name:</label>
                        <input type="text" class="form-control" name="employee_name" placeholder="Enter Employee Name" required>
                     </div>
                     <div class="form-group">
                        <label>Nickname:</label>
                        <input type="text" class="form-control" name="nickname" placeholder="Enter Nickname" required>
                     </div>
                     <div class="form-group">
                        <label>Birthdate:</label>
                        <input type="date" class="form-control" name="birthdate" placeholder="Enter Birthdate" required>
                     </div>
                     <div class="form-group">
                        <label>Address:</label>
                        <textarea class="form-control" name="address" placeholder="Enter Address" required></textarea>
                     </div>
                     <div class="form-group">
                        <label>Contact No.:</label>
                        <input type="text" class="form-control" name="contact_no" placeholder="Contact No." required>
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <label>Civil Status:</label>
                        <select class="form-control" name="civil_status" required>
                           <option value="">Select Civil Status</option>
                           <option value="Single">Single</option>
                           <option value="Married">Married</option>
                           <option value="Widowed">Widowed</option>
                        </select>
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
                        <label>Department:</label>
                        @if(isset($departments))
                        <select class="form-control" name="department" required>
                           <option value="">Select Department</option>
                           @forelse($departments as $department)
                           <option value="{{ $department->department_id }}">{{ $department->department }}</option>
                           @empty
                           <option>No Department(s) Found.</option>
                           @endforelse
                        </select>
                        @endif
                     </div>
                     <div class="form-group">
                        <label>Designation:</label>
                        @if(isset($designations))
                        <select class="form-control" name="designation" required>
                           <option value="">Select Designation</option>
                           @forelse($designations as $designation)
                           <option value="{{ $designation->des_id }}">{{ $designation->designation }}</option>
                           @empty
                           <option>No Designation(s) Found.</option>
                           @endforelse
                        </select>
                        @endif
                     </div>
                     <div class="form-group">
                        <label>Shift:</label>
                        <select class="form-control" name="shift" required>
                           <option value="">Select Shift</option>
                           @forelse($shifts as $shift)
                           <option value="{{ $shift->shift_id }}">{{ $shift->shift_schedule }}</option>
                           @empty
                           <option>No Shift(s) Found.</option>
                           @endforelse
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <label>Branch:</label>
                        <select class="form-control" name="branch" required>
                           <option value="">Select Branch</option>
                              @forelse($branch as $loc)
                              <option value="{{ $loc->branch_id }}">{{ $loc->branch_name }}</option>
                              @empty
                              <option>No Branch Found.</option>
                              @endforelse
                        </select>
                     </div>
                     <div class="form-group">
                        <label>Employment Status:</label>
                        <select class="form-control" name="employment_status" required>
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
                        <select class="form-control" name="user_group" required>
                           <option value="">Select User Group</option>
                           <option value="Employee">Employee</option>
                           <option value="Manager">Manager</option>
                           <option value="HR Personnel">HR Personnel</option>
                           <option value="Editor">Editor</option>
                        </select>
                     </div>
                     <div class="form-group">
                        <label>Local Email:</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                     </div>
                     <div class="form-group">
                        <label>Password:</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
