<div class="col-sm-12 m-0">
   <form id="submitform">
      @csrf
      <input type="hidden" name="eval_period" id="eval-period">
      <div class="row m-0 px-2">
         <div class="col-7">
            <div class="d-flex flex-row">
               <label class="col-4">Schedule</label>
               <select class="col-8" name="schedule_date" id="schedule_date" onchange="show_schedule_date()" required>
                  @forelse($scheduless as $i => $row)
                  @if($eval_type == 'Monthly')
                  <option value="{{ date('F d, Y', strtotime($row['scheduled_date'])) }}">{{ date('F, Y', strtotime('-1 months', strtotime($row['scheduled_date']))) }}</option>
                  @elseif($eval_type == 'Quarterly')
                  <option value="{{ date('F d, Y', strtotime($row['scheduled_date'])) }}">{{ date('F, Y', strtotime('-1 months', strtotime($row['scheduled_date']))) }}</option>
                  @elseif($eval_type == 'Semi-Annual')
                  <option value="{{ date('F d, Y', strtotime($row['scheduled_date'])) }}">{{ date('F, Y', strtotime('-1 months', strtotime($row['scheduled_date']))) }}</option>
                  @elseif($eval_type == 'Annual')
                  <option value="{{ date('F d, Y', strtotime($row['scheduled_date'])) }}">{{ date('Y', strtotime('-1 years', strtotime($row['scheduled_date']))) }}</option>
                  @endif
                  @empty
                  <option>No records found.</option>
                  @endforelse
               </select>
            </div>
         </div>
      </div>
      <div class="mt-2">
         <input type="hidden" name="entry_val" id="entry_val">
         <input type="hidden" name="user_id" id="user_id">
         <input type="hidden" name="depart_id" id="depart_id">
         <table class="table m-0">
            <tr>
               <th class="text-center">No.</th>
               <th class="text-center">Data Input list</th>
               <th class="text-center">Result</th>
            </tr>
            @forelse($datainput as $i => $kpi)
            <tr style="background-color: #A9DFBF;">
               <td colspan="2"><b>KPI:{{ $kpi['kpi_description'] }}</b></td>
               <td align="center"><input type="text" class="resultkpi" name="answerkpi[]" placeholder="Result" style="line-height: 1.5; display: @if($kpi['set_manual'] == 0 || $kpi['set_manual'] == '') none; @endif ">
               </td>
               <input type="hidden" name="kpiID[]" value="{{ $kpi['kpi_id'] }}">
               <input type="hidden" name="kpi_target[]" value="{{ $kpi['kpi_target'] }}"> 
               <input type="hidden" name="kpi_weight[]" value="{{ $kpi['kpi_weight'] }}">
               <input style="display: @if($kpi['set_manual'] == 0 || $kpi['set_manual'] == '')none; @endif" type="hidden" name="set_manual[]" value="{{ $kpi['set_manual'] }}">
            </tr>
        <?php $i = 1; ?>
        @if(count($kpi['nodes']) > 0)
        @foreach($kpi['nodes'] as $index_name)
        <tr>
        <td colspan="2" style="text-indent: 35px;"><i class="fa fa-angle-double-right"></i><b>{{ $index_name['metric_name'] }}</b></td>
        </tr>
        @if(count($index_name['nodess']) > 0)
        @foreach($index_name['nodess'] as $metric)
        <tr>
        <td style="text-align: right;">{{$i++}}.</td>
        <td style="padding-left: 50px;">
        {{ $metric->data_input }}
        </td>

        <input type="hidden" name="input_id[]" value="{{ $metric->input_id }}">
        <td align="center"><input type="text" name="answer[]" 
        placeholder="Result" style=" line-height: 1.5;" required></td>
        </tr>
        @endforeach
        @endif
        @endforeach
        @endif
        @empty
        <tr>
        <td class="text-center text-uppercase text-muted" colspan="10">No Data found</td>
        </tr>
        @endforelse
        </table>

  </div>
  <br>
  <input type="hidden" name="evaluation_id" value="{{ Auth::user()->user_id }}">
  <input type="hidden" name="evaluation_name" value="{{ Auth::user()->employee_name }}">

  <br>
  @if(empty($datainput))

  @else
  <div class="col-md-12">
  <center><button type="submit" class="btn btn-primary" id="submitbutton" name="submitbutton"><i class="fa fa-check"></i> Submit</button></center>
  </div>
  @endif
  </form>

</div>

