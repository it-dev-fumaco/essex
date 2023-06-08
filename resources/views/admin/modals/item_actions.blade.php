<!-- The Modal -->
<div class="modal fade" id="editItem{{ $item->item_id }}">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Item</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/items/{{ $item->item_id }}" method="POST">
               @csrf
               @method('PUT')
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Item Name:</label>
                     <input type="text" class="form-control" name="item_name" id="item_name" placeholder="Enter Item Name" value="{{ $item->item_name }}" required>
                  </div>
                  <div class="form-group">
                     <label>Description:</label>
                     <input type="text" class="form-control" name="description" id="description" value="{{ $item->description }}" placeholder="Enter Description">
                  </div>
                  <div class="form-group">
                     <label>Brand:</label>
                     <input type="text" class="form-control" name="brand" id="brand" value="{{ $item->brand }}" placeholder="Enter Brand">
                  </div>
                  <div class="form-group">
                     <label>Model:</label>
                     <input type="text" class="form-control" name="model" id="model" value="{{ $item->model }}" placeholder="Enter Model">
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Item Type:</label>
                     <select class="form-control" name="item_type" id="item_type">
                        <option value="">Select Item Type</option>
                        <option value="Mobile Phone" {{ $item->item_type === "Mobile Phone" ? "selected" : "" }}>Mobile Phone</option>
                        <option value="Vehicle" {{ $item->item_type === "Vehicle" ? "selected" : "" }}>Vehicle</option>
                        <option value="Laptop" {{ $item->item_type === "Laptop" ? "selected" : "" }}>Laptop</option>
                        <option value="Tablet" {{ $item->item_type === "Tablet" ? "selected" : "" }}>Tablet</option>
                        <option value="Others" {{ $item->item_type === "Others" ? "selected" : "" }}>Others</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Serial No:</label>
                     <input type="text" class="form-control" name="serial_no" id="serial_no" value="{{ $item->serial_no }}" placeholder="Enter Serial No.">
                  </div>
                  <div class="form-group">
                     <label>MAC Address:</label>
                     <input type="text" class="form-control" name="mac_address" id="mac_address" value="{{ $item->mac_address }}" placeholder="Enter MAC Address">
                  </div>
                  <div class="form-group">
                     <label>Other References:</label>
                     <input type="text" class="form-control" name="references" id="references" value="{{ $item->references }}" placeholder="Enter References">
                  </div>
                  
                  <div class="form-group">
                     <label>Remarks:</label>
                     <input type="text" class="form-control" name="remarks" id="remarks" value="{{ $item->remarks }}" placeholder="Enter Remarks">
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
         </form>
      </div>
   </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="deleteItem{{ $item->item_id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Item</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
              <form action="/admin/items/delete/{{ $item->item_id }}" method="POST">
               @csrf
               @method("DELETE")
               <div class="col-sm-12">
                 Delete Item <b>{{ $item->item_name }}</b> ?
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
