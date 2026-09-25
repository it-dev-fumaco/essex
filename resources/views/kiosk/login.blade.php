<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
   <meta http-equiv="x-ua-compatible" content="ie=edge">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta name="apple-mobile-web-app-capable" content="yes">
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
   <link href="{{ asset('kiosk/css/slider.css') }}" rel="stylesheet">
</head>

<body>
   <div class="slider" id="slider">
      <div class="slItems">
         <div class="slItem" style="background-image: url('{{ asset('storage/img/slider/businessman.jpg') }}');">
            <div class="slText">
              <div class="text-center" style="margin-top: 20%;">
                  <h1 class="text-uppercase">Welcome to</h1>
                  <h1 class="yellow-text font-weight-bold display-3 text-uppercase">Essex Kiosk</h1>
                  <p style="font-size: 0.7em;">Attendance Monitoring • Absent Notice • Gatepass Request • Itinerary Slip</p>
              </div>
            </div>
         </div>
         <div class="slItem" style="background-image: url('{{ asset('storage/img/slider/businessman.jpg') }}');">
            <div class="slText">
               <div style="margin-top: 18%;">
                  <h1 class="text-uppercase yellow-text" style="letter-spacing: 3px;">Mission</h1>
                  <p>To design and provide excellent, affordable,<br>quality and energy efficient lighting solutions that<br>doesn't jeopardize the environment.</p>
                </div>
            </div>
         </div>
         <div class="slItem" style="background-image: url('{{ asset('storage/img/slider/businessman.jpg') }}');">
            <div class="slText">
               <div style="margin-top: 18%;">
               <h1 class="text-uppercase yellow-text" style="letter-spacing: 3px;">Vision</h1>
               <p style="font-size: 0.9em;">FUMACO is the leading lighting solutions provider in the Philippines<br>and in the ASEAN region manned by highly motivated and equipped people.<br>We drive new technologies and standards throughout<br>our organization and the industry, lifting and inspiring<br>the various stakeholders around us.</p>
            </div>
            </div>
         </div>
      </div>
   </div>

   <style type="text/css">
    *{
          font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    }
   </style>
   <div class="arrow"></div>
   <main>
      <div class="container">
         <div class="row mt-5">
            <div class="col-md-12 text-center mt-5">
               <div class="clock text-white">
                  <div id="Date" class="h2-responsive"></div>
                  <div id="current-time">--:-- --</div>
               </div>
               <h1 class="display-2 text-white mt-5">ESSEX KIOSK</h1>
               @if(session("message"))
               <div class='alert alert-danger alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert'>&times;</button>
                  <center>{!! session("message") !!}</center>
               </div>
               @endif
               <h3 class="text-white mt-5 tap_here">Tap your card to login</h3>
               <form class="form" method="post" action="/kiosk/loguser">
                  @csrf
                  <input type="password" class="id-key" style="opacity: 0;" name="id_key" required>
                  <button type="submit" id="login-button" style="opacity: 0;">Login</button>
               </form>
            </div>
         </div>
      </div>
      <ul class="bubbles">
         <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
      </ul>
      <div class="text-right fixed-bottom mr-5">
         <a href="" class="btn btn-default btn-rounded mb-5" data-toggle="modal" data-target="#modalLoginForm" id="modalLoginBtn">Login using Access ID</a>
      </div>
   </main>

   {{-- START Modal Login --}}
   <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-header text-center">
               <h4 class="modal-title w-100 font-weight-bold">Log in using Access ID</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <form id="form-submit-login" method="post" action="/kiosk/loguser">
               @csrf
               <div class="modal-body mx-3">
                  <div class="md-form mb-5">
                     <input type="hidden" name="using_access_id" value="1">
                     <i class="fa fa-user prefix grey-text"></i>
                     <input type="text" name="access_id" id="access-id" class="form-control validate" required>
                     <label for="access-id">Your Access ID</label>
                  </div>
                  <div class="md-form mb-4">
                     <i class="fa fa-lock prefix grey-text"></i>
                     <input type="password" name="password" id="password" class="form-control validate" required>
                     <label for="password">Your password</label>
                  </div>
               </div>
               <div class="modal-footer d-flex justify-content-center">
                  <button type="submit" class="btn btn-default">Login</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   {{-- END Modal Login --}}

   <!-- SCRIPTS -->
   <!-- JQuery -->
   <script type="text/javascript" src="{{ asset('kiosk/js/jquery-3.4.1.min.js') }}"></script>
   <!-- Bootstrap tooltips -->
   <script type="text/javascript" src="{{ asset('kiosk/js/popper.min.js') }}"></script>
   <!-- Bootstrap core JavaScript -->
   <script type="text/javascript" src="{{ asset('kiosk/js/bootstrap.min.js') }}"></script>
   <!-- MDB core JavaScript -->
   <script type="text/javascript" src="{{ asset('kiosk/js/mdb.min.js') }}"></script>
   <script type="text/javascript" src="{{asset('kiosk/js/jquery.ui.touch-punch.min.js') }}"></script>

   <script type="text/javascript" src="{{ asset('kiosk/js/jquery.inactivity.js') }}"></script>
   <script type="text/javascript" src="{{ asset('kiosk/js/slider.js') }}"></script>

   <script>
   	function launchFullScreen(element) {
  if(element.requestFullScreen) {
    element.requestFullScreen();
  } else if(element.mozRequestFullScreen) {
    element.mozRequestFullScreen();
  } else if(element.webkitRequestFullScreen) {
    element.webkitRequestFullScreen();
  }
}

