<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface DepartmentApproverRepositoryInterface
{
    /**
     * Get department_id list for an employee (as approver).
     *
     * @return Collection<int, object> with department_id
     */
    public function getByEmployeeId(string $employeeId): Collection;
}
