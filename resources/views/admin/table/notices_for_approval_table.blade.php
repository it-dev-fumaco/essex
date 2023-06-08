

<table class="table">
   <thead>
      <tr>
         <th>ID</th>
         <th>Employee Name</th>
         <th>Department</th>
         <th>Type</th>
         <th>From - To</th>
         <th>Reason</th>
         <th>Actions</th>
      </tr>
   </thead>
   <tbody class="table-body">
      @forelse($pending_notices as $pending_notice)
      <tr>
         <td>{{ $pending_notice->notice_id }}</td>
         <td>{{ $pending_notice->employee_name }}</td>
         <td>{{ $pending_notice->department }}</td>
         <td>{{ $pending_notice->leave_type }}</td>
         <td>{{ $pending_notice->date_from }} - {{ $pending_notice->date_to }}</td>
         <td>{{ $pending_notice->reason }}</td>
         <td><a href="#" data-toggle="modal" data-target="#editEmployee{{ $pending_notice->notice_id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></td>
      </tr>
      {{-- @include('admin.modals.employee_actions') --}}
      @empty
      <tr>
         <td colspan="4">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>
