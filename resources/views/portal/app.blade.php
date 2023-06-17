<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="EstateX">
    <title>ESSEX</title>
    <link rel="stylesheet" href="{{ asset('bootstrap-5.0.2-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts/all.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/fonts/line-icons/line-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/css/main.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/extras/animate.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/extras/owl.carousel.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/extras/owl.theme.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/extras/settings.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/extras/nivo-lightbox.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/css/responsive.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/css/bootstrap-select.min.css') }}">
</head>
<style type="text/css">
    @font-face{
        font-family: 'Poppins-Regular';
        src: url('{{ asset("fonts/Poppins/Poppins-Regular.ttf") }}') format('truetype');
    }

    @font-face{
        font-family: 'Poppins-Bold';
        src: url('{{ asset("fonts/Poppins/Poppins-Bold.ttf") }}') format('truetype');
    }

    @font-face{
        font-family: 'Poppins-Light';
        src: url('{{ asset("fonts/Poppins/Poppins-Light.ttf") }}') format('truetype');
    }
    html, body{
        font-family: 'Poppins-Regular' !important;
    }

    h1, h2, h3, h4, h5, h6, p, span, b, a, small, cite{
        font-family: inherit !important;
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

    .card-greeting{
        background-color: #11703C;
        color: #fff;
        padding: 15px;
        border-radius: 5px;
    }

    .header-text{
        font-size: 14pt;
        font-weight: 200;
    }

    .dashboard-btn{
        margin-top: 16px;
        background-color: #5CB85C !important;
    }

    #autocomplete-container{
        z-index: 1000 !important;
        position: absolute;
        top: 20;
        left: 50 !important;
        width: 95%;
    }

    .profile-image{
        display: block;
        width: 57px;
        height: 57px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,.3);
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
    }

    #imagecontainer {
        background: url("{{ asset('storage/img/slider/businessman.jpg') }}") no-repeat;
        height: 175px;
        background-size: 100% auto;
        background-repeat: no-repeat;
        background-position: center center;
    }

    .carousel-search{
        border-radius: 25px 0 0 25px;
        font-family: 'Trebuchet MS';
        font-size: 17px;
    }

    .thumbnail, .nav-link{
        transition: .4s;
        cursor: pointer;
    }

    .video-play-icon{
        font-size: 80pt;
        color: rgba(0, 0, 0, .3);
    }

    .absolute-center{
        position: absolute;
        left:50%;
        top:50%;
        transform: translate(-50%, -50%)
    }

    #login-tabs a:hover{
        background-color: #f2f4f4;
        color:  #566573;
    }

    #vision-carousel{
        top: 38%;
        transform: translateY(-50%);
    }

    @media (max-width: 1199.98px) {
        .header-text{
            font-size: 12pt;
        }

        .nav li{
            padding: 0 !important;
            margin: 0 !important;
            border: 1px solid red;
        }

        #vision-carousel{
            top: 35%;
            transform: translateY(-50%);
        }

        .header-text{
            font-size: 8pt;
            white-space: nowrap;
        }

        .responsive-font, .nav-item{
            font-size: 9pt !important;
        }
    }
</style>

@include('portal.modals.login_modal')

<div class="header">
    <div class="top-bar">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-7 col-sm-6">
                        <ul class="contact-details">
                            <li>
                                <a class="text-decoration-none" href="#"><i class="icon-location-pin"></i>35 Pleasant View Drive, Bagbaguin, Caloocan City</a>
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
                            <a href="#"  data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="icon-login"></i> <span>Login</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('nav')
</div>
@yield('content')

@include('portal.includes.footer')

<a href="#" class="back-to-top text-decoration-none">
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
{{-- <script type="text/javascript" src="{{ asset('css/js/bootstrap.min.js') }}"></script> --}}
<script type="text/javascript" src="{{ asset('bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>
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
<script src="{{ asset('/js/bootstrap-notify.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/jquery.bootstrap-growl.js') }}"></script>

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

    $(document).on('submit', '#biometric-login-form', function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (data) {
                if (data) {
                    location.reload(false);
                }else{
                    $("#message").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<center><strong>Invalid login!</strong> Access ID or password is incorrect.</center>' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>');
                    $("#message").effect( "shake", {times:4}, 1000 );
                }
            }
        });
    });

    $(document).on('submit', '#ldap-login-form', function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (data) {
                if (data) {
                    location.reload(false);
                }else{
                    $("#message").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<center><strong>Invalid login!</strong> Access ID or password is incorrect.</center>' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>');
                    $("#message").effect( "shake", {times:4}, 1000 );
                }
            }
        });
    });

    $(document).on('click', '#login-tabs .nav-link', function (e){
        e.preventDefault();
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('active');

        $($(this).data('target')).addClass('active');
        $(this).addClass('active');
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
                    <p>&copy; All rights reserved 2023</p>
                </div>
            </div>
        </div>
    </div>
</div>