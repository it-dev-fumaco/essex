<div class="col-7">
    <div class="d-nonse d-xl-block">
        <div class="d-flex flex-row pt-3">
            <div class="p-1 p-xl-2 text-center col-4 d-grid gap-2">
                {{-- <button type="button" class="btn btn-secondary px-xl-3 py-xl-2" data-bs-toggle="modal" data-bs-target="#absentNoticeModal" id="notice-modal">
                    <i class="fas fa-calendar-check fs-3 d-block m-2"></i>
                    Absent Notice
                </button> --}}

                <button type="button" class="data-entry-btn btn btn-secondary btn-sm px-xl-3 py-xl-2 w-100 h-100" data-bs-toggle="modal" data-bs-target="#absentNoticeModal" id="notice-modal">
                    <i class="data-entry-icon fas fa-calendar-check d-block m-xl-2"></i>
                    Absent Notice
                </button>
            </div>
            <div class="p-1 p-xl-2 text-center col-4 d-grid gap-2">
                {{-- <button type="button" class="data-entry-btn btn btn-success px-3 py-2" data-bs-toggle="modal" data-bs-target="#gatepassModal" id="gatepass-modal">
                    <i class="data-entry-icon fas fa-clipboard-list fs-3 d-block m-2"></i>
                    Gatepass
                </button> --}}
                <button class="data-entry-btn btn btn-success btn-sm w-100 h-100" data-bs-toggle="modal" data-bs-target="#gatepassModal">
                    <i class="data-entry-icon fas fa-clipboard-list d-block m-xl-2"></i>&nbsp;Gatepass
                </button>
            </div>
            <div class="p-1 p-xl-2 text-center col-4 d-grid gap-2" data-bs-toggle="modal" data-bs-target="#evaluationModal" id="evaluation-modal">
                <button type="button" class="data-entry-btn btn btn-info btn-sm px-3 py-2 w-100 h-100">
                    <i class="data-entry-icon fas fa-chart-bar d-block m-xl-2"></i>
                    KPI Data Entry
                </button>
            </div>       
        </div>
    </div>

    {{-- <div class="d-block d-xl-none">
        <div class="row">
            <div class="col-4 p-1 p-xl-2 border border-danger">
                <button class="btn btn-secondary btn-sm w-100 h-100" data-bs-toggle="modal" data-bs-target="#absentNoticeModal" style="font-size: 8pt !important;">
                    <i class="fas fa-calendar-check d-block"></i>&nbsp;Absent Notice
                </button>
            </div>

            <div class="col-4 p-1 border border-danger">
                <button class="btn btn-success btn-sm w-100 h-100" data-bs-toggle="modal" data-bs-target="#gatepassModal" style="font-size: 8pt !important;">
                    <i class="fas fa-clipboard-list d-block"></i>&nbsp;Gatepass
                </button>
            </div>

            <div class="col-4 p-1 border border-danger">
                <button class="btn btn-info btn-sm w-100 h-100" data-bs-toggle="modal" data-bs-target="#evaluationModal" style="font-size: 8pt !important;">
                    <i class="fas fa-chart-bar d-block"></i>&nbsp;KPI Data Entry
                </button>
            </div>
        </div>
    </div> --}}

    <div class="card mb-3">
        <div class="card-body p-2">
            <h3 class="widget-title mb-2" style="font-size: 12px !important;">
                <div class="d-flex">
                    <span class="d-inline-block">Pending Exam Schedule</span>
                    <small class="flex-grow-1 text-muted text-end px-1">
                        <a href="#tab-my-exam-history" class="text-decoration-none text-muted text-capitalize" id="view-exam-history-tab" style="letter-spacing: 0.3px;">View All</a>
                    </small>
                </div>
            </h3>
            <div class="container-fluid p-0" style="max-height: 300px; overflow-y: auto">
                <table class="table table-bordered table-striped m-0" style="font-size: 13px;">
                    <col style="width: 50%;">
                    <col style="width: 25%;">
                    <col style="width: 25%;">
                     <thead>
                         <th class="text-center text-uppercase p-1">Exam Title</th>
                         <th class="text-center text-uppercase p-1">Validity Date</th>
                         <th class="text-center text-uppercase p-1">Action</th>
                     </thead>
                     <tbody>
                         @forelse($clientexams as $exam)
                         <tr>
                             @if ($exam->start_time == null)
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
                             @endif
                             <!-- Modal -->
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
                         </tr>
                         @empty
                         <tr>
                             <td colspan="6" class="text-center text-muted text-uppercase">No Pending Examination</td>
                         </tr>
                         @endforelse
                     </tbody>
                 </table>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body p-2">
            <h3 class="widget-title mb-2" style="font-size: 12px !important;">
                <div class="d-flex">
                    <span class="d-inline-block">My Pending Absent Notice Slip(s)</span>
                    <small class="flex-grow-1 text-muted text-end px-1">
                        <a href="#" class="text-decoration-none text-muted text-capitalize" id="view-my-leaves-tab" style="letter-spacing: 0.3px;">View All</a>
                    </small>
                </div>
            </h3>
            <ul class="list-group list-group-unbordered" style="font-size: 12px !important; max-height: 200px; overflow-y: auto">
                @forelse($pending_notices as $notice)
                <li class="list-group-item p-0">
                    <div class="d-flex flex-row bd-highlight align-items-center">
                        <div class="col-1 p-1 text-center bd-highlight"><i class="far fa-calendar fs-4"></i></div>
                        <div class="col-5 p-1 bd-highlight">
                            <a href="#" data-id="{{ $notice->notice_id }}" id="editAbsent" class="hover-icon text-decoration-none">
                                @if ($notice->date_from == $notice->date_to)
                                <span class="d-block fw-bold">{{ \Carbon\Carbon::parse($notice->date_from)->format('M. d, Y') }}</span>
                                @else
                                <span class="d-block fw-bold">{{ \Carbon\Carbon::parse($notice->date_from)->format('M. d, Y') . ' - ' . \Carbon\Carbon::parse($notice->date_to)->format('M. d, Y') }}</span>
                                @endif
                                <small class="d-block text-muted">{{ $notice->leave_type }}</small>
                            </a>
                        </div>
                        <div class="col-6 p-1 bd-highlight">{{ $notice->reason }}</div>
                    </div>
                </li>
                @empty
                <li class="list-group-item text-center text-muted">No Pending Absent Notice Slip(s)</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
