<div class="modal fade" id="update-personal-details">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Update Personal Details</h4>
         </div>
         <div class="modal-body">
            @if ($errors->any())
               <div class="alert alert-danger">
                  <ul style="margin-bottom: 0;">
                     @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               </div>
            @endif

            <div class="alert alert-info" style="font-size: 10pt;">
               <b>Note:</b> After you save changes, HR will be notified of the updated information.
            </div>

            <form action="{{ route('client.profile.update_personal_details') }}" method="POST" autocomplete="off">
               @csrf

               <div class="form-group">
                  <label>Contact Info</label>
                  <input type="text" class="form-control" name="contact_no" value="{{ old('contact_no', $employee_profile->contact_no) }}" required>
               </div>

               <div class="form-group">
                  <label>Personal Email</label>
                  <input type="email" class="form-control" name="personal_email" value="{{ old('personal_email', $employee_profile->personal_email ?? '') }}" placeholder="name@example.com">
               </div>

               <div class="form-group">
                  <label>Home Address</label>
                  <textarea class="form-control" name="address" rows="3" required>{{ old('address', $employee_profile->address) }}</textarea>
               </div>

               <div class="form-group">
                  <label>Emergency Contact Number</label>
                  <input type="text" class="form-control" name="contact_person_no" value="{{ old('contact_person_no', $employee_profile->contact_person_no ?? '') }}" required>
               </div>

               <div class="form-group">
                  <label>Contact Person in Case of Emergency</label>
                  <input type="text" class="form-control" name="contact_person" value="{{ old('contact_person', $employee_profile->contact_person ?? '') }}" required>
               </div>

               <div class="modal-footer" style="padding-right: 0;">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save Changes</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

