<div class="table-responsive-wrapper">
   <table class="table fixed_header">
      <thead>
         <tr>  
            <th style="text-align: center;">Date</th>
            <th style="text-align: center;">DOW</th>
            <th style="text-align: center;">Time In</th>  
            <th style="text-align: center;">Time Out</th>   
            <th style="text-align: center;">Hrs Worked</th>  
            <th style="text-align: center;">Overtime</th>  
            <th style="text-align: center;">Status</th>  
         </tr>
      </thead>
      <tbody>
         @forelse($employee_logs as $row)
         <tr class="{{ $row['attendance_status'] == 'Unfiled Absence' ? 'colorbackground' : '' }}">
            <td style="text-align: center;">{{ $row['transaction_date'] }}</td>
            <td style="text-align: center;">{{ $row['day_of_week'] }}</td>
            <td style="text-align: center;">
               @if($row['time_in'])
             <span class="label label-{{ $row['time_in_status'] === 'late' ? 'danger' : 'success'}}" style="font-size: 9pt;">
               {{ \Carbon\Carbon::parse($row['time_in'])->format('H:i:s') }}
             </span>
             @endif
             </td>
            <td style="text-align: center;">{{ $row['time_out'] ? \Carbon\Carbon::parse($row['time_out'])->format('H:i:s') : '' }}</td>
            <td style="text-align: center;">{{ $row['hrs_worked'] }}</td>
            <td style="text-align: center;">{{ $row['overtime'] }}</td>
            <td style="text-align: center;"><b>{{ $row['attendance_status'] }}</b></td>
         </tr>
         @empty
         <tr>
            <td style="text-align: center; width: 865px;"><h3>No Records Found.</h3></td>
         </tr>
         @endforelse
      </tbody>
   </table>
</div>


<div class="col-md-12">
   <fieldset>
      <legend>Summary</legend>
      <div class="col-sm-6" class="summary-span">Duration: <span class="duration">
        <strong>{{ $summary_details['date_from'] }} — {{ $summary_details['date_to'] }}</strong></span></div>
      <div class="col-sm-6" class="summary-span">
        Overtime Hour(s): <span class="result">{{ collect($employee_logs)->sum('overtime') }}</span>
      </div>
      <div class="col-sm-6" class="summary-span">
        Total Working Day(s): <span class="result">{{ $summary_details['working_days'] }}</span>
      </div>
      <div class="col-sm-6" class="summary-span">
        Total Hour(s) worked: <span class="result">{{ collect($employee_logs)->sum('hrs_worked') }}</span>
      </div>
      <div class="col-sm-6" class="summary-span">
        Required Working Hour(s): <span class="result">{{ $summary_details['reqHrs'] }}</span>
      </div>
      <div class="col-sm-6" class="summary-span">
        Total Late(s): <span class="result">{{ collect($employee_logs)->sum('late_in_minutes') }}</span> <strong>min(s)</strong>
      </div>
   </fieldset>
</div>

<style type="text/css">
   .fixed_header{
      width: 100%;
      border-collapse: collapse;
   }

   .fixed_header th,
   .fixed_header td {
      white-space: normal;
   }

   .duration {
   /*font-size: 12pt;*/
   vertical-align:middle;
   padding-left: 5px;
}

.result{
   vertical-align:middle;
   padding-left: 5px;
   }

.colorbackground{
  background-color: #ec7063;
}

.summary-span{
   padding: 8px 8px 8px 20px;
}
</style>
