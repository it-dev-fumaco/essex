<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\DepartmentApproverRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class DepartmentApproverRepository implements DepartmentApproverRepositoryInterface
{
    public function getByEmployeeId(string $employeeId): Collection
    {
        return DB::table('department_approvers')->where('employee_id', $employeeId)->get();
    }
}
