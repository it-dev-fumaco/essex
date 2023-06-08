<div class="modal fade" id="edit-applicant-{{ $applicant->id }}">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Applicant</h4>
         </div>
         <div class="modal-body">
            <form action="/admin/applicant/update" method="POST">
               @csrf
               <input type="hidden" name="id" value="{{ $applicant->id }}">
               <div class="row" style="margin: 7px;">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Employee Name:</label>
                        <input type="text" class="form-control" name="employee_name" value="{{ $applicant->employee_name }}" placeholder="Enter Employee Name">
                     </div>
                     <div class="form-group">
                        <label>Nickname:</label>
                        <input type="text" class="form-control" name="nickname" value="{{ $applicant->nick_name }}" placeholder="Enter Nickname">
                     </div>
                     <div class="form-group">
                        <label>Birthdate:</label>
                        <input type="date" class="form-control" name="birthdate" value="{{ $applicant->birth_date }}" placeholder="Enter Birthdate">
                     </div>
                     <div class="form-group">
                        <label>Contact No.:</label>
                        <input type="text" class="form-control" name="contact_no" value="{{ $applicant->contact_no }}" placeholder="Contact No.">
                     </div>
                     <div class="form-group">
                        <label>Address:</label>
                        <textarea class="form-control" name="address" placeholder="Enter Address">{{ $applicant->address }}</textarea>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Civil Status:</label>
                        <select class="form-control" name="civil_status">
                           <option value="" disabled>Select Civil Status</option>
                           <option value="Single" {{ $applicant->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                           <option value="Married" {{ $applicant->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                           <option value="Widowed" {{ $applicant->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                     </div>
                     <div class="form-group">
                        <label>SSS No.:</label>
                        <input type="text" class="form-control" name="sss_no" value="{{ $applicant->sss_no }}" placeholder="Enter SSS No.">
                     </div>
                     <div class="form-group">
                        <label>TIN No.:</label>
                        <input type="text" class="form-control" name="tin_no" value="{{ $applicant->tin_no }}" placeholder="Enter TIN No.">
                     </div>
                     <div class="form-group">
                        <label>Position applied for (1st choice):</label>
                        <input type="text" class="form-control" name="position_applied_for1" value="{{ $applicant->position_applied_for1 }}" placeholder="Enter Position applied for (1st choice)">
                     </div>
                     <div class="form-group">
                        <label>Position applied for (2nd choice):</label>
                        <input type="text" class="form-control" name="position_applied_for2" value="{{ $applicant->position_applied_for2 }}" placeholder="Enter Position applied for (2nd choice)">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
