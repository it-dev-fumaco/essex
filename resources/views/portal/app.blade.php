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
    html {
        font-family: 'Poppins-Regular' !important;
        margin: 0;
        padding: 0;
        min-height: 100%;
        /* Fills any sub-pixel gap below body so a white block does not show under the footer */
        background-color: #393939;
    }
    body.portal-app {
        font-family: 'Poppins-Regular' !important;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background-color: #f2f4f4;
    }
    .portal-app > .header {
        flex-shrink: 0;
    }
    main.portal-main {
        flex: 1 0 auto;
        width: 100%;
        min-width: 0;
    }
    #copyright {
        flex-shrink: 0;
        margin-bottom: 0;
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

    .header-text{
        font-size: 12pt;
    }

    .nav li a{
        padding: 10px !important;
        margin: 0 !important;
    }

    .dir-caption{
        font-size: 11px;
    }

    .ellipsis {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
    }

    .one-line{
        -webkit-line-clamp: 1; /* number of lines to show */
                line-clamp: 1; 
    }

    .two-line{
        -webkit-line-clamp: 2; /* number of lines to show */
                line-clamp: 2; 
    }

    .current-time{
        font-size: 2.5rem;
    }

    .account-setting {
        display: flex;
        justify-content: flex-end;
    }

    .account-setting .account-menu-toggle {
        color: #ffffff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 8px 12px;
        border-radius: 999px;
        background-color: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.22);
        min-height: 44px;
        transition: background-color .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    .account-setting .account-menu-toggle:hover,
    .account-setting .account-menu-toggle:focus {
        color: #ffffff;
        background-color: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.35);
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.18);
    }

    .account-setting .account-menu-toggle::after {
        margin-left: 2px;
        vertical-align: middle;
    }

    .account-setting .account-welcome {
        font-size: 15px;
        font-weight: 700;
        line-height: 1.2;
        color: #ffffff;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
        max-width: 320px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .account-setting .account-gear-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        background-color: rgba(0, 0, 0, 0.14);
    }

    .account-setting .dropdown-menu {
        min-width: 210px;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(18, 38, 63, 0.18);
        border: 1px solid rgba(17, 112, 60, 0.18);
        padding: 6px;
    }

    .account-setting .dropdown-menu .dropdown-item {
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 14px;
        font-weight: 600;
    }

    .account-setting .dropdown-menu .dropdown-item i {
        width: 18px;
        text-align: center;
    }

    @media (max-width: 1199.98px) {
        .nav li a{
            padding: 5px !important;
            margin: 0 !important;
        }

        #vision-carousel{
            top: 35%;
            transform: translateY(-50%);
        }

        .header-text{
            font-size: 8pt;
            white-space: nowrap;
        }

        .dir-caption{
            font-size: 9px;
        }

        .responsive-font, .nav-item, .footer-nav li a{
            font-size: 9pt !important;
        }

        .profile-image{
            width: 45px;
            height: 45px;
        }

        .current-time{
            font-size: 1.5rem;
        }

        .account-setting .account-menu-toggle {
            width: 100%;
            justify-content: space-between;
        }

        .account-setting .account-welcome {
            max-width: 210px;
            font-size: 13px;
        }

    }
</style>
</head>
<body class="portal-app">

@include('portal.modals.login_modal')
@php
    $week = [
        0 => 'Sun',
        1 => 'Mon',
        2 => 'Tue',
        3 => 'Wed',
        4 => 'Thu',
        5 => 'Fri',
        6 => 'Sat',
    ];
@endphp
<div class="header">
    <div class="top-bar">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-7 col-sm-6">
                        <ul class="contact-details">
                        </ul>
                    </div>
                    <div class="col-md-5 col-sm-6">
                        <div class="account-setting">
                            @if(Auth::user())
                            <div class="dropdown d-inline-block">
                                <a href="#"
                                   class="account-menu-toggle dropdown-toggle"
                                   id="accountActionDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    <span class="account-welcome">Welcome, {{ Auth::user()->employee_name }}!</span>
                                    <span class="account-gear-icon" aria-hidden="true">
                                        <i class="fas fa-cog"></i>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountActionDropdown">
                                    <li>
                                        <a class="dropdown-item header-change-password" href="/home" data-bs-toggle="modal" data-bs-target="#changePassword">
                                            <i class="fas fa-key me-2" aria-hidden="true"></i>Change Password
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/userLogout') }}">
                                            <i class="icon-logout me-2" aria-hidden="true"></i>Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
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
<main class="portal-main">
@yield('content')
</main>

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
        var form = $(this).find('form').get(0);
        if (form && typeof form.reset === 'function') {
            form.reset();
        }
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

    $(document).on('click', '.header-change-password', function (e) {
        var modalEl = document.getElementById('changePassword');
        if (!modalEl) {
            return;
        }
        e.preventDefault();
        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    });

    load_time();
    function load_time(){
        setInterval(function() {
            var d = new Date();
            var date_options = { year: 'numeric', month: 'short', day: 'numeric' };
            var time_options = { hour: 'numeric', minute: 'numeric' };
            $('.current-date').html('{{ isset($week[Carbon\Carbon::now()->dayOfWeek]) ? $week[Carbon\Carbon::now()->dayOfWeek] : null }}, ' + d.toLocaleDateString('en-US', date_options));
            $('.current-time').html(d.toLocaleTimeString('en-US', time_options));
        }, 1000);
    }
});
</script>

<div id="copyright">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="site-info text-center">
                    <p>&copy; All rights reserved 2026</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>