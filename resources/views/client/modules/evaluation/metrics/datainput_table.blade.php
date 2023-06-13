<table class="table">
   <tr>
      <th>No.</th>
      <th class="text-center">Data Input list</th>
      <th class="text-start">Result</th>
      <th class="text-center">Month</th>
   </tr>
      @forelse($data as $kpi =>$datainputs)
      <tr style="background-color: #F7F7F7;">
         <td colspan="4"><b>{{$kpi}}</b></td>
      </tr>
      <?php $i = 1; ?>
               @foreach($datainputs as $row)
            <tr>
               <td>{{$i++}}</td>
                  <td>{{ $row->data_input }}</td>
                  <td>{{ $row->answer }}</td>
                  <td class="text-center">
                  @if($row->month == 1) January
                  @elseif($row->month == 2) February
                  @elseif($row->month == 3) March
                  @elseif($row->month == 4) April
                  @elseif($row->month == 5) May
                  @elseif($row->month == 6) June
                  @elseif($row->month == 7) July
                  @elseif($row->month == 8) August
                  @elseif($row->month == 9) September
                  @elseif($row->month == 10) October
                  @elseif($row->month == 11) November
                  @elseif($row->month == 12) December
                  @else 
                  @endif
               </td> 
            </tr>
            
            @endforeach
            @empty
<tr>
   <td colspan="4" class="p-2 text-center text-muted text-uppercase">No Records Found</td>
</tr>
            @endforelse
               </table>

<center>
  <div id="datainput_pagination">
   {{ $data->links() }}
  </div>
</center>
<style type="text/css">
.colorbackground{
  background-color: #ec7063;
}
</style>

