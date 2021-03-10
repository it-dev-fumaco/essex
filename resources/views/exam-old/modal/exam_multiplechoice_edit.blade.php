<!-- The Modal -->
<div class="modal fade" id="editMultipleChoice{{$multiplechoice->question_id}}">
   <div class="modal-dialog modal-lg" style="width: 50%;">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Question</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <form method='post' action="{{route('admin.question_update')}}">
                  @csrf
                  
               <div class="col-sm-12">
               <input type="text" name="question_id" id="question_id" value="{{$multiplechoice->question_id}}" hidden="true">
                  <div class="form-group">
                     <label>Exam</label>
                     <input type="text" value="{{$exam->exam_title}}" class="form-control" readonly>
                     <input type="text" name="exam_id" id="exam_id" value="{{$exam->exam_id}}" hidden>
                  </div>
                  <div class="form-group">
                     <label>Exam Type</label>
                     @foreach($examtypes as $examtype)
                        @if($examtype->exam_type == 'Multiple Choice')
                           <div class="form-group">
                              <input type="text" value="{{$examtype->exam_type}}" class="form-control" readonly>
                              <input type="text" name="exam_type_id" id="exam_type_id" value="{{$examtype->exam_type_id}}" hidden>
                           </div>
                        @endif
                     @endforeach
                  </div>
                  <div class="form-group">
                     <label>Question</label>
                     <textarea id="ckeditor" name="questions" class="form-control ckeditor" rows="1" required>{{$multiplechoice->questions}}</textarea>
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Option 1</label>
                     <input type="text" class="form-control" name="option1" id="option1" placeholder="Option 1" value="{{$multiplechoice->option1}}">
                  </div>
                  <div class="form-group">
                     <label>Option 2</label>
                     <input type="text" class="form-control" name="option2" id="option2" placeholder="Option 2" value="{{$multiplechoice->option2}}">
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group">
                     <label>Option 3</label>
                     <input type="text" class="form-control" name="option3" id="option3" placeholder="Option 3" value="{{$multiplechoice->option3}}">
                  </div>
                  <div class="form-group">
                     <label>Option 4</label>
                     <input type="text" class="form-control" name="option4" id="option4" placeholder="Option 4" value="{{$multiplechoice->option4}}">
                  </div>
               </div>
               <div class="col-sm-12">
                  <div class="form-group">
                     <label>Correct Answer</label>
                     <input type="text" class="form-control" name="correct_answer" id="correct_answer" placeholder="Enter Correct Answer" value="{{$multiplechoice->correct_answer}}">
                  </div>

               </div>
               <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="updateQuestion"><i class="fa fa-check"></i> Update</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                </div>
               </form>
            </div>
         </div>
         <!-- Modal footer -->
      </div>
   </div>
</div>