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
      @forelse($pending_gatepasses as $pending_gatepass)
      <tr>
         <td>{{ $pending_gatepass->gatepass_id }}</td>
         <td>{{ $pending_gatepass->item_description }}</td>
         <td>{{ $pending_gatepass->purpose }}</td>
         <td>{{ $pending_gatepass->status }}</td>
         <td>{{ $pending_gatepass->returned_on }}</td>
         <td><a href="#" data-toggle="modal" data-target="#editGatepass{{ $pending_gatepass->gatepass_id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></td>
      </tr>
      {{-- @include('admin.modals.employee_actions') --}}
      @empty
      <tr>
         <td colspan="4">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>
