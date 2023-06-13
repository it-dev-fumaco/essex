<div class="modal fade" id="editNoticeModal">
   <div class="modal-dialog modal-lg" style="min-width: 60%;">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Absent Notice Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="edit-notice-form" action="/notice_slip/create" method="POST">
               @csrf
               <div class="row" style="margin: 7px;" id="datepairExample">
                  <input type="hidden" name="notice_id" class="notice_id">
                  <div class="col-md-8">
                     <div class="row">
                        <input type="hidden" class="user_id" name="user_id" value="{{ Auth::user()->user_id }}">
                        <input type="hidden" name="leave_type" class="leave_type">
                        <input type="hidden" name="department" value="{{ Auth::user()->department_id }}">
                        <div class="col-6" style="padding: 20px 0 20px 15px;">
                           <span class="d-block fst-italic">From*</span>
                           <input type="text" name="date_from" class="from_date date start" id="startdate_notice" onchange="sumofday_refillnotice()" required>
                           <input type="text" style="width: 110px;" name="time_from" class="from_time time" autocomplete="off" id="notice_from_time" onchange="SumHours_notice()" required>                
                        </div>
                        <div class="col-6" style="padding: 20px 0 20px 0;">
                           <span class="d-block fst-italic">To*</span>
                           <input type="text" style="width: 110px;" name="time_to" class="to_time time" autocomplete="off" id="notice_end_time" onchange="SumHours_notice()" required>
                           <input type="text" name="date_to" class="to_date date end" onchange="sumofday_refillnotice()" id="enddate_notice" required>
                        </div>
                        <div class="col-7" style="padding: 20px 0 20px 15px;">
                           <span class="fst-italic" style="vertical-align: middle;">Report made through*</span>
                           <select style="width: 170px;" name="means" class="means" required>
                              <option></option>
                              <option value ="UNREPORTED">Unreported</option>
                              <option value ="Cellphone">Cellphone</option>
                              <option value="Land Line">Land Line</option>
                              <option value="Verbal">Verbal</option>
                           </select>
                        </div>
                        <div class="col-5" style="padding: 20px 0 20px 15px;">
                           <span class="fst-italic" style="vertical-align: middle;">Time*</span>
                           <select style="width: 120px;" name="time_reported" class="time_reported" required>
                              <option value=""></option>
                              <option value="04:00am">4:00</option>
                              <option value="05:00am">5:00</option>
                              <option value="06:00am">6:00</option>
                              <option value="07:00am">7:00</option>
                              <option value="08:00am">8:00</option>
                              <option value="09:00am">9:00</option>
                              <option value="10:00am">10:00</option>
                              <option value="11:00am">11:00</option>
                              <option value="12:00nn">12:00</option>
                              <option disabled>------</option>
                              <option value="01:00pm">13:00 </option>
                              <option value="02:00pm">14:00 </option>
                              <option value="03:00pm">15:00 </option>
                              <option value="04:00pm">16:00 </option>
                              <option value="05:00pm">17:00</option>
                           </select>
                        </div>
                        <div class="col-12" style="padding: 20px 0 20px 70px;">
                           <span class="fst-italic" style="vertical-align: middle;">Received by*</span>
                           <input type="text" style="width: 220px;" name="info_by" class="info_by">
                        </div>
                        <div class="col-md-12" style="padding: 20px 0 20px 100px;">
                           <span class="fst-italic" style="vertical-align: top;">Reason*</span>
                           <textarea name="reason" cols="50" rows="4" class="reason"></textarea>
                        </div>
                     </div>
                  </div>
                  <div class="col-4" style="padding: 20px 0 20px 30px;">
                     <span class="fst-italic d-block" style="padding-bottom: 10px;">Type of Absence*</span>
                     @foreach($leave_types as $leave_type)
                     <div id="notice_emp_L{{ $leave_type->leave_type_id }}">
                        <label class="radio_container">{{ $leave_type->leave_type }}
                           <input type="radio" name="absence_type" value="{{ $leave_type->leave_type_id }}" class="leave_type_id{{ $leave_type->leave_type_id }}"> 
                           <span class="checkmark"></span>
                        </label>
                     </div>
                     @endforeach
                     @foreach($absence_types as $absence_type)
                     <div id="notice_reg_L{{ $absence_type->leave_type_id }}">
                        <label class="radio_container">{{ $absence_type->leave_type }}
                           <input type="radio" name="absence_type" value="{{ $absence_type->leave_type_id }}" class="leave_type_id{{ $absence_type->leave_type_id }}">
                              <span class="checkmark"></span>
                        </label>
                     </div>
                     @endforeach

                     <div class="d-block mt-5">
                        <div class="status mt-5"></div>
                        <div style="padding: 10px 15px 0 5px; display: block;" class="divStatus" hidden><span>Approved by:</span> <span class="approved_by" style="font-weight: bold;"></span></div>
                        <div style="padding: 5px 15px 0 5px; display: block;" class="divStatus" hidden><span>Date approved:</span> <span class="date_approved" style="font-weight: bold;"></span></div>
                     </div>
                  </div>
               </div>
            </div>
            <div style="display: none;">
               <label class="grey-text font-weight-light" style="padding-top: 5%;">Leave Balances</label>
               <span style="display: block; font-size: 8pt; color: #999999;">Remaining</span>
               @foreach($leave_types as $leave_type)
               <div class="col-md-12">
                  <span class="notice_remain_L{{ $leave_type->leave_type_id }}">{{ $leave_type->leave_type }} - {{ $leave_type->remaining }}</span>
                  <input type="hidden" id="notice_remain_L{{ $leave_type->leave_type_id }}" class="notice_remain_L{{ $leave_type->leave_type_id }}" value="{{ $leave_type->remaining }}">
               </div>
               @endforeach
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <a href="#" class="btn btn-primary" id="print-notice"><i class="fa fa-print"></i></i>Print</a>
               <button type="button" class="btn btn-warning" id="cancel-notice"><i class="fa fa-ban"></i></i>Cancel Notice</button>
               <button type="submit" class="btn btn-primary" id="amend-notice"><i class="fa fa-edit"></i></i>Refile Notice</button>
               <button type="button" class="btn btn-danger" data-bs-dismiss="modal">&times; Close</button>
            </div>
         </form>
      </div>
   </div>
</div> 


