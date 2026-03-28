<div class="col-12">
    <div class="d-flex flex-row pt-3">
        <div class="p-1 p-xl-2 text-center col-4 d-grid gap-2">
            <button type="button" class="data-entry-btn btn btn-secondary btn-sm px-xl-3 py-xl-2 w-100 h-100" data-bs-toggle="modal" data-bs-target="#absentNoticeModal" id="notice-modal">
                <i class="data-entry-icon fas fa-calendar-check d-block m-xl-2"></i>
                Absent Notice
            </button>
        </div>
        <div class="p-1 p-xl-2 text-center col-4 d-grid gap-2">
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

    <div class="card mb-3">
      <div class="card-body p-2">
         <h3 class="widget-title mb-2" style="font-size: 12px !important;">
               <div class="d-flex">
                  <span class="d-inline-block">Leaves</span>
               </div>
         </h3>
         <div class="container-fluid p-0">
            <div class="row text-center">
               <div class="col-4 p-3" id="onLeaveToday">
                  <span style="font-size: 23pt;">{{ $on_leave_today }}</span>
                  <span class="mt-2 d-block" style="font-size: 11px;"><b>On leave today</b></span>
              </div>
              <div class="col-4 p-3">
                  <a href="/forApproval" class="text-decoration-none text-transform-none">
                     <span style="font-size: 23pt;">{{ $awaiting_approval }}</span>
                     <span class="mt-2 d-block" style="font-size: 11px;"><b>Awaiting for Approval</b></span>
                  </a>
               </div>
               <div class="col-4 p-3" id="pendingRequests">
                  <span style="font-size: 23pt;">{{ $pending_requests }}</span>
                  <span class="mt-2 d-block" style="font-size: 11px;"><b>Pending Requests</b></span>
               </div>
            </div>
         </div>
      </div>
    </div>

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
                <div class="col-12 text-center text-uppercase text-muted">
                    Employee Leave Allowance not set
                </div>
                @endforelse
            </div>
        </div>
    </div>

    @if($pending_notices->isNotEmpty())
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
            <ul class="list-group list-group-unbordered" style="font-size: 12px !important;">
                @foreach($pending_notices as $notice)
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
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>