<table class="table itinerary-table">
   <thead>
      <tr>  
         <th>ID</th>   
         <th>Project</th>    
         <th>Transaction Date</th>
         <th>Time</th>
         <th>Destination</th>
         <th>Status</th>
         <th>Actions</th> 
      </tr>
   </thead>
   <tbody>
      @forelse($list as $row)

      <tr>
         <td style="width: 50px; text-align: center;">{{ $row->parent }}</td>
         <td style="width: 50px;">{{ $row->project }}</td>
         <td style="width: 50px;">{{ $row->itinerary_date }}</td>
         <td style="width: 50px;">{{ $row->time }}</td>
         <td style="width: 50px; text-align: center;">{{ $row->destination }}</td>

         <td style="width: 50px; text-align: center;">
            @switch(strtolower($row->workflow_state))
               @case('approved') 
                  <span class="label label-primary status">APPROVED</span></h3>
                  @break
               @case('cancelled') 
                  <span class="label label-danger status">CANCELLED</span></h3>
                  @break
               @case('disapproved')
                  <span class="label label-danger status">DISAPPROVED</span></h3>
                  @break
               @case('deferred')
                  <span class="label label-danger status">DISAPPROVED</span></h3>
                  @break
               @default
                  <span class="label label-warning status">FOR APPROVAL</span>
            @endswitch
         </td>
         <td style="width: 50px; text-align: center;">
            <a href="#" data-idnum="{{ $row->parent }}" data-toggle="modal" data-target="#view-list-{{ $row->parent }}"  id=viewItinerary class="viewItinerary">
               <i class="fa fa-search" style="font-size: 18pt; color: #27AE60;"></i>
            </a>
            @include('client.modals.itinerary_modal')
         </td>
      </tr>
      @empty
      <tr>
         <td colspan="7">No records found.</td>
      </tr>
      @endforelse
   </tbody>
</table>

<center>
  <div id="itinerary_pagination">{{ $list->links() }}</div>
</center>

<style type="text/css">
.itinerary-table thead th {
   text-align: center;
}
</style>