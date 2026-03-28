<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BiometricRepositoryInterface
{
    /**
     * Get existing biometric IDs for an employee (excluding type 'adjustment').
     *
     * @return array<int, int|string> List of biometric_id values
     */
    public function getExistingBiometricIdsForEmployee(int $employeeId): array;

    /**
     * Insert raw biometric rows.
     *
     * @param  array<int, array<string, mixed>>  $rows
     */
    public function insertRawBiometrics(array $rows): void;

    /**
     * Delete adjustment record by biometric_id and type 'adjustment'.
     */
    public function deleteAdjustment(int $biometricId): void;

    /**
     * Paginated adjustments (biometrics join users, type = adjustment).
     */
    public function getAdjustmentsPaginated(int $perPage = 8): LengthAwarePaginator;
}
