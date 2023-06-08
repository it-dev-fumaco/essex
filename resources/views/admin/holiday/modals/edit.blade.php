<!-- The Modal -->
<div class="modal fade" id="edit-holiday-{{ $holiday->id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Holiday</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/holiday/update" method="POST">
               @csrf
               <input type="hidden" name="id" value="{{ $holiday->id }}">
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Holiday Date:</label>
                     <input type="date" class="form-control" name="holiday_date" value="{{ $holiday->holiday_date }}" placeholder="Enter Holiday Date" required>
                  </div>
                  <div class="form-group">
                     <label>Description:</label>
                     <input type="text" class="form-control" name="description" value="{{ $holiday->description }}" placeholder="Enter Description" required>
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
