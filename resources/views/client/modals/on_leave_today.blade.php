<!-- The Modal -->
<div class="modal fade" id="onLeaveModal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h5 class="modal-title">On Leave Today</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: -20px 7px 0px 7px;">
               <form>
               <div class="col-md-12">
                  <table class="table">
                     <thead>
                        <tr>
                           <th>Employee Name</th>
                           <th>Leave Type</th>
                           <th>From - To</th>
                           <th>Time</th>
                        </tr>
                     </thead>
                     <tbody class="table-body">
                        @forelse($out_of_office_today as $out_of_office)
                        <tr>
                           <td style="width: 30%;">{{ $out_of_office->employee_name }}</td>
                           <td style="width: 20%;">{{ $out_of_office->leave_type }}</td>
                           <td style="width: 25%;"><i class="fa fa-calendar"></i> {{ $out_of_office->date_from }} <i class="fa fa-long-arrow-right"></i> {{ $out_of_office->date_to }}</td>
                           <td style="width: 25%;"><i class="icon-clock"></i> {{ $out_of_office->time_from }} <i class="fa fa-long-arrow-right"></i> {{ $out_of_office->time_to }}</td>
                        </tr>
                        @empty
                        <tr>
                           <td colspan="4">No records found.</td>
                        </tr>
                        @endforelse
                     </tbody>
                  </table>
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

