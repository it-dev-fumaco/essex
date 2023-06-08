<!-- The Modal -->
<div class="modal fade" id="editShift{{ $shift->shift_id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Shift</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/shifts/{{ $shift->shift_id }}" method="POST">
               @csrf
               @method('PUT')
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Schedule Date:</label>
                     <input type="date" class="form-control" name="shiftSchedule" id="shiftSchedule" value="{{ $shift->shift_schedule }}" required>
                  </div>
                  <div class="form-group">
                     <label>Time In:</label>
                     <input type="text" class="form-control" name="timeIn" id="timeIn" value="{{ $shift->time_in }}" placeholder="Enter Time In" required>
                  </div>
                  <div class="form-group">
                     <label>Time Out:</label>
                     <input type="text" class="form-control" name="timeOut" id="timeOut" value="{{ $shift->time_out }}" placeholder="Enter Time Out" required>
                  </div>
                 
               </div>
               <div class="col-sm-6">
                   <div class="form-group">
                     <label>Breaktime (hrs):</label>
                     <input type="number" class="form-control" name="breaktime" id="breaktime"  value="{{ $shift->breaktime_by_hour }}" required>
                  </div>
                  <div class="form-group">
                     <label>Grace Period (mins):</label>
                     <input type="number" class="form-control" name="gracePeriod" id="gracePeriod" value="{{ $shift->grace_period_in_mins }}" required>
                  </div>
                  <div class="form-group">
                     <label>Remarks:</label>
                     <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Enter Remarks"  value="{{ $shift->remarks }}" required>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
         </form>
      </div>
   </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="deleteShift{{ $shift->shift_id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Shift</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
              <form action="/admin/shifts/delete/{{ $shift->shift_id }}" method="POST">
               @csrf
               @method("DELETE")
               <div class="col-sm-12">
                 Delete Shift <b>{{ $shift->shift_schedule }}</b> ?
               </div>               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> Delete</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
         </form>
      </div>
   </div>
</div>
