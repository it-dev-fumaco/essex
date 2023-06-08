
<table class="table">
   <thead>
      <tr>
         <th>Date</th>
         <th>Time In</th>
         <th>Time Out</th>
         <th>Remarks</th>
         <th>Actions</th>
      </tr>
   </thead>
   <tbody class="table-body">
      @forelse($shifts as $shift)
      <tr>
         <td>{{ $shift->shift_schedule }}</td>
         <td>{{ $shift->time_in }}</td>
         <td>{{ $shift->time_out }}</td>
         <td>{{ $shift->remarks }}</td>
         <td>
            <a href="#" data-toggle="modal" data-target="#editShift{{ $shift->shift_id }}"><i class="fa fa-pencil"></i> Edit</a> | 
            <a href="#" data-toggle="modal" data-target="#deleteShift{{ $shift->shift_id }}"><i class="fa fa-trash"></i> Delete</a>
         </td>
      </tr>
      @include('admin.modals.shift_actions')
      @empty
      <tr>
         <td colspan="5">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>
