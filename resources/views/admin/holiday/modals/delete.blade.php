<!-- The Modal -->
<div class="modal fade" id="delete-holiday-{{ $holiday->id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Holiday</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
              <form action="/admin/holiday/delete" method="POST">
               @csrf
               <input type="hidden" name="id" value="{{ $holiday->id }}">
               <input type="hidden" name="holiday_date" value="{{ $holiday->holiday_date }}">
               <div class="col-sm-12">
                 Delete Holiday <b>{{ $holiday->holiday_date }}</b> ?
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
