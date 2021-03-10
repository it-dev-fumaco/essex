@extends('admin.app')
@section('content')
		<div class="row">
			<div class="col-md-12 col-sm-12">
        <div class="inner-box featured">
          <h2 class="title-2">Exam Group List</h2>
          <div><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addExamGroup"><i class="fa fa-plus"></i> Add Exam Group</a><br><br></div>
          @if(session("message"))
            <div class='alert alert-success alert-dismissible'>
             <button type='button' class='close' data-dismiss='alert'>&times;</button>
             <center>{{ session("message") }}</center>
          </div>
          @endif
          @if(session("error"))
            <div class='alert alert-danger alert-dismissible'>
             <button type='button' class='close' data-dismiss='alert'>&times;</button>
             <center>{{ session("error") }}</center>
          </div>
          @endif
          <div id="employee_list">
             @include('exam.table.examgroup_table')
          </div>
         
        </div>
			</div>
		</div>

@include('exam.modal.examgroup_add')

@endsection

@section('script')
  <script type="text/javascript">
    $('#closeAddExamGroup').click(function(){
      $('#formAddExamGroup').trigger("reset");
    });
    $('#closeEditExamGroup').click(function(){
      $('.editExamGroup').trigger("reset");
    });
    $('#formAddExamGroup').submit(function(event){
      event.preventDefault();
      $.ajax({
        type: 'POST',
        url: '{{route("admin.exam_group_save")}}',
        data: $(this).serialize(),
        success: function(data){
          if(data.error){
            $('#examGroupErrors').html("<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <center>" + data.error + "</center></div>");
            $('#formAddExamGroup').trigger("reset");
          }
          else if(data.message){
            location.reload();
          }
        }
      });
    });
  </script>
@stop