<!-- The Modal -->
<div class="modal fade" id="absentNoticeModal">
   <div class="modal-dialog modal-lg" style="width: 50%;">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-title">
               <h4>Absent Notice Slip</h4>
            </div>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <div class="col-md-12">
                  <div class="tabs-section">
                     <div class="tab-content">
                        <div class="tab-pane in active" id="tab-notice-form">
                           <div class="row">
                              <div class="col-md-8">
                                 <span style="font-size: 14pt;">Create new absent notice slip</span>
                                 <form id="add-notice-form">
                                    @csrf
                                    <div class="row" id="datepairExample" style="margin-top: -25px;">
                                       <input type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
                                       <input type="hidden" name="department" value="{{ Auth::user()->department_id }}">
                                       <div class="col-sm-6" style="padding: 20px 0 20px 15px;">
        <span style="display: block; font-style: italic;">From*</span>
        <input type="text" name="date_from" class="date start absentTodayFilter"    autocomplete="off" id="filterDateStart">
        <input type="text" style="width: 110px;" name="time_from" class="time start"   autocomplete="off" id="starttime" onchange="SumHours();">
 </div>
                                          <div class="col-sm-6" style="padding: 20px 0 20px 0;">
         <span style="display: block; font-style: italic;">To*</span>
         <input type="text" style="width: 110px;" name="time_to" class="time end"   autocomplete="off" id="endtime" onchange="SumHours();">
          <input type="text" onchange="sumofday()" name="date_to" class="date end   absentTodayFilter" autocomplete="off" id="filterDateEnd">
</div>
                                          <div class="col-md-7" style="padding: 20px 0 20px 15px;">
                                             <span style="vertical-align: middle; font-style: italic;">Report made through*</span>
                                             <select style="width: 170px;" name="means">
                                                <option></option>
                                                <option value ="UNREPORTED">Unreported</option>
                                                <option value ="Cellphone">Cellphone</option>
                                                <option value="Land Line">Land Line</option>
                                                <option value="Verbal">Verbal</option>
                                             </select>
                                          </div>
                                          <div class="col-sm-5" style="padding: 20px 0 20px 15px;">
                                             <span style="vertical-align: middle; font-style: italic;">Time*</span>
                                             <select style="width: 120px;" name="time_reported">
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
                                             <span style="vertical-align: middle; font-style: italic;">Received by*</span>
                                             <input type="text" style="width: 220px;" name="info_by">
                                          </div>
                                          <div class="col-md-12" style="padding: 20px 0 20px 100px;">
                                             <span style="vertical-align: top; font-style: italic;">Reason*</span>
                                             <textarea name="reason" cols="50" rows="4"></textarea>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-4" style="padding: 50px 0 20px 30px;">
                                       <span style="display: block; padding-bottom: 10px; font-style: italic;">Type of Absence*</span>
                                       @foreach($leave_types as $leave_type)
                                       <div id="emp_L{{ $leave_type->leave_type_id }}">
                                         <label class="radio_container">{{ $leave_type->leave_type }}
                                         <input type="radio" name="absence_type" value="{{ $leave_type->leave_type_id }}" id="{{ $leave_type->leave_type_id }}">
                                             <span class="checkmark"></span>
                                          </label>
                                       </div>
                                       @endforeach
                                       
                                       @foreach($absence_types as $absence_type)
                                       <div id="reg_L{{ $absence_type->leave_type_id }}">
                                          <label class="radio_container">{{ $absence_type->leave_type }}
                                             <input type="radio" name="absence_type" value="{{ $absence_type->leave_type_id }}" id="{{ $absence_type->leave_type_id }}">
                                            <span class="checkmark"></span>
                                          </label>
                                       </div>
                                       @endforeach
                                    </div>
                                    <div class="col-sm-12" id="out-of-office-table"></div>
                                    <div class="col-sm-12 center"><button type="submit" class="btn btn-primary" id="notice-slip-submit-btn"><i class="fa fa-check"></i>Request for Approval</button></div>
                                    </form>
                                 </div>
                              </div>
                               <div style="display: none;">
                            <label class="grey-text font-weight-light" style="padding-top: 5%;">Leave Balances</label>
                            <span style="display: block; font-size: 8pt; color: #999999;">Remaining</span>
                            @forelse($leave_types as $leave_type)
                           <div class="col-md-12">
                                 
                                 <span class="remain_L{{ $leave_type->leave_type_id }}">{{ $leave_type->leave_type }} - {{ $leave_type->remaining }}</span>
                              <input type="hidden" id="remain_L{{ $leave_type->leave_type_id }}" class="remain_L{{ $leave_type->leave_type_id }}" value="{{ $leave_type->remaining }}">
                           </div>
                           @empty
                           <div class="col-md-4">
                              No records found.
                           </div>
                     @endforelse
                         </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>