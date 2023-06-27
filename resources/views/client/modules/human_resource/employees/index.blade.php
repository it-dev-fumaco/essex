@extends('client.app')
@section('content')
@include('client.modules.nav_menu')
<div class="col-md-12">
   <h2 class="section-title center">Human Resources</h2>
   <a href="/home">
      <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-bottom: -50px; float: left;"></i>
   </a>
</div>
@include('client.modules.human_resource.employees.modals.add')
<div id="tabs">
   <ul class="nav nav-tabs" style="text-align: center;">
      {{-- <li><a href="/module/hr/analytics">Analytics</a></li> --}}
      <li><a href="/module/hr/applicants">Applicant(s)</a></li>
      <li class="active"><a href="/module/hr/employees">Employee(s)</a></li>
      {{-- <li><a href="/module/hr/background_check">Background Investigation Form</a></li> --}}
      <li><a href="/module/hr/applicant_exams">Applicant Exam(s)</a></li>
      <li><a href="/module/hr/exam_results">Exam Result(s)</a></li>
      <li><a href="/module/hr/department_head_list">Department Head(s)</a></li>
      <li><a href="/module/hr/designation">Designation(s)</a></li>
      <li><a href="/module/hr/training">Training(s)</a></li>
   </ul>
   <div class="tab-content">
      <div class="tab-pane in active">
         <div class="row">
           <div class="col-md-12 col-sm-12">
            <div class="inner-box featured">
               <h2 class="title-2">Employee List</h2>
               
               <div class="row">
                  <div class="col-md-12">
                     @if(session("message"))
                     <div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                        <center>{!! session("message") !!}</center>
                     </div>
                     @endif
                     @if(session("error"))
                     <div class='alert alert-danger alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                        <center>{!! session("error") !!}</center>
                     </div>
                     @endif
                     <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-employee-modal" style="float: left; margin-bottom: -55px; z-index: 1;">
                        <i class="fa fa-plus"></i> Employee
                     </a>
                  </div>
                  <div class="col-md-12">
                     <table class="table" id="example">
                        <thead>
                           <tr>
                              <th>Access ID</th>
                              <th>Name</th>
                              <th>Designation</th>
                              <th>Department</th>
                              <th>Status</th>
                              <th>Last Modified</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody class="table-body">
                           @forelse($employees as $employee)
                           <tr>
                              <td>{{ $employee->user_id }}</td>
                              <td>
                                 {{ $employee->employee_name }}
                              </td>
                              <td>{{ $employee->designation }}</td>
                              <td>{{ $employee->department }}</td>
                              <td>{{ $employee->status }}</td>
                              <td style="font-size: 8pt;"><b><i>{{ $employee->updated_at }}-{{ $employee->last_modified_by }}</i></b></td>
                              <td>
                                 <a href="/client/employee/profile/{{ $employee->user_id }}">
                                    <i class="fa fa-search icon-view"></i>
                                 </a>
                                 <a href="#" data-toggle="modal" data-target="#reset-employee-password-{{ $employee->id }}">
                                    <i class="fa fa-repeat" style="color: #5D6D7E; font-size: 14pt;"></i>
                                 </a>
                                 <a href="#" data-id="{{ $employee->id }}" class="edit-employee-btn">
                                    <i class="fa fa-pencil icon-edit"></i>
                                 </a>
                                 <a href="#" data-toggle="modal" data-target="#delete-employee-{{ $employee->id }}">
                                    <i class="fa fa-trash icon-delete"></i> 
                                 </a>
                              </td>
                           </tr>
                           {{-- @include('client.modules.human_resource.employees.modals.edit') --}}
                           @include('client.modules.human_resource.employees.modals.delete')
                           @include('client.modules.human_resource.employees.modals.reset_password')
                           @empty
                           <tr>
                              <td colspan="4">No Employee(s) Found.</td>
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
@include('client.modules.human_resource.employees.modals.edit')
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
.imgPreview {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
}

.upload-btn{
   padding: 6px 12px;
}

.fileUpload {
   position: relative;
   overflow: hidden;
   font-size: 9pt;
}

.fileUpload input.upload {
   position: absolute;
   top: 0;
   right: 0;
   margin: 0;
   padding: 0;
   cursor: pointer;
   opacity: 0;
   filter: alpha(opacity=0);
}
</style>
@endsection

