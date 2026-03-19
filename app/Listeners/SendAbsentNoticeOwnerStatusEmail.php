<?php

namespace App\Listeners;

use App\Events\AbsentNoticeStatusChanged;
use App\Mail\AbsentNoticeOwnerStatusMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

final class SendAbsentNoticeOwnerStatusEmail
{
    public function handle(AbsentNoticeStatusChanged $event): void
    {
        // Currently only send for APPROVED transitions. Other statuses can be added later.
        if (strtoupper($event->toStatus) !== 'APPROVED') {
            return;
        }

        $notice = DB::table('notice_slip as ns')
            ->join('users as u', 'u.user_id', '=', 'ns.user_id')
            ->join('leave_types as lt', 'lt.leave_type_id', '=', 'ns.leave_type_id')
            ->where('ns.notice_id', $event->noticeId)
            ->select(
                'ns.notice_id',
                'ns.status',
                'ns.date_from',
                'ns.date_to',
                'ns.time_from',
                'ns.time_to',
                'ns.reason',
                'u.employee_name',
                'u.email',
                'lt.leave_type',
            )
            ->first();

        if (! $notice) {
            Log::warning('Absent notice not found for owner email.', ['notice_id' => $event->noticeId]);
            return;
        }

        $recipient = (string) ($notice->email ?? '');
        if ($recipient === '') {
            Log::warning('Absent notice owner has no email.', ['notice_id' => $event->noticeId]);
            return;
        }

        $from = Carbon::parse($notice->date_from.' '.$notice->time_from)->format('M. d, Y h:i A');
        $to = Carbon::parse($notice->date_to.' '.$notice->time_to)->format('M. d, Y h:i A');

        $data = [
            'subject' => 'Absent Notice Approved',
            'employee_name' => $notice->employee_name,
            'type_of_absence' => $notice->leave_type,
            'date_range' => $from.' – '.$to,
            'reason' => $notice->reason,
            'approval_status' => $notice->status,
            'notice_id' => $notice->notice_id,
        ];

        $emailSent = 0;
        try {
            Mail::to($recipient)->queue(new AbsentNoticeOwnerStatusMail($data));
            $emailSent = 1;
        } catch (\Throwable $e) {
            Log::error('Failed sending absent notice owner email.', [
                'notice_id' => $event->noticeId,
                'recipient' => $recipient,
                'error' => $e->getMessage(),
            ]);
        }

        // Optional audit trail (does not affect approval flow).
        try {
            DB::table('email_notifications')->insert([
                'type' => 'Absent Notice Slip',
                'recipient' => $recipient,
                'subject' => $data['subject'],
                'template' => 'kiosk.Mail.template.notice_owner_status',
                'template_data' => json_encode($data),
                'user_id' => null,
                'email_sent' => $emailSent,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed inserting email_notifications record for owner email.', [
                'notice_id' => $event->noticeId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

