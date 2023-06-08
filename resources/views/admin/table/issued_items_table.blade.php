<table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Description</th>
                <th>Serial No</th>
                <th>MAC Address</th>
                <th>Issued To</th>
                <th>Date Issued</th>
                <th>Valid Until</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-body">
      @forelse($issued_items as $issued_item)
      <tr>
         <td>{{ $issued_item->issue_id }}</td>
         <td>{{ $issued_item->item_name }}</td>
         <td>{{ $issued_item->brand }}</td>
         <td>{{ $issued_item->model }}</td>
         <td>{{ $issued_item->description }}</td>
         <td>{{ $issued_item->serial_no }}</td>
         <td>{{ $issued_item->mac_address }}</td>
         <td>{{ $issued_item->employee_name }}</td>
         <td>{{ $issued_item->date_issued }}</td>
         <td>{{ $issued_item->valid_until }}</td>
         <td><a href="#" data-toggle="modal" data-target="#editEmpItem{{ $issued_item->issue_id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></td>
      </tr>
      @include('admin.modals.items_issued_actions')
      @empty
      <tr>
         <td colspan="4">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>
