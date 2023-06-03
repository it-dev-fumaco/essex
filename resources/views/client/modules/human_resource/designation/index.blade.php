@extends('client.app')
@section('content')
@include('client.modules.nav_menu')
<div class="col-md-12"{{--  style="margin-top: -30px;" --}}>
   <h2 class="section-title center">Human Resources</h2>
   <a href="/home">
      <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-bottom: -50px; float: left;"></i>
   </a>
</div>
@include('client.modules.human_resource.designation.modals.add')
<div id="tabs">
   <ul class="nav nav-tabs" style="text-align: center;">
      {{-- <li><a href="/module/hr/analytics">Analytics</a></li> --}}
      <li><a href="/module/hr/applicants">Applicant(s)</a></li>
      <li><a href="/module/hr/employees">Employee(s)</a></li>
      {{-- <li><a href="/module/hr/background_check">Background Investigation Form</a></li> --}}
      <li><a href="/module/hr/applicant_exams">Applicant Exam(s)</a></li>
      <li><a href="/module/hr/exam_results">Exam Result(s)</a></li>
      <li><a href="/module/hr/department_head_list">Department Head(s)</a></li>
      <li class="active"><a href="/module/hr/designation">Designation(s)</a></li>
      <li><a href="/module/hr/training">Training(s)</a></li>

   </ul>
   <div class="tab-content">
      <div class="tab-pane in active">
         <div class="row">
            <div class="col-sm-12 col-md-8 col-md-offset-2">
               <div class="inner-box featured">
                  <h2 class="title-2">Designation List</h2>
                  <div class="row">
                     <div class="col-md-12">
                        @if (session()->has('success'))
                           @if(session("message"))
                           <div class='alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible'>
                              <button type='button' class='close' data-dismiss='alert'>&times;</button>
                              <center>{!! session("message") !!}</center>
                           </div>
                           @endif
                        @endif
                        
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-desig-modal" style="float: left; margin-bottom: -55px; z-index: 1;">
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
                              @forelse($designations as $desig)
                              <tr>
                                 <td>{{ $desig->des_id }}</td>
                                 <td>{{ $desig->department }}</td>
                                 <td>{{ $desig->designation }}</td>
                                 <td>
                                    <a href="#" data-toggle="modal" data-target="#edit-desig-{{ $desig->des_id }}">
                                       <i class="fa fa-pencil icon-edit"></i>
                                    </a> |
                                    <a href="#" data-toggle="modal" data-target="#delete-desig-{{ $desig->des_id }}">
                                       <i class="fa fa-trash icon-delete"></i> 
                                    </a>
                                 </td>
                              </tr>
                              @include('client.modules.human_resource.designation.modals.edit')
                              @include('client.modules.human_resource.designation.modals.delete')
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
      </div>
   </div>
</div>
<style>
#tabs .nav-tabs > li {
   float: none;
   display: inline-block;
   /*zoom: 1;*/
}

 input, select{
   height: 35px;
   width: 100%;
}
textarea{
   width: 100%;
}
</style>
@endsection

@section('script')
<script>
$(document).ready(function() {
   $('#example').DataTable({
      "bLengthChange": false,
      "ordering": false,
      "dom": '<"top"f>rt<"bottom"ip><"clear">'
   });
});
</script>
@endsection