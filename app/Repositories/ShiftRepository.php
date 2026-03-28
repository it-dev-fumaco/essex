<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\ShiftRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class ShiftRepository implements ShiftRepositoryInterface
{
    public function getRegularShiftByUserId(string $userId): ?object
    {
        return DB::table('shifts')
            ->join('users', 'shifts.shift_id', '=', 'users.shift_group_id')
            ->where('user_id', $userId)
            ->select('shift_schedule')
            ->first();
    }

    public function getAllShifts(): Collection
    {
        return DB::table('shifts')->get();
    }

    public function getAllBranch(): Collection
    {
        return DB::table('branch')->get();
    }
}
