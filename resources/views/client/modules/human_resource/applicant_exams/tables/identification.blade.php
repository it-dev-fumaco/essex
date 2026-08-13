<table class="table table-striped">
   <thead>
      <tr>
         <th>ID</th>
         <th>Question</th>
         <th>Correct Answer</th>
         <th>Actions</th>
      </tr>
   </thead>
   <tbody class="table-body">
      @forelse($identification as $question)
      <tr>
         <td>{{ $question->question_id }}</td>
         <td>{!! $question->questions !!} 
            @if($question->question_img)
               @php($parts = explode(",",$question->question_img))
                  @foreach($parts as $part)
                     @php($part = \App\Support\QuestionImage::url($part))
                     <br><img src="{{$part}}" style="width: 55%;">
                  @endforeach
            @endif
         </td>
         <td>{{ $question->correct_answer }}</td>
         <td>
            <a href="#" class="hover-icon" data-toggle="modal" data-target="#editIdentification{{$question->question_id}}"><i class="fa fa-pencil" style="font-size: 15pt; color: #27AE60;"></i></a>
            <a href="#" class="hover-icon" data-toggle="modal" data-target="#deleteIdentification{{$question->question_id}}"><i class="fa fa-trash" style="font-size: 15pt; color: #C0392B;"></i></a>
         </td>
      </tr>
      @include('client.modules.human_resource.applicant_exams.modals.delete_identification')
      @include('client.modules.human_resource.applicant_exams.modals.edit_identification')
      @empty
      <tr>
         <td colspan="4">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>

Total: {{count($identification)}}