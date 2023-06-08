<!-- The Modal -->
<div class="modal fade" id="edit-admin-{{ $admin->id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Admin</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/admin/update" method="POST">
               @csrf
               <input type="hidden" name="id" value="{{ $admin->id }}">
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Access ID:</label>
                     <input type="text" class="form-control" name="access_id" value="{{ $admin->access_id }}" placeholder="Enter Access ID" required>
                  </div>
               </div>
                <div class="col-sm-12">
                  <div class="form-group">
                     <label>Name:</label>
                     <input type="text" class="form-control" name="username" value="{{ $admin->name }}" placeholder="Enter Name" required>
                  </div>
               </div>
                <div class="col-sm-12">
                  <div class="form-group">
                     <label>Email:</label>
                     <input type="email" class="form-control" name="email" value="{{ $admin->email }}" placeholder="Enter Email" required>
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