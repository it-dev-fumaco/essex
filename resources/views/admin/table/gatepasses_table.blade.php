<table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Purpose</th>
                <th>Status</th>
                <th>Return On</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-body">
              @forelse($gatepasses as $gatepass)
              <tr>
                <td>{{ $gatepass->gatepass_id }}</td>
                <td>{{ $gatepass->item_description }}</td>
                <td>{{ $gatepass->purpose }}</td>
                <td>{{ $gatepass->status }}</td>
                <td>{{ $gatepass->returned_on }}</td>
                
                <td><a href="#" data-toggle="modal" data-target="#editGatepass{{ $gatepass->gatepass_id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></td>
              </tr>
               {{-- @include('admin.modals.leave_type_actions') --}}
              @empty
              <tr>
                <td colspan="4">No Records Found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>