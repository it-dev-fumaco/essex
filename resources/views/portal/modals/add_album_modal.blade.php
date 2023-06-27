<!-- The Modal -->
<div class="modal fade" id="addAlbumModal">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">New Album</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form method="POST" action="/addAlbum">
               @csrf
               <div class="col-sm-12">
                  <div class="form-group">
                     <label class="fw-bold">Category:</label>
                     <select class="form-control" name="activity_type">
                        @foreach($activity_types as $type)
                        <option value="{{ $type->id }}">{{ $type->activity_name }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="form-group mt-3">
                     <label class="fw-bold">Album Name:</label>
                     <input type="text" class="form-control" name="album_name" placeholder="Album Name" required>
                  </div>
                  <div class="form-group mt-3">
                     <label class="fw-bold">Description:</label>
                     <textarea class="form-control" rows="5" placeholder="Description" name="description" style="height: 100px;"></textarea>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Submit</button>
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
         </form>
      </div>
   </div>
</div>
