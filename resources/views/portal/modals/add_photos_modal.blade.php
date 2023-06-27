<!-- The Modal -->
<div class="modal fade" id="addPhotosModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Photos</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <div class="row" style="margin: 7px;">
          <form action="/gallery/album/uploadImages" method="POST" enctype="multipart/form-data">
            @csrf
          <div class="fileUpload btn btn-primary btn-sm">
            <i class="fas fa-folder-open"></i>
            <span>Browse..</span>
            <input type="file" class="upload" multiple="" id="selectedFiles" name="imageFile[]" />
          </div>
          <input type="hidden" name="album_id" value="{{ $album->id }}" />
          <div id="gallery"></div>
        </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Upload</button>
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
      </div>
      </form>
    </div>
  </div>
</div>


<style type="text/css">
  .fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
}
.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}
.img_upload{
  padding-bottom: 20px;
}
.imageThumb{
  width: 340px;
  height: 210px;
  border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
}
.remove{
     position: absolute;
    top: 78%;
    left: 82%;
    transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    background-color: #d9534f;
    color: white;
    font-size: 16px;
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    border-radius: 2px;
    text-align: center;
}
/* Hide the browser's default checkbox */
.cb_container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}
/* Create a custom checkbox */
.checkmark {
    position: absolute;
    height: 32px;
    width: 32px;
    background-color: #eee;
    position: absolute;
    top: 78%;
    left: 68%;
    transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    color: white;
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    border-radius: 2px;
    text-align: center;
}
/* On mouse-over, add a grey background color */
.cb_container:hover input ~ .checkmark {
    background-color: #ccc;
}
/* When the checkbox is checked, add a blue background */
.cb_container input:checked ~ .checkmark {
    background-color: #2196F3;
}
/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}
/* Show the checkmark when checked */
.cb_container input:checked ~ .checkmark:after {
    display: block;
}
/* Style the checkmark/indicator */
.cb_container .checkmark:after {
    left: 11px;
    top: 5px;
    width: 9px;
    height: 18px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
</style>