<table class="table gatepass-table" style="font-size: 12px;">
   <thead class="text-uppercase">
      <tr>  
         <th class="text-center">ID</th>   
         <th class="text-center">Item</th>    
         <th class="text-center">Purpose</th>
         <th class="text-center">Returned On</th>
         <th class="text-center">Item Type</th>
         <th class="text-center">Status</th>
         <th class="text-center">Actions</th> 
      </tr>
   </thead>
   <tbody>
      @forelse($gatepasses as $gatepass)
      <tr>
         <td class="text-center align-middle" style="width: 50px;">{{ $gatepass->gatepass_id }}</td>
         <td class="align-center" style="width: 50px;">{{ $gatepass->item_description }}</td>
         <td class="align-center" style="width: 50px;">{{ $gatepass->purpose }}</td>
         <td class="text-center align-middle" style="width: 50px;">{{ $gatepass->returned_on }}</td>
         <td class="text-center align-middle" style="width: 50px;">{{ $gatepass->item_type }}</td>
         <td class="text-center align-middle" style="width: 50px;">
            @switch(strtolower($gatepass->status))
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
                  <span class="badge bg-warning status">FOR APPROVAL</span>
            @endswitch
         </td>
         <td class="text-center align-middle" style="width: 50px;">
            <a href="#" data-id="{{ $gatepass->gatepass_id }}" id="editGatepass" class="hover-icon">
               <i class="fa fa-search" style="font-size: 18pt; color: #27AE60;"></i>
            </a>
            @if(strtolower($gatepass->status) == 'approved')
            <a href="#" data-id="{{ $gatepass->gatepass_id }}" id="printGatepass" class="hover-icon">
               <i class="fa fa-print" style="font-size: 18pt; color: #85929E"></i>
            </a>
            @endif
         </td>
      </tr>
      @empty
      <tr>
         <td colspan="7" class="text-center text-muted text-uppercase p-2">No records found</td>
      </tr>
      @endforelse
   </tbody>
</table>

<center>
  <div id="gatepass_pagination">{{ $gatepasses->links() }}</div>
</center>
