<table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Department</th>
                <th>Approver</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-body">
              @forelse($approvers as $approver)
              <tr>
                <td>{{ $approver->approver_id }}</td>
                <td>{{ $approver->department }}</td>
                <td>{{ $approver->employee_name }}</td>
                
                <td><a href="#" data-toggle="modal" data-target="#editApprover{{ $approver->approver_id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a> | <a href="#" data-toggle="modal" data-target="#deleteApprover{{ $approver->approver_id }}"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></td>
              </tr>
               @include('admin.modals.approver_actions')
              @empty
              <tr>
                <td colspan="4">No Records Found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>