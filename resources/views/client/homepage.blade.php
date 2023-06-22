@extends('portal.app')
@section('content')
@php
    $admin_settings = [
        [
            'title' =>'Attendance',
            'url' => '/module/attendance/history'
        ],
        [
            'title' =>'Evaluation',
            'url' => '/evaluation/objectives'
        ],
        [
            'title' =>'Exam',
            'url' => '/examPanel'
        ],
        [
            'title' =>'Leaves',
            'url' => '/module/absent_notice_slip/history'
        ],
        [
            'title' =>'Gatepass',
            'url' => '/client/gatepass/history'
        ],
        [
            'title' =>'HR',
            'url' => '/module/hr/applicants'
        ]
    ];
@endphp
    @include('client.modals.notice_slip_modal')
    @include('client.modals.gatepass_modal')
    {{-- @include('client.modals.attendance_modal') --}}
    @include('client.modals.evaluation_modal')
    @include('client.modals.add_evaluation_file')
    @include('client.modals.edit_evaluation_file')
    @include('client.modals.delete_evaluation_file')
    {{-- @include('client.modals.exam_modal') --}}
    @if ($holiday_reminder)
        <!-- The modal -->
        <div class="modal fade" id="reminder-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="font-weight: 600;">
                    <div class="modal-header bg-success text-white">
                        <h4 class="modal-title" id="modalLabel">
                            <i class="fa fa-info-circle" style="font-size: 15pt;"></i> Reminder
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center" style="padding: 30px 0 30px 0 !important;">
                        <span style="font-size: 13pt;">
                            There are no Special Holiday(s) registered for this year yet.
                        </span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        <a href="/module/attendance/holiday_entry" class="btn bg-primary" style="font-weight: 600;">
                            Go to Holiday(s) list&nbsp;<i class="fa fa-external-link"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn open-reminder d-none" data-bs-toggle="modal" data-bs-target="#reminder-modal">open</button>
    @endif
    <div class="container-fluid mt-2">
        <div class="row m-0 p-0">
            <div class="col-3 col-xl-2 profile-container">
                <div class="card card-primary card-outline mb-3">
                    <div class="card-body box-profile p-2">
                        <div class="text-center">
                            @php
                                $img = Auth::user()->image ? Auth::user()->image : '/storage/img/user.png';
                            @endphp
                            <img class="profile-user-img img-thumbnail img-fluid" src="{{ asset($img) }}"
                                alt="User profile picture" width="170" height="170" style="border-radius: 50%;">
                        </div>
                        <h3 class="profile-username text-center">{{ Auth::user()->employee_name }}</h3>
                        <h6 class="text-muted text-center d-none d-xl-block"><em>{{ $designation }}</em></h6>
                        <small class="text-muted text-center d-block d-xl-none"><em>{{ $designation }}</em></small>
                        <small class="d-block text-muted text-center text-uppercase">{{ $department }}</small>
                        <ul class="list-group list-group-unbordered mt-3 mb-3 responsive-font">
                            <li class="list-group-item">
                                <div class="d-flex flex-row">
                                    <div class="fw-bold">Access ID</div>
                                    <div class="flex-grow-1 text-end"><a class="text-decoration-none">{{ Auth::user()->user_id }}</a></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex flex-row">
                                    <div class="fw-bold">Employment Status</div>
                                    <div class="flex-grow-1 text-end"><a class="text-decoration-none">{{ Auth::user()->employment_status }}</a></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex flex-row">
                                    <div class="fw-bold">Birthdate</div>
                                    <div class="flex-grow-1 text-end"><a class="text-decoration-none">{{ \Carbon\Carbon::parse(Auth::user()->birth_date)->format('M. d, Y') }}</a></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex flex-row">
                                    <div class="fw-bold">Civil Status</div>
                                    <div class="flex-grow-1 text-end"><a class="text-decoration-none">{{ Auth::user()->civil_status }}</a></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex flex-row">
                                    <div class="fw-bold">Contact No.</div>
                                    <div class="flex-grow-1 text-end"><a class="text-decoration-none">{{ Auth::user()->contact_no }}</a></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex flex-row">
                                    <div class="fw-bold">TIN No.</div>
                                    <div class="flex-grow-1 text-end"><a class="text-decoration-none">{{ Auth::user()->tin_no }}</a></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex flex-row">
                                    <div class="fw-bold">SSS No.</div>
                                    <div class="flex-grow-1 text-end"><a class="text-decoration-none">{{ Auth::user()->sss_no }}</a></div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex flex-row">
                                    <div class="fw-bold">Company</div>
                                    <div class="flex-grow-1 text-end"><a class="text-decoration-none">{{ Auth::user()->company }}</a></div>
                                </div>
                            </li>
                        </ul>
                        <a href="#" class="btn btn-primary d-block btn-sm" data-bs-toggle="modal" data-bs-target="#changePassword"><b>
                            <i class="fas fa-cog"></i> Change Password</b></a>
                        @include('client.modals.change_password')
                    </div>
                </div>
            </div>

            <div class="col-9 col-xl-10">
                <div class="row p-0 right-panel">
                    <div class="col-12 col-xl-7">
                        @if(in_array($designation, ['HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President']))
                            <div class="inner-box featured d-block d-xl-none">
                                <div class="widget property-agent">
                                    <h3 class="widget-title">
                                        <div class="d-flex">
                                            HR Settings
                                            <small class="flex-grow-1 text-muted text-end px-1">
                                                <a href="/client/analytics/attendance" class="text-decoration-none text-muted" style="cursor: pointer">
                                                    <i class="fas fa-chart-bar"></i> Analytics
                                                </a>
                                            </small>
                                        </div>
                                    </h3>

                                    <div class="agent-info">
                                        <div class="settings-btn-group settings-btn-block w-100 text-center fw-bold" role="group">
                                            @foreach ($admin_settings as $settings)
                                                <div class="w-100 p-1">
                                                    <a href="{{ $settings['url'] }}">
                                                        <button class="btn btn-primary w-100" style="padding: 5px; font-size: 9pt">{{ $settings['title'] }}</button>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="inner-box featured">
                            <div class="tabs-section">
                                <ul class="nav nav-pills" id="profile-tabs">
                                    <li class="nav-item"><a href="#tab-overview" class="nav-link active border rounded border-success"> Overview</a></li>
                                    <li class="nav-item border rounded border-success"><a href="#tab-my-leaves" class="nav-link">My Leave History</a></li>
                                    <li class="nav-item border rounded border-success"><a href="#tab-my-gatepasses" class="nav-link">My Gatepasses</a></li>
                                    <li class="nav-item border rounded border-success"><a href="#tab-my-itinerary" class="nav-link">My Itinerary</a></li>
                                    <li class="nav-item border rounded border-success"><a href="#tab-my-exam-history" class="nav-link">My Exam History</a></li>
                                    <li class="nav-item border rounded border-success"><a href="#tab-my-evaluations" class="nav-link">My Evaluation(s)</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane in active" id="tab-overview">
                                        <div class="row" id="overview-tab">
                                            @include('client.overview_tab')
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-my-exam-history">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table" style="font-size: 12px;">
                                                    <thead class="text-uppercase">
                                                        <th class="text-center">Exam Date</th>
                                                        <th class="text-center">Exam Title</th>
                                                        <th class="text-center">Exam Group</th>
                                                        <th class="text-center">Date Taken</th>
                                                        <th class="text-center">Validity Date</th>
                                                        <th class="text-center">Action</th>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($clientexams as $exam)
                                                            <tr>
                                                            @if($exam->start_time != null)
                                                                <td class="text-center align-middle">{{ \Carbon\Carbon::parse($exam->date_of_exam)->format('M. d, Y') }}</td>
                                                                <td class="text-center align-middle">{{$exam->exam_title}}</td>
                                                                <td class="text-center align-middle">{{$exam->exam_group_description}}</td>
                                                                <td class="text-center align-middle">{{ \Carbon\Carbon::parse($exam->date_taken)->format('M. d, Y') }}</td>
                                                                <td class="text-center align-middle">{{ \Carbon\Carbon::parse($exam->validity_date)->format('M. d, Y') }}</td>
                                                                <td class="text-center align-middle">
                                                                    <span class="badge bg-success">Completed
                                                                    </span>
                                                                </td>
                                                            @endif
                                                            </tr>
                                                        @empty
                                                            <tr> <td colspan="6" class="text-center text-muted text-uppercase p-2">No records found</td></tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-my-leaves">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div id="my-absent-notice"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-my-gatepasses">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div id="my-gatepasses"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-my-itinerary">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div id="my-itinerary"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane in active" id="tab-memorandum">
                                        <div class="row">
                                            <div class="col-sm-12" id="memo-tab"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-12 col-xl-5">
                        @if(in_array($designation, ['HR Payroll Assistant', 'Human Resources Head', 'Director of Operations', 'President']))
                            <div class="inner-box featured d-none d-xl-block">
                                <div class="widget property-agent">
                                    <h3 class="widget-title">
                                        <div class="d-flex">
                                            HR Settings
                                            <small class="flex-grow-1 text-muted text-end px-1">
                                                <a href="/client/analytics/attendance" class="text-decoration-none text-muted" style="cursor: pointer">
                                                    <i class="fas fa-chart-bar"></i> Analytics
                                                </a>
                                            </small>
                                        </div>
                                    </h3>

                                    <div class="agent-info">
                                        <div class="settings-btn-group settings-btn-block w-100 text-center fw-bold" role="group">
                                            @foreach ($admin_settings as $settings)
                                                <div class="w-100 p-1">
                                                    <a href="{{ $settings['url'] }}">
                                                        <button class="btn btn-primary w-100" style="padding: 5px; font-size: 9pt">{{ $settings['title'] }}</button>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="alert alert-danger blink" id="lateWarning" hidden>
                            <i class="fa fa-info-circle" style="font-size: 15pt;"></i><span> You have reached the maximum late
                                allowed (300 mins.)</span>
                        </div>
                        @if ($kpi_schedules)
                            <div class="alert alert-info blink">
                                <i class="fa fa-info-circle"></i>
                                <span> Schedule for KPI report submission:</span>
                                <ul>
                                    @foreach ($kpi_schedules as $sched)
                                    <li><b>{{ $sched[0] }}:</b> {{ $sched[1] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="inner-box featured">
                            <div class="widget property-agent">
                                <div class="d-flex">
                                    <h3 class="widget-title">My Attendance</h3>
                                    <small id="refreshAttendance" class="flex-grow-1 text-muted text-end px-1" style="cursor: pointer">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </small>
                                </div>
                                <div class="agent-info">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="container">
                                                @php
                                                    $current_date = Carbon\Carbon::now()->format('d');
                                                    $start_date = $end_date = null;
                                                    if($current_date <= 13){
                                                        $start_date = Carbon\Carbon::now()->subMonth(1)->format('Y-m-28');
                                                        $end_date = Carbon\Carbon::now()->format('Y-m-13');
                                                    }else if($current_date >= 14 && $current_date <= 27){
                                                        $start_date = Carbon\Carbon::now()->format('Y-m-14');
                                                        $end_date = Carbon\Carbon::now()->format('Y-m-27');
                                                    }else if($current_date > 27){
                                                        $start_date = Carbon\Carbon::now()->format('Y-m-28');
                                                        $end_date = Carbon\Carbon::now()->addMonth(1)->format('Y-m-13');
                                                    }
                                                @endphp
                                                <div class="row">
                                                    <div class="col-1 date-ctrl d-flex justify-content-center align-items-center" data-action="prev">
                                                        <i class=" fas fa-chevron-left"></i>
                                                    </div>
                                                    <div class="col-10 text-center">
                                                        <h5 class="d-inline" id="cutoff-start">{{ Carbon\Carbon::parse($start_date)->format('M d, Y') }}</h5> - 
                                                        <h5 class="d-inline" id="cutoff-end">{{ Carbon\Carbon::parse($end_date)->format('M d, Y') }}</h5>
                                                        <div class="d-none">
                                                            <input type="text" name="start" value="{{ Carbon\Carbon::parse($start_date)->format('Y-m-d') }}">
                                                            <input type="text" name="end" value="{{ Carbon\Carbon::parse($end_date)->format('Y-m-d') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-1 date-ctrl d-flex justify-content-center align-items-center" data-action="next">
                                                        <i class="fas fa-chevron-right"></i>
                                                    </div>
                                                </div>
                                            </div>
        
                                        </div>
                                        <div class="col-md-12">
                                            <div id="my-attendance" style="min-height: 50px; font-size: 9pt">
                                                <div class="container-fluid d-flex justify-content-center align-items-center p-2">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <iframe id="iframe-print" hidden></iframe>

    <style type="text/css">
    @-webkit-keyframes blinker {
        from {
            opacity: 1.0;
        }

        to {
            opacity: 0.0;
        }
    }

    .blink {
        text-decoration: blink;
        -webkit-animation-name: blinker;
        -webkit-animation-duration: 0.8s;
        -webkit-animation-iteration-count: infinite;
        -webkit-animation-timing-function: ease-in-out;
        -webkit-animation-direction: alternate;
    }

    #evaluationModal {
        overflow-y: scroll
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
    .remove-last-row-border tbody > tr:last-child > td {
        border-bottom: 0;
    }
    .approver-name {
        font-size: 13px;
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
    #profile-tabs li a:not(.active), #evaluation-tabs li a:not(.active), #gatepass-tabs li a:not(.active){
        color: #11703c;
    }
    #profile-tabs li a.active, #evaluation-tabs li a.active, #gatepass-tabs li a.active{
        background-color: #11703c;
    }

    .profile-user-img{
        width: 200px;
        height: 200px;
    }
    
    .data-entry-icon{
        font-size: 28px;
    }

    .data-entry-btn{
        font-size: 16px;
    }

    .date-ctrl{
        cursor: pointer;
    }

    .settings-btn-group.settings-btn-block {
        display: flex;
    }

    .settings-btn-group.settings-btn-block > div {
        flex: 1;
        font-size: 9pt;
        text-decoration: none;
    }

    @media (max-width: 1199.98px) {
        .data-entry-icon{
            font-size: 15pt !important;
        }
        .profile-user-img{
            width: 100px;
            height: 100px;
        }

        .profile-username{
            font-size: 12pt;
        }

        .responsive-font, .data-entry-btn{
            font-size: 8pt !important;
        }

        .right-panel{
            max-height: 85vh;
            overflow-y: auto;
        }
    }
    </style>

    @include('client.modals.add_datainput')
    @include('client.modals.edit_notice_slip')
    @include('client.modals.edit_gatepass')
    @include('client.modals.on_leave_today')
    @include('client.modals.pending_requests')

    <!-- The Birthday Notification Modal -->
    <div class="modal fade" id="birthday-notifcation-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Today is your birthday!</h5>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <form></form>
                        <div class="col-md-12">
                            <center><img src="{{ asset('storage/animated-birthday-image-0015.gif') }}"></center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script src="{{ asset('css/js/calendar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.timepicker.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/jquery.timepicker.css') }}" />
    <script type="text/javascript" src="{{ asset('css/js/datepicker/datepair.js') }}"></script>
    <script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.datepair.js') }}"></script>
    <script type="text/javascript" src="{{ asset('css/js/datepicker/bootstrap-datepicker.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/bootstrap-datepicker.css') }}" />

    <script>
        $(document).ready(function() {
            $('.open-reminder').click();
            $(document).on('click', '.date-ctrl', function (e){
                e.preventDefault();
                $('#my-attendance').html('<div class="container-fluid d-flex justify-content-center align-items-center p-2">' +
                    '<div class="spinner-border" role="status">' +
                        '<span class="visually-hidden">Loading...</span>' +
                    '</div>' +
                '</div>');
                get_cutoff_date($(this).data('action'));
            });

            function get_cutoff_date(op){
                var date = new Date($('input[name="end"]').val());
                var current_date = date.getDate();
                var current_month = date.getMonth() + 1; // + 1, month array starts at 0
                var current_year = date.getFullYear();

                var cutoff_start = cutoff_end = null;
                var start_month = end_month = month = current_month;
                var start_year = end_year = year = current_year;
                if(current_date <= 13){
                    month = op == 'next' ? current_month : current_month - 1;
                    if(month == 0){
                        current_year = current_year - 1;
                        month = 12;
                    }

                    cutoff_start = current_year + '-' + (month < 10 ? '0'+month : month) + '-14';
                    cutoff_end = current_year + '-' + (month < 10 ? '0'+month : month) + '-27';
                }else if(current_date >= 14 && current_date <= 27){
                    if(op == 'next'){
                        end_month = current_month + 1;
                        if(end_month > 12){
                            end_month =  1;
                            end_year = end_year + 1;
                        }
                    }else{
                        start_month = current_month - 1;
                        if(start_month < 1){
                            start_month = 12;
                            start_year = start_year - 1;
                        }
                    }
                    cutoff_start = start_year + '-' + (start_month < 10 ? '0'+start_month : start_month) + '-28';
                    cutoff_end = end_year + '-' + (end_month < 10 ? '0'+end_month : end_month) + '-13';
                }else if(current_date > 27){
                    month = op == 'next' ? current_month + 1 : current_month;
                    if(month == 0){
                        current_year = current_year - 1;
                        month = 12;
                    }

                    cutoff_start = current_year + '-' + (month < 10 ? '0'+month : month) + '-14';
                    cutoff_end = current_year + '-' + (month < 10 ? '0'+month : month) + '-27';
                }

                $('input[name="start"]').val(cutoff_start);
                $('input[name="end"]').val(cutoff_end);

                loadAttendance(1);

                format_date(new Date(cutoff_start), $('#cutoff-start'));
                format_date(new Date(cutoff_end), $('#cutoff-end'));
            }

            function format_date(date, el){
                var month_arr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                var month = date.getMonth();
                month = month_arr[month];
                var day = date.getDate();
                var year = date.getFullYear();

                el.text(month + ' ' + day + ', ' + year);
            }

			$("#profile-tabs li a").click(function(e){
				e.preventDefault();
				$(this).tab("show");
			});

            $('#view-exam-history-tab').click(function (e) {
                e.preventDefault();
                $('#profile-tabs a[href="#tab-my-exam-history"]').tab('show');
            });

            $('#view-my-leaves-tab').click(function (e) {
                e.preventDefault();
                $('#profile-tabs a[href="#tab-my-leaves"]').tab('show');
            });
			
            @if (session()->has('success'))
                $.bootstrapGrowl(
                    "<center><i class=\"fa fa-check-square-o\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">{{ session()->get('message') }}</span></center>", {
                        type: 'success',
                        align: 'center',
                        delay: 4000,
                        width: 450,
                        stackup_spacing: 20
                    });
            @endif

            @if (session()->has('error'))
                $.bootstrapGrowl(
                    "<center><i class=\"fa fa-info\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">{{ session()->get('message') }}</span></center>", {
                        type: 'danger',
                        align: 'center',
                        delay: 4000,
                        width: 450,
                        stackup_spacing: 20
                    });
            @endif

            // initialize input widgets first
            $('.time').timepicker({
                'timeFormat': 'g:i A'
            });

            $('#datepairExample .date').datepicker({
                'format': 'yyyy-mm-dd',
                'autoclose': true
            });

            // initialize datepair
            $('#datepairExample').datepair();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#reg_L7").show();
            $("#reg_L8").show();
            $("#reg_L9").show();
            $("#reg_L10").show();

            var remain_sick = $('#remain_L2').val();
            var remain_vaca = $('#remain_L1').val();

            if (remain_vaca <= 0) {
                $("#emp_L1").hide();
                $(".remain_L1").hide();
            }

            if (remain_sick <= 0) {
                $("#emp_L2").hide();
                $(".remain_L2").hide();
            }

            showBirthdayNotif();

            function showBirthdayNotif() {
                var user_id = '{{ Auth::user()->user_id }}';
                $.ajax({
                    url: "/showBirthdaysToday",
                    data: {
                        user_id: user_id
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            if (sessionStorage.getItem('showModal') !== 'true') {
                                $('#birthday-notifcation-modal').modal('show');
                                sessionStorage.setItem('showModal', 'true');
                            }

                            $('#birthday-notif-div').html(
                                '<div class="alert alert-success" style="font-size: 20pt; text-align: center;"><img src="{{ asset('storage/bday.png') }}" width="150"><span> Today is your birthday! Happy Birthday {{ Auth::user()->nick_name }}!</span></div>'
                                );
                        }
                    }
                });
            }

            function getFirstDayOfMonth() {
                var today = new Date();
                return new Date(today.getFullYear(), today.getMonth(), 1);
            }

            getDeductions();
            loadAttendance();
            loadAbsentNotices();
            loadGatepasses();
            loadItinerary();

            $(document).on('click', '#attendance-modal', function(event) {
                loadAttendanceHistory();
            });

            $(document).on('click', '#onLeaveToday', function(event) {
                event.preventDefault();
                $('#onLeaveModal').modal('show');
            });

            $(document).on('click', '#pendingRequests', function(event) {
                event.preventDefault();
                $('#pendingRequestsModal').modal('show');
            });

            $(document).on('click', '#attendance_pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadAttendance(page);
            });

            $(document).on('click', '#datainput_pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadEmployeedataInput(page);
            });

            $(document).on('click', '#notices_pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadAbsentNotices(page);
            });

            $(document).on('click', '#gatepass_pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadGatepasses(page);
            });

            $(document).on('click', '.viewItinerary', function(e) {
                e.preventDefault();
                var id = $(this).data('idnum');
                data = {
                    id: id
                }

                $.ajax({
                    url: "/itinerary/fetch/companion",
                    type: 'get',
                    data: data,
                    success: function(data) {
                        $('.companiondiv').html(data);
                    }
                });
            });

            function loadItinerary(page) {
                $.ajax({
                    url: "/itinerary/fetch?page=" + page,
                    success: function(data) {
                        $('#my-itinerary').html(data);
                    }
                });
            }

            $(document).on('click', '#itinerary_pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadItinerary(page);
            });

            function loadAttendance(page) {
                var start = $('input[name="start"]').val();
                var end = $('input[name="end"]').val();
                var user_id = '{{ Auth::user()->user_id }}';

                data = {
                    start: start,
                    end: end
                }

                $.ajax({
                    url: "/attendance/dashboard/" + user_id + "?page=" + page,
                    type: 'get',
                    data: data,
                    success: function(data) {
                        $('#my-attendance').html(data);
                    }
                });
            }

            $(document).on('click', '#edit-shift-schedule-btn', function(event) {
                event.preventDefault();
                $('#edit-shift-schedule-form .schedule_id').val($(this).data('id'));
                $('#edit-shift-schedule-form .schedule_date').val($(this).data('schedule'));
                $('#edit-shift-schedule-form .shift_schedule').val($(this).data('shift'));
                $('#edit-shift-schedule-form .branch').val($(this).data('branch'));
                $('#edit-shift-schedule-form .department').val($(this).data('department'));
                $('#edit-shift-schedule-form .remarks').val($(this).data('remarks'));
                $('#edit-shift-schedule-modal').modal('show');
            });

            $(document).on('click', '#delete-shift-schedule-btn', function(event) {
                event.preventDefault();
                $('#delete-shift-schedule-form .schedule_id').val($(this).data('id'));
                $('#delete-shift-schedule-form .schedule_date').val($(this).data('schedule'));
                $('#delete-shift-schedule-form .schedule').text($(this).data('schedule'));
                $('#delete-shift-schedule-modal').modal('show');
            });

            $(document).on('click', '#edit-shift-btn', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                data = {
                    'id': id
                }
                $.ajax({
                    url: "/getShiftDetails",
                    data: data,
                    success: function(data) {
                        $('#edit-shift-form .shift_id').val(data.shift_id);
                        $('#edit-shift-form .schedule_name').val(data.shift_schedule);
                        $('#edit-shift-form .time_in').val(data.time_in);
                        $('#edit-shift-form .time_out').val(data.time_out);
                        $('#edit-shift-form .breaktime').val(data.breaktime_by_hour);
                        $('#edit-shift-form .grace_period').val(data.grace_period_in_mins);
                        $('#edit-shift-modal').modal('show');
                    }
                });
            });

            $(document).on('click', '#delete-shift-btn', function(event) {
                event.preventDefault();
                $('#delete-shift-form .shift_id').val($(this).data('id'));
                $('#delete-shift-form .shift_name').val($(this).data('name'));
                $('#delete-shift-form .shift_name').text($(this).data('name'));
                $('#delete-shift-modal').modal('show');
            });

            function loadAbsentNotices(page) {
                $.ajax({
                    url: "/notice_slip/fetch?page=" + page,
                    success: function(data) {
                        $('#my-absent-notice').html(data);
                    }
                });
            }

            function loadGatepasses(page) {
                $.ajax({
                    url: "/gatepass/fetch?page=" + page,
                    success: function(data) {
                        $('#my-gatepasses').html(data);
                    }
                });
            }

            function loadAttendanceHistory() {
                var start = $('#attendanceHistoryFilter_start').val();
                var end = $('#attendanceHistoryFilter_end').val();
                var user_id = $('#attendanceHistoryFilter_employee').val();

                data = {
                    start: start,
                    end: end,
                }

                $.ajax({
                    url: "/attendance/history/" + user_id,
                    data: data,
                    success: function(data) {
                        $('#attendance-history').html(data);
                    }
                });
            }

            $(document).on('click', '#refreshAttendance', function(e) {
                e.preventDefault();
                $('#my-attendance').html('<div class="container-fluid d-flex justify-content-center align-items-center p-2">' +
                    '<div class="spinner-border" role="status">' +
                        '<span class="visually-hidden">Loading...</span>' +
                    '</div>' +
                '</div>');
                loadAttendance(1);
                // var employee = '{{ Auth::user()->user_id }}';
                // $.ajax({
                //     type: 'POST',
                //     url: '/attendance/update/' + employee,
                //     beforeSend: function() {
                //         $("#refreshAttendance").text("Updating...");
                //     },
                //     success: function(response) {
                //         loadAttendance();
                //     },
                //     complete: function() {
                //         $("#refreshAttendance").html("<i class=\"fa fa-refresh\"></i> Refresh");
                //     }
                // });
            });


            $(document).on('click', '#view-notice', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                data = {
                    'id': id
                }

                $.ajax({
                    url: "/notice_slip/getDetails",
                    data: data,
                    success: function(data) {
                        $('#viewNoticeModal .date-from-val').val(data.date_from);
                        $('#viewNoticeModal .date-to-val').val(data.date_to);
                        $('#viewNoticeModal .leave-type-id-val').val(data.leave_type_id);
                        $('#viewNoticeModal .user-id-val').val(data.user_id);
                        $('#viewNoticeModal .notice-id-val').val(data.notice_id);
                        $('#viewNoticeModal .notice-id').text(data.notice_id);
                        $('#viewNoticeModal .employee-name').text(data.employee_name);
                        $('#viewNoticeModal .department').text(data.department);
                        $('#viewNoticeModal .leave-type').text(data.leave_type);
                        $('#viewNoticeModal .date-from').text(data.date_from);
                        $('#viewNoticeModal .time-from').text(data.time_from);
                        $('#viewNoticeModal .date-to').text(data.date_to);
                        $('#viewNoticeModal .time-to').text(data.time_to);
                        $('#viewNoticeModal .reported-through').text(data.means);
                        $('#viewNoticeModal .time-reported').text(data.time_reported);
                        $('#viewNoticeModal .received-by').text(data.info_by);
                        $('#viewNoticeModal .approved-by').text(data.approved_by);
                        $('#viewNoticeModal .date-approved').text(data.approved_date);
                        $('#viewNoticeModal .reason').text(data.reason);
                        $('#viewNoticeModal .remarks').text(data.remarks);
                        $('#viewNoticeModal .date-filed').text(data.date_filed);

                        var status = data.status;
                        if (status.toLowerCase() == 'approved' || status.toLowerCase() ==
                            'for approval') {
                            $("#manager-cancel-notice").show();
                        } else {
                            $("#manager-cancel-notice").hide();
                        }

                        switch (status.toLowerCase()) {
                            case 'approved':
                                $('#viewNoticeModal .hidden-row').attr('hidden', false);
                                $("#viewNoticeModal .status").html(
                                    "<span class=\"label label-primary\"><i class=\"fa fa-thumbs-o-up\"></i> Approved</span>"
                                    );
                                $("#iframe-print").attr("src", "/printNotice/" + data
                                .notice_id);
                                break;
                            case 'cancelled':
                                $('#viewNoticeModal .hidden-row').attr('hidden', true);
                                $("#viewNoticeModal .status").html(
                                    "<span class=\"label label-danger\"><i class=\"fa fa-ban\"></i> Cancelled</span>"
                                    );
                                break;
                            case 'disapproved':
                                $('#viewNoticeModal .hidden-row').attr('hidden', true);
                                $("#viewNoticeModal .status").html(
                                    "<span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Disapproved</span>"
                                    );
                                break;
                            case 'deferred':
                                $('#viewNoticeModal .hidden-row').attr('hidden', true);
                                $("#viewNoticeModal .status").html(
                                    "<span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Deferred</span>"
                                    );
                                break;
                            default:
                                $('#viewNoticeModal .hidden-row').attr('hidden', true);
                                $("#viewNoticeModal .status").html(
                                    "<span class=\"label label-warning\"><i class=\"fa fa-clock-o\"></i> For Approval</span>"
                                    );
                        }

                        $('#viewNoticeModal').modal('show');
                    }
                });
            });

            $(document).on('click', '#edit-unreturned-gatepass', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                data = {
                    'id': id
                }
                $.ajax({
                    url: "/gatepass/getDetails",
                    data: data,
                    success: function(data) {
                        $('#unreturnedGatepassModal .gatepass_id').val(data.gatepass_id);
                        $('#unreturnedGatepassModal .gatepass-id').text(data.gatepass_id);
                        $('#unreturnedGatepassModal .employee-name').text(data.employee_name);
                        $('#unreturnedGatepassModal .date-filed').text(data.date_filed);
                        $('#unreturnedGatepassModal .time').text(data.time);
                        $('#unreturnedGatepassModal .items').text(data.item_description);
                        $('#unreturnedGatepassModal .purpose').text(data.purpose);
                        $('#unreturnedGatepassModal .returned-on').text(data.returned_on);
                        $('#unreturnedGatepassModal .company-name').text(data.company_name);
                        $('#unreturnedGatepassModal .address').text(data.address);
                        $('#unreturnedGatepassModal .tel-no').text(data.tel_no);
                        $('#unreturnedGatepassModal .remarks').text(data.remarks);
                        $('#unreturnedGatepassModal .item-type').text(data.item_type);
                        $('#unreturnedGatepassModal .status').text(data.status);
                        $('#unreturnedGatepassModal .approved-by').text(data.approved_by);
                        $('#unreturnedGatepassModal .date-approved').text(data.approved_date);
                        var status = data.status;
                        switch (status.toLowerCase()) {
                            case 'approved':
                                $('#unreturnedGatepassModal .hidden-row').attr('hidden', false);
                                $("#unreturnedGatepassModal .status").html(
                                    "<span class=\"label label-primary\"><i class=\"fa fa-thumbs-o-up\"></i> Approved</span>"
                                    );
                                break;
                            case 'cancelled':
                                $('#unreturnedGatepassModal .hidden-row').attr('hidden', true);
                                $("#unreturnedGatepassModal .status").html(
                                    "<span class=\"label label-danger\"><i class=\"fa fa-ban\"></i> Cancelled</span>"
                                    );
                                break;
                            case 'disapproved':
                                $('#unreturnedGatepassModal .hidden-row').attr('hidden', true);
                                $("#unreturnedGatepassModal .status").html(
                                    "<span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Disapproved</span>"
                                    );
                                break;
                            case 'deferred':
                                $('#unreturnedGatepassModal .hidden-row').attr('hidden', true);
                                $("#unreturnedGatepassModal .status").html(
                                    "<span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Deferred</span>"
                                    );
                                break;
                            default:
                                $('#unreturnedGatepassModal .hidden-row').attr('hidden', true);
                                $("#unreturnedGatepassModal .status").html(
                                    "<span class=\"label label-warning\"><i class=\"fa fa-clock-o\"></i> For Approval</span>"
                                    );
                        }
                        $('#unreturnedGatepassModal').modal('show');
                    }
                });
            });

            $(document).on('click', '#view-gatepass', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                data = {
                    'id': id
                }
                $.ajax({
                    url: "/gatepass/getDetails",
                    data: data,
                    success: function(data) {
                        $('#viewGatepassModal .gatepass-id').text(data.gatepass_id);
                        $('#viewGatepassModal .employee-name').text(data.employee_name);
                        $('#viewGatepassModal .date-filed').text(data.date_filed);
                        $('#viewGatepassModal .time').text(data.time);
                        $('#viewGatepassModal .items').text(data.item_description);
                        $('#viewGatepassModal .purpose').text(data.purpose);
                        $('#viewGatepassModal .returned-on').text(data.returned_on);
                        $('#viewGatepassModal .company-name').text(data.company_name);
                        $('#viewGatepassModal .address').text(data.address);
                        $('#viewGatepassModal .tel-no').text(data.tel_no);
                        $('#viewGatepassModal .remarks').text(data.remarks);
                        $('#viewGatepassModal .item-type').text(data.item_type);
                        $('#viewGatepassModal .status').text(data.status);
                        $('#viewGatepassModal .approved-by').text(data.approved_by);
                        $('#viewGatepassModal .date-approved').text(data.approved_date);
                        var status = data.status;
                        switch (status.toLowerCase()) {
                            case 'approved':
                                $('#viewGatepassModal .hidden-row').attr('hidden', false);
                                $("#viewGatepassModal .status").html(
                                    "<span class=\"label label-primary\"><i class=\"fa fa-thumbs-o-up\"></i> Approved</span>"
                                    );
                                break;
                            case 'cancelled':
                                $('#viewGatepassModal .hidden-row').attr('hidden', true);
                                $("#viewGatepassModal .status").html(
                                    "<span class=\"label label-danger\"><i class=\"fa fa-ban\"></i> Cancelled</span>"
                                    );
                                break;
                            case 'disapproved':
                                $('#viewGatepassModal .hidden-row').attr('hidden', true);
                                $("#viewGatepassModal .status").html(
                                    "<span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Disapproved</span>"
                                    );
                                break;
                            case 'deferred':
                                $('#viewGatepassModal .hidden-row').attr('hidden', true);
                                $("#viewGatepassModal .status").html(
                                    "<span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Deferred</span>"
                                    );
                                break;
                            default:
                                $('#viewGatepassModal .hidden-row').attr('hidden', true);
                                $("#viewGatepassModal .status").html(
                                    "<span class=\"label label-warning\"><i class=\"fa fa-clock-o\"></i> For Approval</span>"
                                    );
                        }
                        $('#viewGatepassModal').modal('show');
                    }
                });
            });

            $(document).on('show.bs.modal', '.modal', function(event) {
                var zIndex = 1040 + (10 * $('.modal:visible').length);
                $(this).css('z-index', zIndex);
                setTimeout(function() {
                    $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass(
                        'modal-stack');
                }, 0);
            });

            $(document).on('click', '#print-notice', function(event) {
                event.preventDefault();
                $("#iframe-print").get(0).contentWindow.print();
            });

            $(document).on('click', '#print-gatepass', function(event) {
                event.preventDefault();
                $("#iframe-print").get(0).contentWindow.print();
            });

            $(document).on('click', '#printAbsent', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                $("#iframe-print").attr("src", "/printNotice/" + id);
                $('#iframe-print').load(function() {
                    $(this).get(0).contentWindow.print();
                });
            });

            $(document).on('click', '#printGatepass', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                $("#iframe-print").attr("src", "/printGatepass/" + id);
                $('#iframe-print').load(function() {
                    $(this).get(0).contentWindow.print();
                });
            });

            $(document).on('change', '#filterDateStart', function(e) {
                var type = $('#filterDateStart').val();
                data = {
                    type: type
                }
                $.ajax({
                    url: '/kiosk/notice/getusershift',
                    type: 'get',
                    data: data,
                    success: function(data) {
                        $('#starttime').val(data.shift_in);
                        $('#endtime').val(data.shift_out);
                        SumHours();
                    }
                });
            });

            $(document).on('click', '#editAbsent', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                data = {
                    'id': id
                }
                $.ajax({
                    url: "/notice_slip/getDetails",
                    data: data,
                    success: function(data) {
                        var leave_type = "leave_type_id" + data.leave_type_id;
                        $('#edit-notice-form .' + leave_type).prop('checked', true);
                        $('#edit-notice-form .notice_id').val(data.notice_id);
                        $('#edit-notice-form .from_date').val(data.date_from);
                        $('#edit-notice-form .from_time').val(data.time_from);
                        $('#edit-notice-form .to_date').val(data.date_to);
                        $('#edit-notice-form .to_time').val(data.time_to);
                        $('#edit-notice-form .means').val(data.means);
                        $('#edit-notice-form .time_reported').val(data.time_reported);
                        $('#edit-notice-form .info_by').val(data.info_by);
                        $('#edit-notice-form .approved_by').text(data.approved_by);
                        $('#edit-notice-form .leave_type').val(data.leave_type_id);
                        $('#edit-notice-form .date_approved').text(data.approved_date);
                        $("#print-notice").hide();

                        var remain_sick = $('#notice_remain_L2').val();
                        var remain_vaca = $('#notice_remain_L1').val();

                        SumHours_notice();

                        var start = new Date($("#startdate_notice").val());
                        var end = new Date($("#enddate_notice").val());
                        var totaldays = workingDaysBetweenDates(new Date(start), new Date(end));

                        console.log(totaldays);

                        var status = data.status;
                        if (status.toLowerCase() != 'for approval') {
                            $("#cancel-notice").hide();
                            $("#update-notice").hide();
                        } else {
                            $("#cancel-notice").show();
                            $("#update-notice").show();
                        }
                        if (status.toLowerCase() != 'cancelled') {

                            $("#amend-notice").hide();
                        } else {

                            $("#amend-notice").show();

                            if (remain_vaca > 0) {
                                if (totaldays > remain_vaca) {
                                    $("#notice_emp_L1").hide();
                                    $(".notice_remain_L1").hide();
                                } else {
                                    $("#notice_emp_L1").show();
                                    $(".notice_remain_L1").show();
                                }
                            }
                            if (remain_sick > 0) {
                                if (totaldays > remain_sick) {
                                    $("#notice_emp_L2").hide();
                                    $(".notice_remain_L2").hide();
                                } else {
                                    $("#notice_emp_L2").show();
                                    $(".notice_remain_L2").show();
                                }
                            }
                        }

                        switch (status.toLowerCase()) {
                            case 'approved':
                                $('#edit-notice-form :input').attr('disabled', true);
                                $("#edit-notice-form .status").html(
                                    "<h3><span class=\"badge bg-primary\"><i class=\"fa fa-thumbs-o-up\"></i> Approved</span></h3>"
                                    );
                                $("#iframe-print").attr("src", "/printNotice/" + data
                                .notice_id);
                                $("#edit-notice-form .divStatus").show();
                                $("#print-notice").show();
                                break;
                            case 'cancelled':
                                $('#edit-notice-form input').attr('disabled', false);
                                $("#edit-notice-form .status").html(
                                    "<h3><span class=\"badge bg-danger\"><i class=\"fa fa-ban\"></i> Cancelled</span></h3>"
                                    );
                                $("#edit-notice-form .divStatus").hide();
                                break;
                            case 'disapproved':
                                $('#edit-notice-form :input').attr('disabled', true);
                                $("#edit-notice-form .status").html(
                                    "<h3><span class=\"badge bg-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Disapproved</span></h3>"
                                    );
                                $("#edit-notice-form .divStatus").hide();
                                break;
                            case 'deferred':
                                $('#edit-notice-form :input').attr('disabled', true);
                                $("#edit-notice-form .status").html(
                                    "<h3><span class=\"badge bg-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Deferred</span></h3>"
                                    );
                                $("#edit-notice-form .divStatus").hide();
                                break;
                            default:
                                $('#edit-notice-form :input').attr('disabled', false);
                                $("#edit-notice-form .divStatus").hide();
                                $("#edit-notice-form .status").html(
                                    "<h3><span class=\"badge bg-warning\"><i class=\"fa fa-clock-o\"></i> For Approval</span></h3>"
                                    );
                        }

                        $('#edit-notice-form .reason').text(data.reason);
                        $('#editNoticeModal').modal('show');
                    }
                });
            });

            $(document).on('click', '#editGatepass', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                data = {
                    'id': id
                }
                $.ajax({
                    url: "/gatepass/getDetails",
                    data: data,
                    success: function(data) {
                        $('#edit-gatepass-form .gatepass_id').val(data.gatepass_id);
                        $('#edit-gatepass-form .status').val(data.status);
                        $('#edit-gatepass-form .item_type').val(data.item_type);
                        $('#edit-gatepass-form .date_filed').val(data.date_filed);
                        $('#edit-gatepass-form .returned_on').val(data.returned_on);
                        $('#edit-gatepass-form .company_name').val(data.company_name);
                        $('#edit-gatepass-form .time').val(data.time);
                        $('#edit-gatepass-form .address').val(data.address);
                        $('#edit-gatepass-form .purpose').val(data.purpose);
                        $('#edit-gatepass-form .tel_no').val(data.tel_no);
                        $('#edit-gatepass-form .item_description').text(data.item_description);
                        $('#edit-gatepass-form .remarks').text(data.remarks);
                        $('#edit-gatepass-form .approved_by').text(data.approved_by);
                        $('#edit-gatepass-form .date_approved').text(data.approved_date);
                        $('#edit-gatepass-form .purpose_type').val(data.purpose_type);
                        $("#print-gatepass").hide();

                        var status = data.status;
                        if (status.toLowerCase() != 'for approval') {
                            $("#cancel-gatepass").hide();
                            $("#update-gatepass").hide();
                        } else {
                            $("#cancel-gatepass").show();
                            $("#update-gatepass").show();
                        }

                        switch (status.toLowerCase()) {
                            case 'approved':
                                $('#edit-gatepass-form :input').attr('disabled', true);
                                $("#edit-gatepass-form .status").html(
                                    "<h3><span class=\"label label-primary\"><i class=\"fa fa-thumbs-o-up\"></i> Approved</span></h3>"
                                    );
                                $("#edit-gatepass-form .divStatus").show();
                                $("#iframe-print").attr("src", "/printGatepass/" + data
                                    .gatepass_id);
                                $("#print-gatepass").show();
                                break;
                            case 'cancelled':
                                $("#edit-gatepass-form .divStatus").hide();
                                $('#edit-gatepass-form :input').attr('disabled', true);
                                $("#edit-gatepass-form .status").html(
                                    "<h3><span class=\"label label-danger\"><i class=\"fa fa-ban\"></i> Cancelled</span></h3>"
                                    );
                                break;
                            case 'disapproved':
                                $("#edit-gatepass-form .divStatus").hide();
                                $('#edit-gatepass-form :input').attr('disabled', true);
                                $("#edit-gatepass-form .status").html(
                                    "<h3><span class=\"label label-danger\"><i class=\"fa fa-thumbs-o-down\"></i> Disapproved</span></h3>"
                                    );
                                break;
                            default:
                                $('#edit-gatepass-form :input').attr('disabled', false);
                                $("#edit-gatepass-form .status").html(
                                    "<h3><span class=\"label label-warning\"><i class=\"fa fa-clock-o\"></i> For Approval</span></h3>"
                                    );
                                $("#edit-gatepass-form .divStatus").hide();
                        }
                        $('#editGatepassModal').modal('show');
                    }
                });
            });

            $('#edit-gatepass-form').on("submit", function(event) {
                event.preventDefault();
                $.ajax({
                    url: "/gatepass/updateDetails",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        loadGatepasses();
                        $.bootstrapGrowl(
                            "<center><i class=\"fa fa-check-square-o\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" +
                            data.message + "</span></center>", {
                                type: 'success',
                                align: 'center',
                                delay: 4000,
                                width: 450,
                                offset: {
                                    from: 'top',
                                    amount: 300
                                },
                                stackup_spacing: 20
                            });

                        $('#editGatepassModal').modal('hide');
                    }
                });
            });

            $('#edit-notice-form').on("submit", function(event) {
                event.preventDefault();
                $.ajax({
                    url: "/notice_slip/updateDetails",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        loadAbsentNotices();
                        $.bootstrapGrowl(
                            "<center><i class=\"fa fa-check-square-o\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" +
                            data.message + "</span></center>", {
                                type: 'success',
                                align: 'center',
                                delay: 4000,
                                width: 450,
                                offset: {
                                    from: 'top',
                                    amount: 300
                                },
                                stackup_spacing: 20
                            });
                        $('#editNoticeModal').modal('hide');
                    }
                });
            });

            $('#cancel-notice').on("click", function(event) {
                event.preventDefault();
                var id = $('#edit-notice-form .notice_id').val();
                var leave_id = $('#edit-notice-form .leave_type').val();
                var from_date = $('#edit-notice-form .from_date').val();
                var to_date = $('#edit-notice-form .to_date').val();
                var user_id = $('#edit-notice-form .user_id').val();
                data = {
                    'notice_id': id,
                    'leave_id': leave_id,
                    'date_from': from_date,
                    'date_to': to_date,
                    'user_id': user_id,
                    '_token': '{{ csrf_token() }}'
                }

                $.ajax({
                    url: "/notice_slip/cancelNotice_per_employee",
                    type: "POST",
                    data: data,
                    success: function(data) {
                        loadAbsentNotices();
                        $.bootstrapGrowl(
                            "<center><i class=\"fa fa-ban\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" +
                            data.message + "</span></center>", {
                                type: 'danger',
                                align: 'center',
                                delay: 4000,
                                width: 450,
                                offset: {
                                    from: 'top',
                                    amount: 300
                                },
                                stackup_spacing: 20
                            });
                        $('#editNoticeModal').modal('hide');
                    }
                });
            });

            $('#cancel-gatepass').on("click", function(event) {
                event.preventDefault();
                var id = $('#edit-gatepass-form .gatepass_id').val();

                data = {
                    'id': id
                }

                $.ajax({
                    url: "/gatepass/cancelGatepass",
                    type: "POST",
                    data: data,
                    success: function(data) {
                        loadGatepasses();
                        $.bootstrapGrowl(
                            "<center><i class=\"fa fa-ban\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" +
                            data.message + "</span></center>", {
                                type: 'danger',
                                align: 'center',
                                delay: 4000,
                                width: 450,
                                offset: {
                                    from: 'top',
                                    amount: 300
                                },
                                stackup_spacing: 20
                            });
                        $('#editGatepassModal').modal('hide');
                    }
                });
            });

            $('#add-gatepass-form').on("submit", function(event) {
                event.preventDefault();
                $.ajax({
                    url: "/gatepass/create",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        loadGatepasses();
                        $.bootstrapGrowl(
                            "<i class=\"fa fa-check-circle-o\" style=\"font-size: 60pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 16pt; font-weight: bold; padding-top: 5px;\">Request sent to Managers.</span><span style=\"font-size: 11pt;\">Please wait for the approved gatepass form.<br>" +
                            data.message + "</span>", {
                                type: 'success',
                                align: 'center',
                                delay: 8000,
                                width: 450,
                                offset: {
                                    from: 'top',
                                    amount: 300
                                },
                                stackup_spacing: 20
                            });
                        $('#gatepassModal').modal('hide');
                    }
                });
            });

            $('#add-notice-form').on("submit", function(event) {
                event.preventDefault();
                $('#notice-slip-submit-btn').attr('disabled', true);
                $.ajax({
                    url: "/notice_slip/create",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        $('#notice-slip-submit-btn').attr('disabled', false);
                        if (data.success) {
                            loadAbsentNotices();
                            $.bootstrapGrowl(
                                "<i class=\"fa fa-check-circle-o\" style=\"font-size: 60pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 16pt; font-weight: bold; padding-top: 5px;\">Request sent to Managers.</span><span style=\"font-size: 10pt;\">Please wait for the approved absent notice form.<br>" +
                                data.message + "</span>", {
                                    type: 'success',
                                    align: 'center',
                                    delay: 8000,
                                    width: 450,
                                    offset: {
                                        from: 'top',
                                        amount: 300
                                    },
                                    stackup_spacing: 20
                                });
                            $('#absentNoticeModal').modal('hide');
                        } else {
                            $.bootstrapGrowl(
                                "<i class=\"fa fa-info\" style=\"font-size: 20pt; float: left; padding-right: 10px;\"></i><span style=\"font-size: 10pt;\">" +
                                data.message + "</span>", {
                                    type: 'danger',
                                    align: 'center',
                                    delay: 8000,
                                    width: 450,
                                    stackup_spacing: 20
                                });
                        }
                    },
                    error: function(response) {
                        $('#notice-slip-submit-btn').attr('disabled', false);
                        $.bootstrapGrowl(
                            "<i class=\"fa fa-info\" style=\"font-size: 20pt; float: left; padding-right: 10px;\"></i><span style=\"font-size: 10pt;\">An error occured. Please try again.</span>", {
                                type: 'danger',
                                align: 'center',
                                delay: 8000,
                                width: 450,
                                stackup_spacing: 20
                            });
                    }
                });
            });

            $('.absentTodayFilter').on('change', function() {
                loadAbsentToday();
            });

            function loadAbsentToday() {
                var start = $('#filterDateStart').val();
                var end = $('#filterDateEnd').val();

                data = {
                    start: start,
                    end: end
                }

                $.ajax({
                    url: "/notice_slip/absentToday",
                    data: data,
                    success: function(data) {
                        $('#out-of-office-table').html(data);
                    }
                });
            }

            $('.attendanceFilter').on('change', function() {
                loadAttendance();
            });

            $('.attendanceHistoryFilter').on('change', function() {
                loadAttendanceHistory();
            });

            $('.modal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $("#iframe-print").removeAttr("src");
            });

            $('#absentNoticeModal').on('hidden.bs.modal', function() {
                $('#out-of-office-table').html("");
                $('#notice-slip-submit-btn').removeAttr('disabled');
            });

            function getDeductions() {
                var employee = '{{ Auth::user()->user_id }}';

                $.ajax({
                    url: "/attendance/deductions/" + employee,
                    success: function(data) {
                        if (data >= 300) {
                            $("#lateWarning").removeAttr("hidden");
                        }
                    }
                });
            }
        });

        $(document).ready(function() {
            $(document).on('click', '#evaluation-files-pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadEvaluations(page);
            });

            $(document).on('click', '#evaluation-modal', function(e) {
                e.preventDefault();
                loadEvaluations();
            });

            $(document).on('click', '#add-evaluation-file-btn', function(e) {
                e.preventDefault();
                $('#add-evaluation-file-modal').modal('show');
            });

            $(document).on('click', '#datainputmodal', function(e) {
                e.preventDefault();
                $('#data_inputmodal').modal('show');
            });

            $(document).on('click', '#evaluation-files-pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadEvaluations(page);
            });

            $(document).on('click', '#edit-evaluation-file-btn', function(event) {
                event.preventDefault();
                $('#edit-evaluation-file-modal .id').val($(this).data('id'));
                $('#edit-evaluation-file-modal .title').val($(this).data('title'));
                $('#edit-evaluation-file-modal .employee').val($(this).data('employee'));
                $('#edit-evaluation-file-modal .eval-date').val($(this).data('eval-date'));
                $('#edit-evaluation-file-modal .eval-file').val($(this).data('file'));
                $('#edit-evaluation-file-modal .remarks').val($(this).data('remarks'));
                $('#edit-evaluation-file-modal .modified_date').text($(this).data('modifieddate'));
                $('#edit-evaluation-file-modal .modified_name').text($(this).data('modifiedname'));
                $('#edit-evaluation-file-modal').modal('show');
            });

            $(document).on('click', '#delete-evaluation-file-btn', function(event) {
                event.preventDefault();
                $('#delete-evaluation-file-modal .id').val($(this).data('id'));
                $('#delete-evaluation-file-modal .eval-title').val($(this).data('title'));
                $('#delete-evaluation-file-modal .title').text($(this).data('title'));
                $('#delete-evaluation-file-modal .employee').text($(this).data('employee'));
                $('#delete-evaluation-file-modal').modal('show');
            });

            $(document).on('submit', '#delete-evaluation-file-form', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "/deleteEvaluation",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        $.bootstrapGrowl("<center><i class=\"fa " + data.icon +
                            "\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><div style=\"display:block; font-size: 12pt; padding-top: 5px;\">" +
                            data.message + "</div></center>", {
                                type: data.class_name,
                                align: 'center',
                                delay: 4000,
                                width: 450,
                                offset: {
                                    from: 'top',
                                    amount: 300
                                },
                                stackup_spacing: 20
                            });
                        loadEvaluations();
                        $('#delete-evaluation-file-modal').modal('hide');
                    }
                });
            });

            $(document).on("submit", '#submitform', function(event) {
                event.preventDefault();
                $.ajax({
                    url: "/savedatainput",
                    type: "POST",
                    data: $('#submitform').serialize(),
                    dataType: "json",
                    success: function(data) {
                        if ('1' == data.message) {
                            $.bootstrapGrowl(
                                "<i class=\"fa fa-info-circle\" style=\"font-size: 60pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 16pt; font-weight: bold; padding-top: 5px;\"> Data is already exist! </span><span style=\"font-size: 10pt;\"><br></span>", {
                                    type: 'danger',
                                    align: 'center',
                                    delay: 4000,
                                    width: 450,
                                    offset: {
                                        from: 'top',
                                        amount: 300
                                    },
                                    stackup_spacing: 20
                                });
                        } else {
                            $.bootstrapGrowl(
                                "<i class=\"fa fa-check-circle-o\" style=\"font-size: 60pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 16pt; font-weight: bold; padding-top: 5px;\">" +
                                data.message +
                                "</span><span style=\"font-size: 10pt;\"><br></span>", {
                                    type: 'success',
                                    align: 'center',
                                    delay: 4000,
                                    width: 450,
                                    offset: {
                                        from: 'top',
                                        amount: 300
                                    },
                                    stackup_spacing: 20
                                });
                            $('#data_inputmodal').modal('hide');
                            $('#submitform')[0].reset();

                            loadEmployeedataInput();
                            loadKpiResult();
                        }
                    },
                    error: function(data) {
                        alert('error');
                    }
                });
            });

            loadAppraisal();

            function loadAppraisal(page) {
                var user_id = {{ Auth::user()->user_id }};

                $.ajax({
                    url: "/getEmpAppraisal/" + user_id + "?page=" + page,
                    success: function(data) {
                        $('#appraisal-table').html(data);
                    }
                });
            }

            $(document).on('submit', '#edit-evaluation-file-form', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "/editEvaluation",
                    method: "POST",
                    data: new FormData(this),
                    // dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        msg = '<ul>';
                        $.each(data.message, function(d, i) {
                            msg += '<li>' + i + '</li>';
                        });
                        msg += '</ul>';
                        $.bootstrapGrowl("<center><i class=\"fa " + data.icon +
                            "\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><div style=\"display:block; font-size: 12pt; padding-top: 5px;\">" +
                            msg + "</div></center>", {
                                type: data.class_name,
                                align: 'center',
                                delay: 4000,
                                width: 450,
                                offset: {
                                    from: 'top',
                                    amount: 300
                                },
                                stackup_spacing: 20
                            });

                        if (data.class_name == 'success') {
                            loadEvaluations();
                            $('#edit-evaluation-file-modal').modal('hide');
                        }
                    }
                });
            });

            $(document).on('submit', '#add-evaluation-file-form', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "/addEvaluation",
                    method: "POST",
                    data: new FormData(this),
                    // dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        msg = '<ul>';
                        $.each(data.message, function(d, i) {
                            msg += '<li>' + i + '</li>';
                        });
                        msg += '</ul>';
                        $.bootstrapGrowl("<center><i class=\"fa " + data.icon +
                            "\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><div style=\"display:block; font-size: 12pt; padding-top: 5px;\">" +
                            msg + "</div></center>", {
                                type: data.class_name,
                                align: 'center',
                                delay: 4000,
                                width: 450,
                                offset: {
                                    from: 'top',
                                    amount: 300
                                },
                                stackup_spacing: 20
                            });

                        if (data.class_name == 'success') {
                            loadEvaluations();
                            $('#add-evaluation-file-modal').modal('hide');
                        }
                    }
                });
            });

            loadKpiResult();
            createFunction();
            loadEmployeedataInput();
            entryvalidation();
            getemployeeperdept();

            function loadEvaluations(page) {
                $.ajax({
                    url: "/getEvaluations?page=" + page,
                    success: function(data) {
                        $('#evaluation-table').html(data);
                    }
                });
            }

            loadKpiResult();

        });
    </script>
    <script type="text/javascript">
        function createFunction() {
            var dept = document.getElementById('dept').value;
            var employeelist = document.getElementById('employeelist').value;
            var entry = document.getElementById('entry').value;
            var departmentvalidate = document.getElementById('departmentvalidate').value;
            var user_id = {{ Auth::user()->user_id }};
            var eval_period = document.getElementById('entrysched').value;
            data = {
                employeelist: employeelist,
                dept: dept,
                entry: entry,
                eval_period: eval_period
            }
            $.ajax({
                url: '/getdatainput',
                type: 'get',
                data: data,
                success: function(data) {
                    $('#viewdatainput').html(data);
                    $('#entry_val').val(entry);
                    $('#user_id').val(employeelist);
                    $('#depart_id').val(dept);
                    $('#eval-period').val(eval_period);
                    show_schedule_date();


                    if (entry == 'per_employee') {
                        $("#employeelistdiv").show();

                    } else {
                        $("#employeelistdiv").hide();
                    }
                }

            });

        }
    </script>
    <script type="text/javascript">
        function loadKpiResult(page) {
            var user_id = {{ Auth::user()->user_id }};
            var filmonth = document.getElementById('monthfilter').value;
            var filyear = document.getElementById('yearfilter').value;
            data = {
                filmonth: filmonth,
                filyear: filyear

            }
            $.ajax({
                url: "/getEmpKpiResult/" + user_id + "?page=" + page,
                data: data,
                success: function(data) {
                    $('#kpi-result-table').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function loadEmployeedataInput(page) {

            var schedentry = document.getElementById('schedentry').value;
            var filmonths = document.getElementById('monthfilter').value;
            var filyears = document.getElementById('yearfilter').value;
            var user_id = {{ Auth::user()->user_id }};
            data = {
                schedentry: schedentry,
                filmonths: filmonths,
                filyears: filyears,
                user_id: user_id
            }
            $.ajax({
                url: "/tblDatainput?page=" + page,
                data: data,
                success: function(data) {
                    $('#tblDatainput').html(data);
                    getemployeeperdept();
                }
            });
        }
    </script>

    <script type="text/javascript">
        function getemployeeperdept() {
            createFunction();

            var dept = document.getElementById('dept').value;
            var deptvalidate = document.getElementById('departmentvalidate').value;
            data = {
                dept: dept,
                deptvalidate: deptvalidate,
            }

            $.ajax({
                url: '/getemployeeperdept',
                type: 'get',
                dataType: 'JSON',
                data: data,
                success: function(result) {
                    $('#employeelist').html(result);
                    createFunction();

                },
                error: function(result) {
                    alert('Error fetching data!');
                }
            });
        }
    </script>

    <script type="text/javascript">
        function entryvalidation() {
            createFunction();
            $('#entry').on('change', function() {
                if (this.value == 'per_employee') {
                    $("#employeelistdiv").show();
                    getemployeeperdept();
                } else {
                    $("#employeelistdiv").hide();
                }
            });
        }
    </script>

    <script type="text/javascript">
        function sumofday() {
            var start = $("#filterDateStart").datepicker("getDate");
            var end = $("#filterDateEnd").datepicker("getDate");

            var totaldays = workingDaysBetweenDates(new Date(start), new Date(end));
            var remain_sick = $('#remain_L2').val();
            var remain_vaca = $('#remain_L1').val();

            SumHours();
        }

        function workingDaysBetweenDates(startDate, endDate) {
            // Validate input
            if (endDate < startDate)
                return 0;

            // Calculate days between dates
            var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
            startDate.setHours(0, 0, 0, 1); // Start just after midnight
            endDate.setHours(23, 59, 59, 999); // End just before midnight
            var diff = endDate - startDate; // Milliseconds between datetime objects    
            var days = Math.ceil(diff / millisecondsPerDay);

            // Subtract two weekend days for every week in between
            var weeks = Math.floor(days / 7);
            days = days - (weeks * 1);

            // Handle special cases
            var startDay = startDate.getDay();
            var endDay = endDate.getDay();

            // Remove weekend not previously removed.   
            if (startDay - endDay > 1)
                days = days - 1;

            // Remove start day if span starts on Sunday but ends before Saturday
            if (startDay == 0 && endDay != 6) {
                days = days - 1;
            }

            return days;
        }
    </script>

    <script type="text/javascript">
        function sumofday_refillnotice() {
            var start = new Date($("#startdate_notice").val());
            var end = new Date($("#enddate_notice").val());
            var totaldays = workingDaysBetweenDates(new Date(start), new Date(end));

            var remain_sick = $('#notice_remain_L2').val();
            var remain_vaca = $('#notice_remain_L1').val();

            if (remain_vaca > 0) {
                if (totaldays > remain_vaca) {
                    $("#notice_emp_L1").hide();
                    $(".notice_remain_L1").hide();
                } else {
                    $("#notice_emp_L1").show();
                    $(".notice_remain_L1").show();
                }
            }
            if (remain_sick > 0) {
                if (totaldays > remain_sick) {
                    $("#notice_emp_L2").hide();
                    $(".notice_remain_L2").hide();
                } else {
                    $("#notice_emp_L2").show();
                    $(".notice_remain_L2").show();
                }
            }
        }

        function SumHours_notice() {
            var smon = $('#notice_from_time').val();
            var fmon = $('#notice_end_time').val();
            var convertedFrom = (convertTime12to24(smon));
            var convertedTo = (convertTime12to24(fmon));
            console.log(convertedFrom);
            console.log(convertedTo);
            var remain_sick = $('#notice_remain_L2').val();
            var remain_vaca = $('#notice_remain_L1').val();
            var diff = 0;

            var start = new Date($("#startdate_notice").val());
            var end = new Date($("#enddate_notice").val());
            var totaldays = workingDaysBetweenDates(new Date(start), new Date(end));

            if (smon && fmon) {
                smon = ConvertToSeconds(convertedFrom);
                fmon = ConvertToSeconds(convertedTo);
                diff = Math.abs(fmon - smon);
                if (secondsTohhmmss(diff) <= 3) {
                    $("#notice_reg_L8").hide();
                    $("#notice_reg_L9").show();
                    $("#notice_reg_L10").show();
                    $("#notice_reg_L7").hide();

                    $("#notice_emp_L1").hide();
                    $(".notice_remain_L1").hide();
                    $("#notice_emp_L2").hide();
                    $(".notice_remain_L2").hide();
                } else if (secondsTohhmmss(diff) <= 4) {
                    $("#notice_reg_L7").hide();
                    $("#notice_reg_L8").show();
                    $("#notice_reg_L9").hide();
                    $("#notice_reg_L10").hide();

                    $("#notice_emp_L1").hide();
                    $(".notice_remain_L1").hide();
                    $("#notice_emp_L2").hide();
                    $(".notice_remain_L2").hide();
                } else if (secondsTohhmmss(diff) <= 8) {
                    $("#notice_reg_L7").hide();
                    $("#notice_reg_L8").show();
                    $("#notice_reg_L9").hide();
                    $("#notice_reg_L10").hide();

                    $("#notice_emp_L1").hide();
                    $(".notice_remain_L1").hide();
                    $("#notice_emp_L2").hide();
                    $(".notice_remain_L2").hide();

                } else if (secondsTohhmmss(diff) >= 9) {
                    $("#notice_reg_L7").show();
                    $("#notice_reg_L8").hide();
                    $("#notice_reg_L9").hide();
                    $("#notice_reg_L10").hide();

                    if (remain_vaca > 0) {
                        if (totaldays > remain_vaca) {
                            $("#notice_emp_L1").hide();
                            $(".notice_remain_L1").hide();
                        } else {
                            $("#notice_emp_L1").show();
                            $(".notice_remain_L1").show();
                        }
                    }
                    if (remain_sick > 0) {
                        if (totaldays > remain_sick) {
                            $("#notice_emp_L2").hide();
                            $(".notice_remain_L2").hide();
                        } else {
                            $("#notice_emp_L2").show();
                            $(".notice_remain_L2").show();
                        }
                    }

                }

            }
        }
    </script>
    <script type="text/javascript">
        const convertTime12to24 = (time12h) => {
            const [time, modifier] = time12h.split(' ');

            let [hours, minutes] = time.split(':');

            if (hours === '12') {
                hours = '00';
            }

            if (modifier === 'PM') {
                hours = parseInt(hours, 10) + 12;
            }

            return `${hours}:${minutes}`;
        }

        function SumHours() {
            var smon = $('#starttime').val();
            var fmon = $('#endtime').val();
            var convertedFrom = (convertTime12to24(smon));
            var convertedTo = (convertTime12to24(fmon));
            //   console.log(convertedFrom);
            //   console.log(convertedTo);
            var remain_sick = $('#remain_L2').val();
            var remain_vaca = $('#remain_L1').val();
            var diff = 0;

            var start = new Date($("#filterDateStart").val());
            var end = new Date($("#filterDateEnd").val());
            var totaldays = workingDaysBetweenDates(new Date(start), new Date(end));

            if (smon && fmon) {
                smon = ConvertToSeconds(convertedFrom);
                fmon = ConvertToSeconds(convertedTo);
                diff = Math.abs(fmon - smon);
                console.log(secondsTohhmmss(diff));
                if (secondsTohhmmss(diff) <= 3) {
                    $("#reg_L8").hide();
                    $("#reg_L9").show();
                    $("#reg_L10").show();
                    $("#reg_L7").hide();

                    $("#emp_L1").hide();
                    $("#emp_L1-5").show(); // VL/SL Half day
                    $(".remain_L1").hide();
                    $("#emp_L2").hide();
                    $("#emp_L2-5").show(); // VL/SL Half day
                    $(".remain_L2").hide();
                } else if (secondsTohhmmss(diff) <= 4) {
                    $("#reg_L7").hide();
                    $("#reg_L8").show();
                    $("#reg_L9").hide();
                    $("#reg_L10").hide();

                    $("#emp_L1").hide();
                    $("#emp_L1-5").show(); // VL/SL Half day
                    $(".remain_L1").hide();
                    $("#emp_L2").hide();
                    $("#emp_L2-5").show(); // VL/SL Half day
                    $(".remain_L2").hide();
                } else if (secondsTohhmmss(diff) <= 8) {
                    $("#reg_L7").hide();
                    $("#reg_L8").show();
                    $("#reg_L9").hide();
                    $("#reg_L10").hide();

                    $("#emp_L1").hide();
                    $("#emp_L1-5").show(); // VL/SL Half day
                    $(".remain_L1").hide();
                    $("#emp_L2").hide();
                    $("#emp_L2-5").show(); // VL/SL Half day
                    $(".remain_L2").hide();

                } else if (secondsTohhmmss(diff) >= 9) {
                    $("#reg_L7").show();
                    $("#reg_L8").hide();
                    $("#reg_L9").hide();
                    $("#reg_L10").hide();

                    if (remain_vaca > 0) {
                        if (totaldays > remain_vaca) {
                            $("#emp_L1").hide();
                            $("#emp_L1-5").hide();
                            $(".remain_L1").hide();
                            console.log('hide vaca');
                        } else {
                            $("#emp_L1").show();
                            $("#emp_L1-5").show();
                            $(".remain_L1").show();
                            console.log('show vaca');
                        }
                    }
                    if (remain_sick > 0) {
                        if (totaldays > remain_sick) {
                            $("#emp_L2").hide();
                            $("#emp_L2-5").hide();
                            $(".remain_L2").hide();
                            console.log('hide sick');
                        } else {
                            $("#emp_L2").show();
                            $("#emp_L2-5").show();
                            $(".remain_L2").show();
                            console.log('show sick');
                        }
                    }

                }

            }
        }

        function ConvertToSeconds(time) {
            var splitTime = time.split(":");
            return splitTime[0] * 3600 + splitTime[1] * 60;
        }

        function secondsTohhmmss(secs) {
            var hours = parseInt(secs / 3600);
            var seconds = parseInt(secs % 3600);
            var minutes = parseInt(seconds / 60);
            return hours;
        }
    </script>
    <script type="text/javascript">
        function show_schedule_date() {
            var get_val = $("#schedule_date").val();
            $('#show_scheduledDate').text(get_val);

        }
    </script>
    <script type="text/javascript">
        $(document).on('click', '#employee_submit', function(event) {
            var excode = $(this).data('idcode');
            $.ajax({
                type: 'post',
                url: '/oem/employee/validateExamCode',
                data: {
                    excode: excode
                },
                success: function(data) {
                    console.log(data);
                    $.bootstrapGrowl('<center><span id="msg-alert">' + data.message +
                        '</span></center>', {
                            type: data.status,
                            align: 'center',
                            offset: {
                                from: 'top',
                                amount: 170
                            },
                            width: 400,
                            delay: 4000,
                            stackup_spacing: 10,
                            allow_dismiss: false
                        });

                    if (data.status == 'success') {
                        setTimeout(function() {
                            window.location.href = "/oem/employee/index/" + data.examinee_id;
                        }, 1000);
                    }
                }
            });
        });
    </script>
@endsection
