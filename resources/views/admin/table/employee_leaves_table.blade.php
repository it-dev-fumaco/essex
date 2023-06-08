<table class="table" id="example">
            <thead>
              <tr>
                <th>ID</th>
                <th>Employee</th>
                <th>Leave Type</th>
                <th>Total</th>
                <th>Remaining</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-body">
              @forelse($employee_leaves as $employee_leave)
              <tr>
                <td>{{ $employee_leave->leave_id }}</td>
                <td>{{ $employee_leave->employee_name }}</td>
                <td>{{ $employee_leave->leave_type }}</td>
                <td>{{ $employee_leave->total }}</td>
                <td>{{ $employee_leave->remaining }}</td>
                
                <td><a href="#" data-toggle="modal" data-target="#editEmployeeLeave{{ $employee_leave->leave_id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a> | <a href="#" data-toggle="modal" data-target="#deleteEmployeeLeave{{ $employee_leave->leave_id }}"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></td>
              </tr>
               @include('admin.modals.employee_leave_actions')
              @empty
              <tr>
                <td colspan="4">No Records Found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>