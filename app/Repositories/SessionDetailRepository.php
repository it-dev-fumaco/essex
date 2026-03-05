<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\SessionDetailRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class SessionDetailRepository implements SessionDetailRepositoryInterface
{
    public function getByUserId(string $userId): ?object
    {
        return DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', $userId)
            ->first();
    }
}
