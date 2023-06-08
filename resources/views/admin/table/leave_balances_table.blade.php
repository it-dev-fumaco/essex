<table class="table" id="leave-balances-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Employee</th>
                <th>Leave Type</th>
                <th>Total no. of Leave(s)</th>
                <th>Leave Balance</th>
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
              </tr>
              @empty
              <tr>
                <td colspan="4">No Records Found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>