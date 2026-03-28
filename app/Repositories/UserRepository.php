<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class UserRepository implements UserRepositoryInterface
{
    public function findOneByInternalOrExternalEmail(string $internalEmail, string $externalEmail): ?object
    {
        $user = DB::table('users')
            ->where('email', $internalEmail)
            ->orWhere('email', $externalEmail)
            ->first();

        return $user;
    }

    public function findByUserId(string $userId): ?object
    {
        $user = DB::table('users')->where('user_id', $userId)->first();

        return $user;
    }

    public function updateLastLogin(string $userId): void
    {
        DB::table('users')
            ->where('user_id', $userId)
            ->update(['last_login_date' => now()->toDateTimeString()]);
    }

    public function getEmployeesOrdered(): Collection
    {
        return DB::table('users')
            ->where('user_type', 'Employee')
            ->orderBy('employee_name', 'asc')
            ->get();
    }

    public function getWithDepartmentDesignation(string $userId): ?object
    {
        return DB::table('users')
            ->join('departments', 'users.department_id', 'departments.department_id')
            ->join('designation', 'users.designation_id', 'designation.des_id')
            ->where('user_id', $userId)
            ->select('users.*', 'departments.department', 'designation.designation')
            ->first();
    }

    public function getEmployeeProfilesPaginated(?array $departmentIds, ?string $q, int $perPage = 10): LengthAwarePaginator
    {
        $query = DB::table('users')
            ->join('departments', 'users.department_id', 'departments.department_id')
            ->join('designation', 'users.designation_id', 'designation.des_id');

        if ($departmentIds !== null && $departmentIds !== []) {
            $query = $query->whereIn('users.department_id', $departmentIds);
        }
        if ($q !== null && $q !== '') {
            $query = $query->where('employee_name', 'like', '%'.$q.'%');
        }

        return $query->select('users.*', 'departments.department', 'designation.designation')
            ->orderBy('employee_name')
            ->paginate($perPage);
    }
}
