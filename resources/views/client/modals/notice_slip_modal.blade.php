<div class="modal fade" id="absentNoticeModal">
   <div class="modal-dialog modal-lg" style="min-width: 60%;">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Absent Notice Slip</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="add-notice-form">
               @csrf
               <div class="row m-0 p-3">
                  <div class="col-md-8">
                     <span class="d-block fw-bold fs-6 mb-3">Create new absent notice slip</span>
                     <div class="row" id="datepairExample" style="margin-top: -25px;">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
                        <input type="hidden" name="department" value="{{ Auth::user()->department_id }}">
                        <div class="col-sm-6" style="padding: 20px 0 20px 15px;">
                           <span class="d-block fst-italic">From*</span>
                           <input type="text" name="date_from" class="date start absentTodayFilter" autocomplete="off" id="filterDateStart" required>
                           <input type="text" style="width: 110px;" name="time_from" class="time start" autocomplete="off" id="starttime" onchange="SumHours();" required>
                        </div>
                        <div class="col-sm-6" style="padding: 20px 0 20px 0;">
                           <span class="d-block fst-italic">To*</span>
                           <input type="text" style="width: 110px;" name="time_to" class="time end"   autocomplete="off" id="endtime" onchange="SumHours();" required>
                           <input type="text" onchange="sumofday()" name="date_to" class="date end   absentTodayFilter" autocomplete="off" id="filterDateEnd" required>
                        </div>
                        <div class="col-md-7" style="padding: 20px 0 20px 15px;">
                           <span class="fst-italic" style="vertical-align: middle;">Report made through*</span>
                           <select style="width: 170px;" name="means" required>
                              <option></option>
                              <option value ="UNREPORTED">Unreported</option>
                              <option value ="Cellphone">Cellphone</option>
                              <option value="Land Line">Land Line</option>
                              <option value="Verbal">Verbal</option>
                           </select>
                        </div>
                        <div class="col-sm-5" style="padding: 20px 0 20px 15px;">
                           <span class="fst-italic" style="vertical-align: middle;">Time*</span>
                           <select style="width: 120px;" name="time_reported" required>
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
                              <option value="">------</option>
                              <option value="01:00pm">13:00</option>
                              <option value="02:00pm">14:00</option>
                              <option value="03:00pm">15:00</option>
                              <option value="04:00pm">16:00</option>
                              <option value="05:00pm">17:00</option>
                              <option value="06:00pm">18:00</option>
                              <option value="07:00pm">19:00</option>
                              <option value="08:00pm">20:00</option>
                              <option value="09:00pm">21:00</option>
                              <option value="10:00pm">22:00</option>
                           </select>
                        </div>
                        <div class="col-md-12" style="padding: 20px 0 20px 70px;">
                           <span class="fst-italic" style="vertical-align: middle;">Received by*</span>
                           <input type="text" style="width: 220px;" name="info_by" required>
                        </div>
                        <div class="col-md-12" style="padding: 20px 0 20px 100px;">
                           <span class="fst-italic" style="vertical-align: top;">Reason*</span>
                           <textarea name="reason" cols="50" rows="4" required></textarea>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4" style="padding: 50px 0 20px 30px;">
                     <span class="fst-italic d-block" style="padding-bottom: 10px;">Type of Absence*</span>
                     @foreach($leave_types as $leave_type)
                     <div id="emp_L{{ $leave_type->leave_type_id }}">
                        <label class="radio_container">{{ $leave_type->leave_type }}
                        <input type="radio" name="absence_type" value="{{ $leave_type->leave_type_id }}" id="{{ $leave_type->leave_type_id }}" required>
                           <span class="checkmark"></span>
                        </label>
                     </div>
                     @if (!$leave_type->applied_to_all)
                     <div id="emp_L{{ $leave_type->leave_type_id }}-5">
                        <label class="radio_container">Half Day {{ $leave_type->leave_type }}
                        <input type="radio" name="absence_type" value="{{ $leave_type->leave_type_id }}-halfday" id="{{ $leave_type->leave_type_id }}.5" required>
                           <span class="checkmark"></span>
                        </label>
                     </div>    
                     @endif
                     @endforeach
                     
                     @foreach($absence_types as $absence_type)
                     <div id="reg_L{{ $absence_type->leave_type_id }}">
                        <label class="radio_container">{{ $absence_type->leave_type }}
                           <input type="radio" name="absence_type" value="{{ $absence_type->leave_type_id }}" id="{{ $absence_type->leave_type_id }}" required>
                           <span class="checkmark"></span>
                        </label>
                     </div>
                     @endforeach
                  </div>
                  <div class="col-sm-12" id="out-of-office-table"></div>
                  <div class="col-sm-12 center mb-3"><button type="submit" class="btn btn-primary" id="notice-slip-submit-btn">
                     <i class="fa fa-check"></i>Request for Approval</button>
                  </div>
               </div>
            </form>
            <div style="display: none;">
               <label class="grey-text font-weight-light" style="padding-top: 5%;">Leave Balances</label>
               <span style="display: block; font-size: 8pt; color: #999999;">Remaining</span>
               @forelse($leave_types as $leave_type)
               <div class="col-md-12">
                  <span class="remain_L{{ $leave_type->leave_type_id }}">{{ $leave_type->leave_type }} - {{ $leave_type->remaining }}</span>
                  <input type="hidden" id="remain_L{{ $leave_type->leave_type_id }}" class="remain_L{{ $leave_type->leave_type_id }}" value="{{ $leave_type->remaining }}">
               </div>
               @empty
               <div class="col-md-4">No records found.</div>
               @endforelse
            </div>
         </div>
      </div>
   </div>
</div>