@extends('admin.app')
@section('content')
@include('admin.department.modals.add')
<div class="row">
   <div class="col-sm-12 col-md-8 col-md-offset-2">
      <div class="inner-box featured">
         <h2 class="title-2">Department List</h2>
         <div class="row">
            <div class="col-md-12">
               @if(session("message"))
               <div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert'>&times;</button>
                  <center>{!! session("message") !!}</center>
               </div>
               @endif
               <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-department-modal" style="float: left; margin-bottom: -55px; z-index: 1;">
                  <i class="fa fa-plus"></i> Department
               </a>
            </div>
            <div class="col-md-12">
               <table class="table" id="example">
                  <thead>
                     <tr>
                        <th>ID</th>
                        <th>Department Name</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody class="table-body">
                     @forelse($departments as $department)
                     <tr>
                        <td>{{ $department->department_id }}</td>
                        <td>{{ $department->department }}</td>
                        <td>
                           <a href="#" data-toggle="modal" data-target="#edit-department-{{ $department->department_id }}">
                              <i class="fa fa-pencil"></i>
                           </a> |
                           <a href="#" data-toggle="modal" data-target="#delete-department-{{ $department->department_id }}">
                              <i class="fa fa-trash"></i> 
                           </a>
                        </td>
                     </tr>
                     @include('admin.department.modals.edit')
                     @include('admin.department.modals.delete')
                     @empty
                     <tr>
                        <td colspan="3">No Department(s) Found.</td>
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
});
</script>
@endsection