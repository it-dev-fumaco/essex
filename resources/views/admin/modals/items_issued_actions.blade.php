<!-- The Modal -->
<div class="modal fade" id="editEmpItem{{ $issued_item->issue_id }}">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Item Issued</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/items_issued/update/{{ $issued_item->issue_id }}" method="POST">
               @csrf
               @method('PUT')
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Employee Name:</label>
                      @if(isset($employees))
                     <select class="selectpicker" name="employee" id="employee">
                        <option value="">Select Employee</option>
                        @forelse($employees as $employee)
                        <option value="{{ $employee->user_id }}" {{ $employee->user_id === $issued_item->user_id ? "selected" : "" }}>{{ $employee->employee_name }}</option>
                        @empty
                        <option>No Employees Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Item Name:</label>
                     @if(isset($items))
                     <select class="selectpicker" name="item" id="item">
                        <option value="">Select Item</option>
                        @forelse($items as $item)
                        <option value="{{ $item->item_id }}" {{ $item->item_id === $issued_item->item_id ? "selected" : "" }}>{{ $item->item_name }}</option>
                        @empty
                        <option>No Item(s) Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Status:</label>
                     <input type="text" class="form-control" name="status" id="status" value="{{ $issued_item->status }}">
                  </div>
                  <div class="form-group">
                     <label>Date Issued:</label>
                     <input type="date" class="form-control" name="date_issued" id="date_issued" value="{{ $issued_item->date_issued }}">
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Issued by:</label>
                     @if(isset($employees))
                     <select class="selectpicker" name="issued_by" id="issued_by">
                        <option value="">Select Employee</option>
                        @forelse($employees as $employee)
                        <option value="{{ $employee->user_id }}" {{ $employee->user_id === $issued_item->issued_by ? "selected" : "" }}>{{ $employee->employee_name }}</option>
                        @empty
                        <option>No Employees Found.</option>
                        @endforelse
                     </select>
                     @endif
                  </div>
                  <div class="form-group">
                     <label>Valid Until:</label>
                     <input type="date" class="form-control" name="valid_until" id="valid_until" value="{{ $issued_item->valid_until }}">
                  </div>
                  <div class="form-group">
                     <label>Revoke Reason:</label>
                     <input type="text" class="form-control" name="revoke_reason" id="revoke_reason" value="{{ $issued_item->revoke_reason }}">
                  </div>
                 
                  
                  <div class="form-group">
                     <label>Remarks:</label>
                     <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Enter Remarks" value="{{ $issued_item->remarks }}">
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
