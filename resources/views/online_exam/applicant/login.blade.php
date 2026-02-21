<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<meta name="author" content="EstateX">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<title>ESSEX v7 - Online Exam</title>
<link rel="stylesheet" href="{{ asset('css/css/bootstrap.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('css/fonts/font-awesome.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('css/fonts/line-icons/line-icons.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('css/css/main.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('css/extras/animate.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('css/css/responsive.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('css/css/bootstrap-select.min.css') }}">
</head> 
<style type="text/css">
  *{
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  }

  #msg-alert{
   text-align: center;
   font-size: 15pt;
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
                  <i></i>
                </a>
              </li>
            </ul>
          </div>
          {{--<div class="col-md-5 col-sm-6">
            <div class="account-setting">
              <a href="{{ url('/userLogout') }}">
                <i class="icon-logout"></i>
                <span>Logout</span>
              </a>
            </div>
          </div>--}}
        </div>
      </div>
    </div>

    <div class="top-bar-bottom">
      <div class="container">
        <div class="row">
          <div class="col-md-5 col-sm-6">
            <div class="header-logo">
              <a href="/">
                <img src="{{ asset('storage/img/logo5.png') }}" alt="">
              </a>
            </div>
            <div class="name-title" style="font-size: 17px;"><b>FUMACO Inc. </b><br> The Art of Science & Lighting</div>
          </div>
          <div class="col-md-7 col-sm-6">
            <div class="pull-right">
              <div class="row" style="padding: 3px;">
                <div style="float: left; margin-right: 5px;">
                  <img src="{{ asset('storage/img/user.png') }}" width="60" height="60">
                </div>
                <div style="float: right; margin-top: 8px;">
                  <span style="display: block;">
                    <h4>Applicant</h4>

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
         
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="columns-wrapper">
          <h3 class="h3" style="text-align: center">Welcome, Applicant! Please provide the Examination Code.</h3>
          <div class="col-md-12" style="text-align: center; height: 25em;">
            

              <div id="alert"></div>
            <form autocomplete="off" id="examCodeForm">
              @csrf
              <input type="text" id="code" class="form-control" style="width: 30%; margin:auto; font-size: 17pt; font-weight: bold; text-align: center;" pattern="[0-9]{4}-[0-9]{5}" maxlength="10" minlength="10" name="excode" id="excode" required autofocus>
              <div style="padding: 10px;">
                <input type="submit" class="btn btn-success" name="submit" value="Proceed">
              </div>
            </form>
        </div>

      </div>
    </div>
  </div>
</div>
</div>


<a href="#" class="back-to-top">
  <i class="icon-arrow-up"></i>
</a>

<script src="{{ asset('css/js/ajax.min.js') }}"></script> 
<script type="text/javascript" src="{{ asset('css/js/jquery-min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/jquery.bootstrap-growl.js') }}"></script>
<script type = "text/javascript" src = "{{ asset('css/js/jquery-ui.min.js') }}"></script>

<script>  
   $(document).ready(function(){
      window.history.pushState(null, "", window.location.href);        
      window.onpopstate = function() {
         window.history.pushState(null, "", window.location.href);
      };

      $('#examCodeForm').submit(function(e){
         e.preventDefault();
         $.ajax({
           type: 'post',
           url: '/oem/validate_exam_code',
           data: $(this).serialize(),
           success: function(data){
              console.log(data);
               $.bootstrapGrowl('<center><span id="msg-alert">'+data.message+'</span></center>', {
                  type: data.status,
                  align: 'center',
                  offset: {from: 'top', amount: 170},
                  width: 400,
                  delay: 4000,
                  stackup_spacing: 10,
                  allow_dismiss: false
               });

               if (data.status == 'success') {
                  setTimeout(function() {
                     window.location.href = "/oem/index/" + data.examinee_id;
                  }, 1500);
               }
            }
         });
      });
  });  
</script>

</body>
</html>