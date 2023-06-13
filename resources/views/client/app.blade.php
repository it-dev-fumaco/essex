<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="EstateX">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>ESSEX</title>
<link rel="stylesheet" href="{{ asset('css/css/bootstrap.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('css/fonts/font-awesome.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('css/fonts/line-icons/line-icons.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('css/css/main.css') }}" type="text/css">
{{-- <link rel="stylesheet" href="{{ asset('css/extras/animate.css') }}" type="text/css"> --}}
{{-- <link rel="stylesheet" href="{{ asset('css/extras/owl.carousel.css') }}" type="text/css"> --}}
{{-- <link rel="stylesheet" href="{{ asset('css/extras/owl.theme.css') }}" type="text/css"> --}}
{{-- <link rel="stylesheet" href="{{ asset('css/extras/settings.css') }}" type="text/css"> --}}
{{-- <link rel="stylesheet" href="{{ asset('css/extras/nivo-lightbox.css') }}" type="text/css"> --}}
<link rel="stylesheet" href="{{ asset('css/css/responsive.css') }}" type="text/css">
{{-- <link rel="stylesheet" href="{{ asset('css/css/slicknav.css') }}" type="text/css"> --}}
<link rel="stylesheet" href="{{ asset('css/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
</head>
@php
    $colors_array = [
        [
            'title' => 'primary',
            'color' => '#0069D9',
            'font-color' => 'white'
        ],
        [
            'title' => 'secondary',
            'color' => '#6C757D',
            'font-color' => 'white'
        ],
        [
            'title' => 'success',
            'color' => '#28A745',
            'font-color' => 'white'
        ],
        [
            'title' => 'danger',
            'color' => '#DC3545',
            'font-color' => 'white'
        ],
        [
            'title' => 'warning',
            'color' => '#E0A800',
            'font-color' => 'black'
        ],
        [
            'title' => 'info',
            'color' => '#138496',
            'font-color' => 'white'
        ],
        [
            'title' => 'light',
            'color' => '#E2E6EA',
            'font-color' => 'black'
        ],
        [
            'title' => 'dark',
            'color' => '#343A40',
            'font-color' => 'white'
        ],
        [
            'title' => 'white',
            'color' => '#fff',
            'font-color' => 'black'
        ]
    ];
@endphp
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
.progress1{
  position: relative;
  margin: 4px;
  float:left;
  text-align: center;
}
.barOverflow1{ /* Wraps the rotating .bar */
  position: relative;
  overflow: hidden; /* Comment this line to understand the trick */
  width: 120px; height: 60px; /* Half circle (overflow) */
  margin-bottom: -25px; /* bring the numbers up */
}
.bar1{
  position: absolute;
  top: 0; left: 0;
  width: 120px; height: 120px; /* full circle! */
  border-radius: 50%;
  box-sizing: border-box;
  border: 18px solid #eee;     /* half gray, */
  border-bottom-color: #f39c12;  /* half azure */
  border-right-color: #f39c12;
}

  .hover-image img {
  -webkit-transform: scale(1);
  transform: scale(1);
  -webkit-transition: .3s ease-in-out;
  transition: .3s ease-in-out;
  cursor: pointer;
}
.hover-image:hover img {
  -webkit-transform: scale(1.3);
  transform: scale(1.3);
cursor: pointer;
}


 .hover-icon i{
  -webkit-transform: scale(1);
  transform: scale(1);
  -webkit-transition: .3s ease-in-out;
  transition: .3s ease-in-out;
  cursor: pointer;
}
.hover-icon:hover i{
  -webkit-transform: scale(1.3);
  transform: scale(1.3);
cursor: pointer;
}


.calendar, .calendar_weekdays, .calendar_content {
    max-width: 300px;
}
.calendar {
    margin: auto;
    font-family:'Muli', sans-serif;
    font-weight: 400;
}
.calendar_content, .calendar_weekdays, .calendar_header {
    position: relative;
    overflow: hidden;
}
.calendar_weekdays div {
    display:inline-block;
    vertical-align:top;
}
.calendar_weekdays div, .calendar_content div {
    width: 14.28571%;
    overflow: hidden;
    text-align: center;
    background-color: transparent;
    color: #6f6f6f;
    font-size: 14px;
}
.calendar_content div {
    border: 1px solid transparent;
    float: left;
}
.calendar_content div:hover {
    border: 1px solid #dcdcdc;
    cursor: default;
}
.calendar_content div.blank:hover {
    cursor: default;
    border: 1px solid transparent;
}
.calendar_content div.past-date {
    color: #d5d5d5;
}
.calendar_content div.today {
    font-weight: bold;
    font-size: 14px;
    color: #87b633;
    border: 1px solid #dcdcdc;
}
.calendar_content div.selected {
    background-color: #f0f0f0;
}
.calendar_header {
    width: 100%;
    text-align: center;
}
.calendar_header h2 {
    padding: 0 10px;
    font-family:'Muli', sans-serif;
    font-weight: 300;
    font-size: 18px;
    color: #87b633;
    float:left;
    width:70%;
    margin: 0 0 10px;
}
button.switch-month {
    background-color: transparent;
    padding: 0;
    outline: none;
    border: none;
    color: #dcdcdc;
    float: left;
    width:15%;
    transition: color .2s;
}
button.switch-month:hover {
    color: #87b633;
}
#rcorners1 {
  border-radius: 25px;
  background: #73AD21;
  padding: 20px; 
  width: 200px;
  height: 150px;  
}
.icon-edit{
  font-size: 15pt;
  color: #27AE60;
}

