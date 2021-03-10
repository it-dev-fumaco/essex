<!-- The Modal -->
<div class="modal fade" id="editAlbumModal">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Album</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form method="POST" action="/editAlbum">
               @csrf
               <input type="hidden" name="album_id" class="album_id">
               <div class="col-sm-12">
                  <div class="form-group">
                     <select class="form-control activity_type" name="activity_type">
                        @foreach($activity_types as $type)
                        <option value="{{ $type->id }}">{{ $type->activity_name }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Album Name:</label>
                     <input type="text" class="form-control album_name" name="album_name" placeholder="Album Name" " required>
                  </div>
                  <div class="form-group">
                     <label>Description:</label>
                     <textarea class="form-control description" placeholder="Description" name="description"></textarea>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
         </form>
      </div>
   </div>
</div>
