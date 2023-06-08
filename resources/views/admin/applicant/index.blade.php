@extends('admin.app')
@section('content')
@include('admin.applicant.modals.add')
<div class="row">
	<div class="col-md-12 col-sm-12">
      <div class="inner-box featured">
         <h2 class="title-2">Applicant List</h2>
         <div class="row">
            <div class="col-md-12">
               @if(session("message"))
               <div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert'>&times;</button>
                  <center>{!! session("message") !!}</center>
               </div>
               @endif
               <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-applicant-modal" style="float: left; margin-bottom: -55px; z-index: 1;">
                  <i class="fa fa-plus"></i> Applicant
               </a>
            </div>
            <div class="col-md-12">
               <table class="table" id="example1">
                  <thead>
                     <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Position Applied (1st choice)</th>
                        <th>Position Applied (2nd choice)</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody class="table-body">
                     @foreach($applicants as $applicant)
                     <tr>
                     <td>{{ $applicant->id }}</td>
                     <td>{{ $applicant->employee_name }}</td>
                     <td>{{ $applicant->position_applied_for1 }}</td>
                     <td>{{ $applicant->position_applied_for2 }}</td>
                     <td>
                        <a href="#" data-toggle="modal" data-target="#edit-applicant-{{ $applicant->id }}">
                           <i class="fa fa-pencil"></i>
                        </a> |
                        <a href="#" data-toggle="modal" data-target="#delete-applicant-{{ $applicant->id }}">
                           <i class="fa fa-trash"></i> 
                        </a>
                     </td>
                     @include('admin.applicant.modals.edit')
                     @include('admin.applicant.modals.delete')
                     @endforeach
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
   $('#example1').DataTable({
      "bLengthChange": false,
      "ordering": false
   });
});
</script>
@endsection