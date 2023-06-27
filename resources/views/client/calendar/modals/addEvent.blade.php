<div class="modal fade" id="addquestion">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">New Event</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form action="/addEvent" method="POST">
            @csrf
               <div class="row" style="margin: 7px;">
                  <div class="col-sm-12">
                     <div class="form-group mt-3">
                        <label class="fw-bold">Description:</label>
                        <input type="text" class="form-control" name="desc" placeholder="Enter Description" required>
                     </div>
                     <div class="form-group mt-3">
                        <label class="fw-bold">Date:</label>
                        <input type="Date" class="form-control" name="date"  required>
                     </div>
                     <div class="form-group mt-3">
                        <label class="fw-bold">Category:</label>
                        <select id="category" name="category" class="form-control">
                           <option value="">--Select Month--</option>
                           <option value="Regular Holiday">Regular Holiday</option>
                           <option value="Special Holiday">Special Holiday</option>
                           <option value="Company Activity">Company Activity</option>
                        </select>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Submit</button>
               <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
