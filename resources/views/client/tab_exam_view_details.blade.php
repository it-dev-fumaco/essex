@extends('client.app')
@section('content')
<style type="text/css">
   #exam-question-tabs .nav-tabs > li {
      float: none;
      display: inline-block;
   }
   input, select{
      height: 35px;
      width: 100%;
   }
</style>
<div class="row">
   <div class="col-md-12" style="margin-top: -30px;">
      <h2 class="section-title center">Online Examination System</h2>
      <a href="/tabExams">
         <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-top: -68px; float: left;"></i>
      </a>
   </div>
   <div class="col-md-12 col-sm-12">
      <div class="inner-box featured">
         <h2 class="title-2">Exam Detail(s)</h2>
         <div class="row" style="font-size: 11pt;">
            <div class="col-sm-2" style="padding-left: 7%; text-align: left;">Exam Title:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{ $exam->exam_title }}</div>
            <div class="col-sm-2" style="padding-left: 7%; text-align: left;">Department:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;"> @if($exam->department_id == -1)
                                 All Departments
                                 @elseif($exam->department_id == 0)
                                 Applicants
                                 @else
                                 {{ $exam->department }}
                                 @endif</div>
            <div class="col-sm-2" style="padding-left: 7%; text-align: left;">Exam Group:</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{ $exam->exam_group_description }}</div>
            <div class="col-sm-2" style="padding-left: 7%; text-align: left;">Total Item(s):</div>
            <div class="col-sm-4" style="text-align: left; font-weight: bold;">{{ $questions_count }}</div>

            <div class="col-md-12" style="margin-top: 2%;" id="exam-question-tabs">
               @if(session("message"))
                  <div class='alert alert-success alert-dismissible' style="margin: 2% 0 2% 0;">
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>
                     <center>{{ session("message") }}</center>
                  </div>
               @endif
               <ul class="nav nav-tabs" style="text-align: center; font-size: 10pt;">
                  <li class="active"><a href="#multiplechoice" data-toggle="tab" style="padding: 10px 8px;">Multiple Choice</a></li>
                  <li><a href="#truefalse" data-toggle="tab" style="padding: 10px 8px;">True or False</a></li>
                  <li><a href="#essay" data-toggle="tab" style="padding: 10px 8px;">Essay</a></li>
                  <li><a href="#numerical" data-toggle="tab" style="padding: 10px 8px;">Numerical Exam</a></li>
                  <li><a href="#identif" data-toggle="tab" style="padding: 10px 8px;">Identification</a></li>
                  <li><a href="#abstract" data-toggle="tab" style="padding: 10px 8px;">Abstract</a></li>
                  <li><a href="#dexterity1" data-toggle="tab" style="padding: 10px 8px;">Dexterity & Accuracy 1</a></li>
                  <li><a href="#dexterity2" data-toggle="tab" style="padding: 10px 8px;">Dexterity & Accuracy 2</a></li>
                  <li><a href="#dexterity3" data-toggle="tab" style="padding: 10px 8px;">Dexterity & Accuracy 3</a></li>
                  <li><a href="#frequent-mistakes" data-toggle="tab" style="padding: 10px 8px;">View Frequent Mistakes</a></li>
               </ul>
               <div class="tab-content">
                  <div class="tab-pane active" id="multiplechoice">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="pull-right">
                              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addMultipleChoice">
                                 <i class="fa fa-plus"></i> Multiple Choice
                              </a>
                           </div>
                           @foreach($examtypes as $e)
                              @if($e->exam_type_id == 4)
                                 <span style="display: block;">
                                    <b>Instructions:</b> <a href="#" data-toggle="modal" data-target="#editInstruction{{ $e->exam_type_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                 </span>
                                 <div style="padding-left: 3%;">{!! $e->instruction !!}</div>
                              @include('client.modals.tab_edit_instruction')
                              @endif
                           @endforeach
                           <br>
                           @include('client.tables.tab_exam_details_multiplechoice')
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="truefalse">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="pull-right">
                              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addTrueFalse">
                                 <i class="fa fa-plus"></i> True or False
                              </a>
                           </div>
                           @foreach($examtypes as $e)
                              @if($e->exam_type_id == 7)
                                 <span style="display: block;">
                                    <b>Instructions:</b> <a href="#" data-toggle="modal" data-target="#editInstruction{{ $e->exam_type_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                 </span>
                                 <div style="padding-left: 3%;">{!! $e->instruction !!}</div>
                              @include('client.modals.tab_edit_instruction')
                              @endif
                           @endforeach
                           <br>
                           @include('client.tables.tab_exam_details_trueorfalse')
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="essay">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="pull-right">
                              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addEssay">
                                 <i class="fa fa-plus"></i> Essay
                              </a>
                           </div>
                           @foreach($examtypes as $e)
                              @if($e->exam_type_id == 5)
                                 <span style="display: block;">
                                    <b>Instructions:</b> <a href="#" data-toggle="modal" data-target="#editInstruction{{ $e->exam_type_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                 </span>
                                 <div style="padding-left: 3%;">{!! $e->instruction !!}</div>
                              @include('client.modals.tab_edit_instruction')
                              @endif
                           @endforeach
                           <br>
                           @include('client.tables.tab_exam_details_essay')
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="numerical">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="pull-right">
                              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addNumerical">
                                 <i class="fa fa-plus"></i> Numerical Exam
                              </a>
                           </div>
                           @foreach($examtypes as $e)
                              @if($e->exam_type_id == 6)
                                 <span style="display: block;">
                                    <b>Instructions:</b> <a href="#" data-toggle="modal" data-target="#editInstruction{{ $e->exam_type_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                 </span>
                                 <div style="padding-left: 3%;">{!! $e->instruction !!}</div>
                              @include('client.modals.tab_edit_instruction')
                              @endif
                           @endforeach
                           <br>
                           @include('client.tables.tab_exam_details_numericalexam')
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="identif">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="pull-right">
                              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addIdentif">
                                 <i class="fa fa-plus"></i> Identification
                              </a>
                           </div>
                           @foreach($examtypes as $e)
                              @if($e->exam_type_id == 12)
                                 <span style="display: block;">
                                    <b>Instructions:</b> <a href="#" data-toggle="modal" data-target="#editInstruction{{ $e->exam_type_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                 </span>
                                 <div style="padding-left: 3%;">{!! $e->instruction !!}</div>
                              @include('client.modals.tab_edit_instruction')
                              @endif
                           @endforeach
                           <br>
                           @include('client.tables.tab_exam_details_identification')
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="abstract">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="pull-right">
                              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addAbstract">
                                 <i class="fa fa-plus"></i> Abstract Reasoning
                              </a>
                           </div>
                           @foreach($examtypes as $e)
                              @if($e->exam_type_id == 13)
                                 <span style="display: block;">
                                    <b>Instructions:</b> <a href="#" data-toggle="modal" data-target="#editInstruction{{ $e->exam_type_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                 </span>
                                 <div style="padding-left: 3%;">{!! $e->instruction !!}</div>
                              @include('client.modals.tab_edit_instruction')
                              @endif
                           @endforeach
                           <br>
                           @include('client.tables.tab_exam_details_abstract')
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="dexterity1">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="pull-right">
                              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addDexterity1">
                                 <i class="fa fa-plus"></i> Dexterity & Accuracy 1
                              </a>
                           </div>
                           @foreach($examtypes as $e)
                              @if($e->exam_type_id == 14)
                                 <span style="display: block;">
                                    <b>Instructions:</b> <a href="#" data-toggle="modal" data-target="#editInstruction{{ $e->exam_type_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                 </span>
                                 <div style="padding-left: 3%;">{!! $e->instruction !!}</div>
                              @include('client.modals.tab_edit_instruction')
                              @endif
                           @endforeach
                           <br>
                           @include('client.tables.tab_exam_details_dexterity1')
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="dexterity2">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="pull-right">
                              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addDexterity2">
                                 <i class="fa fa-plus"></i> Dexterity & Accuracy 2
                              </a>
                           </div>
                           @foreach($examtypes as $e)
                              @if($e->exam_type_id == 15)
                                 <span style="display: block;">
                                    <b>Instructions:</b> <a href="#" data-toggle="modal" data-target="#editInstruction{{ $e->exam_type_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                 </span>
                                 <div style="padding-left: 3%;">{!! $e->instruction !!}</div>
                              @include('client.modals.tab_edit_instruction')
                              @endif
                           @endforeach
                           <br>
                           @include('client.tables.tab_exam_details_dexterity2')
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="dexterity3">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="pull-right">
                              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addDexterity3">
                                 <i class="fa fa-plus"></i> Dexterity & Accuracy 3
                              </a>
                           </div>
                           @foreach($examtypes as $e)
                              @if($e->exam_type_id == 16)
                                 <span style="display: block;">
                                    <b>Instructions:</b> <a href="#" data-toggle="modal" data-target="#editInstruction{{ $e->exam_type_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                 </span>
                                 <div style="padding-left: 3%;">{!! $e->instruction !!}</div>
                              @include('client.modals.tab_edit_instruction')
                              @endif
                           @endforeach
                           <br>
                           @include('client.tables.tab_exam_details_dexterity3')
                        </div>
                     </div>
                  </div>

                  <div class="tab-pane" id="frequent-mistakes">
                     <div class="row">
                        <div class="col-md-12">
                           @include('client.tables.frequent_mistakes')
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

