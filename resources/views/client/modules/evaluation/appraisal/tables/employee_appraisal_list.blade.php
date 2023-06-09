<table class="table">
   <thead>
      <tr>
         <th>No.</th>
         <th>Purpose Type</th>
         <th>Evaluation Period</th>
         <th>Evaluation Date</th>
         <th>Evaluated by</th>
         <th>Status</th>
         <th>Actions</th>
      </tr>
   </thead>
   <tbody>
      @forelse($appraisal_list as $i => $row)
      <tr>
         <td>{{ $i + 1 }}</td>
         <td>{{ $row->purpose_type }}</td>
         <td>{{ date('F Y', strtotime($row->evaluation_period_from)) .' - '. date('F Y', strtotime($row->evaluation_period_to)) }}</td>
         <td>{{ $row->evaluation_date }}</td>
         <td>{{ $row->evaluated_by }}</td>
         <td>{{ $row->status }}</td>
         <td>
            <a href="/appraisal_result/{{ $row->appraisal_result_id }}/view"><i class="fa fa-search"></i></a>
         </td>
      </tr>
      @empty
      <tr>
         <td colspan="7" class="text-center text-uppercase text-muted">No record(s) found.</td>
      </tr>
      @endforelse
   </tbody>
</table>