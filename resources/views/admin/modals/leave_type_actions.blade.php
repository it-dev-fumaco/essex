<!-- The Modal -->
<div class="modal fade" id="editLeaveType{{ $leave_type->leave_type_id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Leave Type</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/leave_types/{{ $leave_type->leave_type_id }}" method="POST">
               @csrf
               @method('PUT')
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Leave Type:</label>
                     <input type="text" class="form-control" name="leave_type" id="leave_type" placeholder="Enter Leave Type" value="{{ $leave_type->leave_type }}" required>
                  </div>
                  <div class="form-group">
                     <label>Color Legend:</label>
                     <input type="color" class="form-control" name="color_legend" id="color_legend" placeholder="Enter Color Legend" value="{{ $leave_type->color_legend }}" required>
                  </div>
                  <div class="form-group">
                     <label>Description:</label>
                     <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description" value="{{ $leave_type->description }}" required>
                  </div>
                  <div class="form-group">
                     <label class="checkbox-inline">
                        <input type="checkbox" name="applied_to_all" {{ $leave_type->applied_to_all === 1 ? 'checked="checked' : ''  }}"> Apply to all Employees?
                     </label>
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
<div class="modal fade" id="deleteLeaveType{{ $leave_type->leave_type_id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Leave Type</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
              <form action="/admin/leave_types/delete/{{ $leave_type->leave_type_id }}" method="POST">
               @csrf
               @method("DELETE")
               <div class="col-sm-12">
                 Delete Leave Type <b>{{ $leave_type->leave_type }}</b> ?
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
