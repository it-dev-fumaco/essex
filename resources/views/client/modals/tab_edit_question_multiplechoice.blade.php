<div class="modal fade" id="editMultipleChoice{{$question->question_id}}">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Multiple Choice</h4>
         </div>
         <div class="modal-body">
            <form method='post' action="/tabUpdateQuestion" autocomplete="off" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="question_id" value="{{ $question->question_id }}">
               <div class="row" style="margin: 7px;">
                  <div class="form-group col-md-6" style="margin-top: -30px;">
                     <label>Exam</label>
                     <input type="text" value="{{ $exam->exam_title }}" readonly>
                     <input type="hidden" name="exam_id" value="{{ $exam->exam_id }}">
                  </div>
                  <div class="form-group col-md-6" style="margin-top: -30px;">
                     <label>Exam Type</label>
                     @foreach($examtypes as $examtype)
                        @if($examtype->exam_type_id == 4)
                           <div class="form-group">
                              <input type="text" name="exam_type" value="{{$examtype->exam_type}}" readonly>
                              <input type="hidden" name="exam_type_id" value="{{$examtype->exam_type_id}}">
                           </div>
                        @endif
                     @endforeach
                  </div>
                  <br>
                  <div class="col-md-12">
                     <input type="file" name="qimage[]" id="qimage" multiple>
                  </div>

                  @if($question->question_img)
                     @php($parts = explode(',',$question->question_img))

                     @foreach($parts as $part) @php($part = \App\Support\QuestionImage::url($part))
                        <div class="col-md-3">
                           <img src="{{$part}}">
                        </div>
                     @endforeach
                  @endif
                  <br>
                  <div class="form-group col-md-12">
                     <label>Question</label>
                     <textarea name="questions" class="form-control ckeditor" rows="1" required>{{ $question->questions }}</textarea>
                  </div>
                  <div class="form-group col-md-4">
                     <label>Option 1</label> <br>
                     @if($question->option1_img)
                        <img class="lbl" src="{{$question->option1_img}}" style="height: 80px">
                     @endif
                     <input type="text" name="option1" placeholder="Option 1" value="{{ $question->option1 }}">
                     <input class="lbl form-control" type="file" name="option1_img" id="option1_img">
                  </div>
                  <div class="form-group col-md-4">
                     <label>Option 2</label> <br>
                     @if($question->option2_img)
                        <img class="lbl" src="{{$question->option2_img}}" style="height: 80px">
                     @endif
                     <input type="text" name="option2" placeholder="Option 2" value="{{ $question->option2 }}">
                     <input class="lbl form-control" type="file" name="option2_img" id="option2_img">
                  </div>
                  <div class="form-group col-md-4">
                     <label>Option 3</label> <br>
                     @if($question->option3_img)
                        <img class="lbl" src="{{$question->option3_img}}" style="height: 80px">
                     @endif
                     <input type="text" name="option3" placeholder="Option 3" value="{{ $question->option3 }}">
                     <input class="lbl form-control" type="file" name="option3_img" id="option3_img">
                  </div>
                  <div class="form-group col-md-4">
                     <label>Option 4</label> <br>
                     @if($question->option4_img)
                        <img class="lbl" src="{{$question->option4_img}}" style="height: 80px">
                     @endif
                     <input type="text" name="option4" placeholder="Option 4" value="{{ $question->option4 }}">
                     <input class="lbl form-control" type="file" name="option4_img" id="option4_img">
                  </div>
                  <div class="form-group col-md-8">
                     <label>Correct Answer</label>
                     <input type="text" name="correct_answer" placeholder="Enter Correct Answer" value="{{ $question->correct_answer }}">
                  </div>
                  <div style="font-size: 8pt; padding-right: 5%;float: right;">
                 <i>Last modified: <b>{{ $question->updated_at }} </b> -{{ $question->last_modified_by }} </i>
                 </div>
               </div>
               <div class="modal-footer" style="margin-top: -20px;">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>