<?php

namespace App\Listeners;

use App\Events\EmployeeLifecycleActionTriggered;
use App\Mail\EmployeeLifecycleMail;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

final class SendEmployeeLifecycleNotification
{
    public function handle(EmployeeLifecycleActionTriggered $event): void
    {
        Log::info('Employee lifecycle event received.', [
            'employee_id' => $event->employeeId,
            'action' => $event->action,
        ]);

        $employee = User::find($event->employeeId);
        if (! $employee) {
            Log::warning('Employee lifecycle notification skipped: employee not found.', [
                'employee_id' => $event->employeeId,
                'action' => $event->action,
            ]);

            return;
        }

        $action = strtolower(trim($event->action));
        if (! in_array($action, ['welcome', 'onboarding', 'offboarding'], true)) {
            Log::warning('Employee lifecycle notification skipped: unknown action.', [
                'employee_id' => $event->employeeId,
                'action' => $event->action,
            ]);

            return;
        }

        $department = $employee->department_id ? Department::find($employee->department_id) : null;
        $designation = $employee->designation_id ? Designation::find($employee->designation_id) : null;
        $manager = $employee->reporting_to ? User::find($employee->reporting_to) : null;

        $joiningRaw = $employee->date_joined ?? $employee->joining_date ?? null;
        $joiningFormatted = null;
        try {
            $joiningFormatted = $joiningRaw ? Carbon::parse($joiningRaw)->format('F d, Y') : null;
        } catch (\Throwable $e) {
        }

        $common = [
            'name' => $employee->employee_name,
            'full_name' => $employee->employee_name,
            'role' => $designation?->designation ?? $employee->designation_name ?? null,
            'job_title' => $designation?->designation ?? $employee->designation_name ?? null,
            'department' => $department?->department ?? null,
            'joining_date' => $joiningFormatted,
            'start_date' => $joiningFormatted,
        ];

        $subject = '';
        $template = '';
        $type = '';
        $recipients = [];
        $markColumn = null;
        $mailData = $common;
        $sendAt = null;

        if ($action === 'welcome') {
            if (! empty($employee->welcome_email_sent_at) || empty($employee->email) || ! $joiningRaw) {
                Log::info('Welcome email skipped (guard).', [
                    'employee_id' => $employee->id,
                    'welcome_email_sent_at' => $employee->welcome_email_sent_at ?? null,
                    'has_email' => ! empty($employee->email),
                    'has_joining_raw' => (bool) $joiningRaw,
                ]);

                return;
            }

            try {
                Carbon::parse($joiningRaw)->startOfDay();
            } catch (\Throwable $e) {
                Log::warning('Welcome email skipped: invalid joining date.', [
                    'employee_id' => $employee->id,
                    'joining_raw' => $joiningRaw,
                ]);

                return;
            }

            // Send welcome in the same request as save (see Mail::send() below — no queue worker required).

            $subject = 'Welcome to FUMACO - '.$employee->employee_name;
            $template = 'emails.employee.welcome';
            $type = 'Welcome Email';
            $recipients = [$employee->email];
            $markColumn = 'welcome_email_sent_at';
        }

        if ($action === 'onboarding') {
            if (! empty($employee->onboarding_email_sent_at)) {
                Log::info('Onboarding email skipped (already sent).', [
                    'employee_id' => $employee->id,
                    'onboarding_email_sent_at' => $employee->onboarding_email_sent_at,
                ]);

                return;
            }

            $subject = 'Employee Onboarding - '.$employee->employee_name;
            $template = 'emails.employee.onboarding';
            $type = 'Onboarding Email';

            $primaryLocation = null;
            try {
                if (! empty($employee->branch)) {
                    $primaryLocation = DB::table('branch')
                        ->where('branch_id', $employee->branch)
                        ->pluck('branch_name')
                        ->first();
                }
            } catch (\Throwable $e) {
                // If we fail to resolve branch name, keep it blank.
            }

            $mailData = array_merge($common, [
                'manager_name' => $manager?->employee_name ?? null,
                'primary_location' => $primaryLocation,
                'erp_system_name' => 'ERP',
                'vpn_remote_access' => 'Yes',
                'extension' => 'N/A',
            ]);

            $recipients = array_filter([
                'it@fumaco.com',
                $manager?->email,
            ]);
            $markColumn = 'onboarding_email_sent_at';
        }

        if ($action === 'offboarding') {
            if (! empty($employee->offboarding_email_sent_at)) {
                return;
            }

            $lastDayRaw = $employee->resignation_date ?? ($event->context['last_day'] ?? null);
            $lastDayFormatted = null;
            try {
                $lastDayFormatted = $lastDayRaw ? Carbon::parse($lastDayRaw)->format('F d, Y') : null;
            } catch (\Throwable $e) {
            }

            $primaryLocation = null;
            try {
                if (! empty($employee->branch)) {
                    $primaryLocation = DB::table('branch')
                        ->where('branch_id', $employee->branch)
                        ->pluck('branch_name')
                        ->first();
                }
            } catch (\Throwable $e) {
                // If we fail to resolve branch name, keep it blank.
            }

            $mailData = array_merge($common, [
                'reporting_to' => $manager?->employee_name ?? null,
                'last_day' => $lastDayFormatted,
                'last_working_date' => $lastDayFormatted,
                'primary_location' => $primaryLocation,
            ]);

            $subject = 'Employee Offboarding - '.$employee->employee_name;
            $template = 'emails.employee.offboarding';
            $type = 'Offboarding Email';
            $recipients = array_filter([
                'it@fumaco.com',
                $manager?->email,
            ]);
            $markColumn = 'offboarding_email_sent_at';
        }

        $recipients = array_values(array_unique(array_filter(array_map('trim', $recipients))));
        if ($recipients === []) {
            Log::warning('Employee lifecycle notification skipped: no recipients.', [
                'employee_id' => $employee->id,
                'action' => $action,
            ]);

            return;
        }

        if ($subject === '' || $template === '') {
            Log::error('Employee lifecycle mail misconfigured: empty subject or template.', [
                'employee_id' => $employee->id,
                'action' => $action,
            ]);

            return;
        }

        $emailSent = 0;
        try {
            $mail = new EmployeeLifecycleMail($subject, $template, $mailData);
            if ($sendAt) {
                Mail::to($recipients)->later($sendAt, $mail);
            } else {
                // Send immediately so mail works without Redis / queue workers (QUEUE_CONNECTION=redis
                // would otherwise leave jobs unprocessed until `php artisan queue:work` runs).
                Mail::to($recipients)->send($mail);
            }
            $emailSent = 1;
            Log::info('Employee lifecycle email sent.', [
                'employee_id' => $employee->id,
                'action' => $action,
                'recipients' => $recipients,
                'subject' => $subject,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed sending employee lifecycle email.', [
                'employee_id' => $employee->id,
                'action' => $action,
                'recipients' => $recipients,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        try {
            DB::table('email_notifications')->insert([
                'type' => $type,
                'recipient' => implode(', ', $recipients),
                'subject' => $subject,
                'template' => $template,
                'template_data' => json_encode($mailData),
                'user_id' => $employee->id,
                'email_sent' => $emailSent,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed inserting email_notifications for employee lifecycle email.', [
                'employee_id' => $employee->id,
                'action' => $action,
                'error' => $e->getMessage(),
            ]);
        }

        if ($emailSent && $markColumn && empty($employee->{$markColumn})) {
            try {
                $employee->{$markColumn} = Carbon::now();
                $employee->save();
            } catch (\Throwable $e) {
                Log::warning('Failed setting lifecycle sent marker.', [
                    'employee_id' => $employee->id,
                    'mark_column' => $markColumn,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}