<div class="col-5">
    <div class="card mb-3">
        <div class="card-body p-2">
            <h3 class="widget-title" style="font-size: 12px !important;">My Leave Balances</h3>
            <div class="row text-center">
                @forelse($leave_types as $leave_type)
                <div class="col-md-4">
                    <span style="font-size: 18pt;">{{ $leave_type->remaining }}</span>
                    <span class="d-block text-muted" style="font-size: 10px;">remaining</span>
                    <span style="font-size: 11px;"><b>{{ $leave_type->leave_type }}</b></span>
                </div>
                @empty
                <div class="col-md-4 text-center text-uppercase text-muted">
                    Employee Leave Allowance not set
                </div>
                @endforelse
            </div>
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
                @endif
            </div>
        </div>
    </div>
    <div class="card mb-3">
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
                        <td style="width: 40%;">
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
{{-- <div class="col-7 order-1 order-xl-2">
    <div class="row d-block d-xl-none">
        <div class="col-4">
            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#absentNoticeModal">
                <i class="fas fa-calendar-check"></i>&nbsp;Absent Notice
            </button>
        </div>

        <div class="col-4">
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#gatepassModal">
                <i class="fas fa-clipboard-list"></i>&nbsp;Gatepass
            </button>
        </div>

        <div class="col-4">
            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#evaluationModal">
                <i class="fas fa-chart-bar"></i>&nbsp;KPI Data Entry
            </button>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body p-2">
            <h3 class="widget-title mb-2" style="font-size: 12px !important;">
                <div class="d-flex">
                    <span class="d-inline-block">Pending Exam Schedule</span>
                    <small class="flex-grow-1 text-muted text-end px-1">
                        <a href="#tab-my-exam-history" class="text-decoration-none text-muted text-capitalize" id="view-exam-history-tab" style="letter-spacing: 0.3px;">View All</a>
                    </small>
                </div>
            </h3>
            <div class="container-fluid p-0" style="max-height: 300px; overflow-y: auto">
                <table class="table table-bordered table-striped m-0" style="font-size: 13px;">
                    <col style="width: 50%;">
                    <col style="width: 25%;">
                    <col style="width: 25%;">
                     <thead>
                         <th class="text-center text-uppercase p-1">Exam Title</th>
                         <th class="text-center text-uppercase p-1">Validity Date</th>
                         <th class="text-center text-uppercase p-1">Action</th>
                     </thead>
                     <tbody>
                         @forelse($clientexams as $exam)
                         <tr>
                             @if ($exam->start_time == null)
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
                             @endif
                             <!-- Modal -->
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
                         </tr>
                         @empty
                         <tr>
                             <td colspan="6" class="text-center text-muted text-uppercase">No Pending Examination</td>
                         </tr>
                         @endforelse
                     </tbody>
                 </table>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body p-2">
            <h3 class="widget-title mb-2" style="font-size: 12px !important;">
                <div class="d-flex">
                    <span class="d-inline-block">My Pending Absent Notice Slip(s)</span>
                    <small class="flex-grow-1 text-muted text-end px-1">
                        <a href="#" class="text-decoration-none text-muted text-capitalize" id="view-my-leaves-tab" style="letter-spacing: 0.3px;">View All</a>
                    </small>
                </div>
            </h3>
            <ul class="list-group list-group-unbordered" style="font-size: 12px !important; max-height: 200px; overflow-y: auto">
                @forelse($pending_notices as $notice)
                <li class="list-group-item p-0">
                    <div class="d-flex flex-row bd-highlight align-items-center">
                        <div class="col-1 p-1 text-center bd-highlight"><i class="far fa-calendar fs-4"></i></div>
                        <div class="col-5 p-1 bd-highlight">
                            <a href="#" data-id="{{ $notice->notice_id }}" id="editAbsent" class="hover-icon text-decoration-none">
                                @if ($notice->date_from == $notice->date_to)
                                <span class="d-block fw-bold">{{ \Carbon\Carbon::parse($notice->date_from)->format('M. d, Y') }}</span>
                                @else
                                <span class="d-block fw-bold">{{ \Carbon\Carbon::parse($notice->date_from)->format('M. d, Y') . ' - ' . \Carbon\Carbon::parse($notice->date_to)->format('M. d, Y') }}</span>
                                @endif
                                <small class="d-block text-muted">{{ $notice->leave_type }}</small>
                            </a>
                        </div>
                        <div class="col-6 p-1 bd-highlight">{{ $notice->reason }}</div>
                    </div>
                </li>
                @empty
                <li class="list-group-item text-center text-muted">No Pending Absent Notice Slip(s)</li>
                @endforelse
            </ul>
        </div>
    </div>
    
</div> --}}