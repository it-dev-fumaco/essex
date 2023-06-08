<div class="modal fade" id="add-applicant-modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Applicant</h4>
         </div>
         <div class="modal-body">
            <form action="/admin/applicant/create" method="POST">
            @csrf
               <div class="row" style="margin: 7px;">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Applicant Name:</label>
                        <input type="text" class="form-control" name="employee_name" placeholder="Enter Applicant Name" required>
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
                        <label>Contact No.:</label>
                        <input type="text" class="form-control" name="contact_no" placeholder="Contact No." required>
                     </div>
                     <div class="form-group">
                        <label>Address:</label>
                        <textarea class="form-control" name="address" placeholder="Enter Address" required></textarea>
                     </div>
                  </div>
                  <div class="col-sm-6">
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
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
