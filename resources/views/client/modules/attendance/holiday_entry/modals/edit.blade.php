<!-- The Modal -->
<div class="modal fade" id="edit-holiday-{{ $holiday->id }}">
   <div class="modal-dialog">
      <form action="/module/attendance/holiday/update" method="POST">
               @csrf
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Holiday</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row">
               
               <input type="hidden" name="id" value="{{ $holiday->id }}">
               <div class="col-sm-12" style="margin-top: -30px; margin-bottom: -25px;">
                  <div class="form-group" style="padding: 0 10px;">
                     <label>Holiday Date:</label>
                     <input type="text" name="holiday_date" value="{{ $holiday->holiday_date }}" class="date" placeholder="Enter Holiday Date" required>
                  </div>
                  <div class="form-group" style="padding: 0 10px;">
                     <label>Description:</label>
                     <textarea rows="2" style="display: block; width: 100%;" name="description" placeholder="Enter Description" required>{{ $holiday->description }}</textarea>
                  </div>
                  <div class="form-group" style="padding: 0 10px;">
                        <label>Category:</label>
                        @php
                            $holidays = ['Regular Holiday', 'Special Holiday', 'Company Activity'];
                        @endphp
                         <select id="category" name="category">
                           <option value="">--Select Category--</option>
                           @foreach ($holidays as $h)
                              <option value="{{ $h }}" {{ $h == 'Regular Holiday' ? 'selected' : null }}>{{ $h }}</option>
                           @endforeach
                         </select>
                  </div>
                  <div style="font-size: 8pt; padding-right: 5%;float: right;">
                    <i>Last modified: <b>{{ $holiday->updated_at }} </b> -{{ $holiday->last_modified_by }} </i>
                    </div>
               </div>
               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
      </div>
      </form>
   </div>
</div>
