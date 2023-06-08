

<table class="table">
   <thead>
      <tr>
         <th>Access ID</th>
         <th>Name</th>
         <th>Email</th>
      </tr>
   </thead>
   <tbody class="table-body">
      @forelse($admins as $admin)
      <tr>
         <td>{{ $admin->access_id }}</td>
         <td>{{ $admin->name }}</td>
         <td>{{ $admin->email }}</td>
      </tr>
      @empty
      <tr>
         <td colspan="4">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>
