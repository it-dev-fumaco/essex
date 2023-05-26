<!-- The Modal -->
<div class="modal fade" id="add-holiday-modal">
   <div class="modal-dialog">
      <form action="/module/attendance/holiday/create" method="POST">
               @csrf
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Holiday</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row">
               
               <div class="col-sm-12" style="margin-top: -30px; margin-bottom: -25px;">
                  <div class="form-group" style="padding: 0 10px;">
                     <label>Holiday Date:</label>
                     <input type="text" name="holiday_date" placeholder="Enter Holiday Date" class="date" required>
                  </div>
                  <div class="form-group" style="padding: 0 10px;">
                     <label>Description:</label>
                     <textarea rows="2" style="display: block; width: 100%;" name="description" placeholder="Enter Description" required></textarea>
                     {{-- <input type="text" name="description" placeholder="Enter Description" required> --}}
                  </div>
                  <div class="form-group" style="padding: 0 10px;">
                        <label>Category:</label>
                         <select id="category" name="category">
                           <option value="">--Select Category--</option>
                           <option value="Regular Holiday">Regular Holiday</option>
                           <option value="Special Holiday">Special Holiday</option>
                           <option value="Company Activity">Company Activity</option>
                         </select>
                  </div>
               </div>
               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
      </div>
      </form>
   </div>
</div>
