<!-- The Modal -->
<div class="modal fade" id="changePassword">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h5 class="modal-title">Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form id="changePasswordForm" method="post" action="{{route('client.updatePassword')}}">
                  @csrf
                  Current Password: <input class="form-control" type="Password" name="current_pass" id="current_pass" required autofocus><br>
                  New Password: <input class="form-control" type="Password" name="new_pass" id="new_pass" required><br>
            </div>
                  
               <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="savePass"><i class="fa fa-check"></i> Submit</button>
                    <button type="button" class="btn btn-danger" id="closeAddQuestion" data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                </div>
               </form>

         </div>
         <!-- Modal footer -->
      </div>
   </div>
</div>
