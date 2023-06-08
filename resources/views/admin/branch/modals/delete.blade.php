<!-- The Modal -->
<div class="modal fade" id="delete-branch-{{ $branch->branch_id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Branch</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
              <form action="/admin/branch/delete" method="POST">
               @csrf
               <input type="hidden" name="id" value="{{ $branch->branch_id }}">
               <input type="hidden" name="branch_name" value="{{ $branch->branch_name }}">
               <div class="col-sm-12">
                 Delete Branch <b>{{ $branch->branch_name }}</b> ?
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
