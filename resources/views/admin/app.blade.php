<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="author" content="EstateX">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>ESSEX v5</title>
   <link rel="stylesheet" href="{{ asset('css/css/bootstrap.min.css') }}" type="text/css">
   <link rel="stylesheet" href="{{ asset('css/fonts/font-awesome.min.css') }}" type="text/css">
   <link rel="stylesheet" href="{{ asset('css/fonts/line-icons/line-icons.css') }}" type="text/css">
   <link rel="stylesheet" href="{{ asset('css/css/main.css') }}" type="text/css">
   <link rel="stylesheet" href="{{ asset('css/extras/animate.css') }}" type="text/css">
   <link rel="stylesheet" href="{{ asset('css/extras/owl.carousel.css') }}" type="text/css">
   <link rel="stylesheet" href="{{ asset('css/extras/owl.theme.css') }}" type="text/css">
   <link rel="stylesheet" href="{{ asset('css/extras/settings.css') }}" type="text/css">
   <link rel="stylesheet" href="{{ asset('css/extras/nivo-lightbox.css') }}" type="text/css">
   <link rel="stylesheet" href="{{ asset('css/css/responsive.css') }}" type="text/css">
   <link rel="stylesheet" href="{{ asset('css/css/slicknav.css') }}" type="text/css">
   <link rel="stylesheet" href="{{ asset('css/css/bootstrap-select.min.css') }}">

   <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
</head>

<style type="text/css">
* {
   font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
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
               <div class="col-md-5 col-sm-6">
                  <div class="account-setting">
                     <a href="{{ url('/admin/logout') }}">
                        <i class="icon-logout"></i>
                        <span>Logout</span>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="top-bar-bottom">
         <div class="container">
            <div class="row">
               <div class="col-md-5 col-sm-6">
                  <div class="header-logo">
                     <a href="#">
                        <img src="{{ asset('storage/img/logo5.png') }}" alt="">
                     </a>
                  </div>
                  <div class="name-title">FUMACO Inc. <br> The Art of Science & Lighting</div>
               </div>
               <div class="col-md-7 col-sm-6">
                  <div class="pull-right">
                     <div class="row" style="padding: 3px;">
                        <div style="float: left; margin-right: 5px;">
                           <img src="{{ asset('storage/img/user.png') }}" width="60" height="60">
                        </div>
                        <div style="float: right; margin-top: 10px;">
                           <span style="display: block;">
                              <h4>{{Auth::user()->name}}</h4>
                           </span>
                           <span style="display: block;">Administrator</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="main-container section">
      <div class="container">
         <div class="col-md-12">
            <a href="/admin">
               <h2 class="section-title center">Essex 3 Admin Panel</h2>
            </a>
         </div>

         @yield('content')
      
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

  {{--  <div id="loader">
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
   {{-- <script type="text/javascript" src="{{ asset('css/js/form-validator.min.js') }}"></script> --}}
   {{-- <script type="text/javascript" src="{{ asset('css/js/contact-form-script.js') }}"></script> --}}
   {{-- <script type="text/javascript" src="{{ asset('css/js/jquery.themepunch.revolution.min.js') }}"></script> --}}
   {{-- <script type="text/javascript" src="{{ asset('css/js/jquery.themepunch.tools.min.js') }}"></script> --}}
   {{-- <script type="text/javascript" src="{{ asset('css/js/jquery.slicknav.js') }}"></script> --}}
   {{-- <script src="{{ asset('css/js/bootstrap-select.min.js') }}"></script> --}}
   {{-- <script src="{{ asset('css/js/jQuery-plugin-progressbar.js') }}"></script> --}}
   {{-- <script src="{{ asset('css/js/calendar.js') }}"></script> --}}
   <script type="text/javascript" src="{{asset('js/parsley.min.js')}}"></script>

   <script src="{{ asset('css/js/datatables/jquery.dataTables.min.js')}}"></script>
   <script src="{{ asset('css/js/datatables/dataTables.bootstrap.min.js') }}"></script>

   @yield('script')
</body>
</html>