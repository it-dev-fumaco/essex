<table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Department Name</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-body">
              @forelse($departments as $department)
              <tr>
                <td>{{ $department->department_id }}</td>
                <td>
                  <span>{{ $department->department }}</span>
                </td>
                
                <td><a href="#" data-id='{{ $department->department_id }}' data-toggle="modal" data-target="#editDepartment{{ $department->department_id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a> | <a href="#" data-id='{{ $department->department_id }}' data-toggle="modal" data-target="#deleteDepartment{{ $department->department_id }}"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></td>
              </tr>
               @include('admin.modals.department_actions')
              @empty
              <tr>
                <td colspan="4">No Records Found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>