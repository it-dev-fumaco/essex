<table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Leave Type</th>
                <th>Description</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-body">
              @forelse($leave_types as $leave_type)
              <tr>
                <td>{{ $leave_type->leave_type_id }}</td>
                <td>{{ $leave_type->leave_type }}</td>
                <td>{{ $leave_type->description }}</td>
                
                <td><a href="#" data-toggle="modal" data-target="#editLeaveType{{ $leave_type->leave_type_id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a> | <a href="#" data-toggle="modal" data-target="#deleteLeaveType{{ $leave_type->leave_type_id }}"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></td>
              </tr>
               @include('admin.modals.leave_type_actions')
              @empty
              <tr>
                <td colspan="4">No Records Found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>