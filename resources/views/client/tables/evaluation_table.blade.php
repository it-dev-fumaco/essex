<table class="table" id="evaluation-file-table">
  <thead>
    <tr>  
      <th class="text-center">Employee Name</th>  
      <th class="text-center">Title</th>   
      <th class="text-center">Evaluation Date</th>
      <th class="text-center">Evaluated by</th>
      <th class="text-center">Actions</th>
    </tr>
  </thead>
  <tbody>
    @forelse($files as $eval_files)
    <tr>
      <td class="text-center" style="width: 20%;">{{ $eval_files->employee_name }}</td>
      <td class="text-center" style="width: 25%;">{{ $eval_files->title }}</td>
      <td class="text-center" style="width: 20%;">{{ $eval_files->evaluation_date }}</td>
      <td class="text-center" style="width: 20%;">{{ $eval_files->evaluated_by }}</td>
      <td class="text-center" style="width: 15%;">
        <a href="{{ asset('storage/uploads/evaluations/'.$eval_files->evaluation_file) }}" target="_blank"><i class="fa fa-search"></i></a>
        @if(in_array($designation, ['Human Resources Head', 'Director of Operations', 'President']))
         | <a href="#" id="edit-evaluation-file-btn" data-id="{{ $eval_files->id }}" data-modifieddate="{{ $eval_files->updated_at }}" data-modifiedname="{{ $eval_files->last_modified_by }}" data-title="{{ $eval_files->title }}" data-employee="{{ $eval_files->employee_id }}" data-eval-date="{{ $eval_files->evaluation_date }}" data-file="{{ $eval_files->evaluation_file }}" data-remarks="{{ $eval_files->remarks }}"><i class="fa fa-pencil"></i></a> | 
        <a href="#" id="delete-evaluation-file-btn" data-id="{{ $eval_files->id }}" data-title="{{ $eval_files->title }}" data-employee="{{ $eval_files->employee_name }}"><i class="fa fa-trash"></i></a>
        @endif
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="5" class="text-center text-muted text-uppercase p-2">No Evaluation(s) Found.</td>
    </tr>
    @endforelse
  </tbody>
</table>
<center>
  <div id="evaluation-files-pagination">
  {{ $files->links() }}
</div></center>