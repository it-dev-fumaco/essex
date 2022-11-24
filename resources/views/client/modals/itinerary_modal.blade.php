<div class="modal fade" id="view-list-{{ $row->parent }}" style="text-align: left;">
   <div class="modal-dialog" style="width:720px;">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Itinerary Details</h4>
         </div>
         <div class="modal-body">
            <form action="#" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $row->parent }}">
               <div class="row" style="margin: 7px;padding-top:0;padding-bottom: 0;">
                  <div class="col-sm-6" style="float: left;">
                     <div class="form-group">
                        <b>
                        <input type="text" value="{{ $row->parent }}" id="updatename" class="updatename" name="updatename" style="width: 150px;border: 0px none;" placeholder="Name" readonly>
                        <input type="hidden" name="itinerary_id" id="itinerary_id" value="{{ $row->parent }}" class="itinerary_id" >
                        </b>
                     </div>
                  </div>
                  <div class="col-sm-6" style="float: right;">
                     <label style="width: 21%;">Status:</label>
                     @switch(strtolower($row->workflow_state))
                        @case('approved') 
                           <span class="label label-primary status" style="font-size: 15px;" style="font-size: 30px;">APPROVED</span></h3>
                           @break
                        @case('cancelled') 
                           <span class="label label-danger status" style="font-size: 15px;">CANCELLED</span></h3>
                           @break
                        @case('disapproved')
                           <span class="label label-danger status" style="font-size: 15px;">DISAPPROVED</span></h3>
                           @break
                        @case('deferred')
                           <span class="label label-danger status" style="font-size: 15px;">DISAPPROVED</span></h3>
                           @break
                        @default
                           <span class="label label-warning status" style="font-size: 15px;">FOR APPROVAL</span>
                     @endswitch
                  </div>

                  <div class="col-sm-12"><h5>Project Details</h5>
                  <hr style="margin-top: -1px;">
                  
          
                     
                     <div class="col-sm-6" style="line-height: 15px; margin-top: -5px;">
                        <div>
                           <label>Project:</label>
                           <input type="text" value="{{ $row->project }}" id="updatename" class="updatename" name="updatename" style="border: 0px none;font-weight: 50%;" placeholder="Name" readonly>
                        </div>
                     </div>
                        <div class="col-sm-6" style="line-height: 15px;">
                           <label>Itinerary Date:</label>
                           <input type="text" id="updateitem_category" class="updateitem_category" name="updateitem_category" style="border: 0px none;font-weight: 50%;" value="{{ $row->itinerary_date }}" placeholder="Itinerary Date" readonly>
                        </div>
                     
                     
                     
                     <div class="col-sm-6" style="line-height: 15px;">
                        <label>Destination:</label>
                        <input type="text" value="{{ $row->destination }}" id="updatepurchase_date" class="updatepurchase_date" name="updatepurchase_date" style="border: 0px none;font-weight: 50%;" placeholder="Purchase Date" readonly>
                     </div>
                     <div class="col-sm-6" style="line-height: 15px;">
                        <label>Time:</label>
                        <input type="text" value="{{ $row->time }}" id="updatepurchase_order" class="updatepurchase_order" name="updatepurchase_order" style="border: 0px none;font-weight: 50%;" placeholder="Purchase Order No" readonly>
                     </div>
                     <div class="col-sm-12" style="line-height: 15px;">
                        <label>Purpose:</label>
                        <textarea id="updatedescc" class="updatedescc" name="updatedescc" style="width: 100%;border: 0px none;" placeholder="Description" readonly>{{ strip_tags( $row->purpose ) }}
                        </textarea>

                     </div>

                  <div style="padding-top: 16%;"><h5>Companion(s)</h5>
                  <hr style="margin-top: -1px;">
                  
          
                     
                     <div class="col-sm-6 companiondiv" style="line-height: 15px; margin-top: -5px;" id="companiondiv">
                     </div>
                    
                  </div>


                    


  
                  <div style="font-size: 8pt;float: right;padding-right: 5%; padding-top: 10px;">
               <i>Last modified: <b><label class="modified_date" style="font-size: 8pt;">{{ $row->modified }}</label> </b> -<label class="modified_name" style="font-size: 8pt;">{{ $row->modified_by }}</label> </i>
               </div>
               </div>   
            </div>

          

            <div class="modal-footer">
               
               <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

