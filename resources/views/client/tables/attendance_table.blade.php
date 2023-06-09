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
         <td class="text-center">
            @if($row['time_in'])
          <span class="badge bg-{{ $row['time_in_status'] === 'late' ? 'danger' : 'success'}}" style="font-size: 9pt;">
            {{ $row['time_in'] }}
          </span>
          @endif
          </td>
         <td class="text-center">{{ $row['time_out'] }}</td>
         <td class="text-center">{{ $row['hrs_worked'] }}</td>
         <td class="text-center">{{ $row['overtime'] }}</td>
         <td class="text-center"><b>{{ $row['attendance_status'] }}</b></td>
      </tr>
      @empty
      <tr>
         <td colspan="8">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>

<style type="text/css">
.colorbackground{
  background-color: #ec7063;
}
</style>

<center>
  <div id="attendance_pagination">
   {{ $logs->links() }}
  </div>
</center>