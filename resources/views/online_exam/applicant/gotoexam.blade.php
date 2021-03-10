<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="author" content="EstateX">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
      <title>ESSEX v5 - Online Exam</title>
      <link rel="stylesheet" href="{{ asset('css/css/bootstrap.min.css') }}" type="text/css">
      <link rel="stylesheet" href="{{ asset('css/fonts/font-awesome.min.css') }}" type="text/css">
      <link rel="stylesheet" href="{{ asset('css/fonts/line-icons/line-icons.css') }}" type="text/css">
      <link rel="stylesheet" href="{{ asset('css/css/main.css') }}" type="text/css">
      <link rel="stylesheet" href="{{ asset('css/extras/animate.css') }}" type="text/css">
      <link rel="stylesheet" href="{{ asset('css/css/responsive.css') }}" type="text/css">
      <link rel="stylesheet" href="{{ asset('css/css/bootstrap-select.min.css') }}">

   </head>
   <style type="text/css">
      #msform fieldset:not(:first-of-type) {
         display: none;
      }
      *{
         font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      }
      .msg-alert{
         text-align: center;
         font-size: 15pt;
      }
      #tabs .nav-tabs > li {
         float: none;
         display: inline-block;
      }
   </style>
   <body>
      <div class="main-container section">
         <div class="container">
            <div class="row">
               <div class="col-md-12 col-sm-12" style="margin-top: -20px;">
                  <div class="inner-box featured">
                     <div class="col-md-12">
                        <center><span class="section-title center">Examination Sheets</span></center>
                        <input type="hidden" value="{{ $examinee->duration_in_minutes }}" id="exam-duration">
                     </div>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="row">
                              <div class="col-md-12">
                                 Name: <b>{{ $examinee->employee_name }}</b>
                              </div>
                              <div class="col-md-12">
                                 Exam Title: <b>{{ $examinee->exam_title }}</b>
                              </div>
                           </div>
                        </div>
                        <a href="">click here</a>
                        <div class="col-md-4 text-center" id="time-remaining-div">
                           <span style="font-size: 15pt; display: block;" class="m-0 p-0" id="time-remaining-disp"></span>
                           <small class="m-0 p-0" style="text-transform: uppercase;">Time Remaining</small>
                        </div>
                        <div class="col-md-4">
                           <div class="row">
                              <div class="col-md-12">
                                 Today is: <b>{{ date('l, F d, Y') }}</b>
                              </div>
                              <div class="col-md-12">
                                 Start Time: <b><span id="start-time-disp"></span></b>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div class="row" style="font-size: 11pt;" id="tabs">
                        @php $current_tab = $active_exam_types[0]['type_id']; @endphp
                        <div style="margin-top: 2%; font-size: 10pt;" class="col-md-12 tabbable">
                           @if(count($active_exam_types) > 1)
                           <ul class="nav nav-tabs" style="text-align: center;">
                              @foreach($active_exam_types as $data)
                              <li class="{{ $data['type_id'] == $current_tab ? 'active' : '' }}">
                                 <a href="#tab{{ $data['type_id'] }}" data-toggle="tab" style="padding: 10px 8px;">{{ $data['title'] }}</a>
                              </li>
                              @endforeach
                           </ul>
                           @endif

                           <div class="tab-content">
                              @if(count($active_exam_types) > 1)
                              @foreach($active_exam_types as $data)
                              <div class="tab-pane {{ $data['type_id'] == $current_tab ? 'active' : '' }}" id="tab{{ $data['type_id'] }}">
                                 <h5>Instructions:</h5>
                                 <p>{!! $data['instruction'] !!}</p>
                                 <hr>
                                 <div id="msform">
                                    @include('online_exam.applicant.questions')
                                 </div>
                              </div>
                              @endforeach
                              @endif
                           </div>
                        </div>
                     </div>
                     <div class="row" id="review ">
                        <div class="col-md-12">
                           @foreach($answer as $a => $lvl0)
                             <div class="col-md-2" style="font-size: 6pt;line-height: 10pt;padding-top: 2px;" height=200><h5>{{ $lvl0['exam_name'] }}</h5><br>
                             

                               @foreach($lvl0['nodess'] as $lvl2)
                               <label style="font-size: 10pt;">{{ $lvl2['q_no'] }}.) {{ $lvl2['examinee_answer'] == '' ? 'No Answer' :$lvl2['examinee_answer']}} </label><br>
                               @endforeach
                             </div>
                           @endforeach
                        </div>
                   </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
                           

      

      <!-- Modal -->
      <div class="modal fade" id="warning-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center" id="modal-title">EXAM ENDED</h4>
            </div>
            <div class="modal-body text-center">
               <span style="font-size: 13pt;"></span>
            </div>
            <div class="modal-footer" style="text-align: center;">
              <button type="button" class="btn btn-danger" id="ended-modal-btn">Close</button>
            </div>
          </div>
        </div>
      </div>

      <script src="{{ asset('css/js/ajax.min.js') }}"></script> 
      <script src="{{ asset('/js/exam.js')}}" type="text/javascript"></script>
      <script type="text/javascript" src="{{ asset('css/js/jquery-min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('css/js/bootstrap.min.js') }}"></script>
      {{-- <script type="text/javascript" src="{{ asset('css/js/form-validator.min.js') }}"></script> --}} 
      <script type="text/javascript" src="{{ asset('css/js/jquery.bootstrap-growl.js') }}"></script>
      <script src="{{ asset('css/js/bootstrap-select.min.js') }}"></script>
      <script type = "text/javascript" src = "{{ asset('css/js/jquery-ui.min.js') }}"></script>
      <script type="text/javascript">
         var current_fs, next_fs, previous_fs; //fieldsets
         var left, opacity, scale; //fieldset properties which we will animate
         var animating; //flag to prevent quick multi-click glitches

         $(".next").click(function(){
            if(animating) return false;
            animating = true;
           
            current_fs = $(this).parent().parent().parent();
            next_fs = $(this).parent().parent().parent().next();
           
            //show the next fieldset
            next_fs.show(); 
            current_fs.hide();
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
               step: function(now, mx) {
               //as the opacity of current_fs reduces to 0 - stored in "now"
               //1. scale current_fs down to 80%
               scale = 1 - (1 - now) * 0.2;
               //2. bring next_fs from the right(50%)
               left = (now * 50)+"%";
               //3. increase opacity of next_fs to 1 as it moves in
               opacity = 1 - now;
               current_fs.css({'transform': 'scale('+scale+')'});
               next_fs.css({'left': left, 'opacity': opacity});
            }, 
            duration: 500, 
            complete: function(){
               current_fs.hide();
               animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeOutQuint'
            });
         });

         $(".previous").click(function(){
            if(animating) return false;
            animating = true;
           
            current_fs = $(this).parent().parent().parent();
            previous_fs = $(this).parent().parent().parent().prev();
           
            //show the previous fieldset
            previous_fs.show(); 
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
               step: function(now, mx) {
                  //as the opacity of current_fs reduces to 0 - stored in "now"
                  //1. scale previous_fs from 80% to 100%
                  scale = 0.8 + (1 - now) * 0.2;
                  //2. take current_fs to the right(50%) - from 0%
                  left = ((1-now) * 50)+"%";
                  //3. increase opacity of previous_fs to 1 as it moves in
                  opacity = 1 - now;
                  current_fs.css({'left': left});
                  previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
               }, 
               duration: 500, 
               complete: function(){
                  current_fs.hide();
                  animating = false;
               }, 
               //this comes from the custom easing plugin
               easing: 'easeOutQuint'
            });
         });

      $(document).ready(function(){
         console.log('ready');


         $('#start-exam-btn').click(function(){
            var d = new Date($.now());
            var start_time = d.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second:'numeric', hour12: false });
            var start_time_disp = d.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second:'numeric', hour12: true });


                  setInterval(timeRemaining, 1000);
                  setInterval(timeSpent, 1000);

                  $('#exam-details-modal').modal('hide');

         });
         
         var exam_duration = $('#exam-duration').val();
         $('#time-remaining-disp').text(exam_duration + ':00');
         $('#time-remaining-div').css('color', '#1E8449');
         $('.time-remaining').val(exam_duration + ':00');
         var exam_duration = exam_duration * 60;
         function timeRemaining(){
            if (exam_duration <= 180) {
               $('#time-remaining-div').css('color', '#C0392B');
            }

            if (exam_duration == 180) {
               $.bootstrapGrowl("<center><span class='msg-alert'>WARNING: Exam will end in 3 minute(s).</span></center>", {
                  type: 'danger',
                  align: 'center',
                  width: 450,
                  delay: 10000,
                  stackup_spacing: 10,
                  allow_dismiss: false
               });
            }

            if (exam_duration <= 0) {
               $('#time-remaining-disp').text('Time is up!');
               $('#warning-modal span').text("Time is up! Your examination has ended.");
               $('#warning-modal').modal({backdrop: 'static', keyboard: false});
               $('#warning-modal').modal('show');
            }

            if (exam_duration > 0) {
               --exam_duration;
               m = pad(parseInt(exam_duration / 60));
               s = pad(exam_duration % 60);
               $('#time-remaining-disp').text(m + ':' + s);
            }
            
            $('.time-remaining').val(m + ':' + s);  
         }
         $('.time-spent').val('00:00');
         var totalSeconds = 0;
         function timeSpent(){
            ++totalSeconds;
            m = pad(parseInt(totalSeconds / 60));
            s = pad(totalSeconds % 60);
            $('.time-spent').val(m + ':' + s);
         }

         function pad(val) {
            var valString = val + "";
            if (valString.length < 2) {
               return "0" + valString;
            } else {
               return valString;
            }
         }

         $(window).keydown(function(event){
            if(event.keyCode == 116) {
               event.preventDefault();
               return false;
            }
         });

         window.history.pushState(null, "", window.location.href);        
         window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
         };

         window.onbeforeunload = function () {
           return false;
         };

         var $tabs = $('.tabbable li');

         $('.prevtab').on('click', function() {
             $tabs.filter('.active').prev('li').find('a[data-toggle="tab"]').tab('show');
         });
         
         $('.nexttab').on('click', function() {
             $tabs.filter('.active').next('li').find('a[data-toggle="tab"]').tab('show');
         });

         $('#ended-modal-btn').click(function(){
            window.location.href = "/applicant";
         });
      });
      </script>
   </body>
</html>