<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\NoticeSlipRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class NoticeSlipRepository implements NoticeSlipRepositoryInterface
{
    public function getPendingByUserId(string $userId): Collection
    {
        return DB::table('notice_slip')
            ->join('leave_types', 'notice_slip.leave_type_id', 'leave_types.leave_type_id')
            ->where('user_id', $userId)
            ->where('status', 'For Approval')
            ->select('notice_slip.*', 'leave_type')
            ->get();
    }
}
