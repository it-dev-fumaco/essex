<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\BiometricRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class BiometricRepository implements BiometricRepositoryInterface
{
    /**
     * @return array<int, int|string>
     */
    public function getExistingBiometricIdsForEmployee(int $employeeId): array
    {
        $rows = DB::table('biometrics')
            ->where('employee_id', $employeeId)
            ->where('type', '!=', 'adjustment')
            ->select('biometric_id')
            ->get();

        $ids = [];
        foreach ($rows as $row) {
            $ids[] = $row->biometric_id;
        }

        return $ids;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    public function insertRawBiometrics(array $rows): void
    {
        if ($rows === []) {
            return;
        }

        DB::table('biometrics')->insert($rows);
    }

    public function deleteAdjustment(int $biometricId): void
    {
        DB::table('biometrics')
            ->where('biometric_id', $biometricId)
            ->where('type', 'adjustment')
            ->delete();
    }

    public function getAdjustmentsPaginated(int $perPage = 8): LengthAwarePaginator
    {
        return DB::table('biometrics')
            ->join('users', 'users.user_id', '=', 'biometrics.employee_id')
            ->select('biometrics.*', 'users.employee_name')
            ->where('type', 'adjustment')
            ->paginate($perPage);
    }
}
