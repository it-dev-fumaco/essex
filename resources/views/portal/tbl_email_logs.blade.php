<table class="table table-bordered">
    <tr>
        <th class="text-center">Date</th>
        <th class="text-center">Type</th>
        <th class="text-center">Subject</th>
        <th class="text-center">Recipient</th>
        <th class="text-center">Status</th>
    </tr>
    @forelse ($logs as $log)
        <tr>
            <td class="text-center">{{ Carbon\Carbon::parse($log->created_at)->format('M d, Y - h:i A') }}</td>
            <td class="text-center">{{ $log->type }}</td>
            <td class="text-center">{{ $log->subject }}</td>
            <td class="text-center">{{ $log->recipient }}</td>
            <td class="text-center">
                <span class="badge bg-{{ $log->email_sent ? 'success' : 'secondary' }}">
                    {{ $log->email_sent ? 'Email Sent!' : 'Failed to send' }}
                </span>
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