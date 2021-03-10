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
      @forelse($dexterity3 as $question)
      <tr>
         <td>{{ $question->question_id }}</td>
         <td>{!! $question->questions !!}
            @if($question->question_img)
               @php($parts = explode(",",$question->question_img)) 
                  @foreach($parts as $part) 
                     @php($part = '/storage/questions/'.$part)
                     <br><img src="{{$part}}">
                  @endforeach
            @endif
         </td>
         <td>{{ $question->correct_answer }}</td>
         <td>
            <a href="#" class="hover-icon" data-toggle="modal" data-target="#editDexterity2{{$question->question_id}}"><i class="fa fa-pencil" style="font-size: 15pt; color: #27AE60;"></i></a>
            <a href="#" class="hover-icon" data-toggle="modal" data-target="#deleteDexterity2{{$question->question_id}}"><i class="fa fa-trash" style="font-size: 15pt; color: #C0392B;"></i></a>
         </td>
      </tr>
      @include('client.modules.human_resource.applicant_exams.modals.delete_dexterity2')
      @include('client.modules.human_resource.applicant_exams.modals.edit_dexterity2')
      @empty
      <tr>
         <td colspan="4">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>

Total: {{count($dexterity2)}}
