<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Token-based absent notice approval links from email (GET /notice_slip/updateStatus).
 */
final class AbsentNoticeMailApproval
{
    public static function isRequest(Request $request): bool
    {
        if (! $request->is('notice_slip/updateStatus')) {
            return false;
        }

        if (! $request->isMethod('GET')) {
            return false;
        }

        if ($request->boolean('update_from_mail')) {
            return true;
        }

        // Some clients strip `update_from_mail`; still treat as mail flow when link params are present.
        return $request->filled('notice_id')
            && $request->filled('token')
            && $request->filled('approver_id');
    }
}
