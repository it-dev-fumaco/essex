@extends('admin.app')
@section('content')
   @include('admin.modals.modal')
   <div class="row">
      <div class="col-sm-12">
         <div class="inner-box featured">
            <h2 class="title-2">Item Issued to Employee</h2>
            <div>
               <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addEmpItem"><i class="fa fa-plus"></i> Issue Item</a>
               <br><br>
            </div>

            @if(session("message"))
            <div class='alert alert-success alert-dismissible'>
               <button type='button' class='close' data-dismiss='alert'>&times;</button>
               <center>{{ session("message") }}</center>
            </div>
            @endif

            <div id="leave_types_list">
               @include('admin.table.issued_items_table')
            </div>
            {{-- <textarea id="ckeditor" name="ckeditor" class="form-control ckeditor" rows="10"></textarea> --}}
         </div>
      </div>
   </div>
@endsection