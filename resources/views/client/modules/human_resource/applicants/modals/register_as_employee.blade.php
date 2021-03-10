<div class="modal fade" id="add-employee-modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Register as New Employee</h4>
         </div>
         <div class="modal-body">
            <form action="/hireApplicant/{{ $applicant->id }}" method="POST" autocomplete="off" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="id" value="{{ $applicant->id }}">
               <div class="row" style="padding-top: 0;">
                  <div class="col-md-12">
                     <div class="inner-box featured" style="padding: 2px 10px 2px 10px;">
                        <h2 class="title-2" style="font-size: 12pt;">Personal Details</h2>
                        <div class="row" style="padding-top: 0; padding-bottom: 0;">
                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Access ID:</label>
                                 <input type="text" name="user_id" value="{{ $applicant->user_id }}" placeholder="Enter Access ID">
                              </div>
                              <div class="form-group">
                                 <label>Employee Name:</label>
                                 <input type="text" name="employee_name" value="{{ $applicant->employee_name }}" placeholder="Enter Employee Name">
                              </div>
                              
                              <div class="form-group">
                                 <label>Birthdate:</label>
                                 <input type="date" name="birthdate" value="{{ $applicant->birth_date }}" placeholder="Enter Birthdate">
                              </div>
                              <div class="form-group">
                                 <label>Contact No.:</label>
                                 <input type="text" name="contact_no" value="{{ $applicant->contact_no }}" placeholder="Contact No.">
                              </div>
                              <div class="form-group">
                                 <label>Address:</label>
                                 <textarea name="address" rows="3" placeholder="Enter Address">{{ $applicant->address }}</textarea>
                              </div>
                              
                           </div>
                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Nickname:</label>
                                 <input type="text" name="nickname" value="{{ $applicant->nick_name }}" placeholder="Enter Nickname">
                              </div>
                              <div class="form-group">
                                 <label>Gender:</label>
                                 <select name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value='Male' {{ $applicant->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value='Female' {{ $applicant->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                 </select>
                              </div>
                              <div class="form-group">
                                 <label>Civil Status:</label>
                                 <select name="civil_status">
                                    <option value="">Select Civil Status</option>
                                    <option value="Single" {{ $applicant->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ $applicant->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ $applicant->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                 </select>
                              </div>
                              <div class="form-group">
                                 <label>Contact Person:</label>
                                 <input type="text" name="contact_person" placeholder="Contact Person" value="{{ $applicant->contact_person }}" required>
                              </div>
                              <div class="form-group">
                                 <label>Contact Person No.:</label>
                                 <input type="text" name="contact_person_no" placeholder="Contact No." value="{{ $applicant->contact_person_no }}" required>
                              </div>
                              <div class="form-group">
                                 <label>TIN No.:</label>
                                 <input type="text" name="tin_no" value="{{ $applicant->tin_no }}" placeholder="Enter TIN No.">
                              </div>
                           </div>
                           <div class="col-sm-4">
                              <div class="form-group">
                                 <input type="hidden" name="user_image" value="{{$applicant->image}}">
                                 <div style="text-align: center;">
                                    @php
                                    $img = $applicant->image ? $applicant->image : '/storage/img/user.png'
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
                                 <input type="text" name="sss_no" value="{{ $applicant->sss_no }}" placeholder="Enter SSS No.">
                              </div>
                              <div class="form-group">
                                 <label>PhilHealth No.:</label>
                                 <input type="text" name="philhealth_no" placeholder="Enter PhilHealth No." value="{{ $applicant->philhealth_no }}">
                              </div>
                              <div class="form-group">
                                 <label>PAGIBIG No.:</label>
                                 <input type="text" name="pagibig_no" placeholder="Enter PAGIBIG No." value="{{ $applicant->pagibig_no }}">
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
                                 <input type="text" name="employee_id" placeholder="Enter Employee ID" value="{{ $applicant->employee_id }}" required>
                              </div>
                              <div class="form-group">
                                 <label>Department:</label>
                                 @if(isset($department_list))
                                 <select name="department">
                                    <option value="">Select Department</option>
                                    @forelse($department_list as $department)
                                    <option value="{{ $department->department_id }}" {{ $applicant->department_id == $department->department_id ? 'selected' : '' }}>{{ $department->department }}</option>
                                    @empty
                                    <option>No Department(s) Found.</option>
                                    @endforelse
                                 </select>
                                 @endif
                              </div>
                              <div class="form-group">
                                 <label>Designation:</label>
                                 @if(isset($designation_list))
                                 <select name="designation" class="designation">
                                    <option value="">Select Designation</option>
                                    @forelse($designation_list as $designation)
                                    <option value="{{ $designation->des_id }}" {{ $applicant->designation_id == $designation->des_id ? 'selected' : '' }}>{{ $designation->designation }}</option>
                                    @empty
                                    <option>No Designation(s) Found.</option>
                                    @endforelse
                                 </select>
                                 @endif
                              </div>
                              
                              <input type="hidden" name="designation_name" class="designation_name" value="{{ $applicant->designation_name }}">
                              
                              <div class="form-group">
                                 <label>Employment Status:</label>
                                 <select name="employment_status">
                                    <option value="">Select Employment Status</option>
                                    <option value="Regular" {{ $applicant->employment_status == 'Regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="Contractual" {{ $applicant->employment_status == 'Contractual' ? 'selected' : '' }}>Contractual</option>
                                    <option value="Probationary" {{ $applicant->employment_status == 'Probationary' ? 'selected' : '' }}>Probationary</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Shift:</label>
                                 <select name="shift">
                                    <option value="">Select Shift</option>
                                    @forelse($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ $applicant->shift_group_id == $shift->id ? 'selected' : '' }}>{{ $shift->shift_group_name }}</option>
                                    @empty
                                    <option>No Shift(s) Found.</option>
                                    @endforelse
                                 </select>
                              </div>
                              <div class="form-group">
                                 <label>Branch:</label>
                                 <select name="branch">
                                    <option value="">Select Branch</option>
                                       @forelse($branch as $loc)
                                       <option value="{{ $loc->branch_id }}" {{ $applicant->branch == $loc->branch_id  }}>{{ $loc->branch_name }}</option>
                                       @empty
                                       <option>No Branch Found.</option>
                                       @endforelse
                                 </select>
                              </div>
                              
                              <div class="form-group">
                                 <label>Date Joined:</label>
                                 <input type="date" name="date_joined" value="{{ $applicant->date_joined }}" placeholder="Enter Date Joined">
                              </div>
                              <div class="form-group">
                                 <label>User Group:</label>
                                 <select name="user_group">
                                    <option value="">Select User Group</option>
                                    <option value="Employee" {{ $applicant->user_group == 'Employee' ? 'selected' : '' }}>Employee</option>
                                    <option value="Manager" {{ $applicant->user_group == 'Manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="HR Personnel" {{ $applicant->user_group == 'HR Personnel' ? 'selected' : '' }}>HR Personnel</option>
                                    <option value="Editor" {{ $applicant->user_group == 'Editor' ? 'selected' : '' }}>Editor</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-sm-4">
                              <div class="form-group">
                                 <label>Telephone (Local No.):</label>
                                 <input type="number" value="{{ $applicant->telephone }}" name="telephone" placeholder="Enter Local No.">
                              </div>
                              
                              <div class="form-group">
                                 <label>Email:</label>
                                 <input type="email" value="{{ $applicant->email }}" name="email" placeholder="Enter Email">
                              </div>
                              <div class="form-group">
                                 <label>Password:</label>
                                 <input type="password" name="password" placeholder="Enter Password">
                              </div>
                              <div class="form-group">
                                 <label>ID Security Key:</label>
                                 <input type="password" name="id_key" value="{{ $applicant->id_security_key }}" placeholder="Enter ID Security Key">
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