// Launch fullscreen for browsers that support it!
launchFullScreen(document.documentElement);
      // $(document).inactivity({
      //    timeout: 120000, // the timeout until the inactivity event fire [default: 3000]
      //    mouse: true, // listen for mouse inactivity [default: true]
      //    keyboard: false, // listen for keyboard inactivity [default: true]
      //    touch: true, // listen for touch inactivity [default: true]
      //    customEvents: "", // listen for custom events [default: ""]
      //    triggerAll: true, // if set to false only the first "activity" event will be fired [default: false]
      // });

      // $(document).on("activity", function(){
      //    // function that fires on activity
      //    $('main').fadeIn();
      //    $('.arrow').fadeIn();
      //    $('#slider').fadeOut();
      // });

      // $(document).on("inactivity", function(){
      //    // function that fires on inactivity
      //    $('#slider').fadeIn();
      //    $('main').fadeOut();
      //    $('.arrow').fadeOut();
      // });

      // $(function(){
      //    $('#slider').rbtSlider({
      //       height: '100vh', 
      //       'dots': true,
      //       'arrows': false,
      //       'auto': 5
      //    });
      // });

      $(document).ready(function() {
         $('main').click(function(){
            $('.form .id-key').focus();
         });

         $("input").focus();

         // Create two variables with names of months and days of the week in the array
         var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]; 
         var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

         // Create an object newDate()
         var newDate = new Date();
         // Retrieve the current date from the Date object
         newDate.setDate(newDate.getDate());
         // At the output of the day, date, month and year    
         $('#Date').html(dayNames[newDate.getDay()] + ", " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

         setInterval('updateClock()', 1000);

         // Reload login after 3 hours of inactivity so CSRF/session stay fresh (avoids 419).
         var loginIdleTime = 0;
         var LOGIN_IDLE_TICK_MS = 60000;
         var LOGIN_IDLE_THRESHOLD = 180; // 180 * 60s = 3 hours

         function resetLoginIdle() {
            loginIdleTime = 0;
         }

         $(document).on('mousemove mousedown keypress touchstart click', resetLoginIdle);
         $('.id-key, #access-id, #password').on('focus input', resetLoginIdle);

         setInterval(function () {
            loginIdleTime = loginIdleTime + 1;
            if (loginIdleTime > LOGIN_IDLE_THRESHOLD) {
               window.location.reload();
            }
         }, LOGIN_IDLE_TICK_MS);
      }); 

      function updateClock(){
         var currentTime = new Date();
         var currentHours = currentTime.getHours();
         var currentMinutes = currentTime.getMinutes();
         var currentSeconds = currentTime.getSeconds();
         // Pad the minutes and seconds with leading zeros, if required
         currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
         currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;
         // Choose either "AM" or "PM" as appropriate
         var timeOfDay = (currentHours < 12) ? "AM" : "PM";
         // Convert the hours component to 12-hour format if needed
         currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;
         // Convert an hours component of "0" to "12"
         currentHours = (currentHours === 0) ? 12 : currentHours;
         currentHours = (currentHours < 10 ? "0" : "") + currentHours;
         // Compose the string for display
         var currentTimeString = currentHours + ":" + currentMinutes + " " + timeOfDay;

         $("#current-time").html(currentTimeString);
      }

   </script>
