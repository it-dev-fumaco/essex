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
            
            <div class="col-md-12" style="padding: 2% 5% 1% 5%;">
               <table class="table table-striped" style="font-size: 11pt;">
                  <thead>
                     <tr>
                        <th style="text-align: center;">No.</th>
                        <th>Question</th>
                        <th style="text-align: center;">Correct Answer</th>
                        <th style="text-align: center;">Examinee Answer</th>
                     </tr>
                  </thead>
                  <tbody class="table-body">
                     @foreach($examans as $i => $ans)
                     <tr>
                        <td style="text-align: center;">{{ $i + 1 }}</td>
                        <td>{!! $ans->questions !!}
                           @if($ans->question_img) 
                              @php($parts = explode(",",$ans->question_img)) 
                              @foreach($parts as $part) 
                              @php($part = \App\Support\QuestionImage::url($part)) 
                              <br><img src="{{$part}}" width="440" height="110">
                              @endforeach
                           @endif
                        </td>
                        <td class="center" style={{ strtoupper($ans->correct_answer) == strtoupper($ans->examinee_answer) ? "color:#28B463;" : "color:#C0392B;" }}>{{$ans->correct_answer}}</td>
                        @if(strtoupper($ans->correct_answer) == strtoupper($ans->examinee_answer))
                        <td class="center" style="color:#28B463;">{{ $ans->examinee_answer ? $ans->examinee_answer : 'No Answer' }}<i class="fa fa-check" style="float: right;"></i></td>
                        @else
                        <td class="center" style="color:#C0392B;">{{ $ans->examinee_answer ? $ans->examinee_answer : 'No Answer' }}<i class="fa fa-times" style="float: right;"></i></td>
                        @endif
                     </tr>
                     @endforeach
                     <tr>
                        <td colspan="5" class="center">-- END --</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>

<style type="text/css">
   table tr{
      vertical-align: middle;
   }
</style>
@endsection