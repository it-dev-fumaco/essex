<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * Find user by internal and external email (e.g. @fumaco.local and @fumaco.com).
     *
     * @return object|null StdClass or User with id, user_id, email
     */
    public function findOneByInternalOrExternalEmail(string $internalEmail, string $externalEmail): ?object;

    /**
     * Find user by user_id (employee/user id string).
     *
     * @return object|null StdClass or User with id, user_id, email
     */
    public function findByUserId(string $userId): ?object;

    /**
     * Update last_login_date for the given user_id.
     */
    public function updateLastLogin(string $userId): void;

    /**
     * Get all users where user_type = 'Employee' ordered by employee_name asc.
     *
     * @return Collection<int, object>
     */
    public function getEmployeesOrdered(): Collection;

    /**
     * Get single user with department and designation by user_id.
     *
     * @return object|null
     */
    public function getWithDepartmentDesignation(string $userId): ?object;

    /**
     * Get employee profiles paginated (users join departments, designation).
     * When $departmentIds is null, no department filter; else whereIn users.department_id.
     *
     * @param array<int, mixed>|null $departmentIds
     * @return LengthAwarePaginator
     */
    public function getEmployeeProfilesPaginated(?array $departmentIds, ?string $q, int $perPage = 10): LengthAwarePaginator;
}
