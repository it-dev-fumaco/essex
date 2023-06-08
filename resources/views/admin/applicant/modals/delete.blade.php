<div class="modal fade" id="delete-applicant-{{ $applicant->id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Applicant</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
              <form action="/admin/applicant/delete" method="POST">
               @csrf
               <input type="hidden" name="id" value="{{ $applicant->id }}">
               <input type="hidden" name="employee_name" value="{{ $applicant->employee_name }}">
               <div class="col-sm-12">
                 Delete Applicant <b>{{ $applicant->employee_name }}</b> ?
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