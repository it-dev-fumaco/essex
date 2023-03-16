<div class="modal fade" id="add-employee-modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Employee</h4>
         </div>
         <div class="modal-body">
            <form action="/client/employee/create" method="POST" autocomplete="off" enctype="multipart/form-data">
            @csrf
               <div class="row" style="padding-top: 0;">
                  <div class="col-md-12">
                     <div class="inner-box featured" style="padding: 2px 10px 2px 10px;">
                        <h2 class="title-2" style="font-size: 12pt;">Personal Details</h2>
                        <div class="row" style="padding-top: 0; padding-bottom: 0;">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Access ID:</label>
                                 <input type="text" name="user_id" placeholder="Enter Access ID" required>
                              </div>
                              <div class="form-group">
                                 <label>Employee Name:</label>
                                 <input type="text" name="employee_name" placeholder="Enter Employee Name" required>
                              </div>
                              <div class="form-group">
                                 <label>Birthdate:</label>
                                 <input type="date" name="birthdate" placeholder="Enter Birthdate" required>
                              </div>
                              <div class="form-group">
                                 <label>Contact No.:</label>
                                 <input type="text" name="contact_no" placeholder="Contact No." required>
                              </div>
                              <div class="form-group">
                                 <label>Address:</label>
                                 <textarea name="address" rows="2" placeholder="Enter Address" required></textarea>
                              </div>
                              
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Nickname:</label>
                                 <input type="text" name="nickname" placeholder="Enter Nickname" required>
                              </div>
                              <div class="form-group">
                                 <label>Gender:</label>
                                 <select name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value='Male'>Male</option>
                                    <option value='Female'>Female</option>
                                 </select>
                              </div>
                              <div class="form-group">
                                 <label>Civil Status:</label>
                                 <select name="civil_status" required>
                                    <option value="">Select Civil Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                 </select>
                              </div>
                              <div class="form-group">
                                 <label>Contact Person:</label>
                                 <input type="text" name="contact_person" placeholder="Contact Person" required>
                              </div>
                              <div class="form-group">
                                 <label>Contact Person No.:</label>
                                 <input type="text" name="contact_person_no" placeholder="Contact No." required>
                              </div>
                              <div class="form-group">
                                 <label>TIN No.:</label>
                                 <input type="text" name="tin_no" placeholder="Enter TIN No.">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <div style="text-align: center;">
                                    <div>
                                       <img src="{{ asset('storage/img/user.png') }}" width="110" height="110" class="imgPreview">
                                    </div>
                                    <div class="fileUpload btn btn-primary upload-btn">
                                       <span>Choose File..</span>
                                       <input type="file" name="empImage" class="upload" />
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="form-group">
                                 <label>SSS No.:</label>
                                 <input type="text" name="sss_no" placeholder="Enter SSS No.">
                              </div>
                              <div class="form-group">
                                 <label>PhilHealth No.:</label>
                                 <input type="text" name="philhealth_no" placeholder="Enter PhilHealth No.">
                              </div>
                              <div class="form-group">
                                 <label>PAGIBIG No.:</label>
                                 <input type="text" name="pagibig_no" placeholder="Enter PAGIBIG No.">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="inner-box featured" style="padding: 2px 10px 2px 10px;">
                        <h2 class="title-2" style="font-size: 12pt;">Employment Details</h2>
                        <div class="row" style="padding-top: 0; padding-bottom: 0;">
                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Employee ID:</label>
                                 <input type="text" name="employee_id" placeholder="Enter Employee ID" required>
                              </div>
                              <div class="form-group">
                                 <label>Department:</label>
                                 @if(isset($departments))
                                 <select name="department" required>
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
                                 <select name="designation" class="designation" required>
                                    <option value="">Select Designation</option>
                                    @forelse($designations as $designation)
                                    <option value="{{ $designation->des_id }}">{{ $designation->designation }}</option>
                                    @empty
                                    <option>No Designation(s) Found.</option>
                                    @endforelse
                                 </select>
                                 @endif
                              </div>
                              
                              <input type="hidden" name="designation_name" class="designation_name">
                              
                              <div class="form-group">
                                 <label>Employment Status:</label>
                                 <select name="employment_status" required>
                                    <option value="">Select Employment Status</option>
                                    <option value="Regular">Regular</option>
                                    <option value="Contractual">Contractual</option>
                                    <option value="Probationary">Probationary</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Shift:</label>
                                 <select name="shift" required>
                                    <option value="">Select Shift</option>
                                    @forelse($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->shift_group_name }}</option>
                                    @empty
                                    <option>No Shift(s) Found.</option>
                                    @endforelse
                                 </select>
                              </div>
                               <div class="form-group">
                                 <label>Branch:</label>
                                 <select name="branch" required>
                                    <option value="">Select Branch</option>
                                       @forelse($branch as $loc)
                                       <option value="{{ $loc->branch_id }}">{{ $loc->branch_name }}</option>
                                       @empty
                                       <option>No Branch Found.</option>
                                       @endforelse
                                 </select>
                              </div>
                              <div class="form-group">
                                 <label>Date Joined:</label>
                                 <input type="date" name="date_joined" placeholder="Enter Date Joined" required>
                              </div>
                              
                              <div class="form-group">
                                 <label>User Group:</label>
                                 <select name="user_group" required>
                                    <option value="">Select User Group</option>
                                    <option value="Employee">Employee</option>
                                    <option value="Manager">Manager</option>
                                    <option value="HR Personnel">HR Personnel</option>
                                    <option value="Editor">Editor</option>
                                 </select>
                              </div>
                              
                           </div>
                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Telephone (Local No.):</label>
                                 <input type="number" name="telephone" placeholder="Enter Local No.">
                              </div>
                              <div class="form-group">
                                 <label>Local Email:</label>
                                 <input type="text" name="email" placeholder="Enter Email" required>
                              </div>
                              <div class="form-group">
                                 <label>Password:</label>
                                 <input type="password" name="password" placeholder="Enter Password" required>
                              </div>
                              <div class="form-group">
                                 <label>ID Security Key:</label>
                                 <input type="password" name="id_key" placeholder="Enter ID Security Key">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  
                  
                  
               </div>
            </div>
            <div class="modal-footer" style="margin-top: -30px;">
               <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
