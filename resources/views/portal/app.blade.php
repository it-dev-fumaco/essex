<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="EstateX">
    <title>ESSEX v7.0</title>
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
</head>

<style type="text/css">
    *{
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    }
    .login-content{
        background-color: transparent;
        -webkit-box-shadow: 0 5px 15px rgba(0,0,0,0);
        -moz-box-shadow: 0 5px 15px rgba(0,0,0,0);
        -o-box-shadow: 0 5px 15px rgba(0,0,0,0);
        box-shadow: 0 5px 15px rgba(0,0,0,0);
        border: 0;
        margin-top: 100px;
    }
</style>

@include('portal.modals.login_modal')

<div class="header">
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-6">
                    <ul class="contact-details">
                        <li>
                            <a href="#"><i class="icon-location-pin"></i>35 Pleasant View Drive, Bagbaguin, Caloocan City</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-5 col-sm-6">
                    <div class="account-setting">
                        @if(Auth::user())
                        <strong>Welcome {{ Auth::user()->employee_name }}</strong>
                        <a href="{{ url('/userLogout') }}">
                            <i class="icon-logout"></i><span>Logout</span>
                        </a>
                        @else
                        <a href="#"  data-toggle="modal" data-target="#loginModal">
                            <i class="icon-login"></i> <span>Login</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="top-bar-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-6">
                    <div class="header-logo">
                        <a href="/">
                            <img src="{{ asset('storage/img/logo5.png') }}" alt="">
                        </a>
                    </div>
                    <div class="name-title"><img src="{{ asset('storage/img/company_logo.png') }}" width="100"><br><span style="font-size: 14pt; font-weight: 150;">Employee Portal</span></div>
                </div>
                <div class="col-md-5 col-sm-12 pull-right" style="margin: 15px 0 0 0;">
                    <div class="widget widget-search">
                        <form action="{{ route('search') }}" method="POST">
                            @csrf
                            <input class="form-control" type="search" name="query"  placeholder="Start Searching...">
                            <button class="search-btn" name="search" type="submit">
                                <i class=" icon-magnifier"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('nav')
</div>
@yield('content')

@include('portal.includes.footer')

<a href="#" class="back-to-top">
    <i class="icon-arrow-up"></i>
</a>

<div id="loader">
    <div class="sk-folding-cube">
        <div class="sk-cube1 sk-cube"></div>
        <div class="sk-cube2 sk-cube"></div>
        <div class="sk-cube4 sk-cube"></div>
        <div class="sk-cube3 sk-cube"></div>
    </div>
</div>

<script src="{{ asset('css/js/ajax.min.js') }}"></script> 
<script type="text/javascript" src="{{ asset('css/js/jquery-min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/jquery.parallax.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/wow.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/main.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/jquery.mixitup.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/nivo-lightbox.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/jquery.counterup.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/waypoints.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/form-validator.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/contact-form-script.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/jquery.themepunch.revolution.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/jquery.themepunch.tools.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/jquery.slicknav.js') }}"></script>
<script src="{{ asset('css/js/bootstrap-select.min.js') }}"></script>
<script type = "text/javascript" src = "{{ asset('css/js/jquery-ui.min.js') }}"></script>

@yield('script')

<script>
$(document).ready(function(){
    $('.modal').on('hidden.bs.modal', function(){
        $(this).find('form')[0].reset();
    });
    
    /* Get iframe src attribute value i.e. YouTube video url
    and store it in a variable */
    var url = $("#videohtml").attr('src');
    
    /* Assign empty url value to the iframe src attribute when
    modal hide, which stop the video playing */
    $("#modal6").on('hide.bs.modal', function(){
        $("#videohtml").attr('src', '');
    });
    
    /* Assign the initially stored url back to the iframe src
    attribute when modal is displayed again */
    $("#modal6").on('show.bs.modal', function(){
        $("#videohtml").attr('src', url);
    });

    $(document).on('submit', '#loginModal form', function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (data) {
                console.log(data)
                if (data) {
                    location.reload(false);
                }else{
                    $("#message").html("<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button><center><strong>Invalid login!</strong> Access ID or password is incorrect.</center></div>");
                    $("#message").effect( "shake", {times:4}, 1000 );
                }
            }
        });
    });
});
</script>

</body>
</html>

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