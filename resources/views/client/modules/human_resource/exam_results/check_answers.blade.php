@extends('client.app')
@section('content')
<div class="row">
   <div class="col-md-12 col-md-10 col-md-offset-1" style="margin-top: -30px;">
      <h2 class="section-title center">Exam Result(s)</h2>
      <a href="/client/exam_results/{{ $examres->examinee_id }}/{{ $examres->exam_id }}">
         <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-top: -68px; float: left;"></i>
      </a>
   </div>
   <div class="col-md-12 col-md-10 col-md-offset-1">
      <div class="inner-box featured">
         <h2 class="title-2">Examination Answer(s)</h2>
         <div class="row" style="font-size: 11pt;">
            <div class="col-sm-2" style="padding-left: 5%; text-align: left;">Examinee:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{$examres->employee_name}}</div>
            <div class="col-sm-2" style="padding-left: 5%; text-align: left;">Date Taken:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{date('l, F d, Y',strtotime($examres->date_taken))}}</div>
            <div class="col-sm-2" style="padding-left: 5%; text-align: left;">Exam Title:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{$examres->exam_title}}</div>
            <div class="col-sm-2" style="padding-left: 5%; text-align: left;">Start Time:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{date('h:i:s A',strtotime($examres->start_time))}}</div>
            <div class="col-sm-2" style="padding-left: 5%; text-align: left;">Exam Type:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{ $exam_type->exam_type }}</div>
            <div class="col-sm-2" style="padding-left: 5%; text-align: left;">End Time:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{date('h:i:s A',strtotime($examres->end_time))}}</div>

            <form method="post" action="/client/exam_results/check_answers/update_score/{{$examres->examinee_id}}/{{$examres->exam_id}}" id="update-applicant-score-form">
            @csrf
            <div class="col-md-12" style="padding: 2% 5% 1% 5%;">
              
              <table class="table table-striped" style="font-size: 11pt;">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Question</th>
                    @if($exam_type->exam_type_id != 5)
                    <th>Correct Answer</th>
                    @endif
                    <th>Examinee Answer</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="table-body">
                  @foreach($examans as $index => $ans)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{!! $ans->questions !!}
                           @if($ans->question_img) 
                              @php($parts = explode(",",$ans->question_img)) 
                              @foreach($parts as $part) 
                              @php($part = \App\Support\QuestionImage::url($part)) 
                              <br><img src="{{$part}}" width="120" height="50">
                              @endforeach
                           @endif
                        </td>
                    @if($exam_type->exam_type_id != 5)
                    <td>{!!$ans->correct_answer!!}</td>
                    @endif
                    <td>{!!$ans->examinee_answer == '' ? 'No Answer': $ans->examinee_answer !!}</td>
                    <td>
                      <select name="{{$ans->question_id}}" id="correct_answer">
                        <option value="True" @if($ans->isCorrect == "True"){{ "selected" }}@endif>Correct</option>
                        <option value="False" @if($ans->isCorrect == "False"){{ "selected" }}@endif>Wrong</option>
                        <option value="Pending" @if($ans->isCorrect == "Pending"){{ "selected" }}@endif>Pending</option>
                      </select>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="col-md-12">
              <center>
                <button class="btn btn-primary" type="submit">Update Score</button>
              </center>
            </div>
          </form>
         </div>
      </div>
   </div>
</div>
@endsection