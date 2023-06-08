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
      @forelse($unreturned_items as $unreturned_item)
      <tr>
         <td>{{ $unreturned_item->gatepass_id }}</td>
         <td>{{ $unreturned_item->item_description }}</td>
         <td>{{ $unreturned_item->purpose }}</td>
         <td>{{ $unreturned_item->status }}</td>
         <td>{{ $unreturned_item->returned_on }}</td>
         <td><a href="#" data-toggle="modal" data-target="#editGatepass{{ $unreturned_item->gatepass_id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></td>
      </tr>
      {{-- @include('admin.modals.employee_actions') --}}
      @empty
      <tr>
         <td colspan="4">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>
