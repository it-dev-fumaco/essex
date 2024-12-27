@extends('client.app')
@section('content')
@include('client.modules.nav_menu')
<div class="col-md-12">
   <h2 class="section-title center">Online Examination System</h2>
   <a href="/home">
      <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-bottom: -50px; float: left;"></i>
   </a>
</div>

<div id="online-exam-tab">
   @include('client.tab_header_admin', ['current_page' => '/examPanel'])
   <div class="tab-content">
      <div class="tab-pane in active" id="tab-create-exam">
         <div class="row">
            @include('client.includes.create_examination_sheet')
         </div>
      </div>
      <div class="tab-pane" id="tab-exam-list">
         <div class="row">
            <div class="inner-box featured"></div>
         </div>
      </div>
      <div class="tab-pane" id="tab-examinee-list">
         <div class="row">
            <div class="inner-box featured"></div>
         </div>
      </div>
      <div class="tab-pane" id="tab-exam-report">
         <div class="row">
            <div class="inner-box featured"></div>
         </div>
      </div>
      <div class="tab-pane" id="tab-exam-settings">
         <div class="row">
            <div class="inner-box featured"></div>
         </div>
      </div>
   </div>
</div>

@include('client.modals.add_question')
@include('client.modals.edit_question')
@include('client.modals.delete_question')
@include('client.modals.add_examinee')

@endsection

@section('script')
<script src="{{ asset('css/js/exam_wizard.js') }}"></script>
<script>
;(function($) {
    "use strict";  
    
    //* Form js
    function verificationForm(){
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        $(".next").click(function () {
            if (animating) return false;
            animating = true;

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

            //activate next step on progressbar using the index of next_fs
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = (now * 50) + "%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'position': 'absolute'
                    });
                    next_fs.css({
                        'left': left,
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        });

        $(".previous").click(function () {
            if (animating) return false;
            animating = true;

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //de-activate current step on progressbar
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = ((1 - now) * 50) + "%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'left': left
                    });
                    previous_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        });

    }; 
    
    /*Function Calls*/  
    verificationForm ();
})(jQuery); 
</script>

@endsection
<style>
.panel-box {
    margin-bottom: 20px;
    background-color: #fff;
    border: 1px solid transparent;
    border-radius: 4px;
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
    box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.panel-box-default {
    border-color: #ddd;
}
.panel-box-default>.panel-box-heading {
    color: #333;
    background-color: #f5f5f5;
    border-color: #ddd;
}
.panel-box-heading {
    padding: 10px 15px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
}

.panel-box-body {
    padding: 15px;
}

.panel-box-list li{
   padding: 5px 5px;
   border-bottom: 1px solid #F5F5F5;
}

.panel-box-list li:last-child{
   border-bottom: 0px;
}


.multi_step_form {
  display: block;
  overflow: hidden;
  /*border: 1px solid grey;*/
  /*box-shadow: 2px 1px 1px grey;*/
}
.multi_step_form #msform {
  text-align: center;
  position: relative;
  padding-top: 50px;
  min-height: 820px;
  /*max-width: 810px;*/
  margin: 0 auto;
  background: #ffffff;
  z-index: 1;
}
.multi_step_form #msform .tittle {
  text-align: center;
  padding-bottom: 55px;
}
.multi_step_form #msform .tittle h2 {
  font: 500 24px/35px "Roboto", sans-serif;
  color: #3f4553;
  padding-bottom: 5px;
}
.multi_step_form #msform .tittle p {
  font: 400 16px/28px "Roboto", sans-serif;
  color: #5f6771;
}
.multi_step_form #msform fieldset {
  border: 0;
  padding: 20px 105px 0;
  position: relative;
  width: 100%;
  left: 0;
  right: 0;
}
.multi_step_form #msform fieldset:not(:first-of-type) {
  display: none;
}
.multi_step_form #msform fieldset h3 {
  font: 500 18px/35px "Roboto", sans-serif;
  color: #3f4553;
}
.multi_step_form #msform fieldset h6 {
  font: 400 15px/28px "Roboto", sans-serif;
  color: #5f6771;
  padding-bottom: 30px;
}

.multi_step_form #msform fieldset .form-group {
  padding: 0 10px;
}
.multi_step_form #msform #progressbar {
  margin-bottom: 30px;
  overflow: hidden;
}
.multi_step_form #msform #progressbar li {
  list-style-type: none;
  color: #99a2a8;
  font-size: 9px;
  width: calc(100%/4);
  float: left;
  position: relative;
  font: 500 13px/1 "Roboto", sans-serif;
}
.multi_step_form #msform #progressbar li:nth-child(2):before {
  content: "\f095";
}
.multi_step_form #msform #progressbar li:nth-child(3):before {
  content: "\f095";
}
.multi_step_form #msform #progressbar li:nth-child(4):before {
  content: "\f095";
}
.multi_step_form #msform #progressbar li:before {
  content: "\f095";
  font: normal normal normal 30px/50px Ionicons;
  width: 50px;
  height: 50px;
  line-height: 50px;
  display: block;
  background: #eaf0f4;
  border-radius: 50%;
  margin: 0 auto 10px auto;
}
.multi_step_form #msform #progressbar li:after {
  content: '';
  width: 100%;
  height: 10px;
  background: #eaf0f4;
  position: absolute;
  left: -50%;
  top: 21px;
  z-index: -1;
}
.multi_step_form #msform #progressbar li:last-child:after {
  width: 150%;
}
.multi_step_form #msform #progressbar li.active {
  color: #5cb85c;
}
.multi_step_form #msform #progressbar li.active:before, .multi_step_form #msform #progressbar li.active:after {
  background: #5cb85c;
  color: white;
}
.multi_step_form #msform .action-button {
  background: #5cb85c;
  color: white;
  border: 0 none;
  border-radius: 5px;
  cursor: pointer;
  min-width: 130px;
  font: 700 14px/40px "Roboto", sans-serif;
  border: 1px solid #5cb85c;
  margin: 0 5px;
  text-transform: uppercase;
  display: inline-block;
}
.multi_step_form #msform .action-button:hover, .multi_step_form #msform .action-button:focus {
  background: #405867;
  border-color: #405867;
}
.multi_step_form #msform .previous_button {
  background: transparent;
  color: #99a2a8;
  border-color: #99a2a8;
}
.multi_step_form #msform .previous_button:hover, .multi_step_form #msform .previous_button:focus {
  background: #405867;
  border-color: #405867;
  color: #fff;
}

#add-questions-tab .nav-tabs > li {
   float: none;
   display: inline-block;
   /*zoom: 1;*/
}

#online-exam-tab .nav-tabs > li {
   float: none;
   display: inline-block;
   /*zoom: 1;*/
}
</style>