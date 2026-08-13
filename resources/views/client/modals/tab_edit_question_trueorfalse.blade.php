<div class="modal fade" id="editTrueFalse{{$question->question_id}}">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit True or False</h4>
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
                        @if($examtype->exam_type_id == 7)
                           <div class="form-group">
                              <input type="text" name="exam_type" value="{{ $examtype->exam_type }}" readonly>
                              <input type="hidden" name="exam_type_id" value="{{$examtype->exam_type_id}}">
                           </div>
                        @endif
                     @endforeach
                  </div>
                  <div class="form-group col-md-12">
                     <input type="file" name="qimage[]" id="qimage" multiple="true">
                  </div>
                  @if($question->question_img)
                     @php($parts = explode(',',$question->question_img))

                     @foreach($parts as $part) @php($part = \App\Support\QuestionImage::url($part))
                        <div class="col-md-3">
                           <img src="{{$part}}">
                        </div>
                     @endforeach
                  @endif
                  <div class="form-group col-md-12">
                     <label>Question</label>
                     <textarea name="questions" class="form-control ckeditor" rows="1" required>{{ $question->questions }}</textarea>
                  </div>
                  <div class="form-group col-md-4" hidden>
                     <label>Option 1</label>
                     <input type="text" name="option1" placeholder="Option 1">
                  </div>
                  <div class="form-group col-md-4" hidden>
                     <label>Option 2</label>
                     <input type="text" name="option2" placeholder="Option 2">
                  </div>
                  <div class="form-group col-md-4" hidden>
                     <label>Option 3</label>
                     <input type="text" name="option3" placeholder="Option 3">
                  </div>
                  <div class="form-group col-md-4" hidden>
                     <label>Option 4</label>
                     <input type="text" name="option4" placeholder="Option 4">
                  </div>
                  <div class="form-group col-md-12">
                     <label>Correct Answer</label>
                     <select name="correct_answer">
                        <option value="True" {{ $question->correct_answer== "True" ? "selected" : "" }}>True</option>
                        <option value="False" {{ $question->correct_answer== "False" ? "selected" : "" }}>False</option>
                     </select>
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