<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
      <meta name="author" content="EstateX">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <title>ESSEX v4.2</title>
      <link rel="stylesheet" href="{{ asset('css/css/bootstrap.min.css') }}" type="text/css">
      <link rel="stylesheet" href="{{ asset('css/fonts/font-awesome.min.css') }}" type="text/css">
      <link rel="stylesheet" href="{{ asset('css/fonts/line-icons/line-icons.css') }}" type="text/css">
      <link rel="stylesheet" href="{{ asset('css/css/main.css') }}" type="text/css">
      <link rel="stylesheet" href="{{ asset('css/extras/animate.css') }}" type="text/css">
      <link rel="stylesheet" href="{{ asset('css/css/responsive.css') }}" type="text/css">
      <link rel="stylesheet" href="{{ asset('css/css/bootstrap-select.min.css') }}">
   </head>
   <style type="text/css">
      *{
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      }
      select{
      height: 30px;
      }
       /* The container */
      .radio_container {
      display: block;
      position: relative;
      padding-left: 25px;
      margin-bottom: 8px;
      cursor: pointer;
      font-size: 13px;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      }
      /* Hide the browser's default radio button */
      .radio_container input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
      }
      /* Create a custom radio button */
      .checkmark {
      position: absolute;
      top: 1px;
      left: 0;
      height: 16px;
      width: 16px;
      background-color: #ddd;
      border-radius: 50%;
      }
      /* On mouse-over, add a grey background color */
      .radio_container:hover input ~ .checkmark {
      background-color: #ccc;
      }
      /* When the radio button is checked, add a blue background */
      .radio_container input:checked ~ .checkmark {
      background-color: #50B200;
      }
      /* Create the indicator (the dot/circle - hidden when not checked) */
      .checkmark:after {
      content: "";
      position: absolute;
      display: none;
      }
      /* Show the indicator (dot/circle) when checked */
      .radio_container input:checked ~ .checkmark:after {
      display: block;
      }
      /* Style the indicator (dot/circle) */
      .radio_container .checkmark:after {
      top: 4px;
      left: 4px;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: white;
      }
           
   </style>
   <body>
      <div class="header">
         <div class="top-bar">
            <div class="container">
               <div class="row">
                  <div class="col-md-7 col-sm-6">
                     <ul class="contact-details">
                        <li>
                           <a href="#">
                           <i class="icon-location-pin"></i>35 Pleasant View Drive, Bagbaguin, Caloocan City
                           </a>
                        </li>
                     </ul>
                  </div>
                  {{--
                  <div class="col-md-5 col-sm-6">
                     <div class="account-setting">
                        <a href="{{ url('/userLogout') }}">
                        <i class="icon-logout"></i>
                        <span>Logout</span>
                        </a>
                     </div>
                  </div>
                  --}}
               </div>
            </div>
         </div>
         
      </div>
      <div class="main-container section">
      <div class="container">
      <div class="row">
       {{--   <div class="col-md-12" style="margin-top: -30px;">
            <h2 class="section-title center">Examination Sheet</h2>
         </div> --}}
         <div class="col-md-12 col-sm-12" style="margin-top: -50px;">
            <div class="inner-box featured">
               <div class="col-md-12">
                  <center><span class="section-title center">Examination Sheet</span></center>
                  <h4 class="h4 text-center">Examination Title: {{$examinee->exam_title}}</h4>
               </div>
               <form method="POST" action="{{route('applicant.save_exam')}}" id="formSaveExam" autocomplete="off">
                  @csrf
                  <input type="hidden" id="examinee_id" name="examinee_id" value="{{$examinee->examinee_id}}">
                  <input type="hidden" id="exam_taken" name="exam_taken" value="{{date('Y-m-d')}}">
                  <input type="hidden" id="exam_id" name="exam_id" value="{{$examinee->exam_id}}">
                  <input type="hidden" id="start_time" name="start_time" value="{{date('H:i:s')}}">
                  <input type="hidden" id="end_time" name="end_time" value="{{date('H:i:s')}}">
                  <input type="hidden" id="dur" name="dur" value="{{date('H:i')}}">
                  <input type="hidden" id="spent" name="spent" value="{{date('H:i')}}">
                  <input type="hidden" id="timeLimitReached" name="timeLimitReached" value="false">

                  <div class="row" style="font-size: 11pt;">
                     <div class="col-md-12">
                        <table style="width: 100%;">
                           <tr>
                              <td style="width: 40%;">
                                 <span class="h4" style="display: block;">Name: {{$examinee->employee_name}}</span>
                                 <span style="display: block;">Today is: {{ date('l, F d, Y') }}</span>
                                 <span style="display: block;">Start Time: <span id="startTimeDisp"></span>
                              </td>
                              <td style="width: 20%; text-align: center;"><span class="h5">Duration <br><span id="duration" class="h3" style="color: darkgreen"></span></span></td>
                              <td style="width: 40%; text-align: center;">
                                 <div class="alert alert-warning" style="font-size: 9pt; text-align: justify; display: inline-block; width: 60%;vertical-align: middle; border: 1px solid; margin: 0;">
                                    <i class="fa fa-info-circle"></i> Please review your answers for each exam category before submitting the exam.<button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#confirm-submit-modal">Submit Exam</button>
                                 </div>
                                 
                              </td>
                           </tr>
                        </table>
                     </div>

                
                     <div class="col-md-12 text-center h3" id="timespent" style="color: maroon;"></div>

                     @php $current_tab = $active_exam_types[0]['type_id']; @endphp
                     <div class="col-md-12 tabbable" style="margin-top: 2%; font-size: 10pt;">
                        @if(count($active_exam_types) > 1)
                        <ul class="nav nav-tabs" style="text-align: left;">
                           @foreach($active_exam_types as $data)
                           <li class="{{ $data['type_id'] == $current_tab ? 'active' : '' }}">
                              <a href="#tab{{ $data['type_id'] }}" data-toggle="tab" style="padding: 10px 8px;">{{ $data['title'] }}</a>
                           </li>
                           @endforeach
                        </ul>
                        @endif
                        <div class="tab-content">
                                 {{-- Multiple Choice --}}
                                 <div class="tab-pane {{ 4 == $current_tab ? 'active' : '' }}" id="tab4">
                                    <div class="row">
                                       <div class="head" style="padding: 10px 16px;background: #555;color: #f1f1f1;margin-bottom: 2%;line-height:10px;">
                                          <span class="h4">Multiple Choice: {!! $multipleChoice_ins->instruction !!}</span>
                                       </div>
                                       <div class="col-md-12">
                                          <table border="0">
                                             @foreach($multipleChoice as $i => $q)
                                             <tr>
                                               <td style="width: 3%;">{{ $i + 1 }}. </td>
                                               <td style="width: 97%;" colspan="4">{!! $q->questions !!}</td></tr>
                                             <tr>
                                               <td></td>
                                                <td style="width: 23%; vertical-align: top; padding-bottom: 30px;">
                                                @if($q->option1)
                                                   <label class="radio_container" style="font-size: 12pt;">
                                                   {{$q->option1}} <br>
                                                   @if($q->option1_img) 
                                                      @php($q->option1_img = '/storage/options/'.$q->option1_img) 
                                                      <img src="{{$q->option1_img}}">
                                                   @endif
                                                   <input class="pl-5 tanong" type="radio" name="{{$q->question_id}}" id="{{$q->question_id}}op1" value="{{$q->option1}}">
                                                   <span class="checkmark"></span>
                                                   </label>
                                                @endif
                                                </td>
                                                <td style="width: 23%; vertical-align: top;">      
                                                @if($q->option2)
                                                   <label class="radio_container" style="font-size: 12pt;">
                                                   {{$q->option2}} <br>
                                                   @if($q->option2_img) 
                                                      @php($q->option2_img = '/storage/options/'.$q->option2_img)
                                                      <img src="{{$q->option2_img}}">
                                                   @endif
                                                   <input class="pl-5 tanong" type="radio" name="{{$q->question_id}}" id="{{$q->question_id}}op2" value="{{$q->option2}}">
                                                   <span class="checkmark"></span>
                                                   </label>
                                                @endif
                                                </td>
                                                <td style="width: 23%; vertical-align: top;">
                                                @if($q->option3)
                                                   <label class="radio_container" style="font-size: 12pt;">
                                                   {{$q->option3}} <br>
                                                   @if($q->option3_img) 
                                                      @php($q->option3_img = '/storage/options/'.$q->option3_img)
                                                      <img src="{{$q->option3_img}}">
                                                   @endif
                                                   <input class="pl-5 tanong" type="radio" name="{{$q->question_id}}" id="{{$q->question_id}}op3" value="{{$q->option3}}">
                                                   <span class="checkmark"></span>
                                                   </label>
                                                @endif
                                                </td>
                                                <td style="width: 23%; vertical-align: top;">
                                                @if($q->option4)
                                                   <label class="radio_container" style="font-size: 12pt;">
                                                   {{$q->option4}} <br>
                                                   @if($q->option4_img) 
                                                      @php($q->option4_img = '/storage/options/'.$q->option4_img)
                                                      <img src="{{$q->option4_img}}">
                                                   @endif
                                                   <input class="pl-5 tanong" type="radio" name="{{$q->question_id}}" id="{{$q->question_id}}op4" value="{{$q->option4}}">
                                                   <span class="checkmark"></span>
                                                   </label>
                                                @endif
                                                </td>
                                             </tr>
                                             @endforeach
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 {{-- True or False --}}
                                 <div class="tab-pane {{ 7 == $current_tab ? 'active' : '' }}" id="tab7">
                                    <div class="row">
                                       <div class="head" style="padding: 10px 16px;background: #555;color: #f1f1f1;margin-bottom: 2%;line-height:10px;">
                                          <span class="h4">Instructions: {!! $trueOrFalse_ins->instruction !!}</span>
                                       </div>
                                       <div class="col-md-12">
                                          <table border="0">
                                             @foreach($trueOrFalse as $i => $q)
                                             <tr>
                                               <td style="width: 3%;">{{ $i + 1 }}. </td>
                                               <td style="width: 97%;">{!! $q->questions !!}</td></tr>
                                             <tr>
                                                <td style="width: 100%;" colspan="2">
                                                <label class="radio_container" style="margin-left: 3%; font-size: 12pt;">True
                                                <input class="pl-5 tanong" type="radio" name="{{$q->question_id}}" id="{{$q->question_id}}true" value="True">
                                                <span class="checkmark"></span>
                                             </label>
                                             <label class="radio_container" style="margin-left: 3%; font-size: 12pt;">False
                                                <input class="pl-5 tanong" type="radio" name="{{$q->question_id}}" id="{{$q->question_id}}false" value="False">
                                                <span class="checkmark"></span>
                                             </label>
                                                </td>
                                             </tr>
                                             @endforeach
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 {{-- Essay --}}
                                 <div class="tab-pane {{ 5 == $current_tab ? 'active' : '' }}" id="tab5">
                                    <div class="row">
                                       <div class="head" style="padding: 10px 16px;background: #555;color: #f1f1f1;margin-bottom: 2%;line-height:10px;">
                                          <span class="h4">Instructions: {!! $essay_ins->instruction !!}</span>
                                       </div>
                                       <div class="col-md-12">
                                          <table border="0">
                                             @foreach($essays as $i => $q)
                                             <tr>
                                               <td style="width: 3%;">{{ $i + 1 }}. </td>
                                               <td style="width: 97%;">{!! $q->questions !!}</td></tr>
                                             <tr>
                                                <td style="width: 100%;" colspan="2">
                                                <textarea rows="4" style="resize: none; font-size: 12pt;" id="{{$q->question_id}}" name="{{$q->question_id}}" placeholder="Type your answer here..."></textarea>
                                                </td>
                                             </tr>
                                             @endforeach
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 {{-- Numerical Exam --}}
                                 <div class="tab-pane {{ 6 == $current_tab ? 'active' : '' }}" id="tab6">
                                    <div class="row">
                                       <div class="head" style="padding: 10px 16px;background: #555;color: #f1f1f1;margin-bottom: 2%;line-height:10px;">
                                          <span class="h4">Instructions: {!! $numericalExam_ins->instruction !!}</span>
                                       </div>
                                       <div class="col-md-12">
                                          <table border="0">
                                             @foreach($numericalExam as $i => $q)
                                             <tr>
                                               <td style="width: 3%;">{{ $i + 1 }}. </td>
                                               <td style="width: 50%;">
                                                <div style=" vertical-align: middle">
                                                   {!! $q->questions !!}
                                                   @if($q->question_img)
               @php($parts = explode(",",$q->question_img))
                  @foreach($parts as $part)
                     @php($part = \App\Support\QuestionImage::url($part))
                     <br><img src="{{$part}}" style="width: 55%;">
                  @endforeach
            @endif
         </div></td>
                                               <td style="width: 47%;"><input type="text" name="{{$q->question_id}}" id="{{$q->question_id}}" placeholder="Enter your answer here..." style="font-size: 12pt;"></td>
                                            </tr>
                                           
                                             @endforeach
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 {{-- Identification --}}
                                 <div class="tab-pane {{ 12 == $current_tab ? 'active' : '' }}" id="tab12">
                                    <div class="row">
                                       <div class="head" style="padding: 10px 16px;background: #555;color: #f1f1f1;margin-bottom: 2%;line-height:10px;">
                                          <span class="h4">Instructions: {!! $identification_ins->instruction !!}</span>
                                       </div>
                                       <div class="col-md-12">
                                          <table border="0">
                                             @foreach($identifications as $i => $q)
                                             <tr>
                                               <td style="width: 3%;">{{ $i + 1 }}. </td>
                                               <td style="width: 50%;">{!! $q->questions !!}</td>
                                               <td style="width: 47%;"><input type="text" name="{{$q->question_id}}" id="{{$q->question_id}}" placeholder="Enter your answer here..." style="font-size: 12pt;"></td>
                                             </tr>
                                             <tr>
                                                {{-- <td style="width: 100%;" colspan="2">
                                                   <input type="text" name="{{$q->question_id}}" id="{{$q->question_id}}" placeholder="Enter your answer here..." style="font-size: 12pt;">
                                                </td> --}}
                                             </tr>
                                             @endforeach
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 {{-- Abstract Reasoning --}}
                                 <div class="tab-pane {{ 13 == $current_tab ? 'active' : '' }}" id="tab13">
                                    <div class="row">
                                       <div class="head" style="padding: 10px 16px;background: #555;color: #f1f1f1;margin-bottom: 2%;line-height:10px;">
                                          <span class="h4">Instructions: {!! $abstract_ins->instruction !!}</span>
                                       </div>
                                       <div class="col-md-12">
                                          <table border="0">
                                             @foreach($abstracts as $i => $q)
                                             <tr>
                                               <td style="width: 3%;">{{$i + 1}}.</td>
                                               @if($q->question_img) 
                                                 @php($parts = explode(",",$q->question_img)) 
                                                 @foreach($parts as $part) 
                                                   @php($part = \App\Support\QuestionImage::url($part)) 
                                                   <td style="vertical-align: middle; width: 50%; text-align: center;">
                                                     <img src="{{$part}}" width="450" height="120">
                                                   </td>
                                                   <td style="width: 47%;">
                                                     <select   name="{{$q->question_id}}" id="{{$q->question_id}}" style="font-size: 15pt;  width: 30%; height: 50px; text-align-last:center;">
                                                       <option value=''>--select--</option>
                                                       <option value='1'>1</option>
                                                       <option value='2'>2</option>
                                                       <option value='3'>3</option>
                                                       <option value='4'>4</option>
                                                       <option value='5'>5</option>
                                                     </select>
                                                   </td>
                                                 @endforeach
                                               @endif
                                             </tr>                  
                                           @endforeach
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 {{-- Dexterity & Accuracy 1 --}}
                                 <div class="tab-pane {{ 14 == $current_tab ? 'active' : '' }}" id="tab14">
                                    <div class="row">
                                       <div class="head" style="padding: 10px 16px;background: #555;color: #f1f1f1;margin-bottom: 2%;line-height:10px;">
                                          <span class="h4">Instructions: {!! $dexterity1_ins->instruction !!}</span>
                                       </div>
                                       <div class="col-md-12">
                                          <table border="0">
                                             @foreach($dexterity1 as $i => $q)
                                             <tr>
                                               <td style="width: 3%;">{{ $i + 1 }}. </td>
                                               <td style="width: 50%;">{!! $q->questions !!}</td>
                                               <td style="width: 47%;" colspan="2">
                                                   <input type="text" name="{{$q->question_id}}" id="{{$q->question_id}}" placeholder="Enter your answer here..." style="font-size: 12pt;">
                                                </td>
                                             </tr>
                                           @endforeach
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 {{-- Dexterity & Accuracy 2 --}}
                                 <div class="tab-pane {{ 15 == $current_tab ? 'active' : '' }}" id="tab15">
                                    <div class="row">
                                       <div class="head" style="padding: 10px 16px;background: #555;color: #f1f1f1;margin-bottom: 2%;line-height:10px;">
                                          <span class="h4">Instructions: {!! $dexterity2_ins->instruction !!}</span>
                                       </div>
                                       <div class="col-md-12">
                                          <table border="0">
                                             @foreach($dexterity2 as $i => $q)
                                             <tr>
                                               <td style="width: 3%;">{{ $i + 1 }}. </td>
                                               <td style="width: 50%;">{!! $q->questions !!}</td>
                                               <td style="width: 47%;" colspan="2">
                                                   <input type="text" name="{{$q->question_id}}" id="{{$q->question_id}}" placeholder="Enter your answer here..." style="font-size: 12pt;">
                                                </td>
                                             </tr>
                                           @endforeach
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 {{-- Dexterity & Accuracy 3 --}}
                                 <div class="tab-pane {{ 16 == $current_tab ? 'active' : '' }}" id="tab16">
                                    <div class="row">
                                       <div class="head" style="padding: 10px 16px;background: #555;color: #f1f1f1;margin-bottom: 2%;line-height:10px;">
                                          <span class="h4">Instructions: {!! $dexterity3_ins->instruction !!}</span>
                                       </div>
                                       <div class="col-md-12">
                                          <table border="0">
                                             @foreach($dexterity3 as $i => $q)
                                             <tr>
                                               <td style="width: 3%;">{{ $i + 1 }}. </td>
                                               <td style="width: 50%;">{!! $q->questions !!}</td>
                                               <td style="width: 47%;" colspan="2">
                                                   <input type="text" name="{{$q->question_id}}" id="{{$q->question_id}}" placeholder="Enter your answer here..." style="font-size: 12pt;">
                                                </td>
                                             </tr>
                                           @endforeach
                                          </table>





                                       </div>
                                    </div>
                                 </div>
                        </div>
                     </div>
                  </div>
                   <div class="btn-group" style="margin-left: 75%;">
                     <button class="btn btn-primary" id="prevtab" type="button" onclick="topFunction()">Prev</button>
                     <button class="btn btn-primary" id="nexttab" type="button" onclick="topFunction()">Next</button>
                  </div>
     <!-- The Modal -->
