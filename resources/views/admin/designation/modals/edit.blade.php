<!-- The Modal -->
<div class="modal fade" id="edit-designation-{{ $designation->des_id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Designation</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form method="POST" action="/admin/designation/update">
               @csrf
               <input type="hidden" name="id" value="{{ $designation->des_id }}">
                <div class="col-sm-12">
                  <div class="form-group">
                     <label>Department:</label>
                     @if(isset($departments))
                     <select class="form-control" name="department" required>
                        <option value="">Select Department</option>
                        @forelse($departments as $department)
                        <option value="{{ $department->department_id }}" {{ $department->department_id === $designation->department_id ? "selected" : "" }}>{{ $department->department }}</option>
                        @empty
                        <option>No Department(s) Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Designation Name:</label>
                     <input type="text" class="form-control" name="designation" placeholder="Enter Designation" value="{{ $designation->designation }}" required>
                  </div>
                  <div class="form-group">
                     <label>Remarks:</label>
                     <textarea cols="10" rows="4" name="remarks" class="form-control">{{ $designation->remarks }}</textarea>
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