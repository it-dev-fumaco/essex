<table class="table table-striped">
   <col width="3%">
   <col width="40%">
   <col width="10%">
   <thead>
      <tr>
         <th>ID</th>
         <th>Question</th>
         <th>Actions</th>
      </tr>
   </thead>
   <tbody class="table-body">
      @forelse($essay as $question)
      <tr>
         <td>{{ $question->question_id }}</td>
         <td>{!! $question->questions !!} 
            @if($question->question_img)
               @php($parts = explode(",",$question->question_img))
               @foreach($parts as $part)
                  @php($part = \App\Support\QuestionImage::url($part))
                  <br><img src="{{$part}}">
               @endforeach
            @endif
         </td>
         <td>
            <a href="#" class="hover-icon" data-toggle="modal" data-target="#editEssay{{$question->question_id}}"><i class="fa fa-pencil" style="font-size: 15pt; color: #27AE60;"></i></a>
            <a href="#" class="hover-icon" data-toggle="modal" data-target="#deleteEssay{{$question->question_id}}"><i class="fa fa-trash" style="font-size: 15pt; color: #C0392B;"></i></a>
         </td>
      </tr>
      @include('client.modules.human_resource.applicant_exams.modals.edit_essay')
      @include('client.modules.human_resource.applicant_exams.modals.delete_essay')
      @empty
      <tr>
         <td colspan="4">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>

Total: {{count($essay)}}