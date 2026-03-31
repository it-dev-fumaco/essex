@extends('portal.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/calendar/fullcalendar.css') }}" />
    <style>
        .portal-calendar-wrap {
            padding: 16px 12px;
        }

        .portal-calendar-card {
            border-top: 3px solid #FFC414;
        }

        .portal-calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px 12px;
            padding: 10px 12px;
            border-bottom: 1px solid rgba(0,0,0,.06);
        }

        .portal-calendar-title {
            font-weight: 800;
            margin: 0;
            font-size: 14pt;
        }

        .portal-calendar-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 10px;
            font-size: 10.5pt;
            color: rgba(0,0,0,.65);
        }

        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            display: inline-block;
        }

        .portal-calendar-body {
            padding: 12px;
        }

        /* Keep calendar usable on narrow screens */
        @media (max-width: 576px) {
            .portal-calendar-wrap {
                padding: 12px 8px;
            }
            .portal-calendar-body {
                padding: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid portal-calendar-wrap">
        <div class="card portal-card portal-calendar-card">
            <div class="portal-calendar-header">
                <h2 class="portal-calendar-title">Calendar</h2>
                <div class="portal-calendar-legend">
                    <span class="legend-item"><span class="legend-dot" style="background:#F1C40F"></span> Birthday</span>
                    <span class="legend-item"><span class="legend-dot" style="background:#8E44AD"></span> Work Anniversary</span>
                    @if(Auth::check())
                        <span class="legend-item"><span class="legend-dot" style="background:#E74C3C"></span> Out Of Office</span>
                    @endif
                </div>
            </div>
            <div class="portal-calendar-body">
                <div id="portalCalendar"></div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('css/calendar/moment.min.js') }}"></script>
    <script src="{{ asset('css/calendar/fullcalendar.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#portalCalendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                eventSources: [
                    '{{ url('/calendar/events') }}'
                ],
                selectable: false,
                editable: false,
                eventLimit: true
            });
        });
    </script>
@endsection