@include('client.modals.tab_add_question_multiplechoice')
@include('client.modals.tab_add_question_trueorfalse')
@include('client.modals.tab_add_question_essay')
@include('client.modals.tab_add_question_numericalexam')
@include('client.modals.tab_add_question_identification')
@include('client.modals.tab_add_question_abstract')
@include('client.modals.tab_add_question_dexterity1')
@include('client.modals.tab_add_question_dexterity2')
@include('client.modals.tab_add_question_dexterity3')


@endsection

@section('script')

<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
<script>
   CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
   CKEDITOR.config.resize_enabled = true;
   CKEDITOR.config.autoParagraph = false;
   CKEDITOR.config.height = 100; 

   CKEDITOR.replace( 'ckeditor-multiple-choice', {
      removeButtons: ''
   });
   CKEDITOR.replace( 'ckeditor-trueorfalse', {
      removeButtons: ''
   });
   CKEDITOR.replace( 'ckeditor-essay', {
      removeButtons: ''
   });
   CKEDITOR.replace( 'ckeditor-numerical-exam', {
      removeButtons: ''
   });
   CKEDITOR.replace( 'ckeditor-identification', {
      removeButtons: ''
   });
   CKEDITOR.replace( 'ckeditor-abstract', {
      removeButtons: ''
   });
   CKEDITOR.replace( 'ckeditor-dexterity1', {
      removeButtons: ''
   });
   CKEDITOR.replace( 'ckeditor-dexterity2', {
      removeButtons: ''
   });
   CKEDITOR.replace( 'ckeditor-dexterity3', {
      removeButtons: ''
   });

   
$('.btn-danger').click(function(event){
  CKEDITOR.instances['questions'].setData('');
});

$('.modal').on('hidden.bs.modal', function(){
  $(this).find('form')[0].reset();
});
</script>
@endsection
