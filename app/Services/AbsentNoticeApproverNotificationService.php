<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\SendMail_notice;
use App\Models\AbsentNotice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Emails department approvers and the employee's reporting manager (head)
 * when an absent notice needs approval.
 */
final class AbsentNoticeApproverNotificationService
{
    /**
     * @param  array<string, mixed>  $data
     * @return array{has_recipient: bool, email_sent: int}
     */
    public function notify(AbsentNotice $noticeSlip, array $data): array
    {
        $leaveApprovers = DB::table('department_approvers')
            ->join('users', 'users.user_id', '=', 'department_approvers.employee_id')
            ->where('department_approvers.department_id', $noticeSlip->dept_id)
            ->distinct()
            ->pluck('users.email', 'users.user_id');

        $owner = DB::table('users')
            ->select('user_id', 'reporting_to')
            ->where('user_id', $noticeSlip->user_id)
            ->first();

        $manager = null;
        if ($owner && $owner->reporting_to) {
            $manager = DB::table('users')
                ->select('user_id', 'email')
                ->where('user_id', $owner->reporting_to)
                ->first();
        }

        $recipients = [];
        foreach ($leaveApprovers as $userId => $email) {
            if ($email) {
                $recipients[(string) $userId] = $email;
            }
        }

        if ($manager && $manager->email) {
            $recipients[(string) $manager->user_id] = $manager->email;
        }

        $emailSent = 0;
        $hasRecipient = false;
        $actingUserId = Auth::user()?->user_id;

        foreach ($recipients as $userId => $email) {
            $hasRecipient = true;
            $data['approver'] = $userId;

            $sent = 0;
            try {
                // Send immediately so delivery works even when queue workers are unavailable.
                Mail::to($email)->send(new SendMail_notice($data));
                $sent = 1;
            } catch (\Throwable $th) {
                $sent = 0;
                Log::error('Failed sending absent notice approval email.', [
                    'notice_id' => $noticeSlip->notice_id ?? null,
                    'approver_user_id' => $userId,
                    'recipient' => $email,
                    'error' => $th->getMessage(),
                ]);
            }

            if ($sent) {
                $emailSent = 1;
            }

            DB::table('email_notifications')->insert([
                'type' => 'Absent Notice Slip',
                'recipient' => $email,
                'subject' => 'Absent Notice Slip - FOR YOUR APPROVAL',
                'template' => 'kiosk.Mail.template.notice_template',
                'template_data' => json_encode($data),
                'user_id' => $actingUserId,
                'email_sent' => $sent,
            ]);
        }

        return [
            'has_recipient' => $hasRecipient,
            'email_sent' => $emailSent,
        ];
    }
}
