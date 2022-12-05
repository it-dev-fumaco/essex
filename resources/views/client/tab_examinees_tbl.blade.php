<table class="table" id="example" style="font-size: 11pt;">
   <thead>
      <tr>
         <th class="text-center">No.</th>
         <th>Examinee</th>
         <th class="text-center">User Type</th>
         <th class="text-center">Exam Code</th>
         <th>Exam Title</th>
         <th class="text-center">Exam Date</th>
         <th class="text-center">Start - End</th>
         <th class="text-center">Duration</th>
         <th class="text-center">Time Remaining</th>
         <th class="text-center">Valid Until</th>
         <th class="text-center">Action</th>
      </tr>
   </thead>
   <tbody class="table-body">
      @forelse($examinees as $index => $examinee)
      @php 
         $from = \Carbon\Carbon::createFromFormat('H:i', date('h:i',strtotime($examinee->start_time)));
         $to = \Carbon\Carbon::createFromFormat('H:i', date('h:i',strtotime($examinee->end_time)));
         $row_class = '';
         if ($examinee->status == 'On Going') {
            $status_color = '#7DCEA0';
            $row_class = 'blink';
         }elseif ($examinee->status == 'Not Started') {
            $status_color = '#EC7063';
         }else{
            $status_color = '';
         }

         $diff_in_hours = $to->diffInMinutes($from);
      @endphp
      <tr style="background-color: {{ $status_color }}" class="{{ $row_class }}">
         <td class="text-center">{{ $index + 1 }}</td>
         <td>{{ $examinee->employee_name }}</td>
         <td class="text-center">{{ $examinee->user_type }}</td>
         <td class="text-center">{{ $examinee->exam_code }}</td>
         <td>{{ $examinee->exam_title }}</td>
         <td class="text-center">{{ date('m-d-Y', strtotime($examinee->date_of_exam)) }}</td>
         @if($examinee->status == 'Completed')
            <td class="text-center">{{ date('h:i A',strtotime($examinee->start_time)) }} - {{ date('h:i A',strtotime($examinee->end_time)) }}</td>
         @else
            <td class="text-center">{{ $examinee->status }}</td>
         @endif
         <td class="text-center">{{ ($examinee->status == 'Completed') ? $diff_in_hours . 'min(s)' : '–' }}</td>
         <td class="text-center">{{ $examinee->remaining_time }}</td>
         @if(date('m-d-Y') <= date('m-d-Y',strtotime($examinee->validity_date)))
            <td class="text-center">{{ date('m-d-Y', strtotime($examinee->validity_date)) }}</td>
         @else
            @if($examinee->start_time)
               <td class="text-center">{{ date('m-d-Y', strtotime($examinee->validity_date)) }}</td>
            @else
               <td class="text-center">Validity Expired</td>
            @endif
         @endif
         <td>
            <button class="btn btn-sm btn-danger cancel-exam-btn" data-examinee-id="{{ $examinee->examinee_id }}" style="padding: 5px !important">Cancel</button>
         </td>
      </tr>
      @empty
         <tr>
            <td colspan=11 class="text-center">
               No on-going exam(s)
            </td>
         </tr>
      @endforelse
  </tbody>
</table>