<div class="modal fade" id="gatepassModal">
   <div class="modal-dialog modal-lg" style="min-width: 50%;">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Gatepass</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="row" style="margin: 7px;">
               <div class="tabs-section">
                  @if(in_array($designation, ['Operations Manager', 'Human Resources Head', 'Director of Operations', 'President', 'Product Manager', 'HR Payroll Assistant']))
                  <ul class="nav nav-pills" id="gatepass-tabs">
                     <li class="nav-item border rounded border-success"><a href="#tab-gatepass-form" class="nav-link active" data-bs-toggle="tab">Gatepass Form</a></li>
                     <li class="nav-item border rounded border-success"><a href="#tab-item-accountability" class="nav-link" data-bs-toggle="tab">Item Accountability</a></li>
                  </ul>
                  @endif
                  <div class="tab-content">
                     <div class="tab-pane in active" id="tab-gatepass-form">
                        <div class="row">
                           <span class="d-block fw-bold fs-6 mb-3">Create new gatepass</span>
                           <form id="add-gatepass-form">
                              @csrf
                              <input type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
                              <div class="col-md-12" id="datepairExample" style="margin-top: 10px;">
                                 <table style="width: 100%;">
                                    <tr>
                                       <td style="text-align: right; padding: 1% 5% 1% 1%; width: 50%;">
                                          <span class="fst-italic">Date*</span>
                                          <input type="text" class="date" style="width: 220px;" name="date_filed" autocomplete="off" required>
                                       </td>
                                       <td style="width: 50%;"><span class="fst-italic" style="line-height: 30px;">If not connected to FUMACO Inc.</span></td>
                                    </tr>
                                    <tr>
                                       <td style="text-align: right; padding: 1% 5% 1% 1%;">
                                          <span class="fst-italic">Returned on*</span>
                                          <input type="text" style="width: 220px;" class="date" name="returned_on" autocomplete="off" required>
                                       </td>
                                       <td style="text-align: right; padding: 1% 5% 1% 1%;">
                                          <span class="fst-italic">Company Name*</span>
                                          <input type="text" style="width: 220px;" name="company_name" required>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td style="text-align: right; padding: 1% 5% 1% 1%;">
                                          <span class="fst-italic">Time*</span>               
                                          <select style="width: 220px;" name="time" required>
                                             <option></option>
                                             <option value="07:00:00">7:00 AM</option>
                                             <option value="08:00:00">8:00 AM</option>
                                             <option value="09:00:00">9:00 AM</option>
                                             <option value="10:00:00">10:00 AM</option>
                                             <option value="11:00:00">11:00 AM</option>
                                             <option value="12:00:00">12:00 PM</option>
                                             <option disabled>---------</option>
                                             <option value="13:00:00">1:00 PM</option>
                                             <option value="14:00:00">2:00 PM</option>
                                             <option value="15:00:00">3:00 PM</option>
                                             <option value="16:00:00">4:00 PM</option>
                                             <option value="17:00:00">5:00 PM</option>
                                          </select>
                                       </td>
                                       <td class="text-end" style="padding: 1% 5% 1% 1%;">
                                          <span class="fst-italic">Address*</span>
                                          <input type="text" style="width: 220px;" name="address" required>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td class="text-end" style="padding: 1% 5% 1% 1%;">
                                          <span class="fst-italic">Purpose Type*</span>
                                          <select style="width: 220px;" name="purpose_type" required>
                                             <option value=""></option>
                                             <option value="For Servicing">For Servicing</option>
                                             <option value="For Company Activity">For Company Activity</option>
                                             <option value="For Personal Use">For Personal Use</option>
                                             <option value="Others">Others</option>
                                          </select>
                                       </td>
                                       <td class="text-end" style="padding: 1% 5% 1% 1%;">
                                          <span class="fst-italic">Tel. No.*</span>
                                          <input type="text" style="width: 220px;" name="tel_no" required>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td class="text-end" style="padding: 1% 5% 1% 1%;">
                                          <span class="fst-italic">Purpose*</span>
                                          <input type="text" style="width: 220px;" name="purpose" required>
                                       </td>
                                       <td rowspan="2" class="text-end" style="padding: 1% 5% 1% 1%; vertical-align: top">
                                          <span class="fst-italic" style="vertical-align: top;">Item(s)*</span>
                                          <textarea name="item_description" rows="4" cols="28" required></textarea>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td class="text-end" style="padding: 1% 5% 1% 1%;">
                                          <span class="fst-italic" style="vertical-align: top;">Remarks*</span>
                                          <textarea name="remarks" rows="4" cols="28" required></textarea>
                                       </td>
                                    </tr>
                                 </table>
                              </div>
                              <div class="col-sm-12 center" style="margin-top: 2%;">
                                 <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i>Request for Approval</button>
                              </div>
                           </form>
                        </div>
                     </div>
                     <div class="tab-pane" id="tab-item-accountability">
                        <div class="row">
                           <div class="col-sm-12" style="margin-top: 1%;">
                              <table class="table table-striped table-bordered">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>Item Code</th>
                                       <th>Description</th>
                                       <th>Qty</th>
                                       <th>Date Issued</th>
                                       <th>Issued by</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @forelse($emp_item_accountability as $row)
                                    <tr>
                                       <td>{{ $row->item_id }}</td>
                                       <td>{{ $row->item_code }}</td>
                                       <td>{{ $row->item_desc }}</td>
                                       <td>{{ $row->qty }}</td>
                                       <td>{{ $row->date_issued }}</td>
                                       <td>{{ $row->issued_by }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                       <td colspan="6" class="p-3 text-center text-muted text-uppercase">No records found</td>
                                    </tr>
                                    @endforelse
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
