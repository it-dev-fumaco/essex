@extends('admin.app')
@section('content')
   @include('admin.modals.modal')
		<div class="row">
			<div class="col-md-12 col-sm-12">
            <div class="inner-box featured">
               <h2 class="title-2">Admin List</h2>
               <div>
                  <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addAdmin"><i class="fa fa-plus"></i> Add Admin</a>
                  <br><br>
               </div>
          
               @if(session("message"))
               <div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert'>&times;</button>
                  <center>{{ session("message") }}</center>
               </div>
               @endif
               
               <div id="employee_list">
                  @include('admin.table.adminusers_table')
               </div>
            </div>
         </div>
      </div>
@endsection