</body>

<style type="text/css">
   :not(input):not(textarea) {
      user-select: none; /* supported by Chrome and Opera */
      -webkit-user-select: none; /* Safari */
      -khtml-user-select: none; /* Konqueror HTML */
      -moz-user-select: none; /* Firefox */
      -ms-user-select: none; /* Internet Explorer/Edge */
   }

   .arrow,
   .arrow:before {
      position: absolute;
      left: 50%;
   }

   .arrow {
      width: 80px;
      height: 80px;
      top: 75%;
      bottom: 0;
      margin: -20px 0 0 -20px;
      -webkit-transform: rotate(45deg);
      border-left: none;
      border-top: none;
      border-right: 8px #fff solid;
      border-bottom: 8px #fff solid;
      z-index: 3;
   }

   .arrow:before {
      content: "";
      width: 40px;
      height: 40px;
      top: 50%;
      margin: -10px 0 0 -10px;
      border-left: none;
      border-top: none;
      border-right: 8px #fff solid;
      border-bottom: 8px #fff solid;
      animation-duration: 1.5s;
      animation-iteration-count: infinite;
      animation-name: arrow;
   }

   @keyframes arrow {
      0% {
         opacity: 1;
      }
      100% {
         opacity: 0;
         transform: translate(-10px, -10px);
      }
   }

   body{
      background: #50a3a2;
      background: linear-gradient(to bottom right, #50a3a2 0%, #53e3a6 100%) no-repeat center center fixed;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
   }

   ul {
      margin:0 auto;
      padding:0px;
      list-style:none;
      text-align:center;
   }
 
   #current-time {
      margin:0 auto;
      padding:0px;
      display:inline;
      font-size:5em;
      text-align:center;
   }

   .bubbles {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      transform: translateZ(0);
      overflow: hidden;
   }

   .bubbles li {
      position: absolute;
      list-style: none;
      background-color: rgba(255,255,255,0.15);
      bottom: -100px;
      animation: square 15s infinite;
      transition-timing-function: linear;
   }

   .bubbles li:nth-child(1) {
      width: 40px;
      height: 40px;
      left: 20%;
   }

   .bubbles li:nth-child(2) {
      width: 60px;
      height: 60px;
      left: 40%;
      animation-delay: 2s;
      animation-duration: 17s;
   }

   .bubbles li:nth-child(3) {
      width: 10px;
      height: 10px;
      left: 60%;
      animation-delay: 4s;
      animation-duration: 13s;
   }

   .bubbles li:nth-child(4) {
      width: 80px;
      height: 80px;
      left: 80%;
      animation-delay: 1s;
      animation-duration: 22s;
   }

   .bubbles li:nth-child(5) {
      width: 50px;
      height: 50px;
      left: 50%;
      animation-delay: 7s;
      animation-duration: 12s;
   }

   .bubbles li:nth-child(6) {
      width: 40px;
      height: 40px;
      left: 20%;
   }

   .bubbles li:nth-child(7) {
      width: 60px;
      height: 60px;
      left: 40%;
      animation-delay: 2s;
      animation-duration: 17s;
   }

   .bubbles li:nth-child(8) {
      width: 10px;
      height: 10px;
      left: 60%;
      animation-delay: 4s;
      animation-duration: 13s;
   }

   .bubbles li:nth-child(9) {
      width: 80px;
      height: 80px;
      left: 80%;
      animation-delay: 1s;
      animation-duration: 22s;
   }

   .bubbles li:nth-child(10) {
      width: 50px;
      height: 50px;
      left: 50%;
      animation-delay: 7s;
      animation-duration: 12s;
   }

   .tap_here {
      animation: bounce 1s linear infinite;
   }

   @keyframes square {
      0%{
         transform: translateY(0);
         -webkit-transform: translateY(0);
      }
      100%{
         transform: translateY(-1080px) rotate(630deg);
         -webkit-transform: translateY(-1080px) rotate(630deg);
      }
   }

   @keyframes bounce {
      10% {
         transform: translateY(-10px);
         -webkit-transform: translateY(-10px)
      }
      70% {
         transform: translateY(0);
         -webkit-transform: translateY(0)
      }
   }
</style>
</html>