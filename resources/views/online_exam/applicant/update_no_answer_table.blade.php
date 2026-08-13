      <form id="form_to_submit">
         @csrf
         <input type="hidden" name="remaining_time" class="update_time-remaining">
         <input type="hidden" name="spent_time" class="update_time-spent">
         <input type="hidden" name="examinee_answer_id" value="{{ $questions->examinee_answer_id }}">
         <input type="hidden" name="q_no" value="{{ $questions->q_no }}">
         <input type="hidden" name="examinee_id" value="{{ $examinee_id }}">
         <input type="hidden" name="exam_id" value="{{ $questions->exam_id }}">
         <input type="hidden" name="exam_type_id" value="{{ $questions->exam_type_id }}">
         <input type="hidden" name="question_id" value="{{ $questions->question_id }}">
      <div class="row">
         <label style="display: block;">{!! $exam_type_details->exam_type !!}</label>
         <label style="font-size: 10pt;display: block;">{!! $exam_type_details->instruction !!}</label>

         <label style="display: block;text-align: center;line-height: 150%; padding-top: 30px;font-size: 15pt;">Question no.{{$questions->q_no}}</label>
         {{-- Multiple Choice --}}
         @if($questions->exam_type_id == 4)
         <div class="col-md-12 text-center mt-5" style="padding: 10px 0;">
            <span style="font-size: 15pt;">{!! $questions->questions !!}</span>
         </div>
         <div class="col-md-8 col-md-offset-2" id="opt" style="padding: 10px 0;">
            <div class="col-md-12">
               <!-- Default inline 1-->
               <div class="custom-control custom-radio">
                 <input type="radio" class="custom-control-input" id="opt1" name="examinee_answer" value="{{ $questions->option1 }}">
                 <label class="custom-control-label" for="opt1">{{ $questions->option1 }}</label>
               </div>
            </div>
            <div class="col-md-12">
               <!-- Default inline 2-->
               <div class="custom-control custom-radio">
                 <input type="radio" class="custom-control-input" id="opt2" name="examinee_answer" value="{{ $questions->option2 }}">
                 <label class="custom-control-label" for="opt2">{{ $questions->option2 }}</label>
               </div>
            </div>
            <div class="col-md-12">
               <!-- Default inline 3-->
               <div class="custom-control custom-radio">
                 <input type="radio" class="custom-control-input" id="opt3" name="examinee_answer" value="{{ $questions->option3 }}">
                 <label class="custom-control-label" for="opt3">{{ $questions->option3 }}</label>
               </div>
            </div>
            <div class="col-md-12">
               <!-- Default inline 4-->
               <div class="custom-control custom-radio">
                 <input type="radio" class="custom-control-input" id="opt4" name="examinee_answer" value="{{ $questions->option4 }}">
                 <label class="custom-control-label" for="opt4">{{ $questions->option4 }}</label>
               </div>
            </div>
         </div>
         @endif

         {{-- Essay --}}
         @if($questions->exam_type_id == 5)
         <div class="col-md-12 text-center mt-5" style="padding: 10px 0;">
            <span style="font-size: 15pt;">{!! $questions->questions !!}</span>
         </div>
         <div class="col-md-8 col-md-offset-2" style="padding: 10px 0;">
            <textarea class="form-control" rows="5" name="examinee_answer" placeholder="Start typing.."></textarea>
         </div>
         @endif

         {{-- True or False --}}
         @if($questions->exam_type_id == 7)
         <div class="col-md-12 text-center mt-5" style="padding: 10px 0;">
            <span style="font-size: 15pt;">{!! $questions->questions !!}</span>
         </div>
         <div class="col-md-6 col-md-offset-4" style="padding: 10px 0;">
            <div class="row" id="opt">
               <div class="col-md-6">
                  <!-- Default inline 1-->
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="tf1" name="examinee_answer" value="True">
                    <label class="custom-control-label" for="tf1">True</label>
                  </div>
               </div>
               <div class="col-md-6">
                  <!-- Default inline 2-->
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="tf2" name="examinee_answer" value="False">
                    <label class="custom-control-label" for="tf2">False</label>
                  </div>
               </div>
            </div>
         </div>
         @endif

         {{-- Abstract Reasoning --}}
         @if($questions->exam_type_id == 13)
         <div class="col-md-12 text-center mt-2" style="padding: 10px 0;">
            @php($parts = explode(",",$questions->question_img)) 
            @foreach($parts as $part) 
               @php($part = \App\Support\QuestionImage::url($part))
               <img src="{{$part}}" width="450" height="120">
            @endforeach
         </div>
         <div class="col-md-12 text-center" style="padding: 10px 0;">
            <center>
               <select class="form-control" name="examinee_answer" style="width: 25%;">
                  <option value=''>-- SELECT --</option>
                  <option value='1'>1</option>
                  <option value='2'>2</option>
                  <option value='3'>3</option>
                  <option value='4'>4</option>
                  <option value='5'>5</option>
               </select>
            </center>
         </div>
         @endif

         {{-- Identification --}}
         @if($questions->exam_type_id == 12)
         <div class="col-md-6 col-md-offset-3 text-center mt-5" style="padding: 10px 0;">
            @if(!$questions->questions)
            @php($parts = explode(",",$questions->question_img)) 
            @foreach($parts as $part) 
               @php($part = \App\Support\QuestionImage::url($part))
               <img src="{{$part}}" width="450" height="120">
            @endforeach
            @else
            <span style="font-size: 15pt;">{!! $questions->questions !!}</span>
            @endif
         </div>
         <div class="col-md-12 text-center" style="padding: 10px 0;">
               <center>
                  <textarea class="form-control" rows="3" placeholder="Start typing.." name="examinee_answer" style="width: 50%;"></textarea>
               </center>
            </div>
         @endif

         {{-- Numerical Exam --}}
         @if($questions->exam_type_id == 6)
         <div class="col-md-12 text-center mt-5" style="padding: 10px 0;">
            @if(!$questions->questions)
            @php($parts = explode(",",$questions->question_img)) 
            @foreach($parts as $part) 
               @php($part = \App\Support\QuestionImage::url($part))
               <img src="{{$part}}" style="width: 10%;">
            @endforeach
            @else
            <span style="font-size: 15pt;">{!! $questions->questions !!}</span>
            @endif
         </div>
         <div class="col-md-12 text-center" style="padding: 10px 0;">
            <center>
               <input type="text" name="examinee_answer" placeholder="Type your answer here.." class="form-control w-25" style="width: 25%;">
            </center>
         </div>
         @endif

         {{-- Dexterity & Accuracy Measures 1, 2, 3 --}}
         @if(in_array($questions->exam_type_id, [14, 15, 16]))
         <div class="col-md-12 text-center mt-5" style="padding: 10px 0;">
            @if(!$questions->questions)
            @php($parts = explode(",",$questions->question_img)) 
            @foreach($parts as $part) 
               @php($part = \App\Support\QuestionImage::url($part))
               <img src="{{$part}}" width="450" height="120">
            @endforeach
            @else
            <span style="font-size: 15pt;">{!! $questions->questions !!}</span>
            @endif
         </div>
         <div class="col-md-12 text-center" style="padding: 10px 0;">
            <center>
               <input type="text" name="examinee_answer" placeholder="Type your answer here.." class="form-control w-25" style="width: 25%;">
            </center>
         </div>
         @endif
      </div>
      </form>
      <div class="modal-footer">
         <button type="submit" class="btn btn-primary updateNoAnswer"  data-id="{{$questions->examinee_answer_id}}"><i class="fa fa-check"></i> SUBMIT</button>
         <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> CANCEL</button>
      </div>

