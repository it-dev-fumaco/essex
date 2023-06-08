<!-- The Modal -->
<div class="modal fade" id="edit-department-{{ $department->department_id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Department</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form method="POST" action="/admin/department/update">
               @csrf
               <input type="hidden" name="id" value="{{ $department->department_id }}">
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Department:</label>
                     <input type="text" class="form-control" name="department" placeholder="Enter Department" value="{{ $department->department }}" required>
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