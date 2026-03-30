@extends('portal.app')
@section('content')
@php
    $admin_users = ['HR Payroll Assistant', 'HR Assistant', 'Human Resources Head', 'HR Head', 'Director of Operations', 'President'];
    $admin_settings = [
        [
            'icon' => 'fas fa-calendar',
            'title' => 'Attendance',
            'url' => '/module/attendance/history',
            'bg-color' => 'bg-gradient bg-secondary',
            'allowed-users' => $admin_users,
        ],
        [
            'icon' => 'fas fa-clipboard-list',
            'title' => 'Evaluation',
            'url' => '/evaluation/objectives',
            'bg-color' => 'bg-gradient bg-primary',
            'allowed-users' => $admin_users
        ],
        [
            'icon' => 'fas fa-pen-square',
            'title' => 'Exam',
            'url' => '/examPanel',
            'bg-color' => 'bg-gradient bg-warning',
            'allowed-users' => ['HR Payroll Assistant', 'HR Assistant', 'Human Resources Head', 'HR Head', 'Director of Operations', 'President', 'Operations Manager']
        ],
        [
            'icon' => 'fas fa-user-check',
            'title' => 'Leaves',
            'url' => '/module/absent_notice_slip/history',
            'bg-color' => 'bg-gradient bg-success',
            'allowed-users' => $admin_users
        ],
        [
            'icon' => 'fas fa-clipboard-check',
            'title' => 'Gatepass',
            'url' => '/client/gatepass/history',
            'bg-color' => 'bg-gradient bg-info',
            'allowed-users' => $admin_users
        ],
        [
            'icon' => 'fas fa-users',
            'title' => 'HR',
            'url' => '/module/hr/applicants',
            'bg-color' => 'bg-gradient bg-dark',
            'allowed-users' => $admin_users
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
        <div class="row m-0 p-0 align-items-start">
            <div class="col-3 col-xl-2 profile-container">
                <div class="card card-primary card-outline mb-3">
                    <div class="card-body box-profile p-2">
                        <div class="text-center">
                            @php
                                use Illuminate\Support\Facades\Storage;
                                use Illuminate\Support\Str;

                                $avatarUrl = asset('storage/img/user.png');
                                try {
                                    $disk = Storage::disk('upcloud');
                                    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                                    $key = 'employees/profile/'.(string) Auth::user()->user_id.'.jpg';
                                    if ($disk->exists($key)) {
                                        $v = optional(Auth::user()->updated_at)->timestamp ?? time();
                                        $avatarUrl = $disk->url($key).'?v='.$v;
                                    }
                                } catch (\Throwable $e) {
                                    // keep fallback
                                }
                            @endphp

                            <div class="profile-photo-wrapper-home">
                                <img
                                    id="homeProfilePhotoImg"
                                    data-user-id="{{ Auth::user()->user_id }}"
                                    src="{{ $avatarUrl }}"
                                    alt="User profile picture"
                                    width="170"
                                    height="170"
                                    class="profile-user-img img-thumbnail img-fluid"
                                    style="border-radius: 50%;"
                                >

                                <div class="profile-photo-overlay-home" aria-hidden="true"></div>
                                <button
                                    type="button"
                                    class="btn btn-primary profile-photo-change-btn-home"
                                    id="homeProfilePhotoChangeBtn"
                                >
                                    <i class="fa fa-upload"></i> Change Photo
                                </button>
                                <input
                                    type="file"
                                    id="homeProfilePhotoInput"
                                    name="empImage"
                                    accept="image/png,image/jpeg,image/jpg"
                                    style="display: none;"
                                >
                            </div>

                            <div id="home-profile-photo-message" style="display:none; margin-top: 10px;"></div>
                        </div>
                        <h3 class="profile-username text-center">{{ Auth::user()->employee_name }}</h3>
                        @php
                            $joiningDateRaw = Auth::user()->date_joined ?? Auth::user()->joining_date ?? null;
                            $tenureText = 'Tenure: N/A';

                            if (! empty($joiningDateRaw)) {
                                try {
                                    $joinDate = \Carbon\Carbon::parse($joiningDateRaw);
                                    $now = \Carbon\Carbon::now();

                                    if ($joinDate->lte($now)) {
                                        $diff = $joinDate->diff($now);

                                        $years = (int) $diff->y;
                                        $months = (int) $diff->m;
                                        $days = (int) $diff->d;

                                        $yearsLabel = $years.' year'.($years === 1 ? '' : 's');
                                        $monthsLabel = $months.' month'.($months === 1 ? '' : 's');
                                        $daysLabel = $days.' day'.($days === 1 ? '' : 's');

                                        if ($years < 1) {
                                            if ($months > 0 && $days > 0) {
                                                $tenureText = $monthsLabel.' and '.$daysLabel;
                                            } elseif ($months > 0) {
                                                $tenureText = $monthsLabel;
                                            } else {
                                                $tenureText = $daysLabel;
                                            }
                                        } else {
                                            $parts = [$yearsLabel];
                                            if ($months > 0) {
                                                $parts[] = $monthsLabel;
                                            }
                                            if ($days > 0) {
                                                $parts[] = $daysLabel;
                                            }

                                            $tenureText = implode(' and ', $parts);
                                        }
                                    }
                                } catch (\Throwable $e) {
                                    // fall back to Tenure: N/A
                                }
                            }
                        @endphp

                        <h6 class="text-muted text-center d-none d-xl-block"><em>{{ $designation }}</em></h6>
                        <small class="text-muted text-center d-block d-xl-none"><em>{{ $designation }}</em></small>
                        <small class="d-block text-muted text-center text-uppercase">{{ $department }}</small>
                        <small class="text-muted text-center d-block" style="margin-top: -2px;"><em>{{ $tenureText }}</em></small>
                        <div class="card mb-3 mt-3">
                            <div class="card-body p-2">
                                <h3 class="widget-title mb-2" style="font-size: 12px !important;">My Leave Approver(s)</h3>
                                <table class="table m-0 remove-last-row-border">
                                    <tbody class="table-body">
                                        @forelse($approvers as $approver)
                                        <tr>
                                            @if ($approver->employee_id != Auth::user()->user_id)
                                            <td>
                                                @php
                                                    $img = $approver->image ? $approver->image : '/storage/img/user.png';
                                                @endphp
                                                <img src="{{ $img }}" width="50" height="50" class="rounded-circle img-thumbnail" style="float: left; margin-right: 10px;">
                                                <span class="approver-name d-block">{{ $approver->employee_name }}</span>
                                                <small class="d-block fst-italic text-muted">{{ $approver->designation }}</small>
                                            </td>
                                            @endif
                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center text-uppercase text-muted">Leave Approver not found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body p-2">
                                <h3 class="widget-title mb-2" style="font-size: 12px !important;">Reporting to</h3>
                                <div class="d-flex align-items-center px-2">
                                    @php
                                        $img = $reports_to ? $reports_to->image : '/storage/img/user.png';
                                    @endphp
                                    @if ($reports_to)
                                    <img src="{{ $img }}" width="50" height="50" class="rounded-circle img-thumbnail" style="float: left; margin-right: 10px;">
                                    <div class="p-2">
                                        <span class="approver-name d-block">{{ $reports_to->employee_name }}</span>
                                        <small class="d-block fst-italic text-muted">{{ $reports_to->designation }}</small>
                                    </div>
                                    @else
                                    <div class="col-12 p-2 text-center text-uppercase text-muted">
                                        Immediate Supervisor not set
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($direct_reports->isNotEmpty())
                        <div class="card mb-3">
                            <div class="card-body p-2">
                                <h3 class="widget-title mb-2" style="font-size: 12px !important;">Direct reports</h3>
                                <table class="table m-0 remove-last-row-border">
                                    <tbody class="table-body">
                                        @foreach ($direct_reports as $direct_report)
                                            <tr>
                                                <td>
                                                    @php
                                                        $drImg = $direct_report->image ? $direct_report->image : '/storage/img/user.png';
                                                    @endphp
                                                    <img src="{{ $drImg }}" width="50" height="50" class="rounded-circle img-thumbnail" style="float: left; margin-right: 10px;" alt="">
                                                    <span class="approver-name d-block">{{ $direct_report->employee_name }}</span>
                                                    <small class="d-block fst-italic text-muted">{{ $direct_report->designation }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        @include('client.modals.change_password')
                    </div>
                </div>
            </div>
            <div class="col-9 col-xl-10">
                <div class="row p-0 right-panel align-items-start">
                    <div class="col-12 col-xl-7">
                        @if(in_array($designation, ['HR Payroll Assistant', 'HR Head', 'Human Resources Head', 'Director of Operations', 'President', 'Operations Manager']))
                            <div class="inner-box featured d-block d-xl-none mb-3">
                                <div class="widget property-agent">
                                    <h3 class="widget-title">
                                        <div class="d-flex">
                                            Settings
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
                                            @php
                                                $settings_btn_status = '';
                                                if (!in_array($designation, $settings['allowed-users'])) {
                                                    $settings_btn_status = 'settings-btn-opacity disabled';
                                                }

                                                if ($depart == 'head' && $settings['title'] == 'Evaluation') {
                                                    $settings_btn_status = '';
                                                }
                                            @endphp
                                        
                                            <div class="w-100 p-1">
                                                <button class="btn settings-btn {{ $settings_btn_status }} {{ $settings['bg-color'] }} w-100 text-capitalize p-2 {{ $settings['title'] == 'Exam' ? 'text-dark' : null }}" style="padding: 5px; border-radius: 0.7rem;" {{ $settings_btn_status }} data-href="{{ $settings['url'] }}">
                                                    <i class="{{ $settings['icon'] }} d-block m-1"></i>
                                                    <span class="d-block" style="font-size: 9pt;">{{ $settings['title'] }}</span>
                                                </button>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="inner-box featured">
                            <div class="tabs-section">
                                <ul class="nav nav-pills" id="profile-tabs">
                                    <li class="nav-item"><a href="#tab-overview" class="nav-link active border rounded border-success"> Overview</a></li>
                                    <li class="nav-item border rounded border-success"><a href="#tab-personal-info" class="nav-link">Personal Info</a></li>
                                    <li class="nav-item border rounded border-success"><a href="#tab-my-leaves" class="nav-link">My Leave History</a></li>
                                    <li class="nav-item border rounded border-success"><a href="#tab-my-gatepasses" class="nav-link">My Gatepasses</a></li>
                                    <li class="nav-item border rounded border-success"><a href="#tab-my-itinerary" class="nav-link">My Itinerary</a></li>
                                    <li class="nav-item border rounded border-success"><a href="#tab-my-exam-history" class="nav-link">Assessments</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane in active" id="tab-overview">
                                        <div class="row" id="overview-tab">
                                            @include('client.overview_tab')
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-personal-info">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="d-flex justify-content-end mb-2">
                                                    <a href="#" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#updateDetails"><b>
                                                        <i class="fas fa-user-edit"></i> Update Details</b></a>
                                                </div>
                                                <ul class="list-group list-group-unbordered mt-2 mb-3 responsive-font">
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
                                                            <div class="flex-grow-1 text-end"><a class="text-decoration-none" id="sidebarContactNo">{{ Auth::user()->contact_no }}</a></div>
                                                        </div>
                                                    </li>
                                                    @php
                                                        $personalInfoAddress = collect([
                                                            Auth::user()->address,
                                                            Auth::user()->barangay,
                                                            Auth::user()->city,
                                                        ])->filter(fn ($v) => filled($v))->implode(', ');
                                                    @endphp
                                                    <li class="list-group-item">
                                                        <div class="d-flex flex-row align-items-start">
                                                            <div class="fw-bold">Address</div>
                                                            <div class="flex-grow-1 text-end text-break ps-2">{{ $personalInfoAddress !== '' ? $personalInfoAddress : '—' }}</div>
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
                                                @include('client.modals.update_details')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-my-exam-history">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                @php
                                                    $pendingClientExams = $clientexams->filter(fn ($e) => $e->start_time == null);
                                                    $completedClientExams = $clientexams->filter(fn ($e) => $e->start_time != null);
                                                @endphp
                                                <div class="card mb-3">
                                                    <div class="card-body p-2">
                                                        <h3 class="widget-title mb-2" style="font-size: 12px !important;">
                                                            <div class="d-flex">
                                                                <span class="d-inline-block">Pending Exam Schedule</span>
                                                            </div>
                                                        </h3>
                                                        <div class="container-fluid p-0">
                                                            <table class="table table-bordered table-striped m-0" style="font-size: 13px;">
                                                                <col style="width: 50%;">
                                                                <col style="width: 25%;">
                                                                <col style="width: 25%;">
                                                                <thead>
                                                                    <th class="text-center text-uppercase p-1">Assessment</th>
                                                                    <th class="text-center text-uppercase p-1">Validity Date</th>
                                                                    <th class="text-center text-uppercase p-1">Action</th>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse($pendingClientExams as $exam)
                                                                    <tr>
                                                                        <td class="align-middle p-1">
                                                                            <span class="d-block fw-bold">{{ $exam->exam_title }}</span>
                                                                            <small class="d-block text-muted">{{ $exam->exam_group_description }}</small>
                                                                        </td>
                                                                        <td class="align-middle text-center p-1">
                                                                            <small>{{ \Carbon\Carbon::parse($exam->validity_date)->format('M. d, Y') }}</small>
                                                                        </td>
                                                                        <td class="align-middle text-center p-1">
                                                                            @if (date('m-d-Y') <= date('m-d-Y', strtotime($exam->validity_date)) && date('m-d-Y') >= date('m-d-Y', strtotime($exam->date_of_exam)))
                                                                            <a href="#" class="btn btn-xs px-3 py-2 btn-primary" data-bs-toggle="modal" data-bs-target="#take-exam-modal-{{ $exam->examinee_id }}">Take Exam</a>
                                                                            @else
                                                                            <span class="badge bg-secondary" style="font-size: 10px;">Validity Expired!</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @empty
                                                                    <tr>
                                                                        <td colspan="3" class="text-center text-muted text-uppercase">No Pending Examination</td>
                                                                    </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                @foreach($pendingClientExams as $exam)
                                                <div class="modal fade" id="take-exam-modal-{{ $exam->examinee_id }}" tabindex="-1"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Exam Confirmation</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <dl class="row m-0 p-0">
                                                                    <dt class="col-sm-3">Exam Title</dt>
                                                                    <dd class="col-sm-9">{{ $exam->exam_title }}</dd>
                                                                    <dt class="col-sm-3">Exam Date</dt>
                                                                    <dd class="col-sm-9">{{ \Carbon\Carbon::parse($exam->validity_date)->format('M. d, Y') }}</dd>
                                                                    <dt class="col-sm-3">Duration</dt>
                                                                    <dd class="col-sm-9">{{ $exam->duration }} minute(s)</dd>
                                                                </dl>
                                                                <div class="alert alert-info mb-0 mt-3" role="alert">
                                                                <p class="text-center">Please click <b><i>Take Exam</i></b> to start exam.</p>
                                                                </div>
                                                                <input type="hidden" name="employee_exam_code" id="employee_exam_code" class="employee_exam_code_class" value="{{ $exam->exam_code }}">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary" data-idcode="{{ $exam->exam_code }}" id="employee_submit"><i class="fa fa-check"></i> Take Exam</button>
                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach

                                                <h3 class="widget-title mb-2" style="font-size: 12px !important;">Completed assessments</h3>
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
                                                        @forelse($completedClientExams as $exam)
                                                            <tr>
                                                                <td class="text-center align-middle">{{ \Carbon\Carbon::parse($exam->date_of_exam)->format('M. d, Y') }}</td>
                                                                <td class="text-center align-middle">{{$exam->exam_title}}</td>
                                                                <td class="text-center align-middle">{{$exam->exam_group_description}}</td>
                                                                <td class="text-center align-middle">{{ \Carbon\Carbon::parse($exam->date_taken)->format('M. d, Y') }}</td>
                                                                <td class="text-center align-middle">{{ \Carbon\Carbon::parse($exam->validity_date)->format('M. d, Y') }}</td>
                                                                <td class="text-center align-middle">
                                                                    <span class="badge bg-success">Completed
                                                                    </span>
                                                                </td>
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
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-12 col-xl-5">
                        @if(in_array($designation, $admin_users))
                        <div class="inner-box featured d-none d-xl-block mb-3">
                            <div class="widget property-agent">
                                <h3 class="widget-title">
                                    <div class="d-flex">
                                        Settings
                                        <small class="flex-grow-1 text-muted text-end px-1">
                                            <a href="/client/analytics/attendance" class="text-decoration-none text-muted" style="cursor: pointer">
                                                <i class="fas fa-chart-bar"></i> Analytics
                                            </a>
                                        </small>
                                    </div>
                                </h3>
                                <style>
                                    
                                </style>

                                <div class="agent-info">
                                    <div class="settings-btn-group settings-btn-block w-100 text-center fw-bold" role="group">
                                        @foreach ($admin_settings as $settings)
                                        @php
                                            $settings_btn_status = '';
                                            if (!in_array($designation, $settings['allowed-users'])) {
                                                $settings_btn_status = 'settings-btn-opacity disabled';
                                            }

                                            if ($depart == 'head' && $settings['title'] == 'Evaluation') {
                                                $settings_btn_status = '';
                                            }
                                        @endphp
                                        <div class="w-100 p-1">
                                            <button class="btn settings-btn {{ $settings_btn_status }} {{ $settings['bg-color'] }} {{ $settings['title'] == 'Exam' ? 'text-dark' : null }} w-100 text-capitalize p-2" style="padding: 5px; border-radius: 0.7rem;" {{ $settings_btn_status }} data-href="{{ $settings['url'] }}">
                                                <i class="{{ $settings['icon'] }} d-block m-1"></i>
                                                <span class="d-block" style="font-size: 9pt;">{{ $settings['title'] }}</span>
                                            </button>
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
                        {{-- Clock In / Clock Out — disabled temporarily (re-enable with routes + HomeController)
                        @php
                            $clock_status = $clock_status ?? 'none';
                            $clocked_in_at = $clocked_in_at ?? null;
                        @endphp
                        <div class="mb-2">
                            <button type="button" id="clockBtn" class="btn w-100 p-2 fw-bold {{ $clock_status === 'clocked_out' ? 'btn-secondary clock-btn-completed' : 'btn-primary' }}" style="border-radius: 0.7rem; font-size: 11pt;"
                                data-status="{{ $clock_status }}"
                                data-time-in="{{ $clocked_in_at }}"
                                @if($clock_status === 'clocked_out') disabled @endif>
                                @if($clock_status === 'none')
                                    <i class="fas fa-clock me-1"></i> Clock In
                                @elseif($clock_status === 'clocked_in')
                                    <i class="fas fa-sign-out-alt me-1"></i> Clock Out
                                @else
                                    <i class="fas fa-check-circle me-1"></i> Completed
                                @endif
                            </button>
                            <div id="clocked-in-timer" class="text-center small text-muted mt-1" style="display: none;">
                                <span class="clocked-in-at">Clocked in at <strong class="time-in-display">--:--:--</strong></span>
                                <span class="elapsed-display ms-1">— Elapsed: <strong class="elapsed-time">00:00:00</strong></span>
                            </div>
                            <div id="resume-clock-wrap" class="mt-1" style="{{ $clock_status === 'clocked_out' ? '' : 'display: none;' }}">
                                <button type="button" id="resumeClockBtn" class="btn btn-outline-primary btn-sm w-100 clock-resume-btn" style="border-radius: 0.5rem; font-size: 10pt;">
                                    <i class="fas fa-play me-1"></i> Continue working (undo clock out)
                                </button>
                            </div>
                        </div>
                        --}}
                        <div class="inner-box featured p-2">
                            <div class="widget property-agent p-0">
                                <div class="d-flex w-100 p-0">
                                    <h3 class="widget-title mb-2 pb-2 w-100 my-attendance-widget-title">
                                        <div class="text-center">
                                            <span class="d-block my-attendance-heading">My Attendance</span>
                                            <small class="d-flex align-items-center justify-content-center gap-1 text-info mt-2 my-attendance-note flex-wrap">
                                                <i class="fas fa-info-circle flex-shrink-0" aria-hidden="true"></i>
                                                <span><strong>Note:</strong> Attendance data is synced every 15 minutes.</span>
                                            </small>
                                        </div>
                                    </h3>
                                </div>
                                <div class="agent-info">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="container-fluid p-0">
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
    .profile-photo-wrapper-home{
        position: relative;
        display: inline-block;
    }
    .profile-photo-overlay-home{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(0,0,0,0.35);
        opacity: 0;
        transition: opacity 0.15s ease-in-out;
        z-index: 1;
    }
    .profile-photo-wrapper-home:hover .profile-photo-overlay-home{
        opacity: 1;
    }
    .profile-photo-change-btn-home{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.15s ease-in-out;
        font-size: 10pt;
    }
    .profile-photo-wrapper-home:hover .profile-photo-change-btn-home{
        opacity: 1;
        pointer-events: auto;
    }
    .profile-photo-change-btn-home:disabled{
        opacity: 0.75;
        cursor: not-allowed;
    }
    #home-profile-photo-message{
        text-align: center;
    }
    /* Clock button styles — disabled with portal clock feature (single block; no nested comments)
    Clock button "Completed" state: keep text and icon always visible (no hover-only)
    #clockBtn.btn-secondary,
    #clockBtn.btn-secondary:disabled,
    #clockBtn.clock-btn-completed {
        color: #fff !important;
        opacity: 1 !important;
        background-color: #6c757d !important;
        border-color: #6c757d !important;
    }
    #clockBtn.btn-secondary:hover,
    #clockBtn.btn-secondary:focus,
    #clockBtn.clock-btn-completed:hover,
    #clockBtn.clock-btn-completed:focus {
        color: #fff !important;
        background-color: #5a6268 !important;
        border-color: #545b62 !important;
    }
    #clockBtn.btn-secondary i,
    #clockBtn.clock-btn-completed i {
        color: inherit !important;
        opacity: 1 !important;
    }
    /* "Continue working" button: always show text and icon (no hover needed) */
    #resumeClockBtn.clock-resume-btn,
    #resumeClockBtn.clock-resume-btn:hover,
    #resumeClockBtn.clock-resume-btn:focus {
        color: #0d6efd !important;
        opacity: 1 !important;
        background-color: transparent !important;
    }
    #resumeClockBtn.clock-resume-btn i {
        color: inherit !important;
        opacity: 1 !important;
    }
    */
    .settings-btn-opacity {
        opacity: 30% !important;
    }
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

    /* Prevent equal-height columns from stretching the shorter white card (dead vertical space) */
    .right-panel > [class*="col-"] {
        align-self: flex-start;
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
    <script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.timepicker.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/jquery.timepicker.css') }}" />
    <script type="text/javascript" src="{{ asset('css/js/datepicker/datepair.js') }}"></script>
    <script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.datepair.js') }}"></script>
    <script type="text/javascript" src="{{ asset('css/js/datepicker/bootstrap-datepicker.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/bootstrap-datepicker.css') }}" />

    <script>
        $(document).ready(function() {
            $(document).on('click', '.settings-btn', function(e) {
                e.preventDefault();

                window.location.href = $(this).data('href');
            });

            $('.open-reminder').click();

            // Profile Photo Upload (AJAX, no page reload)
            const homeProfilePhotoImg = document.getElementById('homeProfilePhotoImg');
            const homeProfilePhotoInput = document.getElementById('homeProfilePhotoInput');
            const homeProfilePhotoChangeBtn = document.getElementById('homeProfilePhotoChangeBtn');
            const homeProfilePhotoMessage = document.getElementById('home-profile-photo-message');

            let currentHomeProfileSrc = homeProfilePhotoImg ? homeProfilePhotoImg.src : null;

            function setHomeProfilePhotoMessage(type, message) {
                if (!homeProfilePhotoMessage) return;
                homeProfilePhotoMessage.style.display = 'block';
                homeProfilePhotoMessage.className = 'alert alert-' + type;
                homeProfilePhotoMessage.textContent = message;
            }

            if (homeProfilePhotoImg && homeProfilePhotoInput && homeProfilePhotoChangeBtn) {
                const uploadUrl = "/client/employee/profile/" + (homeProfilePhotoImg.dataset.userId || "") + "/photo";

                homeProfilePhotoChangeBtn.addEventListener('click', function () {
                    homeProfilePhotoInput.click();
                });

                homeProfilePhotoInput.addEventListener('change', function () {
                    const file = this.files && this.files[0];
                    if (!file) return;

                    const allowedTypes = ['image/jpeg', 'image/png'];
                    const maxBytes = 5 * 1024 * 1024; // 5MB

                    if (!allowedTypes.includes(file.type)) {
                        setHomeProfilePhotoMessage('danger', 'Please upload a JPG or PNG image.');
                        this.value = '';
                        if (currentHomeProfileSrc) homeProfilePhotoImg.src = currentHomeProfileSrc;
                        return;
                    }

                    if (file.size > maxBytes) {
                        setHomeProfilePhotoMessage('danger', 'Image must be 5MB or less.');
                        this.value = '';
                        if (currentHomeProfileSrc) homeProfilePhotoImg.src = currentHomeProfileSrc;
                        return;
                    }

                    // Preview before upload
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        homeProfilePhotoImg.src = e.target.result;
                    };
                    reader.readAsDataURL(file);

                    // `portal.app` layout (used by /home) may not include csrf meta tag.
                    // Use Blade-generated token to avoid CSRF mismatch.
                    const csrf = '{{ csrf_token() }}' || ($('meta[name="csrf-token"]').attr('content') || '');
                    const formData = new FormData();
                    formData.append('empImage', file);

                    homeProfilePhotoChangeBtn.disabled = true;
                    homeProfilePhotoChangeBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Uploading...';

                    $.ajax({
                        url: uploadUrl,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': csrf
                        },
                        success: function (res) {
                            if (res && res.success && res.image_url) {
                                currentHomeProfileSrc = res.image_url;
                                homeProfilePhotoImg.src = res.image_url + '&t=' + Date.now();
                                setHomeProfilePhotoMessage('success', 'Photo updated successfully.');
                                homeProfilePhotoInput.value = '';
                            } else {
                                setHomeProfilePhotoMessage(
                                    'danger',
                                    (res && res.error_detail) ? res.error_detail : ((res && res.message) ? res.message : 'Upload failed.')
                                );
                                if (currentHomeProfileSrc) homeProfilePhotoImg.src = currentHomeProfileSrc;
                            }
                        },
                        error: function (xhr) {
                            let msg = 'Upload failed. Please try again.';
                            try {
                                const j = xhr.responseJSON;
                                if (j && j.error_detail) msg = j.error_detail;
                                else if (j && j.message) msg = j.message;
                                else if (j && j.errors && j.errors.empImage && j.errors.empImage[0]) msg = j.errors.empImage[0];
                            } catch (e) {}
                            setHomeProfilePhotoMessage('danger', msg);
                            if (currentHomeProfileSrc) homeProfilePhotoImg.src = currentHomeProfileSrc;
                        },
                        complete: function () {
                            homeProfilePhotoChangeBtn.disabled = false;
                            homeProfilePhotoChangeBtn.innerHTML = '<i class="fa fa-upload"></i> Change Photo';
                        }
                    });
                });
            }

            const showNotification = (icon, message, status, title = null) => {
                titleTxt = ''
                if(title){
                    titleTxt = '<span style="display:block; font-size: 16pt; font-weight: bold; padding-top: 5px;">' + title + '</span>'
                }
                $.bootstrapGrowl(
                    '<i class="' + icon + '" style="font-size: 60pt; float: left; padding-right: 10px;"></i>' + titleTxt + '<span style="font-size: 10pt;">' + message + '</span>', {
                    type: status,
                    align: 'center',
                    delay: 8000,
                    width: 450,
                    offset: {
                        from: 'top',
                        amount: 300
                    },
                    stackup_spacing: 20
                });
            }

            $(document).on('click', '.date-ctrl', function (e){
                e.preventDefault();
                $('#my-attendance').html('<div class="container-fluid d-flex justify-content-center align-items-center p-2">' +
                    '<div class="spinner-border" role="status">' +
                        '<span class="visually-hidden">Loading...</span>' +
                    '</div>' +
                '</div>');
                get_cutoff_date($(this).data('action'));
            });

            // Update Details (AJAX)
            const updateDetailsModalEl = document.getElementById('updateDetails');
            const updateDetailsForm = document.getElementById('updateDetailsForm');
            const saveDetailsBtn = document.getElementById('saveDetails');
            const updateDetailsAlert = document.getElementById('updateDetailsAlert');

            if (updateDetailsModalEl && updateDetailsForm && saveDetailsBtn) {
                const setAlert = (type, message) => {
                    updateDetailsAlert.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');
                    updateDetailsAlert.classList.add('alert-' + type);
                    updateDetailsAlert.textContent = message;
                };

                const clearAlert = () => {
                    updateDetailsAlert.classList.add('d-none');
                    updateDetailsAlert.textContent = '';
                };

                const inputs = Array.from(updateDetailsForm.querySelectorAll('input, textarea, select'));
                const captureOriginal = () => {
                    inputs.forEach((el) => {
                        el.dataset.original = (el.value ?? '').toString();
                        el.classList.remove('border', 'border-warning');
                    });
                    saveDetailsBtn.disabled = true;
                };

                const computeDirty = () => {
                    let dirty = false;
                    inputs.forEach((el) => {
                        const original = (el.dataset.original ?? '').toString();
                        const current = (el.value ?? '').toString();
                        const changed = original !== current;
                        if (changed) dirty = true;
                        el.classList.toggle('border', changed);
                        el.classList.toggle('border-warning', changed);
                    });
                    saveDetailsBtn.disabled = !dirty;
                };

                updateDetailsModalEl.addEventListener('shown.bs.modal', () => {
                    clearAlert();
                    captureOriginal();
                });

                inputs.forEach((el) => el.addEventListener('input', computeDirty));

                saveDetailsBtn.addEventListener('click', async () => {
                    clearAlert();
                    saveDetailsBtn.disabled = true;

                    try {
                        const csrf = $('meta[name="csrf-token"]').attr('content');
                        const formData = new FormData(updateDetailsForm);

                        const res = await fetch("{{ route('client.profile.update_personal_details') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        if (!res.ok) {
                            if (res.status === 422) {
                                const data = await res.json();
                                const msg = Object.values(data.errors || {}).flat().join(' ');
                                setAlert('danger', msg || 'Please check your inputs.');
                            } else {
                                setAlert('danger', 'An error occurred. Please try again.');
                            }
                            computeDirty();
                            return;
                        }

                        const data = await res.json();
                        if (!data.success) {
                            setAlert('danger', data.message || 'An error occurred. Please try again.');
                            computeDirty();
                            return;
                        }

                        if (!data.updated) {
                            setAlert('warning', data.message || 'No changes were made.');
                            captureOriginal();
                            return;
                        }

                        showNotification('fa fa-check-circle', 'Profile updated successfully.', 'success');

                        // Update sidebar display without page reload
                        const sidebarContactNo = document.getElementById('sidebarContactNo');
                        const newContactNo = updateDetailsForm.querySelector('input[name="contact_no"]')?.value;
                        if (sidebarContactNo && typeof newContactNo === 'string') {
                            sidebarContactNo.textContent = newContactNo;
                        }

                        const modal = bootstrap.Modal.getInstance(updateDetailsModalEl) || new bootstrap.Modal(updateDetailsModalEl);
                        modal.hide();
                    } catch (e) {
                        setAlert('danger', 'An error occurred. Please try again.');
                        computeDirty();
                    }
                });
            }

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

            // Auto-fetch attendance: run update then reload once on page load (same as Refresh button)
            (function autoRefreshAttendanceOnce() {
                var employee = '{{ Auth::user()->user_id }}';
                $.ajax({
                    type: 'POST',
                    url: '/attendance/update/' + employee,
                    data: { '_token': '{{ csrf_token() }}' },
                    success: function() { loadAttendance(); }
                });
            })();
            // Auto-refresh attendance every 5 minutes while page is open
            setInterval(function() {
                var employee = '{{ Auth::user()->user_id }}';
                $.ajax({
                    type: 'POST',
                    url: '/attendance/update/' + employee,
                    data: { '_token': '{{ csrf_token() }}' },
                    success: function() { loadAttendance(); }
                });
            }, 5 * 60 * 1000);

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

            {{-- Portal clock in/out JS — disabled temporarily (re-enable with HTML block above + routes)
            $(document).on('click', '#clockBtn', function(e) {
                e.preventDefault();
                var btn = $(this);
                if (btn.prop('disabled')) return;
                var status = btn.data('status');
                var isClockIn = (status === 'none');
                var url = isClockIn ? '/attendance/clock-in' : '/attendance/clock-out';
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status"></span> Processing...');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: { '_token': '{{ csrf_token() }}' },
                    dataType: 'json',
                    success: function(res) {
                        var newStatus = (res && res.status) ? res.status : (isClockIn ? 'clocked_in' : 'clocked_out');
                        btn.data('status', newStatus);
                        if (res && res.time_in) { btn.data('time-in', res.time_in); }
                        if (newStatus === 'clocked_in') {
                            btn.prop('disabled', false).html('<i class="fas fa-sign-out-alt me-1"></i> Clock Out');
                            startClockedInTimer(res && res.time_in ? res.time_in : btn.data('time-in'));
                        } else if (newStatus === 'clocked_out') {
                            btn.prop('disabled', true).html('<i class="fas fa-check-circle me-1"></i> Completed').removeClass('btn-primary').addClass('btn-secondary clock-btn-completed');
                            stopClockedInTimer();
                            $('#resume-clock-wrap').show();
                        }
                        if (typeof $.bootstrapGrowl === 'function') {
                            $.bootstrapGrowl((res && res.message) || (newStatus === 'clocked_in' ? 'Clocked in.' : 'Clocked out.'), { type: 'success', align: 'center', delay: 3000 });
                        }
                        loadAttendance();
                    },
                    error: function(xhr) {
                        var res = xhr.responseJSON;
                        var errStatus = (res && res.status) ? res.status : 'none';
                        btn.data('status', errStatus);
                        btn.prop('disabled', false);
                        if (errStatus === 'none') {
                            btn.html('<i class="fas fa-clock me-1"></i> Clock In');
                            stopClockedInTimer();
                        } else if (errStatus === 'clocked_in') {
                            btn.html('<i class="fas fa-sign-out-alt me-1"></i> Clock Out');
                            startClockedInTimer(btn.data('time-in'));
                        }
                        var msg = (res && res.message) ? res.message : 'Request failed.';
                        if (typeof $.bootstrapGrowl === 'function') {
                            $.bootstrapGrowl(msg, { type: 'danger', align: 'center', delay: 4000 });
                        } else {
                            alert(msg);
                        }
                    },
                    complete: function() {
                        if (btn.html().indexOf('Processing') !== -1) {
                            var s = btn.data('status');
                            if (s === 'clocked_in') {
                                btn.prop('disabled', false).html('<i class="fas fa-sign-out-alt me-1"></i> Clock Out').removeClass('clock-btn-completed');
                                $('#resume-clock-wrap').hide();
                            } else if (s === 'clocked_out') {
                                btn.prop('disabled', true).html('<i class="fas fa-check-circle me-1"></i> Completed').removeClass('btn-primary').addClass('btn-secondary clock-btn-completed');
                                $('#resume-clock-wrap').show();
                            } else {
                                btn.prop('disabled', false).html('<i class="fas fa-clock me-1"></i> Clock In').removeClass('clock-btn-completed');
                                $('#resume-clock-wrap').hide();
                            }
                        }
                    }
                });
            });

            $(document).on('click', '#resumeClockBtn', function(e) {
                e.preventDefault();
                var $resumeBtn = $(this);
                if ($resumeBtn.prop('disabled')) return;
                $resumeBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status"></span> Resuming...');
                $.ajax({
                    type: 'POST',
                    url: '/attendance/resume',
                    data: { '_token': '{{ csrf_token() }}' },
                    dataType: 'json',
                    success: function(res) {
                        var btn = $('#clockBtn');
                        if (res && res.status === 'clocked_in') {
                            btn.data('status', 'clocked_in').data('time-in', res.time_in || btn.data('time-in'));
                            btn.prop('disabled', false).html('<i class="fas fa-sign-out-alt me-1"></i> Clock Out').removeClass('btn-secondary clock-btn-completed').addClass('btn-primary');
                            if (res.time_in) startClockedInTimer(res.time_in);
                            $('#resume-clock-wrap').hide();
                        }
                        if (typeof $.bootstrapGrowl === 'function') {
                            $.bootstrapGrowl((res && res.message) || 'Resumed. Clock out when you are done.', { type: 'success', align: 'center', delay: 3000 });
                        }
                        loadAttendance();
                    },
                    error: function(xhr) {
                        var res = xhr.responseJSON;
                        var msg = (res && res.message) ? res.message : 'Could not resume.';
                        if (typeof $.bootstrapGrowl === 'function') {
                            $.bootstrapGrowl(msg, { type: 'danger', align: 'center', delay: 4000 });
                        } else {
                            alert(msg);
                        }
                    },
                    complete: function() {
                        $resumeBtn.prop('disabled', false).html('<i class="fas fa-play me-1"></i> Continue working (undo clock out)');
                    }
                });
            });
            @if($clock_status === 'clocked_out')
            $(function(){ $('#resume-clock-wrap').show(); });
            @endif

            var clockedInTimerInterval = null;
            function startClockedInTimer(timeInStr) {
                if (!timeInStr) return;
                var $timer = $('#clocked-in-timer');
                $timer.find('.time-in-display').text(timeInStr);
                $timer.show();
                function tick() {
                    var parts = timeInStr.split(':');
                    var start = new Date();
                    start.setHours(parseInt(parts[0],10), parseInt(parts[1],10), parseInt(parts[2],10) || 0, 0);
                    var now = new Date();
                    if (start > now) start.setDate(start.getDate() - 1);
                    var sec = Math.floor((now - start) / 1000);
                    var h = Math.floor(sec / 3600), m = Math.floor((sec % 3600) / 60), s = sec % 60;
                    var fmt = function(n){ return (n < 10 ? '0' : '') + n; };
                    $timer.find('.elapsed-time').text(fmt(h) + ':' + fmt(m) + ':' + fmt(s));
                }
                tick();
                if (clockedInTimerInterval) clearInterval(clockedInTimerInterval);
                clockedInTimerInterval = setInterval(tick, 1000);
            }
            function stopClockedInTimer() {
                if (clockedInTimerInterval) { clearInterval(clockedInTimerInterval); clockedInTimerInterval = null; }
                $('#clocked-in-timer').hide();
            }
            @if($clock_status === 'clocked_in' && $clocked_in_at)
            $(function(){ startClockedInTimer('{{ $clocked_in_at }}'); });
            @endif
            --}}

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

            function resendManagerNotice(btn, noticeId) {
                if (btn.prop('disabled')) {
                    return;
                }

                var icon = btn.find('i');
                var originalIconClass = icon.attr('class');
                var originalTitle = btn.attr('title');
                var originalBtnText = btn.text();
                var isModalButton = btn.attr('id') === 'notify-manager-modal-btn';

                btn.prop('disabled', true).attr('title', 'Sending...');
                icon.removeClass().addClass('fa fa-spinner fa-spin');
                if (isModalButton) {
                    btn.contents().filter(function() {
                        return this.nodeType === 3;
                    }).remove();
                    btn.append(' Sending...');
                }

                $.ajax({
                    url: "/notice_slip/resend-manager-notification",
                    type: "POST",
                    data: {
                        notice_id: noticeId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.success) {
                            if (typeof showNotification === 'function') {
                                showNotification("fa fa-check-circle-o", data.message, 'success', 'Manager notified');
                            } else {
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
                            }
                        } else {
                            if (typeof showNotification === 'function') {
                                showNotification("fa fa-exclamation-triangle", data.message || 'Unable to notify manager.', 'danger');
                            } else {
                                $.bootstrapGrowl(data.message || 'Unable to notify manager.', {
                                    type: 'danger',
                                    align: 'center',
                                    delay: 3500
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        var message = 'Unable to notify manager. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        if (typeof showNotification === 'function') {
                            showNotification("fa fa-exclamation-triangle", message, 'danger');
                        } else {
                            $.bootstrapGrowl(message, {
                                type: 'danger',
                                align: 'center',
                                delay: 3500
                            });
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).attr('title', originalTitle || 'Notify Manager');
                        icon.removeClass().addClass(originalIconClass || 'fa fa-bell');
                        if (isModalButton) {
                            btn.contents().filter(function() {
                                return this.nodeType === 3;
                            }).remove();
                            btn.append(originalBtnText);
                        }
                    }
                });
            }

            $(document).on('click', '.notify-manager-btn', function(event) {
                event.preventDefault();
                resendManagerNotice($(this), $(this).data('id'));
            });

            $(document).on('click', '#notify-manager-modal-btn', function(event) {
                event.preventDefault();
                resendManagerNotice($(this), $('#edit-notice-form .notice_id').val());
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
                        $('#notify-manager-modal-btn').hide().prop('disabled', false).removeData('id');
                        if (status.toLowerCase() != 'for approval') {
                            $("#cancel-notice").hide();
                            $("#update-notice").hide();
                        } else {
                            $("#cancel-notice").show();
                            $("#update-notice").show();
                            $('#notify-manager-modal-btn').show().data('id', data.notice_id);
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
                    success: (data) => {
                        $('#notice-slip-submit-btn').attr('disabled', false);
                        if (data.success) {
                            loadAbsentNotices();
                            if(!data.email_sent){
                                showNotification("fa fa-check-circle-o", 'Email not sent!', 'warning', 'Notice')
                            }
                            showNotification("fa fa-check-circle-o", data.message, 'success', 'Request sent to Managers.')
                            $('#absentNoticeModal').modal('hide')
                        } else {
                            showNotification("fa fa-check-circle-o",  data.message, 'danger')
                        }
                    },
                    error: (response) => {
                        $('#notice-slip-submit-btn').attr('disabled', false)
                        showNotification("fa fa-check-circle-o",  'An error occured. Please try again.', 'danger')
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

            $('#editNoticeModal').on('hidden.bs.modal', function() {
                $('#notify-manager-modal-btn').hide().prop('disabled', false).removeData('id');
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
