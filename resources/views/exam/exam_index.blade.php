@extends('admin.app')
@section('content')
    @include('exam.modal.exam_add')
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="inner-box featured">
          <h2 class="title-2">Exam List</h2>
          <div><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addExam"><i class="fa fa-plus"></i> Add Exam</a><br><br></div>
          @if(session("message"))
            <div class='alert alert-success alert-dismissible'>
               <button type='button' class='close' data-dismiss='alert'>&times;</button>
               <center>{{ session("message") }}</center>
            </div>
          @endif
          <div id="employee_list">
             @include('exam.table.exam_table')
          </div>
        </div>
      </div>
    </div>
@endsection


@section('script')
<script type="text/javascript">
  
</script>
@stop