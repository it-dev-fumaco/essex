@extends('client.app')
@section('content')
@include('client.modules.nav_menu')
<div class="col-md-12">
   <h2 class="section-title center">Absent Notice Slip</h2>
   <a href="/home">
      <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-bottom: -50px; float: left;"></i>
   </a>
</div>

<div id="tabs">
   <ul class="nav nav-tabs" style="text-align: center;">
      <li><a href="/module/absent_notice_slip/history">Absent Notice History</a></li>
      <li><a href="/module/absent_notice_slip/leave_approvers">Leave Approver(s)</a></li>
      <li class="active"><a href="/module/absent_notice_slip/leave_balances">Leave Balance(s)</a></li>
   </ul>
   <div class="tab-content">
      <div class="tab-pane in active">
         <div class="row">
            <div class="col-sm-12 col-md-8 col-md-offset-2">
               @include('client.modules.absent_notice_slip.leave_balances.add')
               <div class="inner-box featured">
                  <h2 class="title-2">Employee Leave Balance</h2>
                  <div class="text-right" style="margin-top: -50px;">
                     <a href="#" class="btn" data-toggle="modal" data-target="#reset-employee-leave-modal" style="z-index: 1; padding: 8px 10px; background-color: #abb2b9;">
                        <i class="fa fa-undo"></i> Reset Leave(s)
                     </a>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        @if(session("message"))
                        <div class='alert alert-success alert-dismissible'>
                           <button type='button' class='close' data-dismiss='alert'>&times;</button>
                           <center>{!! session("message") !!}</center>
                        </div>
                        @endif
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-employee-leave-modal" style="float: left; margin-bottom: -55px; z-index: 1;">
                           <i class="fa fa-plus"></i> Employee Leave
                        </a>
                     </div>
                     <div class="col-md-12">
                        <table class="table" id="example">
                           <thead>
                              <tr>
                                 <th>EmpID</th>
                                 <th>Employee</th>
                                 <th>Leave Type</th>
                                 <th>Total</th>
                                 <th>Remaining</th>
                                 <th>Year</th>
                                 <th>Actions</th>
                              </tr>
                           </thead>
                           <tbody class="table-body">
                              @forelse($employee_leaves as $employee_leave)
                              <tr>
                                 <td>{{ $employee_leave->employee_id }}</td>
                                 <td>{{ $employee_leave->employee_name }}</td>
                                 <td>{{ $employee_leave->leave_type }}</td>
                                 <td>{{ $employee_leave->total }}</td>
                                 <td>{{ $employee_leave->remaining }}</td>
                                 <td>{{ $employee_leave->year }}</td>
                                 <td>
                                    <a href="#" data-toggle="modal" data-target="#edit-employee-leave-{{ $employee_leave->leave_id }}">
                                       <i class="fa fa-pencil icon-edit"></i>
                                    </a>
                                    <a href="#" data-toggle="modal" data-target="#delete-employee-leave-{{ $employee_leave->leave_id }}">
                                       <i class="fa fa-trash icon-delete"></i> 
                                    </a>
                                 </td>
                              </tr>
                              @include('client.modules.absent_notice_slip.leave_balances.edit')
                              @include('client.modules.absent_notice_slip.leave_balances.delete')
                              @empty
                              <tr>
                                 <td colspan="4">No Employee Leave(s) Found.</td>
                              </tr>
                              @endforelse
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="reset-employee-leave-modal">
   <div class="modal-dialog">
      <form action="/client/employee_leave_balances/create" method="POST">
         @csrf
         @php
             $next_year = date('Y') + 1;
         @endphp
         <input type="hidden" name="next_year" value="{{ $next_year }}">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Reset Employee Leave</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
               <div class="text-center" style="font-size: 12pt;">Reset leave balances of ALL employees for the year <b>{{ $next_year }}</b>?</div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Confirm</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
         </div>
      </form>
   </div>
</div>

@endsection

@section('script')
<script>
   $(document).ready(function(){
$('#example').DataTable({
      "bLengthChange": false,
      "ordering": false,
      "dom": '<"top"f>rt<"bottom"ip><"clear">'
   });
      
   });
</script>

@endsection

<style>
#tabs .nav-tabs > li {
   float: none;
   display: inline-block;
   /*zoom: 1;*/
}
</style>