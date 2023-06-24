@extends('portal.app')
@section('content')
   <div class="row p-3">
      <div class="col-9">
         <div class="card">
            <div class="card-body p-2">
               <div class="d-flex flex-row justify-content-between">
                  @if ($designation == 'Human Resources Head')
                  <div class="col-4">
                     <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addquestion" style="z-index: 1;">
                        <i class="fa fa-plus"></i> event
                     </a>
                  </div>
                  @endif
                  <h2 class="section-title center p-2 col-4">Leave Calendar</h2>
                  <div class="p-2 col-4 text-end">
                     <a href="#" onclick="printElem('calendar')">
                        <div style="font-size: 30px"><i class="fa fa-print"></i></div>
                     </a>
                  </div>
               </div>
               @include('client.calendar.modals.addEvent')

               <div class="d-flex flex-row justify-content-between">
                  @if (session('message'))
                  <div class='alert alert-success alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>
                     <center>{!! session('message') !!}</center>
                  </div>
                  @endif
                  <div class="p-3 mt-3" id="div1" name="div1">
                     <div id="calendar"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-3">
         <div class="card mb-3">
            <div class="card-body p-2" style="min-height: 1000px;">
                <h3 class="widget-title mb-2" style="font-size: 12px !important;">Out of the office today</h3>
                <table class="table m-0 remove-last-row-border">
                    <tbody class="table-body">
                        @forelse($out_of_office_today as $out_of_office)
                        <tr>
                            <td style="width: 60%;">
                                @php
                                    $img = $out_of_office->image ? $out_of_office->image : '/storage/img/user.png';
                                @endphp
                                <img src="{{ $img }}" width="50" height="50" class="rounded-circle img-thumbnail" style="float: left; margin-right: 10px;">
                                <span class="approver-name d-block">{{ $out_of_office->employee_name }}</span>
                                <small class="d-block fst-italic text-muted">{{ $out_of_office->designation }}</small>
                            </td>
                            <td style="width: 40%; font-size: 10px;">
                                <i class="icon-clock"></i> {{ $out_of_office->time_from }} -
                                {{ $out_of_office->time_to }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted text-uppercase">Nobody's out</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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

                header: {
                    left: 'prev,next today myCustomButton',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                eventSources: [
                    '/holidays',
                    '/bday',
                    '/calendar/fetch'
                ],

                selectable: true,
                selectHelper: true,
            });
        });
    </script>
    <script type="text/javascript">
        $('.printBtn').on('click', function() {
            window.print();
        });
    </script>

    <script type="text/javascript">
        function printElem(div1) {
            var headerElements = document.getElementsByClassName('fc-header'); //.style.display = 'none';
            for (var i = 0, length = headerElements.length; i < length; i++) {
                headerElements[i].style.display = 'none';
            }
            var toPrint = document.getElementById('div1').cloneNode(true);

            for (var i = 0, length = headerElements.length; i < length; i++) {
                headerElements[i].style.display = '';
            }

            var linkElements = document.getElementsByTagName('div1');
            var link = '';
            for (var i = 0, length = linkElements.length; i < length; i++) {
                link = link + linkElements[i].outerHTML;
            }

            var styleElements = document.getElementsByTagName('div1');
            var styles = '';
            for (var i = 0, length = styleElements.length; i < length; i++) {
                styles = styles + styleElements[i].innerHTML;
            }

            var popupWin = window.open('', '_blank', 'left=0,top=0,width=800,height=600,toolbar=0,scrollbars=0,status=0');
            popupWin.document.open();
            popupWin.document.write('<html><title></title>' + link +
                '<style>' + styles + '</style></head><body">')
            popupWin.document.write(
                '<link href="{{ asset('css/calendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />');
            popupWin.document.write(
                '<link href="{{ asset('css/calendar/fullcalendar.print.css') }}" rel="stylesheet" type="text/css" />');

            popupWin.document.write(toPrint.innerHTML);
            popupWin.document.write('</html>');
            setTimeout(function() {
                popupWin.print();
            }, 1000);
            popupWin.document.close();
            return true;
        }
    </script>

    <style type="text/css" media="print">
        @media print {
            @page {
                size: landscape
            }
        }

        ;
    </style>
    <style type="text/css">
        .fa fa-plus {
            width: 24px !important;
            height: 24px !important;
        }
    </style>
@endsection
