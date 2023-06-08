<table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Item Type</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-body">
              @forelse($items as $item)
              <tr>
                <td>{{ $item->item_id }}</td>
                <td>{{ $item->item_name }}</td>
                <td>{{ $item->item_type }}</td>
                <td>{{ $item->brand }}</td>
                <td>{{ $item->model }}</td>
                <td>{{ $item->status }}</td>
                
                <td><a href="#" data-toggle="modal" data-target="#editItem{{ $item->item_id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a> | <a href="#" data-toggle="modal" data-target="#deleteItem{{ $item->item_id }}"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></td>
              </tr>
               @include('admin.modals.item_actions')
              @empty
              <tr>
                <td colspan="4">No Records Found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>