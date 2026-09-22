@extends('kiosk.app')
@section('content')
<div class="col-md-12 text-center mt-4" id="clock-display">
   <div class="clock">
      <div id="Date" class="h4-responsive"></div>
      <div id="current-time">--:-- --</div>
   </div>
</div>

<div style="height: 40vh;">
<div class="col-md-12 d-flex justify-content-center align-items-center h-100">
   <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-2 mt-3 fadeInDown">
         <a href="/kiosk/attendance" class="redirect">
            <div class="card">
               <div class="view overlay text-center text-white aqua-gradient">
                  <img src="{{ \App\Support\StorageUrl::get('kiosk/comp.png') }}" class="rounded mx-auto d-block w-25 mt-3">
                  <h6 class="mb-3 mt-2">{{-- <i class="fa fa-calendar mr-1"> --}}</i>ATTENDANCE</h6>
               </div>
               <div class="card-body text-center">
                 {{--  <a href="/kiosk/attendance" class="btn btn-block btn-primary btn-lg">
                     <i class="fa fa-calendar mr-1"></i>Attendance
                  </a> --}}
               </div>
            </div>
         </a>
      </div>
      <div class="col-md-2 mt-3 fadeInDown">
         <a href="/kiosk/notice" class="redirect">
            <div class="card">
               <div class="view overlay text-center text-white aqua-gradient">
                  <img src="{{ \App\Support\StorageUrl::get('kiosk/absent_notice.png') }}" class="rounded mx-auto d-block w-25 mt-3">
                  <h6 class="mb-3 mt-2">{{-- <i class="fa fa-check-circle-o mr-1"></i> --}}ABSENT NOTICE</h6>
               </div>
               <div class="card-body text-center">
                 {{--  <a href="/kiosk/notice" class="btn btn-block btn-primary btn-lg">
                     <i class="fa fa-check-circle-o mr-1"></i>Absent Notice
                  </a> --}}
               </div>
            </div>
         </a>
      </div>
      <div class="col-md-2 mt-3 fadeInDown">
         <a href="/kiosk/gatepass" class="redirect">
            <div class="card">
               <div class="view overlay text-center text-white aqua-gradient">
                  <img src="{{ \App\Support\StorageUrl::get('kiosk/gatepass.png') }}" class="rounded mx-auto d-block w-25 mt-3">
                  <h6 class="mb-3 mt-2">{{-- <i class="fa fa-book mr-1"></i> --}}GATEPASS</h6>
               </div>
               <div class="card-body text-center">
                 {{--  <a href="/kiosk/gatepass" class="btn btn-block btn-primary btn-lg">
                     <i class="fa fa-book mr-1"></i>Gatepass
                  </a> --}}
               </div>
            </div>
         </a>
      </div>
      <div class="col-md-2 mt-3 fadeInDown">
         <a href="/kiosk/itinerary" class="redirect">
            <div class="card">
               <div class="view overlay text-center text-white aqua-gradient">
                  <img src="{{ \App\Support\StorageUrl::get('kiosk/itinerary.png') }}" class="rounded mx-auto d-block w-25 mt-3">
                  <h6 class="mb-3 mt-2">{{-- <i class="fa fa-question-circle mr-1"></i> --}}ITINERARY</h6>
               </div>
               <div class="card-body text-center">
                  {{-- <a href="#" class="btn btn-block btn-primary btn-lg">
                     <i class="fa fa-question-circle mr-1"></i>Help
                  </a> --}}
               </div>
            </div>
         </a>
      </div>
   </div>
</div>
</div>

<div class="col-md-12 mt-4 fixed-bottom pb-5">
   <div class="card">
      <div class="card-header primary-color text-white text-center">
         <h4 class="mb-0">Quick Links</h4>
      </div>
      <div class="card-body text-center">
         <a href="/kiosk/notice/form" class="btn btn-primary btn-lg redirect">File Absent Notice</a>
         <a href="/kiosk/gatepass/form" class="btn btn-primary btn-lg redirect">File Gatepass</a>
         <a href="/kiosk/itinerary/form" class="btn btn-primary btn-lg redirect">File Itinerary</a>
         <a href="/kiosk/leave_calendar" class="btn btn-primary btn-lg redirect">Leave Calendar</a>
      </div>
   </div>
</div>

<style>
  .fadeInDown {
  -webkit-animation-name: fadeInDown;
  animation-name: fadeInDown;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
  }
  @-webkit-keyframes fadeInDown {
  0% {
  opacity: 0;
  -webkit-transform: translate3d(0, -100%, 0);
  transform: translate3d(0, -100%, 0);
  }
  100% {
  opacity: 1;
  -webkit-transform: none;
  transform: none;
  }
  }
  @keyframes fadeInDown {
  0% {
  opacity: 0;
  -webkit-transform: translate3d(0, -100%, 0);
  transform: translate3d(0, -100%, 0);
  }
  100% {
  opacity: 1;
  -webkit-transform: none;
  transform: none;
  }
  }

  #current-time {
      margin:0 auto;
      padding:0px;
      display:inline;
      font-size:5em;
      text-align:center;
   }
</style>

<!-- Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalTitle"
  aria-hidden="true">

  <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Welcome to Essex Kiosk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
         <div class="row">
            <div class="col-md-12">
            @php
               $img = app(\App\Services\EmployeeAvatarUrlResolver::class)->resolve(
                  Auth::user()->image ?? null,
                  (string) Auth::user()->user_id
               );
            @endphp
               <div class="avatar mx-auto white">
                  <img src="{{ $img }}" alt="avatar mx-auto white" class="rounded img-fluid w-50">
               </div>
               <div class="card-body p-0">
                  <h3 class="card-title mt-1">{{ Auth::user()->employee_name }}</h3>
                  <hr>
                  <h6 class="font-weight-bold">{{ $user_details->designation }} | {{ $user_details->department }}</h6>
                  <p class="mb-0">Card ID No.: {{ Auth::user()->id_security_key }}</p>
               </div>
            </div>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Continue</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
   $(document).ready(function(){
      if (sessionStorage.getItem('showUserModal') !== 'true') {
         $('#userDetailsModal').modal('show');
         sessionStorage.setItem('showUserModal','true');
      }
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

</script>
@endsection