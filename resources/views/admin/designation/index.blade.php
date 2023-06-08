@extends('admin.app')
@section('content')
@include('admin.designation.modals.add')
<div class="row">
   <div class="col-sm-12 col-md-8 col-md-offset-2">
      <div class="inner-box featured">
         <h2 class="title-2">Designation List</h2>
         <div class="row">
            <div class="col-md-12">
               @if(session("message"))
               <div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert'>&times;</button>
                  <center>{!! session("message") !!}</center>
               </div>
               @endif
               <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-designation-modal" style="float: left; margin-bottom: -55px; z-index: 1;">
                  <i class="fa fa-plus"></i> Designation
               </a>
            </div>
            <div class="col-md-12">
               <table class="table" id="example">
                  <thead>
                     <tr>
                        <th>ID</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody class="table-body">
                     @forelse($designations as $designation)
                     <tr>
                        <td>{{ $designation->des_id }}</td>
                        <td>{{ $designation->department }}</td>
                        <td>{{ $designation->designation }}</td>
                        <td>
                           <a href="#" data-toggle="modal" data-target="#edit-designation-{{ $designation->des_id }}">
                              <i class="fa fa-pencil"></i>
                           </a> |
                           <a href="#" data-toggle="modal" data-target="#delete-designation-{{ $designation->des_id }}">
                              <i class="fa fa-trash"></i> 
                           </a>
                        </td>
                     </tr>
                     @include('admin.designation.modals.edit')
                     @include('admin.designation.modals.delete')
                     @empty
                     <tr>
                        <td colspan="3">No Designation(s) Found.</td>
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