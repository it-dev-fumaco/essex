<!-- The Modal -->
<div class="modal fade" id="updateDetails" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Update Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>

         <div class="modal-body">
            <div id="updateDetailsAlert" class="alert d-none" role="alert"></div>

            <div class="alert alert-info py-2" style="font-size: 10pt;">
               <b>Note:</b> After you save changes, HR will be notified of the updated information.
            </div>

            <form id="updateDetailsForm" autocomplete="off">
               @csrf

               <div class="mb-2">
                  <label class="form-label">Contact Info</label>
                  <input type="text" class="form-control" name="contact_no" value="{{ Auth::user()->contact_no }}" required>
               </div>

               <div class="mb-2">
                  <label class="form-label">Personal Email</label>
                  <input type="email" class="form-control" name="personal_email" value="{{ Auth::user()->personal_email }}" placeholder="name@example.com">
               </div>

               <div class="mb-2">
                  <label class="form-label">Home Address</label>
                  <input type="text" class="form-control" name="address" value="{{ Auth::user()->address }}" required>
               </div>

               <div class="row g-2">
                  <div class="col-6">
                     <label class="form-label">Barangay</label>
                     <input type="text" class="form-control" name="barangay" value="{{ Auth::user()->Barangay }}">
                  </div>
                  <div class="col-6">
                     <label class="form-label">City</label>
                     <input type="text" class="form-control" name="city" value="{{ Auth::user()->City }}">
                  </div>
               </div>

               <div class="mb-2 mt-2">
                  <label class="form-label">Emergency Contact Number</label>
                  <input type="text" class="form-control" name="contact_person_no" value="{{ Auth::user()->contact_person_no }}" required>
               </div>

               <div class="mb-2">
                  <label class="form-label">Contact Person in Case of Emergency</label>
                  <input type="text" class="form-control" name="contact_person" value="{{ Auth::user()->contact_person }}" required>
               </div>
            </form>
         </div>

         <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="saveDetails" disabled><i class="fa fa-check"></i> Save Changes</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
         </div>
      </div>
   </div>
</div>

