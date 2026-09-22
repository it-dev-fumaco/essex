<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
   <meta http-equiv="x-ua-compatible" content="ie=edge">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta name="apple-mobile-web-app-capable" content="yes">
   <link rel="apple-touch-icon" href="single-page-icon.png">
   @yield('metawebapp')
   @include('kiosk.stepper.stepper_modal')
   @include('kiosk.logout_confirmation')
   <title>Essex Kiosk</title>
   <!-- Font Awesome -->
   <link rel="stylesheet" href="{{ asset('kiosk/css/all.css') }}">
   <link rel="stylesheet" href="{{ asset('css/fonts/font-awesome.min.css') }}" type="text/css">
   <!-- Bootstrap core CSS -->
   <link href="{{ asset('kiosk/css/bootstrap.min.css') }}" rel="stylesheet">
   <!-- Material Design Bootstrap -->
   <link href="{{ asset('kiosk/css/mdb.min.css') }}" rel="stylesheet">
   <!-- Your custom styles (optional) -->
   <link href="{{ asset('kiosk/css/style.css') }}" rel="stylesheet">
</head>

<body>
   <!-- Start your project here-->
   <main id="main">
      <div id="mySidenav" class="sidenav grey lighten-3">
        <a href="javascript:void(0)" class="closebtn blue-grey-text" onclick="closeNav()">&times;</a>
        <a href="/kiosk/home" class="blue-grey-text" id="shome-btn">Home</a>
        <a href="/kiosk/attendance" class="blue-grey-text" id="sattendance-btn">Attendance</a>
        <a href="/kiosk/notice" class="blue-grey-text" id="snotice-btn">Absent Notice</a>
        <a href="/kiosk/gatepass" class="blue-grey-text" id="sgatepass-btn">Gatepass</a>
        <a href="/kiosk/itinerary" class="blue-grey-text" id="sitinerary-btn">Itinerary</a>
        <a href="#!" class="blue-grey-text" id="shelp-btn">Help</a>
      </div>
      <div class="container-fluid">
         <div class="row pt-2">
            <div class="col-md-12">
               <div class="card primary-color text-white">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-6">
                           <h2 class="h2-responsive m-0" onclick="openNav()">
                              <i class="fa fa-bars mr-2 mb-0" aria-hidden="true"></i>ESSEX KIOSK
                           </h2>
                        </div>
                        <div class="col-md-6">
                           <div class="float-right">
                              <a href="/kiosk/logoutuser" id="client-logout">
                                 <i class="fa fa-sign-out h2 m-0 p-0 text-white mr-3" aria-hidden="true"></i></a>
                           <a href="#" onclick="helpbutton()" id="client-logout" data-toggle="popover" data-placement="bottom" data-html="true" title="Need Help?" data-content="Click ^ for Help">
                            <i class="fa fa-question-circle h2 m-0 p-0 mr-2 text-white animated bounce"></i>
                           </a>
                          </div>
                          <div class="float-right">
                            <h4 class="mr-3 mt-1">Hi, {{ Auth::user()->employee_name }}!</h4>
                          </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row pt-2" id="page-body">
            @yield('content')
         </div>
      </div>
   </main>

<style type="text/css">
  .animated {
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
  -webkit-animation-timing-function: linear;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
  -webkit-animation-iteration-count: infinite;
}
@-webkit-keyframes bounce {
  0%, 100% {
    -webkit-transform: translateY(0);
  }
  50% {
    -webkit-transform: translateY(-8px);
  }
}
@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-8px);
  }
}
.bounce {
  -webkit-animation-name: bounce;
  animation-name: bounce;
}

     .stepwizard-step p {
    margin-top: 0px;
    color:#666;
}
.stepwizard-row {
    display: table-row;
}
.stepwizard {
    display: table;
    width: 100%;
    position: relative;
}
.stepwizard-step button[disabled] {
    /*opacity: 1 !important;
    filter: alpha(opacity=100) !important;*/
}
.stepwizard .btn.disabled, .stepwizard .btn[disabled], .stepwizard fieldset[disabled] .btn {
    opacity:1 !important;
    color:blue;
}
.stepwizard-row:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content:" ";
    width: 100%;
    height: 1px;
    background-color: #ccc;
    z-index: 0;
}
.stepwizard-step {
    display: table-cell;
    text-align: center;
    position: relative;
}
.btn-circle {
    width: 30px;
    height: 30px;
    text-align: center;
    padding: 6px 0;
    font-size: 12px;
    line-height: 1.428571429;
    border-radius: 15px;
    background-color: blue;
}
   </style>
   <style type="text/css">
      :not(input):not(textarea) {
         user-select: none; /* supported by Chrome and Opera */
         -webkit-user-select: none; /* Safari */
         -khtml-user-select: none; /* Konqueror HTML */
         -moz-user-select: none; /* Firefox */
         -ms-user-select: none; /* Internet Explorer/Edge */
      }
.sidenav {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 5;
  top: 0;
  left: 0;
  /*background-color: #111;*/
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  /*color: #fff;*/
  display: block;
  transition: 0.3s;
}

.sidenav a:hover {
  color: #ddd;
}

.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