@section('script')
<script>

   $(document).ready(function(){
   $('#example').DataTable({
      "bLengthChange": false,
      "ordering": false,
      "dom": '<"top"f>rt<"bottom"ip><"clear">'
   });

   $("#add-employee-modal .upload").change(function () {
      if (this.files && this.files[0]) {
         var reader = new FileReader();
         reader.onload = function (e) {
            $('#add-employee-modal .imgPreview').attr('src', e.target.result);
         }
      reader.readAsDataURL(this.files[0]);
      }
   });

   $("#edit-employee-modal .upload").change(function () {
      if (this.files && this.files[0]) {
         var reader = new FileReader();
         reader.onload = function (e) {
            $('#edit-employee-modal .imgPreview').attr('src', e.target.result);
         }
         reader.readAsDataURL(this.files[0]);
      }
   });

   hideResignationDate();
   $('#edit-employee-modal .status').change(function(){
      hideResignationDate();
   });

   function hideResignationDate(){
      if ($('#edit-employee-modal .status').val() == 'Resigned') {
         $('#edit-employee-modal .resignation-date-div').show();
         $("#edit-employee-modal input[name='resignation_date']").prop('required',true);
      }else{
         $('#edit-employee-modal .resignation-date-div').hide();
         $("#edit-employee-modal input[name='resignation_date']").prop('required',false);
      }
   }

   $(document).on('click', '.edit-employee-btn', function(e){
         e.preventDefault();
         var id = $(this).data('id');
         $.ajax({
            url: "/getEmployeeDetails/" + id,
            type: 'GET',
            success: function(response) {
               var img = '/storage/img/user.png';
               if (response.image) {
                  img = response.image;
               }
               
               $('#edit-employee-form').attr('action', '/client/employee/update/' + response.id);
               $('#edit-employee-form .imgPreview').attr('src', img);
               $('#edit-employee-form .user_image').val(img);
               $('#edit-employee-form .id').val(response.id);
               $('#edit-employee-form .user_id').val(response.user_id);
               $('#edit-employee-form .employee_name').val(response.employee_name);
               $('#edit-employee-form .birth_date').val(response.birth_date);
               $('#edit-employee-form .contact_no').val(response.contact_no);
               $('#edit-employee-form .address').val(response.address);
               $('#edit-employee-form .nick_name').val(response.nick_name);
               $('#edit-employee-form .civil_status').val(response.civil_status);
               $('#edit-employee-form .contact_person').val(response.contact_person);
               $('#edit-employee-form .contact_person_no').val(response.contact_person_no);
               $('#edit-employee-form .tin_no').val(response.tin_no);
               $('#edit-employee-form .sss_no').val(response.sss_no);
               $('#edit-employee-form .philhealth_no').val(response.philhealth_no);
               $('#edit-employee-form .pagibig_no').val(response.pagibig_no);
               $('#edit-employee-form .employee_id').val(response.employee_id);
               $('#edit-employee-form .department').val(response.department_id);
               $('#edit-employee-form .designation').val(response.designation_id);
               $('#edit-employee-form .designation_name').val(response.designation_name);
               $('#edit-employee-form .employment_status').val(response.employment_status);
               $('#edit-employee-form .branch').val(response.branch);
               $('#edit-employee-form .date_joined').val(response.date_joined);
               $('#edit-employee-form .user_group').val(response.user_group);
               $('#edit-employee-form .reporting-to').val(response.reporting_to);
               $('#edit-employee-form .telephone').val(response.telephone);
               $('#edit-employee-form .email').val(response.email);
               $('#edit-employee-form .status').val(response.status);
               $('#edit-employee-form .modified_date').text(response.updated_at);
               $('#edit-employee-form .modified_name').text(response.last_modified_by);
               $('#edit-employee-form .id-key').val(response.id_security_key);
               $('#edit-employee-form .gender').val(response.gender);
               $('#edit-employee-form input[name="employee_id"]').val(response.employee_id);
               $('#edit-employee-form input[name="id_key"]').val(response.id_security_key);
               $('#edit-employee-form input[name="date_joined"]').val(response.date_joined);
               $('#edit-employee-form select[name="company"]').val(response.company);
               $('#edit-employee-form select[name="branch"]').val(response.branch);
               $('#edit-employee-form select[name="user_group"]').val(response.user_type);
               $('#edit-employee-form select[name="department"]').val(response.department_id);
               $('#edit-employee-form select[name="reporting_to"]').val(response.reporting_to);
               $('#edit-employee-form select[name="shift"]').val(response.shift_group_id);
               $('#edit-employee-form select[name="employment_status"]').val(response.employment_status);
               $('#edit-employee-form input[name="telephone"]').val(response.telephone);
               $('#edit-employee-form input[name="email"]').val(response.email);
               $('#edit-employee-form select[name="status"]').val(response.status);
               $('#edit-employee-modal').modal('show');
            },
            error: function(data) {
               alert('Error fetching data!');
            }
         });
      });

   $(document).on('change', '#add-employee-modal .designation', function(e){
         var designation = $('#add-employee-modal .designation option:selected').text();
         $('#add-employee-modal .designation_name').val(designation);
      });

   $(document).on('change', '#edit-employee-modal .designation', function(e){
         var designation = $('#edit-employee-modal .designation option:selected').text();
         $('#edit-employee-modal .designation_name').val(designation);
      });

   $('.modal').on('hidden.bs.modal', function(){
         $(this).find('form')[0].reset();
      });
   });

   $(document).on('change', 'select[name="status"]', function (e){
      e.preventDefault();
      if($(this).val() == 'Resigned'){
         $('#resignation-date-field').css('display', 'inline-block');
      }else{
         $('#resignation-date-field').css('display', 'none');
      }
   });
</script>

@endsection

