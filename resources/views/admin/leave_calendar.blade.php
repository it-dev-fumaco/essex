@extends('admin.app')
@section('content')
	<div class="row">
		<div class="col-sm-12 col-md-10 col-md-offset-1">
         <div class="inner-box featured">
            <h2 class="title-2">Employee Leave Calendar</h2>
               
            @if(session("message"))
            <div class='alert alert-success alert-dismissible'>
               <button type='button' class='close' data-dismiss='alert'>&times;</button>
               <center>{{ session("message") }}</center>
            </div>
            @endif
            <center>
               <div id="calendar" style="padding-top: 50px;"></div>
           </center>
            

         </div>
      </div>
   </div>
@endsection

@section('script')

   <link rel="stylesheet" href="{{ asset('css/calendar/fullcalendar.css') }}" />
   <script src="{{ asset('css/calendar/moment.min.js') }}"></script>
   <script src="{{ asset('css/calendar/fullcalendar.min.js') }}"></script>
   <script>

   $(document).ready(function() {
      var calendar = $('#calendar').fullCalendar({
         header:{
            left:'prev,next today',
            center:'title',
            right:'month,agendaWeek,agendaDay'
         },
         eventSources: [{
            url: '/admin/leave_calendar/load',
            type: 'GET',
            // data: {
            //   custom_param1: 'something',
            //   custom_param2: 'somethingelse'
            // },
            error: function() {
               alert('there was an error while fetching events!');
            }
         }],
         selectable:true,
         selectHelper:true,
      });
   });
   </script>
@endsection