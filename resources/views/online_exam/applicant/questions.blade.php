@foreach($data['questions'] as $index => $q)
   @php $index = $index + 1; @endphp
   <fieldset>
      <div class="text-center col-md-12" >
         <div style="font-size: 15pt; padding: 2%;"><b>Question {{ $index }} of {{ count($data['questions']) }}</b></div>
      </div>
      <form id="form{{ $q->examinee_answer_id }}">
         @csrf
         <input type="hidden" name="remaining_time" class="time-remaining">
         <input type="hidden" name="spent_time" class="time-spent">
         <input type="hidden" name="examinee_answer_id" value="{{ $q->examinee_answer_id }}">
         <input type="hidden" name="q_no" value="{{ $index }}">
         <input type="hidden" name="examinee_id" value="{{ $examinee->examinee_id }}">
         <input type="hidden" name="exam_id" value="{{ $examinee->exam_id }}">
         <input type="hidden" name="exam_type_id" value="{{ $data['type_id'] }}">
         <input type="hidden" name="question_id" value="{{ $q->question_id }}">
      <div class="row">

         {{-- Multiple Choice --}}
         @if($data['type_id'] == 4)
         <div class="col-md-12 text-center mt-5" style="padding: 10px 0;">
            <span style="font-size: 15pt;">{!! $q->questions !!}</span>
         </div>
         <div class="col-md-8 col-md-offset-2" id="opt" style="padding: 10px 0;">
            @for($i = 1; $i <= 4; $i++)
               @php
                   $option = 'option'.$i;
               @endphp
               <div class="col-md-12">
                  <!-- Default inline 1-->
                  <div class="custom-control custom-radio">
                  <input type="radio" class="custom-control-input" id="opt1" name="examinee_answer" value="{{ $q->$option }}" {{ $q->$option == $q->examinee_answer ? 'checked' : null }}>
                  <label class="custom-control-label" for="opt1">{{ $q->$option }}</label>
                  </div>
               </div>
            @endfor
         </div>
         @endif

         {{-- Essay --}}
         @if($data['type_id'] == 5)
         <div class="col-md-12 text-center mt-5" style="padding: 10px 0;">
            <span style="font-size: 15pt;">{!! $q->questions !!}</span>
         </div>
         <div class="col-md-8 col-md-offset-2" style="padding: 10px 0;">
            <textarea class="form-control" rows="5" name="examinee_answer" placeholder="Start typing..">{{ $q->examinee_answer }}</textarea>
         </div>
         @endif

         {{-- True or False --}}
         @if($data['type_id'] == 7)
         <div class="col-md-12 text-center mt-5" style="padding: 10px 0;">
            <span style="font-size: 15pt;">{!! $q->questions !!}</span>
         </div>
         <div class="col-md-6 col-md-offset-4" style="padding: 10px 0;">
            <div class="row" id="opt">
               <div class="col-md-6">
                  <!-- Default inline 1-->
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="tf1" name="examinee_answer" value="True" {{ $q->examinee_answer == 'True' ? 'checked' : null }}>
                    <label class="custom-control-label" for="tf1">True</label>
                  </div>
               </div>
               <div class="col-md-6">
                  <!-- Default inline 2-->
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="tf2" name="examinee_answer" value="False" {{ $q->examinee_answer == 'False' ? 'checked' : null }}>
                    <label class="custom-control-label" for="tf2">False</label>
                  </div>
               </div>
            </div>
         </div>
         @endif

         {{-- Abstract Reasoning --}}
         @if($data['type_id'] == 13)
         <div class="col-md-12 text-center mt-2" style="padding: 10px 0;">
            @php($parts = explode(",",$q->question_img)) 
            @foreach($parts as $part) 
               @php($part = '/storage/questions/'.$part)
               <img src="{{$part}}" width="450" height="120">
            @endforeach
         </div>
         <div class="col-md-12 text-center" style="padding: 10px 0;">
            <center>
               <select class="form-control" name="examinee_answer" style="width: 25%;">
                  <option value=''>-- SELECT --</option>
                  @for($i = 1; $i <= 5; $i++)
                     <option value="{{ $i }}" {{ $i == $q->examinee_answer ? 'selected' : null }}>{{ $i }}</option> 
                  @endfor
               </select>
            </center>
         </div>
         @endif

         {{-- Identification --}}
         @if($data['type_id'] == 12)
         <div class="col-md-6 col-md-offset-3 text-center mt-5" style="padding: 10px 0;">
            @if(!$q->questions)
            @php($parts = explode(",",$q->question_img)) 
            @foreach($parts as $part) 
               @php($part = '/storage/questions/'.$part)
               <img src="{{$part}}" width="450" height="120">
            @endforeach
            @else
            <span style="font-size: 15pt;">{!! $q->questions !!}</span>
            @endif
         </div>
         <div class="col-md-12 text-center" style="padding: 10px 0;">
               <center>
                  <textarea class="form-control" rows="3" placeholder="Start typing.." name="examinee_answer" style="width: 50%;">{{ $q->examinee_answer }}</textarea>
               </center>
            </div>
         @endif

         {{-- Numerical Exam --}}
         @if($data['type_id'] == 6)
         <div class="col-md-12 text-center mt-5" style="padding: 10px 0;">
            @if(!$q->questions)
            @php($parts = explode(",",$q->question_img)) 
            @foreach($parts as $part) 
               @php($part = '/storage/questions/'.$part)
               <img src="{{$part}}" style="width: 10%;">
            @endforeach
            @else
            <span style="font-size: 15pt;">{!! $q->questions !!}</span>
            @endif
         </div>
         <div class="col-md-12 text-center" style="padding: 10px 0;">
            <center>
               <input type="text" name="examinee_answer" placeholder="Type your answer here.." class="form-control w-25" style="width: 25%;" value="{{ $q->examinee_anwer }}">
            </center>
         </div>
         @endif

         {{-- Dexterity & Accuracy Measures 1, 2, 3 --}}
         @if(in_array($data['type_id'], [14, 15, 16]))
         <div class="col-md-12 text-center mt-5" style="padding: 10px 0;">
            @if(!$q->questions)
            @php($parts = explode(",",$q->question_img)) 
            @foreach($parts as $part) 
               @php($part = '/storage/questions/'.$part)
               <img src="{{$part}}" width="450" height="120">
            @endforeach
            @else
            <span style="font-size: 15pt;">{!! $q->questions !!}</span>
            @endif
         </div>
         <div class="col-md-12 text-center" style="padding: 10px 0;">
            <center>
               <input type="text" name="examinee_answer" placeholder="Type your answer here.." class="form-control w-25" style="width: 25%;" value="{{ $q->examinee_anwer }}">
            </center>
         </div>
         @endif
      </div>
         </form>
      <div class="row" style="margin-top: 3%;">
         <div class="col-md-6">
            @if($loop->first)
               @if($data['type_id'] == 4)
               <button type="button" class="previous btn btn-success action-button" disabled>Previous</button>
               @else
               <button type="button" class="btn btn-success action-button prevtab">Previous</button>
               @endif
            @else
               <button type="button" class="previous btn btn-success action-button">Previous</button>
            @endif
         </div>
         <div class="text-right col-md-6">
            @if(!$loop->last)
            <button type="button" class="next btn btn-success action-button submit-btn" data-id="{{$q->examinee_answer_id}}">Next</button>
            @else
               @if($data['type_id'] == 16 && $loop->last)
                  <button type="button" class="btn btn-success action-button preview submit-btn" data-id="{{$q->examinee_answer_id}}" onclick="preview_answer()">Preview Answers</button>
               @else
               <button type="button" class="btn btn-success action-button nexttab submit-btn {{ $i == count($active_exam_types) - 1 ? 'submit-exam-form' : null }}" data-id="{{$q->examinee_answer_id}}">Next</button>
               @endif
            @endif
         </div>
      </div>
   </fieldset>
@endforeach

<style type="text/css">
   #opt label{
      font-size: 14pt;
   }
</style>