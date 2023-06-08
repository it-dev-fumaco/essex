

<table class="table">
   <thead>
      <tr>
         <th>Access ID</th>
         <th>Name</th>
         <th>Position Applied (1st choice)</th>
         <th>Position Applied (2nd choice)</th>
         <th>Actions</th>
      </tr>
   </thead>
   <tbody class="table-body">
      @forelse($applicants as $applicant)
      <tr>
         <td>{{ $applicant->id }}</td>
         <td>
            {{-- <img src="{{ asset('storage/img/user.png') }}" width="55" height="45" style="float: left; padding-right: 10px;">  --}}
            <span class="approver-name">{{ $applicant->employee_name }}</span>
         </td>
         <td>{{ $applicant->position_applied_for1 }}</td>
         <td>{{ $applicant->position_applied_for2 }}</td>
         <td><a href="#" data-toggle="modal" data-target="#editApplicant{{ $applicant->id }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a> | <a href="#" data-toggle="modal" data-target="#deleteApplicant{{ $applicant->id }}"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></td>
      </tr>
      @include('admin.modals.applicant_actions')
      @empty
      <tr>
         <td colspan="4">No Records Found.</td>
      </tr>
      @endforelse
   </tbody>
</table>
