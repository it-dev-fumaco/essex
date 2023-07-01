<!-- The Modal -->
<div class="modal fade" id="pendingRequestsModal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h5 class="modal-title">Pending Request(s)</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: -20px 7px 0px 7px;">
               <form>
               <div class="col-md-12">

                  @if(!empty($pending_notices))

                  <span style="font-style: italic; font-size: 14pt;">Absent Notice Slips</span>
                  <table class="table">
                     <thead>
                        <tr>
                           <th>No.</th>
                           <th>Type</th>
                           <th>From - To</th>
                           <th>Reason</th>
                        </tr>
                     </thead>
                     <tbody class="table-body">
                        @forelse($pending_notices as $notice)
                        <tr>
                           <td style="width: 10%;">{{ $notice->notice_id }}</td>
                           <td style="width: 27%;">{{ $notice->leave_type }}</td>
                           <td style="width: 28%;">{{ $notice->date_from }} <i class="fa fa-long-arrow-right"></i> {{ $notice->date_to }}</td>
                           <td style="width: 35%;">{{ $notice->reason }}</td>
                        </tr>
                        @empty
                        <tr>
                           <td colspan="4">No pending notices found.</td>
                        </tr>
                        @endforelse
                     </tbody>
                  </table>
                  <br>

                  @endif

                  @if(!empty($pending_gatepasses))

                  <span style="font-style: italic; font-size: 14pt;">Gatepasses</span>
                  <table class="table">
                     <thead>
                        <tr>
                           <th>No.</th>
                           <th>Item</th>
                           <th>Purpose</th>
                           <th>Return On</th>
                           <th>Remarks</th>
                        </tr>
                     </thead>
                     <tbody class="table-body">
                        @foreach($pending_gatepasses as $gatepass)
                        <tr>
                           <td style="width: 10%;">{{ $gatepass->gatepass_id }}</td>
                           <td style="width: 25%;">{{ $gatepass->item_description }}</td>
                           <td style="width: 25%;">{{ $gatepass->purpose }}</td>
                           <td style="width: 20%;">{{ $gatepass->returned_on }}</td>
                           <td style="width: 20%;">{{ $gatepass->remarks }}</td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>

                  @endif
               </div>
               </form>
            </div>
         </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        {{-- <button type="submit" class="btn btn-primary update"><i class="fa fa-check"></i>Update</button> --}}
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">&times; Close</button>
      </div>
      </form>

    </div>
  </div>
</div>

