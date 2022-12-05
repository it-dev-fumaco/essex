<div class="col-md-12">
   <section class="multi_step_form">
      <div class="inner-box featured">
      <div id="msform">
         <!-- Tittle -->
         <div class="tittle" style="margin-top: -40px; padding-bottom: 30px;">
            <h2>Create Examination Sheet</h2>
            <p>In order to create examination sheet, you have to complete this process</p>
         </div>
         <!-- progressbar -->
         <ul id="progressbar">
            <li class="active">Create Exam</li>  
            <li>Add Questions</li> 
            <li>Register Examinee</li>
            <li>Finalize Exam</li>
         </ul>
         <!-- fieldsets -->
         {{-- CREATE EXAM --}}
         <fieldset>
            <form id="add-exam-form">
            @csrf
               <div class="form-group col-sm-6">
                  <label>Exam Group</label>
                  <select class="form-control exam-group" name="exam_group_id" class="form-control">
                     @foreach($examgroups as $examgroup)
                     <option value="{{ $examgroup->exam_group_id }}">{{ $examgroup->exam_group_description }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group col-sm-6">
                  <label>Department</label>
                  <select class="form-control department" name="department_id">
                     @foreach($departments as $dept)
                     <option value="{{ $dept->department_id }}">{{ $dept->department }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group col-sm-12">
                  <label>Exam Title</label>
                  <input type="text" class="form-control exam-title req" name="exam_title" placeholder="Enter Exam Title" required>
               </div>
               <div class="form-group col-sm-4">
                  <label>Status</label>
                  <select class="form-control status" name="status" class="form-control">
                     <option value="Active">Active</option>
                     <option value="Inactive">Inactive</option>
                  </select>
               </div>
               <div class="form-group col-sm-4">
                  <label>Duration In Minutes</label>
                  <input type="number" class="form-control duration req" name="duration_in_minutes" placeholder="Duration (mins)" required>
               </div>
               <div class="form-group col-sm-4">
                  <label>Passing Mark</label>
                  <input type="text" class="form-control passing-mark" name="passing_mark" placeholder="Passing Mark" required>
               </div>
               <div class="form-group col-sm-12">
                  <label>Remarks</label>
                  <textarea class="form-control" name="remarks" placeholder="Enter Remarks" required></textarea>
               </div>
            </form>
            {{-- <button type="button" class="action-button previous_button">Back</button> --}}
            <button type="button" class="next action-button" id="add-exam-submit">Continue</button>
         </fieldset>

         {{-- ADD QUESTION --}}
         <fieldset style="padding: 0;">
            <div id="add-questions-step">
               <div class="col-md-12" style="padding: 1%;">
                  <span style="font-size: 16pt;" class="exam-title">Exam Title Here</span>
                  <input type="hidden" name="exam_id" class="exam-id">
                  <input type="hidden" class="exam-title">
               </div>
               <div id="add-questions-tab">
                  <ul class="nav nav-tabs" style="text-align: center;">
                     <li class="active"><a href="#tab-part1" data-toggle="tab">Part 1: Multiple Choice</a></li>
                     <li><a href="#tab-part2" data-toggle="tab">Part II: True or False</a></li>
                     <li><a href="#tab-part3" data-toggle="tab">Part III: Essay</a></li>
                     <li><a href="#tab-part4" data-toggle="tab">Part IV: Numerical Exam</a></li>
                     <li><a href="#tab-part5" data-toggle="tab">Part V: Identification</a></li>
                  </ul>
               <div class="tab-content">
                  <div class="tab-pane in active" id="tab-part1">
                     <div class="row">
                        <div class="col-md-12" style="text-align: right;">
                           <a href="#" class="btn btn-primary" data-exam-type="Multiple Choice" id="add-question-btn"><i class="fa fa-plus"></i> Multiple Choice</a>
                        </div>
                        <div class="col-md-12">
                           <div id="multiple-choice-table"></div>
                           {{-- @include('client.tables.questions_multiple_choice_table') --}}
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="tab-part2">
                     <div class="row">
                        <div class="col-md-12" style="text-align: right;">
                           <a href="#" class="btn btn-primary" data-exam-type="True or False" id="add-question-btn"><i class="fa fa-plus"></i> True or False</a>
                        </div>
                        <div class="col-md-12">
                           <div id="true-or-false-table"></div>
                           {{-- @include('client.tables.questions_true_false_table') --}}
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="tab-part3">
                     <div class="row">
                        <div class="col-md-12" style="text-align: right;">
                           <a href="#" class="btn btn-primary" data-exam-type="Essay" id="add-question-btn"><i class="fa fa-plus"></i> Essay</a>
                        </div>
                        <div class="col-md-12">
                           <div id="essay-table"></div>
                           {{-- @include('client.tables.questions_essay_table') --}}
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="tab-part4">
                     <div class="row">
                        <div class="col-md-12" style="text-align: right;">
                           <a href="#" class="btn btn-primary" data-exam-type="Numerical Exam" id="add-question-btn"><i class="fa fa-plus"></i> Numerical Exam</a>
                        </div>
                        <div class="col-md-12">
                           <div id="numerical-exam-table"></div>
                           {{-- @include('client.tables.questions_numerical_exam_table') --}}
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="tab-part5">
                     <div class="row">
                        <div class="col-md-12" style="text-align: right;">
                           <a href="#" class="btn btn-primary" data-exam-type="Identification" id="add-question-btn"><i class="fa fa-plus"></i> Identification</a>
                        </div>
                        <div class="col-md-12">
                           <div id="identification-table"></div>
                           {{-- @include('client.tables.questions_identification_table') --}}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
               {{-- <button type="button" class="action-button previous previous_button">Back</button> --}}
               <button type="button" class="next action-button">Continue</button>
            
         </fieldset>

         <fieldset style="padding: 0;">
            <div class="row">
               <div class="col-md-12" style="text-align: right;">
                  <a href="#" class="btn btn-primary" id="add-examinee-btn"><i class="fa fa-plus"></i> Examinee</a>
               </div>
               <div class="col-md-12">
                  <div id="examinee-table"></div>
                  {{-- @include('client.tables.examinees_table') --}}
               </div>
            </div>
            
            <button type="button" class="action-button previous previous_button">Back</button>
            <button type="button" class="next action-button" id="go-finalize-btn">Continue</button>
            
         </fieldset>

         <fieldset>
            <div class="row" id="finalize-exam-step">
               <div class="inner-box featured">
                  <h2 class="title-2 exam-title">Exam Title Here</h2>
                  <div class="row" style="font-size: 12pt;">
                     <div class="col-sm-2" style="text-align: right;">Exam Group:</div>
                     <div class="col-sm-4" style="text-align: left; font-weight: bold;"><span class="exam-group"></span></div>
                     <div class="col-sm-2" style="text-align: right;">Duration:</div>
                     <div class="col-sm-4" style="text-align: left; font-weight: bold;"><span class="duration"></span> min(s)</div>
                     <div class="col-sm-2" style="text-align: right;">Department:</div>
                     <div class="col-sm-4" style="text-align: left; font-weight: bold;"><span class="department"></span></div>
                     <div class="col-sm-2" style="text-align: right;">Status:</div>
                     <div class="col-sm-4" style="text-align: left; font-weight: bold;"><span class="status"></span></div>
                  </div>
                  <div class="row" style="font-size: 12pt; padding: 7px;">
                     <div class="col-md-12" id="finalize-examinee-table">
                        {{-- @include('client.tables.finalize_examinee_table') --}}
                     </div>
                  </div>
               </div>
            </div>

           <button type="button" class="action-button previous previous_button">Back</button>
           <a href="/tabExams" class="action-button">Finish</a>

         </fieldset>  

  </div> 
</div>
</section> 
</div>