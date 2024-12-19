@extends('client.app')
@section('content')
<div class="row">
   <div class="col-md-12 col-md-10 col-md-offset-1" style="margin-top: -30px;">
      <h2 class="section-title center">Online Examination System</h2>
      <a href="/tabExamReport">
         <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-top: -68px; float: left;"></i>
      </a>
          <div class="pull-right" style="padding: 5px; margin-top: -48px; float: left;">
            <a href="/printExamResult/{{ $examres->examinee_id}}/{{ $examres->exam_id}}" target="_blank">
              <i class="fa fa-print" style="font-size: 30px;"></i>
            </a>
         </div>
   </div>
   <div class="col-md-12 col-md-10 col-md-offset-1">
      <div class="inner-box featured">
         <h2 class="title-2">Examination Result(s)</h2>
         <div class="row" style="font-size: 11pt;">
            <div class="col-sm-2" style="padding-left: 5%; text-align: left;">Examinee:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{$examres->employee_name}}</div>
            <div class="col-sm-2" style="padding-left: 5%; text-align: left;">Date Taken:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{date('l, F d, Y',strtotime($examres->date_taken))}}</div>
            <div class="col-sm-2" style="padding-left: 5%; text-align: left;">Exam Title:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{$examres->exam_title}}</div>
            <div class="col-sm-2" style="padding-left: 5%; text-align: left;">Start Time:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{date('h:i:s A',strtotime($examres->start_time))}}</div>
            <div class="col-sm-2" style="padding-left: 5%; text-align: left;">Duration:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{ $examres->duration_in_minutes }} minute(s)</div>
            <div class="col-sm-2" style="padding-left: 5%; text-align: left;">End Time:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{date('h:i:s A',strtotime($examres->end_time))}}</div>
            <div class="col-md-12" style="padding: 2% 5% 1% 5%;">
               <table class="table table-striped">
                  <thead>
                     <tr>
                        <th>Exam Type</th>
                        <th style="text-align: center;">Total no. of items</th>
                        <th style="text-align: center;">Total Score</th>
                        <th style="text-align: center;">Average</th>
                        <th style="text-align: center;">Actions</th>
                     </tr>
                  </thead>
                  <tbody class="table-body">
                     @if($items_multiple_choice > 0)
                     <tr>
                        <td>Multiple Choice</td>
                        <td style="text-align: center;">{{ $items_multiple_choice }}</td>
                        <td style="text-align: center;">{{ $count_multiple_choice }}</td>
                        <td style="text-align: center;">{{ number_format(100*($count_multiple_choice / $items_multiple_choice), 2) }} %</td>
                        <td style="text-align: center;">
                           <a href="/viewAnswers/{{ $examres->examinee_id }}/{{ $examres->exam_id }}/4">
                              <i class="fa fa-search" style="font-size: 15pt; color: #2980B9;"></i> View Answers
                           </a>
                        </td>
                     </tr>
                     @endif
                     @if($items_true_or_false > 0)
                     <tr>
                        <td>True or False</td>
                        <td style="text-align: center;">{{ $items_true_or_false }}</td>
                        <td style="text-align: center;">{{ $count_true_or_false }}</td>
                        <td style="text-align: center;">{{ number_format(100*($count_true_or_false / $items_true_or_false), 2) }} %</td>
                        <td style="text-align: center;">
                           <a href="/viewAnswers/{{ $examres->examinee_id }}/{{ $examres->exam_id }}/7">
                              <i class="fa fa-search" style="font-size: 15pt; color: #2980B9;"></i> View Answers
                           </a>
                        </td>
                     </tr>
                     @endif
                     @if($items_essay > 0)
                     <tr>
                        <td>Essay</td>
                        <td style="text-align: center;">{{ $items_essay }}</td>
                        <td style="text-align: center;">{{ $count_essay }}</td>
                        <td style="text-align: center;">{{ number_format(100*($count_essay / $items_essay), 2) }} %</td>
                        <td style="text-align: center;">
                           <a href="/checkAnswers/{{$examres->examinee_id}}/{{$examres->exam_id}}/5">
                              <i class="fa fa-search" style="font-size: 15pt; color: #2980B9;"></i> Update Score
                           </a>
                        </td>
                     </tr>
                     @endif
                     @if($items_numerical_exam > 0)
                     <tr>
                        <td>Numerical Exam</td>
                        <td style="text-align: center;">{{ $items_numerical_exam }}</td>
                        <td style="text-align: center;">{{ $count_numerical_exam }}</td>
                        <td style="text-align: center;">{{ number_format(100*($count_numerical_exam / $items_numerical_exam), 2) }} %</td>
                        <td style="text-align: center;">
                           <a href="/checkAnswers/{{ $examres->examinee_id }}/{{ $examres->exam_id }}/6">
                              <i class="fa fa-search" style="font-size: 15pt; color: #2980B9;"></i> Update Score
                           </a>
                        </td>
                     </tr>
                     @endif
                     @if($items_identification > 0)
                     <tr>
                        <td>Identification</td>
                        <td style="text-align: center;">{{ $items_identification }}</td>
                        <td style="text-align: center;">{{ $count_identification }}</td>
                        <td style="text-align: center;">{{ number_format(100*($count_identification / $items_identification), 2) }} %</td>
                        <td style="text-align: center;">
                           <a href="/checkAnswers/{{ $examres->examinee_id }}/{{ $examres->exam_id }}/12"><i class="fa fa-search" style="font-size: 15pt; color: #2980B9;"></i> Update Score</a>
                        </td>
                     </tr>
                     @endif   
                     @if($items_abstract > 0)
                     <tr>
                        <td>Abstract Reasoning</td>
                        <td style="text-align: center;">{{ $items_abstract }}</td>
                        <td style="text-align: center;">{{ $count_abstract }}</td>
                        <td style="text-align: center;">{{ number_format(100*($count_abstract / $items_abstract), 2) }} %</td>
                        <td style="text-align: center;">
                           <a href="/viewAnswers/{{ $examres->examinee_id }}/{{ $examres->exam_id }}/13"><i class="fa fa-search" style="font-size: 15pt; color: #2980B9;"></i> View Answers</a>
                        </td>
                     </tr>
                     @endif
                     @if($items_dexterity1 > 0)
                     <tr>
                        <td>Dexterity and Accuracy Measures 1</td>
                        <td style="text-align: center;">{{ $items_dexterity1 }}</td>
                        <td style="text-align: center;">{{ $count_dexterity1 }}</td>
                        <td style="text-align: center;">{{ number_format(100*($count_dexterity1 / $items_dexterity1), 2) }} %</td>
                        <td style="text-align: center;">
                           <a href="/viewAnswers/{{ $examres->examinee_id }}/{{ $examres->exam_id }}/14"><i class="fa fa-search" style="font-size: 15pt; color: #2980B9;"></i> View Answers</a>
                        </td>
                     </tr>
                     @endif
                     @if($items_dexterity2 > 0)
                     <tr>
                        <td>Dexterity and Accuracy Measures 2</td>
                        <td style="text-align: center;">{{ $items_dexterity2 }}</td>
                        <td style="text-align: center;">{{ $count_dexterity2 }}</td>
                        <td style="text-align: center;">{{ number_format(100*($count_dexterity2 / $items_dexterity2), 2) }} %</td>
                        <td style="text-align: center;">
                           <a href="/viewAnswers/{{ $examres->examinee_id }}/{{ $examres->exam_id }}/15"><i class="fa fa-search" style="font-size: 15pt; color: #2980B9;"></i> View Answers</a>
                        </td>
                     </tr>
                     @endif
                     @if($items_dexterity3 > 0)
                     <tr>
                        <td>Dexterity and Accuracy Measures 3</td>
                        <td style="text-align: center;">{{ $items_dexterity3 }}</td>
                        <td style="text-align: center;">{{ $count_dexterity3 }}</td>
                        <td style="text-align: center;">{{ number_format(100*($count_dexterity3 / $items_dexterity3), 2) }} %</td>
                        <td style="text-align: center;">
                           <a href="/viewAnswers/{{ $examres->examinee_id }}/{{ $examres->exam_id }}/16"><i class="fa fa-search" style="font-size: 15pt; color: #2980B9;"></i> View Answers</a>
                        </td>
                     </tr>
                     @endif
                     <tr style="border-top: 2px solid #CACFD2;">
                        <td style="text-align: right;"><b>TOTALS</b></td>
                        <td style="text-align: center;"><b>{{ $totalItems }}</b></td>
                        <td style="text-align: center;"><b>{{ $totalScore }}</b></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <div class="col-md-6">
               <div style="text-align: center;">
                  Passing Mark = <b>{{ $examres->passing_mark }}</b>
               </div>
            </div>
            <div class="col-md-6">
               <div style="padding-left: 100px;">
                  Average Score = <b>{{ $average }} %</b>
               </div>
               <div style="padding-left: 100px;">
                  Remarks = <b style="font-size: 14pt;">{!! (float) $average >= (float) $examres->passing_mark ? '<span class="label label-success">Passed</span>' : '<span class="label label-danger">Failed</span>' !!}</b>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection