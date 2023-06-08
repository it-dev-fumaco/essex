@extends('admin.app')
@section('content')
@include('admin.holiday.modals.add')
<div class="row">
   <div class="col-sm-12 col-md-8 col-md-offset-2">
      <div class="inner-box featured">
         <h2 class="title-2">Holiday List</h2>
         <div class="row">
            <div class="col-md-12">
               @if(session("message"))
               <div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert'>&times;</button>
                  <center>{!! session("message") !!}</center>
               </div>
               @endif
               <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-holiday-modal" style="float: left; margin-bottom: -55px; z-index: 1;">
                  <i class="fa fa-plus"></i> Holiday
               </a>
            </div>
            <div class="col-md-12">
               <table class="table" id="example">
                  <thead>
                     <tr>
                        <th>ID</th>
                        <th>Holiday Date</th>
                        <th>Description</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody class="table-body">
                     @forelse($holidays as $holiday)
                     <tr>
                        <td>{{ $holiday->id }}</td>
                        <td>{{ $holiday->holiday_date }}</td>
                        <td>{{ $holiday->description }}</td>
                        <td>
                           <a href="#" data-toggle="modal" data-target="#edit-holiday-{{ $holiday->id }}">
                              <i class="fa fa-pencil"></i>
                           </a> |
                           <a href="#" data-toggle="modal" data-target="#delete-holiday-{{ $holiday->id }}">
                              <i class="fa fa-trash"></i> 
                           </a>
                        </td>
                     </tr>
                     @include('admin.holiday.modals.edit')
                     @include('admin.holiday.modals.delete')
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