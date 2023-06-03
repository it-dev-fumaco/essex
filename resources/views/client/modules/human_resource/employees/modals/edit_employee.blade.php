<div class="modal fade" id="edit-employee">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Employee</h4>
         </div>
         <div class="modal-body">
            <form action="/employee/update" method="POST" autocomplete="off" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="id" value="{{ $employee_profile->id }}">
               <div class="row" style="padding-top: 0;">
                  <div class="col-md-12">
                     <div class="inner-box featured" style="padding: 2px 10px 2px 10px;">
                        <h2 class="title-2" style="font-size: 12pt;">Personal Details</h2>
                        <div class="row" style="padding-top: 0; padding-bottom: 0;">
                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Access ID:</label>
                                 <input type="text" name="user_id" value="{{ $employee_profile->user_id }}" placeholder="Enter Access ID">
                              </div>
                              <div class="form-group">
                                 <label>Employee Name:</label>
                                 <input type="text" name="employee_name" value="{{ $employee_profile->employee_name }}" placeholder="Enter Employee Name">
                              </div>
                              <div class="form-group">
                                 <label>Birthdate:</label>
                                 <input type="date" name="birthdate" value="{{ $employee_profile->birth_date }}" placeholder="Enter Birthdate">
                              </div>
                              <div class="form-group">
                                 <label>Contact No.:</label>
                                 <input type="text" name="contact_no" value="{{ $employee_profile->contact_no }}" placeholder="Contact No.">
                              </div>
                              <div class="form-group">
                                 <label>Address:</label>
                                 <textarea name="address" rows="2" placeholder="Enter Address">{{ $employee_profile->address }}</textarea>
                              </div>
                             
                           </div>
                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Nickname:</label>
                                 <input type="text" name="nickname" value="{{ $employee_profile->nick_name }}" placeholder="Enter Nickname">
                              </div>
                              <div class="form-group">
                                 <label>Gender:</label>
                                 <select name="gender" class="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value='Male' {{ $employee_profile->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value='Female'  {{ $employee_profile->gender == 'Female' ? 'selected' :    '' }}>Female</option>
                                  </select>
                               </div>
                               <div class="form-group">
                                 <label>Civil Status:</label>
                                 <select name="civil_status">
                                    <option value="" disabled>Select Civil Status</option>
                                    <option value="Single" {{ $employee_profile->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ $employee_profile->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ $employee_profile->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                 </select>
                              </div>
                              <div class="form-group">
                                 <label>Contact Person:</label>
                                 <input type="text" name="contact_person" placeholder="Contact Person" value="{{ $employee_profile->contact_person }}" required>
                              </div>
                              <div class="form-group">
                                 <label>Contact Person No.:</label>
                                 <input type="text" name="contact_person_no" placeholder="Contact No." value="{{ $employee_profile->contact_person_no }}" required>
                              </div>
                              <div class="form-group">
                                 <label>TIN No.:</label>
                                 <input type="text" name="tin_no" value="{{ $employee_profile->tin_no }}" placeholder="Enter TIN No.">
                              </div>
                           </div>
                           <div class="col-sm-4">
                              <div class="form-group">
                                 <input type="hidden" name="user_image" value="{{$employee_profile->image }}">
                                 <div style="text-align: center;">
                                    @php
                                    $img = $employee_profile->image ? $employee_profile->image : '/storage/img/user.png'
                                    @endphp
                                    <div>
                                       <img src="{{ asset($img) }}" width="110" height="110" class="imgPreview">
                                    </div>
                                    <div class="fileUpload btn btn-primary upload-btn">
                                       <span>Choose File..</span>
                                       <input type="file" name="empImage" class="upload" />
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="form-group">
                                 <label>SSS No.:</label>
                                 <input type="text" name="sss_no" value="{{ $employee_profile->sss_no }}" placeholder="Enter SSS No.">
                              </div>
                              <div class="form-group">
                                 <label>PhilHealth No.:</label>
                                 <input type="text" name="philhealth_no" placeholder="Enter PhilHealth No." value="{{ $employee_profile->philhealth_no }}">
                              </div>
                              <div class="form-group">
                                 <label>PAGIBIG No.:</label>
                                 <input type="text" name="pagibig_no" placeholder="Enter PAGIBIG No." value="{{ $employee_profile->pagibig_no }}">
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
                           </div>

                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>ID Security Key:</label>
                                 <input type="password" name="id_key" placeholder="Enter ID Security Key">
                              </div>
                           </div>

                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Date Joined:</label>
                                 <input type="date" name="date_joined" placeholder="Enter Date Joined" required>
                              </div>
                           </div>

                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Company:</label>
                                 <select name="company" required>
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                       <option value="{{ $company }}">{{ $company }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>

                           <div class="col-sm-4">
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
                           </div>

                           <div class="col-sm-4">
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
                           </div>

                           <div class="col-sm-4">
                              <input type="hidden" name="designation_name" class="designation_name">
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
                           </div>

                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Reporting to:</label>
                                 <select name="reporting_to" required>
                                    <option value="">Select an Employee</option>
                                    @foreach ($regular_employees as $emp)
                                       <option value="{{ $emp->id }}">{{ $emp->employee_name }}</option>
                                    @endforeach
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
                           </div>

                           <div class="col-sm-4">
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
                                 <label>Telephone (Local No.):</label>
                                 <input type="number" name="telephone" placeholder="Enter Local No.">
                              </div>
                           </div>

                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Local Email:</label>
                                 <input type="text" name="email" placeholder="Enter Email" required>
                              </div>
                           </div>

                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Password:</label>
                                 <input type="password" name="password" placeholder="Enter Password" required>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer" style="margin-top: -30px;">
               <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