<div class="modal fade" id="confirm-submit-modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Confirm Exam Submission</h4>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row" style="margin: -5px 0 -5px 0; font-size: 12pt;">
               <div class="col-sm-12">
                  Are you sure you want to submit the exam?
               </div>               
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="saveform"><i class="fa fa-check"></i> Yes</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
         </div>
      </div>
   </div>
</div>
               </form>
            </div>
         </div>
      </div>
   
   <style type="text/css">
   table{
      width: 100%;
   }
   table td{
      padding: 0.9%;
      /*font-size: 1.2em;*/
   }
   .nav-tabs > li {
      float: none;
      display: inline-block;
   }

   input, select{
      height: 37px;
      width: 100%;
   }
   textarea{
      width: 100%;
   }

   .scrollme {
  padding: 16px;
}

.fixed {
    position: fixed;
    top:0; left:0;
    width: 100%;
    z-index: 1; }
</style>
            </div>
         </div>
      </div>
      <div id="copyright">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="site-info text-center">
                     <p>&copy; All rights reserved 2019</p>
                  </div>
               </div>
            </div>
         </div>
      </div>

 


      <script src="{{ asset('css/js/ajax.min.js') }}"></script> 
      <script type="text/javascript" src="{{ asset('css/js/jquery-min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('css/js/bootstrap.min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('css/js/form-validator.min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('css/js/jquery.bootstrap-growl.js') }}"></script>
      <script src="{{ asset('css/js/bootstrap-select.min.js') }}"></script>
      <script type = "text/javascript" src = "{{ asset('css/js/jquery-ui.min.js') }}"></script>
      <script>  
         var stickyOffset = $('.head').offset().top;

            $(window).scroll(function(){
               var sticky = $('.head');
               scroll = $(window).scrollTop();

             if (scroll >= 400) {
                 sticky.addClass('fixed');
             }
             else {
                 sticky.removeClass('fixed');
             }
         });

         var $tabs = $('.tabbable li');

         $('#prevtab').on('click', function() {
             $tabs.filter('.active').prev('li').find('a[data-toggle="tab"]').tab('show');
         });
         
         $('#nexttab').on('click', function() {
             $tabs.filter('.active').next('li').find('a[data-toggle="tab"]').tab('show');
         });


         function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
         }


         $('#notif').hide()
         window.onbeforeunload = function () {
           // $('#formSaveExam').submit();
           return false;
         };
         
         function disableF5(e) { if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82) e.preventDefault(); };
         
         
           $(document).ready(function(){
                $(document).on("keydown", disableF5);
           });
         
           function preventBack(){window.history.forward();}
           setTimeout(preventBack, 1);
           window.onunload=function () {$('#formSaveExam').submit();};
         
         
         $(document).ready(function(){
           var d = new Date($.now());
           var latestTime = d.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second:'numeric', hour12:false });
           $('#start_time').val(latestTime);
           $('#startTimeDisp').text(d.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second:'numeric', hour12: true }));
           var dbDur = {{$examinee->duration_in_minutes}};
           var durDisp = dbDur;
           if(dbDur < 10){ durDisp = '0' + dbDur; }
         
           $('#duration').text(durDisp+":00");
           var rem_secs = max = {{$examinee->duration_in_minutes}} * 60;
           var rem_mins = rem_secs / 60;
           var minR = 0;
           var secR = 60;
           var minL = secL = 0;
           var timer = setInterval(function(){ 
             rem_secs--;
             rem_mins = rem_secs / 60;
             minR = parseInt(rem_mins);
             secR > 0 ? secR-- : secR = 59;
         
             
         
             var remSecDisp,remMinDisp;
         
             secR < 10 ? remSecDisp = '0' + secR : remSecDisp = secR;
             minR < 10 ? remMinDisp = '0' + minR : remMinDisp = minR;
         
             document.getElementById('duration').innerHTML = remMinDisp + ':' + remSecDisp;
             $('#dur').val($('#duration').text());
             // document.getElementById('viewDur').innerHTML = "Duration: " + remMinDisp + ':' + remSecDisp;
         
             secL++;
             if(secL == 60){
               minL++;
               secL = 0;
             }
         
             var lapSecDisp,lapMinDisp;
         
             secL < 10 ? lapSecDisp = '0' + secL : lapSecDisp = secL;
             minL < 10 ? lapMinDisp = '0' + minL : lapMinDisp = minL;
             var timelapsed = lapMinDisp + ':' + lapSecDisp;
             $('#spent').val(timelapsed);
             // document.getElementById('timespent').innerHTML = lapMinDisp + ':' + lapSecDisp;
             // document.getElementById('viewSpent').innerHTML = "Time Spent: " + lapMinDisp + ':' + lapSecDisp;
               $('#timespent').hide();
             if(rem_secs == 180){
               $('#timespent').show();
               $('#timespent').addClass('alert alert-danger');
               // alert($('#timespent').scrollTop());
               $('#timespent').text("WARNING: 3 MINUTES REMAINING BEFORE AUTO-SUBMIT!");
               window.scrollTo(0,0);
         
               // $('#formSaveExam input, #formSaveExam textarea, #formSaveExam select').attr('readonly', 'readonly');
               // $('#formSaveExam input[type=radio]:not(:checked)').attr('disabled', true);
             }
             if(rem_secs <= 180){
               $('#duration').css('color','darkred');
             }
         
             if(rem_secs == 0){
               clearInterval(timer);
               // alert('TIME LIMIT REACHED!');
               $('#timeLimitReached').val('true');
               $('#formSaveExam input, #formSaveExam textarea, #formSaveExam select').attr('readonly', 'readonly');
               $('#formSaveExam input[type=radio]:not(:checked)').attr('disabled', true);
               $('#formSaveExam').submit();
             }
           },1000);
         });
         
         $('#formSaveExam').submit(function(e){
           e.preventDefault();
           
           $('#formSaveExam input, #formSaveExam textarea, #formSaveExam select').attr('readonly', 'readonly');
           $('#formSaveExam input[type=radio]:not(:checked)').attr('disabled', true);
           var d = new Date($.now());
           var latestTime = d.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second:'numeric', hour12:false });
           $('#end_time').val(latestTime);
           $('#dur').val($('#duration').text());
           $('#spent_time').val($('#timespent').text());
           $(this).unbind('submit').submit();
           $('#saveform')
            .text("Please Wait...")
            .attr('disabled', 'disabled');
         });
      </script>

      <script type="text/javascript">
  $(document).ready(function() {
      window.history.pushState(null, "", window.location.href);        
      window.onpopstate = function() {
          window.history.pushState(null, "", window.location.href);
      };
  });
</script>
   </body>
</html>