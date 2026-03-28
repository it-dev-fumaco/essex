{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Admin Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="access_id" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

 --}}



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        
    {{-- <link rel="icon" type="image/ico" href="img/logo1.png"> --}}
    <title>Essex v3 Admin</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/login/css/fonts/fonts-login.css')}}">
    <link rel="stylesheet" href="{{ asset('css/login/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/assets/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/assets/css/form-elements.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/assets/css/style.css') }}">
    
    <!-- Favicon and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('css/login/assets/ico/apple-touch-icon-144-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('css/login/assets/ico/apple-touch-icon-114-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('css/login/assets/ico/apple-touch-icon-72-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('css/login/assets/ico/apple-touch-icon-57-precomposed.png') }}">
  </head>
  <body>
    <!-- Top content -->
    <div class="top-content">
      <div class="inner-bg">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-sm-offset-2 text">
              <h1>
                <strong>Essex</strong> v3.0 - Admin
              </h1>
              <div class="description"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-sm-offset-3 form-box">
              <div class="form-top">
                <div class="form-top-left">
                  <h3>Login to system</h3>
                  <p>Enter your username and password to log on</p>
                </div>
                <div class="form-top-right">
                  <i class="fa fa-lock"></i>
                </div>
              </div>
              <div class="form-bottom">
                <form role="form" class="login-form" method="POST" action="{{ route('admin.login.submit') }}" name="login">
                @csrf

                  <div class="form-group">
                    <label class="sr-only" for="form-username">Access Id</label>
                    <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="access_id" value="{{ old('email') }}" placeholder="Access ID" required autofocus>
                  </div>
                  <div class="form-group">
                    <label class="sr-only" for="form-password">Password</label>
                    {{-- <input type="password" name="password" class="form-password form-control" id="form-password"> --}}
                    <input id="password" type="password" placeholder="Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                  </div>
                  <button type="submit" class="btn">Sign in!</button>
                </form>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-sm-offset-3 social-login">
              <h3>...or login to other fumaco sytems:</h3>
              <div class="social-login-buttons">
                <a href="javascript:" class="btn btn-link-2" onClick="window.open('https://zimbra.fumaco.local');">Zimbra</a>
                <a href="javascript:" class="btn btn-link-2" onClick="window.open('http://athenaerp.fumaco.com');">AthenaERP Inventory</a>
                <a href="javascript:" class="btn btn-link-2" onClick="window.open('http://mes.fumaco.com');">MES</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Javascript -->
    <script src="{{ asset('css/login/assets/js/jquery-1.11.1.min.js') }}"></script>
    <script src="{{ asset('css/login/assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('css/login/assets/js/jquery.backstretch.min.js') }}"></script>
    <script src="{{ asset('css/login/assets/js/scripts.js') }}"></script>
  </body>
</html>
{{-- @endsection --}}


