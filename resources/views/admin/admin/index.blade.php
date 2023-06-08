@extends('admin.app')
@section('content')
@include('admin.admin.modals.add')
<div class="row">
	<div class="col-md-12 col-sm-12">
      <div class="inner-box featured">
         <h2 class="title-2">Admin List</h2>
         <div class="row">
            <div class="col-md-12">
               @if(session("message"))
               <div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert'>&times;</button>
                  <center>{!! session("message") !!}</center>
               </div>
               @endif
               <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-admin-modal" style="float: left; margin-bottom: -55px; z-index: 1;">
                  <i class="fa fa-plus"></i> Admin
               </a>
            </div>
            <div class="col-md-12">
               <table class="table" id="example">
                  <thead>
                     <tr>
                        <th>Access ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody class="table-body">
                     @forelse($admins as $admin)
                     <tr>
                        <td>{{ $admin->access_id }}</td>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                           <a href="#" data-toggle="modal" data-target="#reset-admin-password-{{ $admin->id }}">
                              <i class="fa fa-repeat"></i>
                           </a> |
                           <a href="#" data-toggle="modal" data-target="#edit-admin-{{ $admin->id }}">
                              <i class="fa fa-pencil"></i>
                           </a> |
                           <a href="#" data-toggle="modal" data-target="#delete-admin-{{ $admin->id }}">
                              <i class="fa fa-trash"></i> 
                           </a>
                        </td>
                     </tr>
                     @include('admin.admin.modals.edit')
                     @include('admin.admin.modals.delete')
                     @include('admin.admin.modals.reset_password')
                     @empty
                     <tr>
                        <td colspan="4">No Admin(s) Found.</td>
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