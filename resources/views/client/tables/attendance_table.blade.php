<table class="table">
   <thead>
      <tr>  
         <th class="text-center">Date</th>  
         <th class="text-center">DOW</th>    
         <th class="text-center">Time In</th>  
         <th class="text-center">Time Out</th>   
         <th class="text-center">Hrs Worked</th>  
         <th class="text-center">Overtime</th>  
         <th class="text-center">Status</th> 
      </tr>
   </thead>
   <tbody>
      @forelse($logs as $row)
      <tr class="{{ $row['attendance_status'] == 'Unfiled Absence' ? 'colorbackground' : '' }}">
         <td class="text-center">{{ $row['transaction_date'] }}</td>
         <td class="text-center">{{ $row['day_of_week'] }}</td>
         @if ($row['attendance_status'] == 'Unfiled Absence')
            <td class="text-center" colspan=2>
               <b>{{ $row['attendance_status'] }}</b>
            </td>
         @else
            <td class="text-center">
               @if($row['time_in'])
               <span class="badge bg-{{ $row['time_in_status'] === 'late' ? 'danger' : 'success'}}" style="font-size: 9pt;">
                  {{ $row['time_in'] }}
               </span>
               @endif
            </td>
            <td class="text-center">{{ $row['time_out'] }}</td>   
         @endif
         <td class="text-center">{{ $row['hrs_worked'] }}</td>
         <td class="text-center">{{ $row['overtime'] }}</td>
         <td class="text-center"><b>{{ $row['attendance_status'] == 'Unfiled Absence' ? 'Absent' : $row['attendance_status'] }}</b></td>
      </tr>
      @empty
      <tr>
         <td class="text-center" colspan="8">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>

<center>
  <div id="attendance_pagination">
   {{ $logs->links() }}
  </div>
</center>
<hr>
<div class="container-fluid">
   <div class="row">
      <div class="col-12 p-0 mb-2">
         <h3>Summary</h3>
      </div>
      <div class="col-6 p-0">
         <div class="d-flex justify-content-between">
            Duration: <b>{{ Carbon\Carbon::parse($start)->format('M d, Y').' - '.Carbon\Carbon::parse($end)->format('M d, Y') }}</b>
         </div>
         <div class="d-flex justify-content-between">
            Total Working Day(s): <b>{{ $no_of_days }} day(s)</b>
         </div>
         <div class="d-flex justify-content-between">
            Total Late(s): <b>{{ $total_late }} min(s)</b>
         </div>
      </div>
      <div class="col-6">
         <div class="d-flex justify-content-between">
            Required Working Hour(s): <b>{{ $required_working_hrs }} hour(s)</b>
         </div>
         <div class="d-flex justify-content-between">
            Total hour(s) worked: <b>{{ $total_hours_worked }} hour(s)</b>
         </div>
         <div class="d-flex justify-content-between">
            Overtime Hour(s): <b>{{ $total_overtime }} hour(s)</b>
         </div>
      </div>
   </div>
</div>

<style type="text/css">
   .colorbackground{
      background-color: #ec7063;
   }

   .page-link{
      line-height: 20px !important;
      height: auto !important;
      min-width: auto !important;
   }
</style>