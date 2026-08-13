<div class="modal fade" id="deleteAbstract{{$question->question_id}}">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete Question</h4>
      </div>
      <div class="modal-body">
        <form method='post' action="/tabDeleteQuestion">
        @csrf
          <input type="hidden" name="question_id" id="question_id" value="{{$question->question_id}}">
          <input type="hidden" name="exam_id" id="exam_id" value="{{$question->exam_id}}">
          <div class="row" style="margin-top: -3%;">
            <div class="col-sm-12">
              <span style="font-size: 12pt;">Delete Abstract Reasoning @if($question->questions)<b>"{!! $question->questions !!}"</b> ? @endif
            @if($question->question_img)
               @php($parts = explode(",",$question->question_img))
                  @foreach($parts as $part)
                     @php($part = \App\Support\QuestionImage::url($part))
                     <br><img src="{{$part}}" style="width: 55%;">
                  @endforeach
            @endif
          </span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Delete</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>