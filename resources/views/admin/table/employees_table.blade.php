

<table class="table">
   <thead>
      <tr>
         <th>Access ID</th>
         <th>Name</th>
         <th>Designation</th>
         <th>Department</th>
         <th>Status</th>
         <th>Actions</th>
      </tr>
   </thead>
   <tbody class="table-body">
      @forelse($employees as $employee)
      <tr>
         <td>{{ $employee->user_id }}</td>
         <td>
            {{-- <img src="{{ asset('storage/img/user.png') }}" width="55" height="45" style="float: left; padding-right: 10px;">  --}}
            <span class="approver-name">{{ $employee->employee_name }}</span>
         </td>
         <td>{{ $employee->designation }}</td>
         <td>{{ $employee->department }}</td>
         <td>{{ $employee->status }}</td>
         <td><a href="#" data-id='{{ $employee->id }}' data-toggle="modal" data-target="#editEmployee{{ $employee->id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a> | <a href="#" data-id='{{ $employee->id }}' data-toggle="modal" data-target="#deleteEmployee{{ $employee->id }}"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></td>
      </tr>
      @include('admin.modals.employee_actions')
      @empty
      <tr>
         <td colspan="4">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>
