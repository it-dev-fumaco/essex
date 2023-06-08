@extends('admin.app')
@section('content')
  @include('admin.modals.modal')
	<div class="row">
		<div class="col-sm-12 col-md-8 col-md-offset-2">
         <div class="inner-box featured">
            <h2 class="title-2">Employee Leave Balances</h2>
               
            @if(session("message"))
            <div class='alert alert-success alert-dismissible'>
               <button type='button' class='close' data-dismiss='alert'>&times;</button>
               <center>{{ session("message") }}</center>
            </div>
            @endif
               
            <div id="leave_balances">
               @include('admin.table.leave_balances_table')
            </div>
         </div>
      </div>
   </div>
@endsection
@section('script')
<script>
$(document).ready(function() {
   $('#leave-balances-table').DataTable({
      "bLengthChange": false,
      "ordering": false
   });
});
</script>
@endsection