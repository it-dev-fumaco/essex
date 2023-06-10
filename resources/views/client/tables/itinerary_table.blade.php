<table class="table itinerary-table" style="font-size: 12px;">
   <thead class="text-uppercase">
      <tr>  
         <th class="text-center">ID</th>   
         <th class="text-center">Project</th>    
         <th class="text-center">Transaction Date</th>
         <th class="text-center">Time</th>
         <th class="text-center">Destination</th>
         <th class="text-center">Status</th>
         <th class="text-center">Actions</th> 
      </tr>
   </thead>
   <tbody>
      @forelse($list as $row)
      <tr>
         <td class="text-center align-middle" style="width: 50px;">{{ $row->parent }}</td>
         <td class="align-middle" style="width: 50px;">{{ $row->project }}</td>
         <td class="align-middle" style="width: 50px;">{{ $row->itinerary_date }}</td>
         <td class="align-middle" style="width: 50px;">{{ $row->time }}</td>
         <td class="text-center align-middle" style="width: 50px;">{{ $row->destination }}</td>

         <td class="text-center align-middle" style="width: 50px;">
            @switch(strtolower($row->workflow_state))
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
            <a href="#" data-idnum="{{ $row->parent }}" data-toggle="modal" data-target="#view-list-{{ $row->parent }}"  id=viewItinerary class="viewItinerary">
               <i class="fa fa-search" style="font-size: 18pt; color: #27AE60;"></i>
            </a>
            @include('client.modals.itinerary_modal')
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
  <div id="itinerary_pagination">{{ $list->links() }}</div>
</center>
