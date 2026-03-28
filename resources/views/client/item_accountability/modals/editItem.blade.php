<div class="modal fade" id="edit-itemlist-{{ $row->id }}">
   <div class="modal-dialog" style="width:720px;">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Issued Item</h4>
         </div>
         <div class="modal-body">
            <form action="/editItem/{{ $row->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $row->id }}">
               <div class="row" style="margin: 7px;padding-top:0;padding-bottom: 0;">
                  <div class="col-sm-6" style="float: left;">
                     <div class="form-group">
                        <label style="width: 43%;">Name:</label>
                        <input type="text" value="{{ $employee_profile->employee_name }}" id="updatename" class="updatename" name="updatename" style="width: 150px;border: 0px none;" placeholder="Name" readonly>
                     </div>
                  </div>
                  <div class="col-sm-6" style="float: right;">
                     <label style="width: 21%;">Status:</label>
                     <select name="updatestat" id="updatestat" style="width: 100px;">
                        <option value="{{ $row->status }}"></option>
                       <option value="Active" selected="selected">Active</option>    
                       <option value="Returned">Returned</option> 
                     </select>

                  </div>


                  <div class="col-sm-12">
         
                    <div class="form-group">
                     <label style="width: 21%;">Category:</label>
                          
                           <input type="text" id="updatecategory" class="updatecategory" name="updatecategory" value="{{ $row->category}}" style="width: 150px;" placeholder="updatecategory" readonly>
                           <br>
                           <label style="width: 21%;">Item Code:</label>
   
                                
                           <input type="text" id="updateitemcode" class="updateitemcode" name="updateitemcode" style="width: 150px;" value="{{ $row->item_code}}" placeholder="updateitemcode" readonly>      
                     </div>
                  </div>
                  <h5>Item Details</h5>
                  <hr>
                  <div class="col-sm-12">
                     <div class="col-sm-4 imgUp"  style="margin-left: 0px;">
                     <!-- <div class="imagePrevieww"></div> -->
                     <!-- <img class="imagePreview" src="{{ asset('storage/'.$row->filepath) }}" alt="" style="border: 1px solid #ddd;border-radius: 4px;padding: 5px;width: 150px;"> -->
                     <div class="imagePreview" style="width: 80%;height: 100px;background-position: center center;background:url({{ Illuminate\Support\Facades\Storage::disk('upcloud')->url(ltrim((string) $row->filepath, '/')) }});background-color:#fff;background-size: cover;background-repeat:no-repeat;display: inline-block;box-shadow:0px -3px 6px 2px rgba(0,0,0,0.2);"></div>
                           <label class="btn btn-primary" style="width: 150px;height: 45px;">
                               Upload<input type="file" class="uploadFile img" id="selectedFiles" name="imageFile[]" value="{{ $row->filepath }}" style="width: 0px;height: 0px;overflow: hidden;">
                           </label>
                     </div>
                     <div class="col-sm-8" style=" padding-bottom: 10px;line-height: 15px;">
                        <div>
                           <label>Name:</label>
                           <input type="text" value="{{ $row->name}}" id="updatename" class="updatename" name="updatename" style="width: 50%;border: 0px none;font-weight: 50%;" placeholder="Name" readonly>
                        </div>
                        <div  style="line-height: 15px;">
                           <label>Item Classification:</label>
                           <input type="text" id="updateitem_category" class="updateitem_category" name="updateitem_category" style="width: 50%;border: 0px none;font-weight: 50%;" value="{{ $row->class }}" placeholder="Item Classification" readonly>
                        </div>
                     </div>
                     
                     <div class="col-sm-5" style="line-height: 10px;">
                        <label>Description:</label>
                        <textarea id="updatedescc" class="updatedescc" name="updatedescc" style="width: 80%;border: 0px none;resize: none;" placeholder="Description" readonly>{{ $row->desc}}
                        </textarea>

                     </div>
                     <div class="col-sm-3" style="line-height: 25px;">
                        <label style="width: 20%">Qty:</label>
                        <input type="text" value="{{ $row->qty}}" id="updateqty" name="updateqty" style="width: 80px;" placeholder="Qty">
                     </div>
                     <div class="col-sm-4" style="line-height: 15px;">
                        <label>Purchase Date:</label>
                        <input type="text" value="{{ $row->purchase_date}}" id="updatepurchase_date" class="updatepurchase_date" name="updatepurchase_date" style="width: 50%;border: 0px none;font-weight: 50%;" placeholder="Purchase Date" readonly>
                     </div>
                     <div class="col-sm-4" style="line-height: 15px;">
                        <label>Purchase Order No:</label>
                        <input type="text" value="{{ $row->purchase_orderno }}" id="updatepurchase_order" class="updatepurchase_order" name="updatepurchase_order" style="width: 50%;border: 0px none;font-weight: 50%;" placeholder="Purchase Order No" readonly>
                     </div>

                     <div class="col-sm-6" style="line-height: 25px;">
                        <label>Item Status:</label>
                        <input type="text" value="{{ $row->item_status}}" id="updatestatus" class="updatestatus" name="updatestatus" style="width: 50%;border: 0px none;font-weight: 50%;" placeholder="Status" readonly>
                     </div>
                    


                  </div>
                  <h5 style="margin-left: 0px;">Additional Details</h5>
                  <hr>
                  <div class="col-sm-6">
                     
                     <div style="line-height: 25px;">
                        <label style="width: 44%;line-height: 35px;">Brand:</label>
                        <input type="text" value="{{ $row->brand }}" id="updatebrand" name="updatebrand" style="width: 150px;" placeholder="Brand">
                     </div>
                     
                     <div style="line-height: 25px;">
                        <label style="width: 44%;line-height: 35px;">Model:</label>
                        <input type="text" value="{{ $row->model }}" id="updatemodel" name="updatemodel" style="width: 150px;" placeholder="Model">
                     </div>
                     <div class="@if($row->class != 'Car') hideme @endif" style="line-height: 25px;">
                        <label style="width: 44%;line-height: 35px;">Color:</label>
                        <input type="text" value="{{ $row->color }}" id="updatecolor" name="updatecolor" style="width: 150px;" placeholder="Color">
                     </div>

                     <div class="@if($row->class != 'Car') hideme @endif" style="line-height: 25px;">
                        <label style="width: 44%;line-height: 35px;">Engine no.:</label>
                        <input type="text" value="{{ $row->engine }}" id="updateengine" name="updateengine" style="width: 150px;" placeholder="Engine No.">
                     </div>
                     <div class="@if($row->class != 'Car') hideme @endif" style="line-height: 25px;">
                        <label style="width: 44%;line-height: 35px;">Chasis no.:</label>
                        <input type="text" value="{{ $row->chasis }}" id="updatechasis" name="updatechasis" style="width: 150px;" placeholder="Chasis No.">
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="@if($row->class == 'Car') hideme @endif" style="line-height: 25px;">
                        <label style="width: 44%;line-height: 35px;">Mc Address:</label>
                        <input type="text" class="@if($row->mcaddress == '') disableme @endif" value="{{ $row->mcaddress }}" id="updatemcaddress" name="updatemcaddress" style="width: 150px;" placeholder="Mc Address">
                     </div>
                     <div class="@if($row->class == 'Car') hideme @endif" style="line-height: 25px;">
                        <label style="width: 44%;line-height: 35px;">Serial No:</label>
                        <input type="text" value="{{ $row->serial_no }}" id="updateserial_no" name="updateserial_no" style="width: 150px;" placeholder="Serial No">
                     </div>
                      
                      <div class="@if($row->class != 'Car') hideme @endif" style="line-height: 25px;">
                        <label style="width: 44%;line-height: 35px;">Plate No:</label>
                        <input type="text" value="{{ $row->plate_no }}" id="updateplate" name="updateplate" style="width: 150px;" placeholder="Plate No.">
                     </div>
                     <div class="@if($row->class != 'Car') hideme @endif" style="line-height: 25px;">
                        <label style="width: 44%;line-height: 35px;">Driver License No.:</label>
                        <input type="text" value="{{ $row->driver_license }}" id="updatedln" name="updatedln" style="width: 150px;" placeholder="Driver License No.">
                     </div>
                     <div class="@if($row->class != 'Car') hideme @endif" style="line-height: 25px;">
                        <label style="width: 44%;line-height: 35px;">DL Type:</label>
                        <input type="text" value="{{ $row->dl_type }}" id="updatedl_type" name="updatedl_type" style="width: 150px;" placeholder="DL Type">
                     </div>
                     <div class="@if($row->class != 'Car') hideme @endif" style="line-height: 25px;">
                        <label style="width: 44%;line-height: 35px;">RC No.:</label>
                        <input type="text" value="{{ $row->rc_no }}" id="updaterc_no" name="updaterc_no" style="width: 150px;" placeholder="RC No.">
                     </div>
                  </div>
                  <input type="hidden" name="issued_to" value="{{ $row->issued_to }}"/>  
                  <input type="hidden" name="issued_by" value="{{ Auth::user()->user_id }}"/>
                  <input type="hidden" name="issued_by_name" value="{{ Auth::user()->employee_name }}"/>   

                  <div style="font-size: 8pt;float: right;padding-right: 5%; padding-top: 10px;">
                     <i>Last modified: <b><label class="modified_date" style="font-size: 8pt;">{{ $row->updated_at }}</label> </b> -<label class="modified_name" style="font-size: 8pt;">{{ $row->last_modified_by }}</label> </i>
               </div>

               </div>
            </div>

          

            <div class="modal-footer">
               <button type="submit" class="btn btn-primary" id="submit"><i class="fa fa-check"></i> Submit</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