#main {
  transition: margin-left .5s;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}

       .slideInLeft {
  -webkit-animation-name: slideInLeft;
  animation-name: slideInLeft;
  -webkit-animation-duration: 0.5s;
  animation-duration: 0.5s;
 /* -webkit-animation-fill-mode: both;
  animation-fill-mode: both;*/
  }
  @-webkit-keyframes slideInLeft {
  0% {
  -webkit-transform: translateX(-100%);
  transform: translateX(-100%);
  visibility: visible;
  }
  100% {
  -webkit-transform: translateX(0);
  transform: translateX(0);
  }
  }
  @keyframes slideInLeft {
  0% {
  -webkit-transform: translateX(-100%);
  transform: translateX(-100%);
  visibility: visible;
  }
  100% {
  -webkit-transform: translateX(0);
  transform: translateX(0);
  }
  } 
   </style>
   
   <!-- Start your project here-->

   <!-- SCRIPTS -->
   <!-- JQuery -->
   <script src="{{ asset('css/js/ajax.min.js') }}"></script> 
   <script type="text/javascript" src="{{ asset('kiosk/js/jquery-3.4.1.min.js') }}"></script>
   <!-- Bootstrap tooltips -->
   <script type="text/javascript" src="{{ asset('kiosk/js/popper.min.js') }}"></script>
   <!-- Bootstrap core JavaScript -->
   <script type="text/javascript" src="{{ asset('kiosk/js/bootstrap.min.js') }}"></script>
   <!-- MDB core JavaScript -->
   <script type="text/javascript" src="{{ asset('kiosk/js/mdb.min.js') }}"></script>

   @yield('script')

   <script>
      $(document).ready(function(){
         $('#client-logout').click(function(){
            sessionStorage.clear();
         });
      
         $(document).on('click', 'a', function(e){
            e.preventDefault();
            location.assign($(this).attr('href'));
          });

         $('.modal').on('hidden.bs.modal', function(){
          $(this).find('form')[0].reset();
  });
      });
   </script>
  <script>
    $(document).ready(function() {
      $(function () {
          $('[data-toggle="popover"]').popover('show')
        });
      console.log("document ready occurred!");
      // $('.redirect').click(function(){
      //   $('#page-body').removeClass('slideInLeft').addClass('slideOutLeft');
      // });
    });
function openNav() {
  document.getElementById("mySidenav").style.width = "350px";
  // document.getElementById("main").style.marginLeft = "250px";
  // document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  // document.getElementById("main").style.marginLeft= "0";
  // document.body.style.backgroundColor = "white";
}


</script>
<script type="text/javascript">
  $(document).ready(function () {

    var navListItems = $('div.setup-panel div a'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-success').addClass('btn-default');
            $item.addClass('btn-success');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function () {
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for (var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-success').trigger('click');
});
</script>
<script type="text/javascript">
  function stepper_function_notice(){
    $.ajax({
      url: "/stepper/notice",
      success: function(data){
        $('#table_stepper').html(data);
      }
    });
  }
  function stepper_function_gatepass(){
    $.ajax({
      url: "/stepper/gatepass",
      success: function(data){
        $('#table_stepper').html(data);
      }
    });
  }
  function stepper_function_itinerary(){
    $.ajax({
      url: "/stepper/itinerary",
      success: function(data){
        $('#table_stepper').html(data);
      }
    });
  }
</script>
<script type="text/javascript">
  function helpbutton(){
    $('#helpmodal').modal('show');
  }
</script>
<script type="text/javascript">
var idleTime = 0;
var KIOSK_IDLE_TICK_MS = 5000;
var KIOSK_IDLE_THRESHOLD = 59; // (59+1) * 5s = 300s = 5 minutes
var KIOSK_COUNTDOWN_SEC = 5;
$(document).ready(function () {
    // Increment idle counter every 5s; modal after ~5 minutes of inactivity.
    var idleInterval = setInterval(timerIncrement, KIOSK_IDLE_TICK_MS);

    //Zero the idle timer on mouse movement.
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });
    $(this).mousedown(function (e) {
        idleTime = 0;
    });
    $(this).click(function (e) {
        idleTime = 0;
    });
    $(this).scroll(function (e) { 
        idleTime = 0;
    });
    $(this).scrollTop(function (e) {
        idleTime = 0;
    });

});
  

function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > KIOSK_IDLE_THRESHOLD) { // ~5 minutes of inactivity
        // window.location.reload();
         $('#logout_confirmation').modal('show');
         timercountdown();
        idleTime = 0;
    }
}
var downloadTimer;
function timercountdown(){
  var timeleft = KIOSK_COUNTDOWN_SEC;
  downloadTimer = setInterval(function(){
  document.getElementById("timer").innerHTML = timeleft + " seconds remaining";

  timeleft -= 1;
  if(timeleft <= 0){
    window.location.replace("/kiosk/logoutuser");
    clearInterval(downloadTimer);
  }
}, 1000);
}
function stoptimer(){
  clearInterval(downloadTimer);
  document.getElementById("timer").innerHTML = "";
}
function logout_user(){
  window.location.replace("/kiosk/logoutuser");
}

</script>
</body>

</html>