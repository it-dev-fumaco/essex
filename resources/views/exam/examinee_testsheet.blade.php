@extends('admin.app')
@section('content')
<style type="text/css">
	*{
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	}

  .hover-image img {
    -webkit-transform: scale(1);
    transform: scale(1);
    -webkit-transition: .3s ease-in-out;
    transition: .3s ease-in-out;
    cursor: pointer;
    }
    .hover-image:hover img {
      -webkit-transform: scale(1.3);
      transform: scale(1.3);
    cursor: pointer;
    }
    .nav-tabs { border-bottom: 2px solid #DDD; }
    .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover { border-width: 0; }
    .nav-tabs > li > a { border: none; color: #666; }
        .nav-tabs > li.active > a, .nav-tabs > li > a:hover { border: none; color: #4285F4 !important; background: transparent; }
        .nav-tabs > li > a::after { content: ""; background: #4285F4; height: 2px; position: absolute; width: 100%; left: 0px; bottom: -1px; transition: all 250ms ease 0s; transform: scale(0); }
    .nav-tabs > li.active > a::after, .nav-tabs > li:hover > a::after { transform: scale(1); }
    .tab-nav > li > a::after { background: #21527d none repeat scroll 0% 0%; color: #fff; }
    .tab-pane { padding: 15px 0; }
    .tab-content{padding:20px}

    .card {background: #FFF none repeat scroll 0% 0%; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.3); margin-bottom: 30px; }
    

</style>

{{--@include('exam.modal.question_add')--}}
		<div class="row">
			<div class="col-md-12 col-sm-12">
		        <div class="inner-box featured">
		          <h2 class="title-2">Examination Sheet</h2>
		          <div>
		          	<div class="col-md-12">
		          	<div class="col-md-5">
		          		<span class="h3">Examination: {{$examinee->exam_title}}</span><br>
		          		<span class="h4">Examinee: {{$examinee->employee_name}} ({{$examinee->user_type}})</span>
		          	</div>
		          	
			          <div class="col-md-7">
		          		<div class="col-md-4">
		          			<span class="h4">Duration <br><span id="duration" class="h2"></span></span>
		          		</div>
		          		<div class="col-md-3">
		          		<span class="h5">Time Spent <br><span id="timespent" class="h4"></span></span>
		          		</div>
		          		<div class="col-md-5"><span class="h5">{{date('l, F d, Y',strtotime($examinee->date_of_exam))}}</span><br>
		          			<span class="h5"> Start Time: {{date('h:i A')}}</span>
		          		</div>
			          </div>
		          </div>
		          </div>
		          @if(session("message"))
		            <div class='alert alert-success alert-dismissible'>
		               <button type='button' class='close' data-dismiss='alert'>&times;</button>
		               <center>{{ session("message") }}</center>
		            </div>
		          @endif
		          <br><br>
		          <form method="post" action="{{route('admin.examinee_testsheet_save')}}">
		          	@csrf
		          	<input type="hidden" id="start_time" name="start_time" value="{{date('H:i')}}">
		          	<input type="hidden" id="end_time" name="end_time" value="{{date('H:i')}}">
		          	<input type="hidden" id="dur" name="dur" value="{{date('H:i')}}">
		          	<input type="hidden" id="spent" name="spent" value="{{date('H:i')}}">
		          <!-- Nav tabs -->
            
              <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#multiplechoice" aria-controls="multiplechoice" role="tab" data-toggle="tab">Part I: Multiple Choice</a></li>
                  <li role="presentation"><a href="#truefalse" aria-controls="truefalse" role="tab" data-toggle="tab">Part II: True or False</a></li>
                  <li role="presentation"><a id="tessay" href="#essay" aria-controls="essay" role="tab" data-toggle="tab">Part III: Essay</a></li>
                  <li role="presentation"><a id="tnumerical" href="#numerical" aria-controls="numerical" role="tab" data-toggle="tab">Part IV: Numerical Exam</a></li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="multiplechoice">
                    <div class="inner-box featured">
                      <span class="h4">Instructions: {{$mcins->instruction}}.</span>
                      @if(session("message"))
                        <div class='alert alert-success alert-dismissible'>
                           <button type='button' class='close' data-dismiss='alert'>&times;</button>
                           <center>{{ session("message") }}</center>
                        </div>
                      @endif
                      <div>
                      	@php $ctr = 1; @endphp
                      	@foreach($multiplechoices as $multiplechoice)
                      	<div class="container">
                      		<div class="col-md-6 m-auto"> 
	                      	@php echo $ctr . $multiplechoice->questions; $ctr++;@endphp
	                      	<div class="col-md-6 pl-5 ml-5">
	                      		@if($multiplechoice->option1)
		                      		<input class="pl-5" type="radio" name="{{$multiplechoice->question_id}}" id="{{$multiplechoice->question_id}}op1" value="{{$multiplechoice->option1}}">
		                      		<label for="{{$multiplechoice->question_id}}op1"> {{$multiplechoice->option1}}</label><br>
		                      	@endif
		                      	@if($multiplechoice->option2)
		                      		<input class="h5" type="radio" name="{{$multiplechoice->question_id}}" id="{{$multiplechoice->question_id}}op2" value="{{$multiplechoice->option2}}"> <label for="{{$multiplechoice->question_id}}op2"> {{$multiplechoice->option2}}</label><br>
		                      	@endif
		                      	@if($multiplechoice->option3)
		                      		<input class="h5" type="radio" name="{{$multiplechoice->question_id}}" id="{{$multiplechoice->question_id}}op3" value="{{$multiplechoice->option3}}"> <label for="{{$multiplechoice->question_id}}op3"> {{$multiplechoice->option3}}</label><br>
		                      	@endif
		                      	@if($multiplechoice->option4)
		                      		<input class="h5" type="radio" name="{{$multiplechoice->question_id}}" id="{{$multiplechoice->question_id}}op4" value="{{$multiplechoice->option4}}"> <label for="{{$multiplechoice->question_id}}op4"> {{$multiplechoice->option4}}</label><br>
		                      	@endif
	                      	</div>
	                      </div>
                      	</div>
                      	
                      @endforeach
                      
                      </div>
                    </div>
                  </div>

                  <div role="tabpanel" class="tab-pane" id="truefalse">
                    <div class="inner-box featured">
                      <h2 class="title-2">True or False</h2>
                      <div><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addTrueFalse"><i class="fa fa-plus"></i> Add True or False</a><br><br></div>
                      @if(session("message"))
                        <div class='alert alert-success alert-dismissible'>
                           <button type='button' class='close' data-dismiss='alert'>&times;</button>
                           <center>{{ session("message") }}</center>
                        </div>
                      @endif
                      <div id="exam_type">
                        @include('exam.table.exam_truefalse_table')
                      </div>
                    </div>
                  </div>

                  <div role="tabpanel" class="tab-pane" id="essay">
                    <div class="inner-box featured">
                      <h2 class="title-2">Essay</h2>
                      <div><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addEssay"><i class="fa fa-plus"></i> Add Essay</a><br><br></div>
                      @if(session("message"))
                        <div class='alert alert-success alert-dismissible'>
                           <button type='button' class='close' data-dismiss='alert'>&times;</button>
                           <center>{{ session("message") }}</center>
                        </div>
                      @endif
                      <div id="exam_type">
                        @include('exam.table.exam_essay_table')
                      </div>
                    </div>
                  </div>

                  <div role="tabpanel" class="tab-pane" id="numerical">
                    <div class="inner-box featured">
                      <h2 class="title-2">Numerical Exam</h2>
                      <div><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addNumerical"><i class="fa fa-plus"></i> Add Question</a><br><br></div>
                      @if(session("message"))
                        <div class='alert alert-success alert-dismissible'>
                           <button type='button' class='close' data-dismiss='alert'>&times;</button>
                           <center>{{ session("message") }}</center>
                        </div>
                      @endif
                      <div id="exam_type">
                      </div>
                    </div>
                  </div>
		        </div>
		        <input type="submit" class="btn btn-primary m-auto" value="Submit Exam">
			
		</form>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	
		window.onbeforeunload = function() {
	        return "Dude, are you sure you want to leave? Think of the kittens!";
	  }
	function disableF5(e) { if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82) e.preventDefault(); };


	  $(document).ready(function(){
	       $(document).on("keydown", disableF5);
	  });

	  function preventBack(){window.history.forward();}
	  setTimeout("preventBack()", 0);
	  window.onunload=function(){null};
    var rem_secs = max = {{$examinee->duration}} * 60;
    var rem_mins = rem_secs / 60;
    var minR = 0;
    var secR = 60;
    var minL = secL = 0;
    var timer = setInterval(function(){ 
      rem_secs--;
      rem_mins = rem_secs / 60;
      minR = parseInt(rem_mins);
      secR > 0 ? secR-- : secR = 59;

      var remSecDisp,remMinDisp;

      secR < 10 ? remSecDisp = '0' + secR : remSecDisp = secR;
      minR < 10 ? remMinDisp = '0' + minR : remMinDisp = minR;

      document.getElementById('duration').innerHTML = remMinDisp + ':' + remSecDisp;
      // document.getElementById('viewDur').innerHTML = "Duration: " + remMinDisp + ':' + remSecDisp;

      secL++;
      if(secL == 60){
        minL++;
        secL = 0;
      }

      var lapSecDisp,lapMinDisp;

      secL < 10 ? lapSecDisp = '0' + secL : lapSecDisp = secL;
      minL < 10 ? lapMinDisp = '0' + minL : lapMinDisp = minL;

      document.getElementById('timespent').innerHTML = lapMinDisp + ':' + lapSecDisp;
      // document.getElementById('viewSpent').innerHTML = "Time Spent: " + lapMinDisp + ':' + lapSecDisp;


      if(rem_secs == 0){
        clearInterval(timer);
      }
    },1000);

</script>
@endsection