.icon-view{
  font-size: 15pt;
  color: #2980B9;
}

.icon-delete{
  font-size: 15pt;
  color: #C0392B;
}

.user-image {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
}
</style>
@foreach ($colors_array as $color)
<style>
    .bg-{{ $color['title'] }}{
        background-color: {{ $color["color"] }};
        color: {{ $color["font-color"] }};
    }

    .border-{{ $color['title'] }}{
        border: 1px solid {{ $color["color"] }};
    }

    .text-{{ $color['title'] }}{
        color: {{ $color["color"] }};
    }
</style>
@endforeach

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
          <div class="col-md-5 col-sm-6">
            <div class="account-setting">
              <a href="{{ url('/userLogout') }}" id="client-logout">
                <i class="icon-logout"></i>
                <span>Logout</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="top-bar-bottom">
      <div class="container-fluid">
        <div class="col-md-10 col-md-offset-1">
          <div class="row">
            <div class="col-md-5 col-sm-6">
              <div class="header-logo">
                <a href="/">
                  <img src="{{ asset('storage/img/logo5.png') }}" alt="">
                </a>
              </div>
              <div class="name-title">FUMACO Inc. <br> The Art of Science & Lighting</div>
            </div>
            <div class="col-md-7 col-sm-6">
              <div class="pull-right">
                <div class="row" style="padding: 3px;">
                  <div style="float: left; margin-right: 5px;">
                      @php
                     $img = Auth::user()->image ? Auth::user()->image : '/storage/img/user.png'
                     @endphp
                    <img src="{{ asset($img) }}" width="60" height="60" class="user-image">
                  </div>
                  <div style="float: right; margin-top: 8px;">
                    <span style="display: block;">
                      <h4>{{ Auth::user()->employee_name }}</h4>
                    </span>
                    @if($designation)
                    <span style="display: block;">{{ $designation }} | {{ $department }}</span>
                    @endif
                    <a href="#" data-toggle="modal" data-target="#user_profile" onclick="profileFunction()"><i class="fa fa-user" aria-hidden="true" title="User Profile"></i> User Profile</a>
                    @include('client.modals.user_profile') &nbsp;
                    <a href="#" data-toggle="modal" data-target="#changePassword"><i class="fa fa-cog" aria-hidden="true" title="Update Password"></i> Update Password</a>
                    @include('client.modals.change_password')
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="main-container section">
  <div class="container-fluid">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="columns-wrapper">
  
            @yield('content')
  
          </div>
        </div>
      </div>
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
<a href="#" class="back-to-top">
  <i class="icon-arrow-up"></i>
</a>

{{-- <div id="loader">
  <div class="sk-folding-cube">
    <div class="sk-cube1 sk-cube"></div>
    <div class="sk-cube2 sk-cube"></div>
    <div class="sk-cube4 sk-cube"></div>
    <div class="sk-cube3 sk-cube"></div>
  </div>
</div> --}}

<script src="{{ asset('css/js/ajax.min.js') }}"></script> 
<script type="text/javascript" src="{{ asset('css/js/jquery-min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/bootstrap.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('css/js/jquery.parallax.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('css/js/owl.carousel.min.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('css/js/wow.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('css/js/main.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('css/js/jquery.mixitup.min.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('css/js/nivo-lightbox.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('css/js/jquery.counterup.min.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('css/js/waypoints.min.js') }}"></script> --}}
<script type="text/javascript" src="{{ asset('css/js/form-validator.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('css/js/contact-form-script.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('css/js/jquery.themepunch.revolution.min.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('css/js/jquery.themepunch.tools.min.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('css/js/jquery.slicknav.js') }}"></script> --}}
<script type="text/javascript" src="{{ asset('css/js/jquery.bootstrap-growl.js') }}"></script>
<script src="{{ asset('css/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('css/js/jQuery-plugin-progressbar.js') }}"></script>
<script src="{{ asset('css/js/calendar.js') }}"></script>
<script type = "text/javascript" src = "{{ asset('css/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('css/js/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('css/js/datatables/dataTables.bootstrap.min.js') }}"></script>



<script>  
  $(document).ready(function(){
    $('#client-logout').click(function(){
      sessionStorage.clear();
    });

    $(".progress1").each(function(){
      var $bar = $(this).find(".bar1");
      var $val = $(this).find(".percentage");
      var perc = parseInt( $val.text(), 10);
      $({p:0}).animate({p:perc}, {
        duration: 3000,
        easing: "swing",
        step: function(p) {
          $bar.css({
            transform: "rotate("+ (45+(p*1.8)) +"deg)", // 100%=180° so: ° = % * 1.8
            // 45 is to add the needed rotation to have the green borders at the bottom
          });
          $val.text(p|0);
        }
      });
    });
  });

</script>
<script type="text/javascript">
    function profileFunction(){
    $.ajax({
      url: '/getprofile',
      type: 'get',
      success: function(data){
        $('#myprofile').html(data);
      }
    });
  }
</script>

@yield('script')

</body>
</html>