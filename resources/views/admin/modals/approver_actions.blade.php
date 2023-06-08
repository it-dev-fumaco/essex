<!-- The Modal -->
<div class="modal fade" id="editApprover{{ $approver->approver_id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Approver</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form action="/admin/approvers/{{ $approver->approver_id }}" method="POST">
               @csrf
               @method('PUT')
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Department:</label>
                     <select class="form-control" name="department" id="department" required>
                        <option value="">Select Department</option>
                        @forelse($departments as $department)
                        <option value="{{ $department->department_id }}" {{ $department->department_id === $approver->department_id ? "selected" : "" }}>{{ $department->department }}</option>
                        @empty
                        <option>No Departments Found.</option>
                        @endforelse
                     </select>
                  </div>
                   <div class="form-group">
                     <label>Employee:</label>
                     @if(isset($employees))
                     <select class="form-control" name="employee" id="employee" required>
                        <option value="">Select Employee</option>
                        @forelse($employees as $employee)
                        <option value="{{ $employee->user_id }}" {{ $employee->user_id === $approver->employee_id ? "selected" : "" }}>{{ $employee->employee_name }}</option>
                        @empty
                        <option>No Employees Found.</option>
                        @endforelse
                     </select>
                     @endif
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
<div class="modal fade" id="deleteApprover{{ $approver->approver_id }}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Approver</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
              <form action="/admin/approvers/delete/{{ $approver->approver_id }}" method="POST">
               @csrf
               @method("DELETE")
               <div class="col-sm-12">
                 Delete Approver <b>{{ $approver->employee_name }}</b> ?
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
