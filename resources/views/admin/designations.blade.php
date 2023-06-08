@extends('admin.app')
@section('content')
   @include('admin.modals.modal')
   <div class="row">
      <div class="col-sm-12 col-md-8 col-md-offset-2">
         <div class="inner-box featured">
            <h2 class="title-2">Designation List</h2>
            <div>
               <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addDesignation"><i class="fa fa-plus"></i> Add Designation</a>
               <br><br>
            </div>
            
            @if(session("message"))
            <div class='alert alert-success alert-dismissible'>
               <button type='button' class='close' data-dismiss='alert'>&times;</button>
               <center>{{ session("message") }}</center>
            </div>
            @endif
            <div id="designation_list">
               @include('admin.table.designations_table')
            </div>
         </div>
      </div>
   </div>
@endsection