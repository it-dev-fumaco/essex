@extends('client.app')
@section('content')
@include('client.modules.nav_menu')
@include('client.modals.tab_add_applicant')
<div class="col-md-12">
   <h2 class="section-title center">Online Examination System</h2>
   <a href="/home">
      <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-bottom: -50px; float: left;"></i>
   </a>
</div>

<div id="online-exam-tab">
   @include('client.tab_header_admin', ['current_page' => '/tabApplicants'])
   <div class="tab-content">
      <div class="tab-pane in active" id="tab-applicants-list">
         <div class="row">
            <div class="inner-box featured">
               <h2 class="title-2">Applicant(s)</h2>
               <div class="row">
                  <div class="col-md-12">
                     @if(session("message"))
                     <div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                        <center>{!! session("message") !!}</center>
                     </div>
                     @endif
                  </div>
                  <div class="col-md-12">
                     <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addApplicant" style="float: left; z-index: 1;">
                        <i class="fa fa-plus"></i> Applicant
                     </a>
                     <table class="table" id="example" style="font-size: 11pt;">
                        <thead>
                           <tr>
                              <th>ID.</th>
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
                           <td>{{ $applicant->pos1 }}</td>
                           <td>{{ $applicant->pos2 }}</td>
                           <td>
                              <a href="#" class="hover-icon"  data-toggle="modal" data-target="#edit-applicant-{{ $applicant->id }}">
                                 <i class="fa fa-pencil" style="font-size: 15pt; color: #27AE60;"></i>
                              </a>
                              <a href="#" class="hover-icon"  data-toggle="modal" data-target="#delete-applicant-{{ $applicant->id }}">
                                 <i class="fa fa-trash" style="font-size: 15pt; color: #C0392B;"></i> 
                              </a>
                           </td>
                           @include('client.modals.tab_edit_applicant')
                           @include('client.modals.tab_delete_applicant')
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')

<script type="text/javascript">
   $(document).ready(function(){
      $('#example').DataTable({
         "bLengthChange": false,
         "ordering": false,
         "dom": '<"top"f>rt<"bottom"ip><"clear">'
      });

      $('.modal').on('hidden.bs.modal', function(){
         $(this).find('form')[0].reset();
      });
   });
</script>
@endsection


<style>
#online-exam-tab .nav-tabs > li {
   float: none;
   display: inline-block;
   /*zoom: 1;*/
}
 input, select{
   height: 35px;
   width: 100%;
}
</style>

