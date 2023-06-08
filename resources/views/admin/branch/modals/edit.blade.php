<!-- The Modal -->
<div class="modal fade" id="edit-branch-{{ $branch->branch_id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Branch</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/branch/update" method="POST">
               @csrf
               <input type="hidden" name="id" value="{{ $branch->branch_id }}">
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Branch Name:</label>
                     <input type="text" class="form-control" name="branch_name" value="{{ $branch->branch_name }}" placeholder="Enter Branch Name" required>
                  </div>
                  <div class="form-group">
                     <label>Address:</label>
                     <input type="text" class="form-control" name="address" value="{{ $branch->address }}" placeholder="Enter Address" required>
                  </div>
               </div>
               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div></form>
      </div>
   </div>
</div>
