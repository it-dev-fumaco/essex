{{-- <div class="modal fade" id="examModal">
    <div class="modal-dialog modal-lg" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Online Examination</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin: 7px;">
                    <div class="col-md-12">
                        <div class="tabs-section">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab-1" data-toggle="tab">Pending Examination Schedule</a>
                                </li>
                                <li>
                                    <a href="#tab-2" data-toggle="tab">Online Exam History</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane in active" id="tab-1">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table">
                                                <thead>
                                                    <th>Exam Date</th>
                                                    <th>Exam Title</th>
                                                    <th>Exam Group</th>
                                                    <th>Validity Date</th>
                                                    <th>Action</th>
                                                </thead>
                                                <tbody>
                                                    @forelse($clientexams as $exam)
                                                    <tr>
                                                        @if($exam->start_time == null)
                                                        <td>{{$exam->date_of_exam}}</td>
                                                        <td>{{$exam->exam_title}}</td>
                                                        <td>{{$exam->exam_group_description}}</td>
                                                        <td>{{$exam->validity_date}}</td>
                                                        @if(date('m-d-Y') <= date('m-d-Y',strtotime($exam->validity_date)) && date('m-d-Y') >= date('m-d-Y',strtotime($exam->date_of_exam)))
                                                        <td><a href="#" data-toggle="modal" data-target="#clientTakeExam{{$exam->examinee_id}}"><i class="fa fa-pen"></i> Take Exam</a></td>
                                                @else
                                                    <td><p>Validity Expired!</p></td>
                                                @endif
                                            @endif
<!-- The Modal -->
<div class="modal fade" id="clientTakeExam{{$exam->examinee_id}}">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Exam Confirmation</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: -20px 0 -20px 0; font-size: 12pt;">
               <div class="col-sm-12">
                <span style="display: block; padding: 1%;">Exam Title: <b>{{$exam->exam_title}}</b></span>
                <span style="display: block; padding: 1%;">Exam Date: <b>{{$exam->date_of_exam}}</b></span>
                <span style="display: block; padding: 1%;">Duration: <b>{{$exam->duration}} minute(s)</b></span>
                <span style="display: block; padding: 2% 1% 1% 1%;">Please click <b><i>Take Exam</i></b> to start exam.</span>
               </div>               
            </div>
         </div>
         <input type="hidden" name="employee_exam_code" id="employee_exam_code" class="employee_exam_code_class" value="{{$exam->exam_code}}">
         <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-idcode="{{$exam->exam_code}}" id="employee_submit"><i class="fa fa-check"></i> Take Exam</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
      </div>
   </div>
</div>
                                         </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6"> No Exams found.</td>
                                            </tr>
                                            
                                        @endforelse
                                        </tbody>
                                     </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-2">
                            <div class="row">
                                <div class="col-sm-12">
                                 
                                 <table class="table">
                                         <th>Exam Date</th>
                                         <th>Exam Title</th>
                                         <th>Exam Group</th>
                                         <th>Date Taken</th>
                                         <th>Validity Date</th>
                                         <th>Action</th>
                                        @forelse($clientexams as $exam)
                                         <tr>
                                            @if($exam->start_time != null)
                                                <td>{{$exam->date_of_exam}}</td>
                                                <td>{{$exam->exam_title}}</td>
                                                <td>{{$exam->exam_group_description}}</td>
                                                <td>{{$exam->date_taken}}</td>
                                                <td>{{$exam->validity_date}}</td>
                                                <td>Completed</td>
                                                
                                                
                                            @endif
                                         </tr>
                                        @empty
                                            <tr><td colspan="6"> No Exams found.</td></tr>
                                        @endforelse
                                     </table>
                
                                </div>
                            </div>                                          
                        </div>
                        <div class="tab-pane" id="tab-3">
                            <div class="row">
                                <div class="col-sm-12">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-4">
                            <div class="row">
                                <div class="col-sm-12">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               </div>
            </div>
         </div>
         </form>
      </div>
   </div>
</div> --}}