<div class="modal fade" id="reset-admin-password-{{ $admin->id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Reset Admin Password</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
              <form action="/admin/admin/reset_password" method="POST">
               @csrf
               <input type="hidden" name="id" value="{{ $admin->id }}">
               <input type="hidden" name="username" value="{{ $admin->name }}">
               <div class="col-sm-12">
                 Reset password for admin <b>{{ $admin->name }}</b> ?
               </div>               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> Reset</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
         </form>
      </div>
   </div>
</div>