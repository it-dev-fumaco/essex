<table class="table table-striped">
   <col width="3%">
   <col width="15%">
   <col width="5%">
   <col width="5%">
   <col width="5%">
   <col width="5%">
   <col width="8%">
   <col width="4%">
   <thead>
      <tr>
         <th>ID</th>
         <th>Question</th>
         <th>Option 1</th>
         <th>Option 2</th>
         <th>Option 3</th>
         <th>Option 4</th>
         <th>Correct Answer</th>
         <th>Actions</th>
      </tr>
   </thead>
   <tbody class="table-body">
      @forelse($multipleChoice as $question)
      <tr>
         <td>{{ $question->question_id }}</td>
         <td><span style="color:black">{!! $question->questions !!}</span><br>@if($question->question_img)@php($parts = explode(",",$question->question_img)) @foreach($parts as $part) @php($part = \App\Support\QuestionImage::url($part))<div class="col-md-4"><img src="{{$part}}"></div>@endforeach @endif</td>
         <td>{!! $question->option1 !!} <br>@if($question->option1_img) @php($question->option1_img = '/storage/options/'.$question->option1_img) <div class="col-md-12"><img src="{{$question->option1_img}}"></div>@endif</td>
         <td>{!! $question->option2 !!} <br>@if($question->option2_img) @php($question->option2_img = '/storage/options/'.$question->option2_img) <div class="col-md-12"><img src="{{$question->option2_img}}"></div>@endif</td>
         <td>{!! $question->option3 !!} <br>@if($question->option3_img) @php($question->option3_img = '/storage/options/'.$question->option3_img) <div class="col-md-12"><img src="{{$question->option3_img}}"></div>@endif</td>
         <td>{!! $question->option4 !!} <br>@if($question->option4_img) @php($question->option4_img = '/storage/options/'.$question->option4_img) <div class="col-md-12"><img src="{{$question->option4_img}}"></div>@endif</td>
         <td>{!! $question->correct_answer !!}</td>
         <td>
            <a href="#" class="hover-icon" data-toggle="modal" data-target="#editMultipleChoice{{$question->question_id}}"><i class="fa fa-pencil" style="font-size: 15pt; color: #27AE60;"></i></a>
            <a href="#" class="hover-icon" data-toggle="modal" data-target="#deleteMultipleChoice{{$question->question_id}}"><i class="fa fa-trash" style="font-size: 15pt; color: #C0392B;"></i></a>
         </td>
      </tr>
      @include('client.modals.tab_edit_question_multiplechoice')
      @include('client.modals.tab_delete_question_multiplechoice')
      @empty
      <tr>
         <td colspan="10">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>

Total: {{count($multipleChoice)}}