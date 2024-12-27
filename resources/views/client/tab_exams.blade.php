@extends('client.app')
@section('content')
@include('client.modals.tab_add_exam')
@include('client.modules.nav_menu')
<div class="col-md-12">
   <h2 class="section-title center">Online Examination System</h2>
   <a href="/home">
      <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-bottom: -50px; float: left;"></i>
   </a>
</div>

<div id="online-exam-tab">
   @include('client.tab_header_admin', ['current_page' => '/tabExams'])
   <div class="tab-content">
      <div class="tab-pane in active" id="tab-exam-list">
         <div class="row">
            <div class="inner-box featured">
               <h2 class="title-2">Exam List</h2>
               <div class="row">
                  <div class="col-md-12">
                     @if(session("message"))
                     <div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                        <center>{{ session("message") }}</center>
                     </div>
                     @endif           
                  </div>
                  <div class="col-md-12">
                     <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addExam" style="float: left; z-index: 1;">
                        <i class="fa fa-plus"></i> Exam
                     </a>
                     <table class="table" id="example">
                        <thead>
                           <tr>
                              <th>No.</th>
                              <th>Exam Title</th>
                              <th>Exam Group</th>
                              <th>Department</th>
                              <th>Duration</th>
                              <th>Status</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody class="table-body">
                           @foreach($exams as $index => $exam)
                           <tr>
                              <td>{{ $index + 1 }}</td>
                              <td>{{ $exam->exam_title }}</td>
                              <td>{{ $exam->exam_group_description }}</td>
                              <td>
                               @if($exam->department_id == -1)
                                 All Departments
                                 @elseif($exam->department_id == 0)
                                 Applicants
                                 @else
                                 {{ $exam->department }}
                                 @endif</td>
                              <td>{{ $exam->duration_in_minutes }} minutes</td>
                              <td>{{ $exam->status }}</td>
                              <td>
                                 <a href="/tabviewExamDetails/{{ $exam->exam_id }}" class="hover-icon">
                                    <i class="fa fa-search" style="font-size: 15pt; color: #2980B9;"></i>
                                 </a>
                                 <a href="#" class="hover-icon" data-toggle="modal" data-target="#editExam{{$exam->exam_id}}">
                                    <i class="fa fa-pencil" style="font-size: 15pt; color: #27AE60;"></i>
                                 </a>
                                 <a href="#" class="hover-icon" data-toggle="modal" data-target="#deleteExam{{$exam->exam_id}}">
                                    <i class="fa fa-trash" style="font-size: 15pt; color: #C0392B;"></i>
                                 </a>
                              </td>
                              @include('client.modals.tab_edit_exam')
                              @include('client.modals.tab_delete_exam')
                           </tr>
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
<style>
#online-exam-tab .nav-tabs > li {
   float: none;
   display: inline-block;
}
input, select{
   height: 35px;
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