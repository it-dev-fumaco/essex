@extends('admin.app')
@section('content')
   <div class="row">
      <div class="col-sm-12">
         <div class="inner-box featured">
            <h2 class="title-2">Absent Notice Slip for Approval</h2>
          
            @if(session("message"))
            <div class='alert alert-success alert-dismissible'>
               <button type='button' class='close' data-dismiss='alert'>&times;</button>
               <center>{{ session("message") }}</center>
            </div>
            @endif

            <div id="leave_types_list">
               @include('admin.table.notices_for_approval_table')
            </div>
         </div>
      </div>
   </div>
@endsection  