<table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-body">
              @forelse($designations as $designation)
              <tr>
                <td>{{ $designation->des_id }}</td>
                <td>
                  <span>{{ $designation->department }}</span>
                </td>
                <td>
                  <span>{{ $designation->designation }}</span>
                </td>
                
                <td><a href="#" data-id='{{ $designation->des_id }}' data-toggle="modal" data-target="#editDesignation{{ $designation->des_id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a> | <a href="#" data-id='{{ $designation->des_id }}' data-toggle="modal" data-target="#deleteDesignation{{ $designation->des_id }}"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></td>
              </tr>
               @include('admin.modals.designation_actions')
              @empty
              <tr>
                <td colspan="4">No Records Found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>