<table class="table table-bordered">
    <tr>
        <th class="text-center">Date</th>
        <th class="text-center">Type</th>
        <th class="text-center">Subject</th>
        <th class="text-center">Recipient</th>
        <th class="text-center">Status</th>
        <th class="text-center">Action</th>
    </tr>
    @forelse ($logs as $log)
        <tr>
            <td class="text-center">{{ Carbon\Carbon::parse($log->created_at)->format('M d, Y - h:i A') }}</td>
            <td class="text-center">{{ $log->type }}</td>
            <td class="text-center">{{ $log->subject }}</td>
            <td class="text-center">
                @if (!in_array($log->recipient, $erp_email))
                    <span class="badge bg-warning text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="This email does not exist in ERP" style="font-size: 10pt">!</span> &nbsp;
                @endif
                {{ $log->recipient }}</td>
            <td class="text-center">
                <span class="badge bg-{{ $log->email_sent ? 'success' : 'secondary' }}">
                    {{ $log->email_sent ? 'Email Sent!' : 'Failed to send' }}
                </span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#resend-modal-{{ $log->id }}">
                    Resend <i class="fa fa-undo"></i>
                </button>
                  
                <div class="modal fade" id="resend-modal-{{ $log->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Resend Email</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Resend email to {{ $log->recipient }}?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-sm btn-primary resend-email" data-log-id='{{ $log->id }}'>
                                    Resend&nbsp;
                                    <div class="spinner-border spinner-border-sm d-none" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan=10 class="text-center">
                No result(s) found
            </td>
        </tr>
    @endforelse
</table>
<div id="logs-pagination" class="mt-3 ml-3 clearfix pagination d-block">
    {{ $logs->links() }}
</div>