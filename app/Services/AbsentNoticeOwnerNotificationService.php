<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\AbsentNoticeOwnerStatusMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

final class AbsentNoticeOwnerNotificationService
{
    public function sendApprovedNotice(int $noticeId): bool
    {
        $notice = DB::table('notice_slip as ns')
            ->join('users as u', 'u.user_id', '=', 'ns.user_id')
            ->join('leave_types as lt', 'lt.leave_type_id', '=', 'ns.leave_type_id')
            ->where('ns.notice_id', $noticeId)
            ->select(
                'ns.notice_id',
                'ns.status',
                'ns.date_from',
                'ns.date_to',
                'ns.time_from',
                'ns.time_to',
                'ns.reason',
                'u.user_id',
                'u.employee_name',
                'u.email',
                'lt.leave_type',
            )
            ->first();

        if (! $notice) {
            Log::warning('Absent notice not found for owner email.', ['notice_id' => $noticeId]);

            return false;
        }

        if (strtoupper((string) $notice->status) !== 'APPROVED') {
            return false;
        }

        $recipient = trim((string) ($notice->email ?? ''));
        if ($recipient === '') {
            Log::warning('Absent notice owner has no email.', ['notice_id' => $noticeId]);

            return false;
        }

        $data = [
            'subject' => 'Absent Notice Approved',
            'employee_name' => $notice->employee_name,
            'type_of_absence' => $notice->leave_type,
            'date_range' => $this->formatNoticeDateRange($notice),
            'reason' => $notice->reason,
            'approval_status' => $notice->status,
            'notice_id' => $notice->notice_id,
        ];

        $emailSent = false;
        try {
            Mail::to($recipient)->send(new AbsentNoticeOwnerStatusMail($data));
            $emailSent = true;
        } catch (\Throwable $e) {
            Log::error('Failed sending absent notice owner email.', [
                'notice_id' => $noticeId,
                'recipient' => $recipient,
                'error' => $e->getMessage(),
            ]);
        }

        $this->recordEmailNotification($notice, $recipient, $data, $emailSent);

        return $emailSent;
    }

    private function formatNoticeDateRange(object $notice): string
    {
        try {
            $from = $this->formatNoticeDateTime($notice->date_from, $notice->time_from);
            $to = $this->formatNoticeDateTime($notice->date_to, $notice->time_to);

            return $from.' – '.$to;
        } catch (\Throwable $e) {
            Log::warning('Failed formatting absent notice date range for owner email.', [
                'notice_id' => $notice->notice_id ?? null,
                'error' => $e->getMessage(),
            ]);

            return trim((string) ($notice->date_from ?? '')).' – '.trim((string) ($notice->date_to ?? ''));
        }
    }

    private function formatNoticeDateTime(?string $date, ?string $time): string
    {
        $date = trim((string) ($date ?? ''));
        if ($date === '') {
            return '';
        }

        $time = trim((string) ($time ?? ''));
        $value = $time !== '' ? $date.' '.$time : $date;

        return Carbon::parse($value)->format('M. d, Y h:i A');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function recordEmailNotification(object $notice, string $recipient, array $data, bool $emailSent): void
    {
        try {
            DB::table('email_notifications')->insert([
                'type' => 'Absent Notice Slip',
                'recipient' => $recipient,
                'subject' => $data['subject'],
                'template' => 'kiosk.Mail.template.notice_owner_status',
                'template_data' => json_encode($data),
                'user_id' => (int) $notice->user_id,
                'email_sent' => $emailSent ? 1 : 0,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed inserting email_notifications record for owner email.', [
                'notice_id' => $notice->notice_id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
