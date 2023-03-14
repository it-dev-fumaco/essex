@extends('admin.app')
@section('content')

   @include('admin.employee.modals.add')
		<div class="row">
			<div class="col-md-12 col-sm-12">
            <div class="inner-box featured">
               <h2 class="title-2">Employee List</h2>
               
               <div class="row">
                  <div class="col-md-12">
                     @if(session("message"))
                     <div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                        <center>{!! session("message") !!}</center>
                     </div>
                     @endif
                     <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-employee-modal" style="float: left; margin-bottom: -55px; z-index: 1;">
                        <i class="fa fa-plus"></i> Employee
                     </a>
                  </div>
                  <div class="col-md-12">
                     <table class="table" id="example">
                        <thead>
                           <tr>
                              <th style="white-space: nowrap;">Access ID</th>
                              <th>Name</th>
                              <th>Designation</th>
                              <th>Department</th>
                              <th>Status</th>
                              <th>Last Login</th>
                              <th>Last Active</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody class="table-body">
                           @forelse($employees as $employee)
                           <tr>
                              <td>{{ $employee->user_id }}</td>
                              <td>
                                 {{-- <img src="{{ asset('storage/img/user.png') }}" width="55" height="45" style="float: left; padding-right: 10px;">  --}}
                                 {{ $employee->employee_name }}
                              </td>
                              <td>{{ $employee->designation }}</td>
                              <td>{{ $employee->department }}</td>
                              <td>{{ $employee->status }}</td>
                              <td style="white-space: nowrap;">{{ $employee->last_login_date ? Carbon\Carbon::parse($employee->last_login_date)->format('M. d, Y h:i A') : '-' }}</td>
                              <td style="white-space: nowrap;">{{ $employee->last_active ? Carbon\Carbon::parse($employee->last_active)->format('M. d, Y h:i A') : '-' }}</td>
                              <td style="white-space: nowrap;">
                                 <a href="#" data-toggle="modal" data-target="#reset-employee-password-{{ $employee->id }}">
                                    <i class="fa fa-repeat"></i>
                                 </a> |
                                 <a href="#" data-toggle="modal" data-target="#edit-employee-{{ $employee->id }}">
                                    <i class="fa fa-pencil"></i>
                                 </a> |
                                 <a href="#" data-toggle="modal" data-target="#delete-employee-{{ $employee->id }}">
                                    <i class="fa fa-trash"></i> 
                                 </a>
                              </td>
                           </tr>
                           @include('admin.employee.modals.edit')
                           @include('admin.employee.modals.delete')
                           @include('admin.employee.modals.reset_password')
                           @empty
                           <tr>
                              <td colspan="4">No Employee(s) Found.</td>
                           </tr>
                           @endforelse
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
@endsection

@section('script')

<script>
   $(document).ready(function() {
    $('#example').DataTable({
      "bLengthChange": false,
      "ordering": false
    });
} );
</script>
@endsection