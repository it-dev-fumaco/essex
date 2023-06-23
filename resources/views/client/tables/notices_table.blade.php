<table class="table notice-table" style="font-size: 12px;">
   <thead class="text-uppercase">
      <tr>  
         <th class="text-center">ID</th>
         <th class="text-center">Leave Type</th>
         <th class="text-center">From - To</th>    
         <th class="text-center">Reason</th>
         <th class="text-center">Status</th> 
         <th class="text-center">Actions</th> 
      </tr>
   </thead>
   <tbody class="table-body">
      @forelse($notice_slips as $notice_slip)
      <tr>
         <td class="text-center align-middle" style="width: 8%;">{{ $notice_slip->notice_id }}</td>
         <td class="text-center align-middle" style="width: 21%;">{{ $notice_slip->leave_type }}</td>
         <td class="text-center align-middle" style="width: 21%;">
            @if ($notice_slip->date_from ==  $notice_slip->date_to)
            {{ \Carbon\Carbon::parse($notice_slip->date_from)->format('M. d, Y') }}
            @else
            {{ \Carbon\Carbon::parse($notice_slip->date_from)->format('M. d, Y') . ' - ' . \Carbon\Carbon::parse($notice_slip->date_to)->format('M. d, Y') }}
            @endif
         </td>
         <td class="align-middle" style="width: 25%;">{{ $notice_slip->reason }}</td>
         <td class="text-center align-middle" style="width: 15%;">
            @switch(strtolower($notice_slip->status))
               @case('approved') 
                  <span class="badge bg-primary status">APPROVED</span></h3>
                  @break
               @case('cancelled') 
                  <span class="badge bg-danger status">CANCELLED</span></h3>
                  @break
               @case('disapproved')
                  <span class="badge bg-danger status">DISAPPROVED</span></h3>
                  @break
               @case('deferred')
                  <span class="badge bg-danger status">DISAPPROVED</span></h3>
                  @break
               @default
                  <span class="badge bg-warning status">FOR APPROVAL</span></h3>
            @endswitch
         </td>
         <td class="text-center" style="width: 10%;">
            <a href="#" data-id="{{ $notice_slip->notice_id }}" id="editAbsent" class="hover-icon text-decoration-none">
               <i class="fa fa-search" style="font-size: 18pt; color: #27AE60"></i>
            </a>
            @if(strtolower($notice_slip->status) == 'approved')
            <a href="#" data-id="{{ $notice_slip->notice_id }}" id="printAbsent" class="hover-icon text-decoration-none">
               <i class="fa fa-print" style="font-size: 18pt; color: #85929E"></i>
            </a>
            @endif
         </td>
      </tr>
      @empty
      <tr>
         <td colspan="6" class="text-center text-muted text-uppercase p-2">No records found</td>
      </tr>
      @endforelse
   </tbody>
</table>

<center>
   <div id="notices_pagination">
      {{ $notice_slips->links() }}
   </div>
</center>