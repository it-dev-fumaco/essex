<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\BiometricRepositoryInterface;
use App\Contracts\Repositories\SessionDetailRepositoryInterface;
use App\Models\Biometric_logs;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class AttendanceService
{
    public function __construct(
        private readonly BiometricRepositoryInterface $biometricRepository,
        private readonly SessionDetailRepositoryInterface $sessionDetailRepository
    ) {}

    public function getSessionDetail(string $column)
    {
        $userId = Auth::user()->user_id ?? '';
        $detail = $this->sessionDetailRepository->getByUserId($userId);

        return $detail->$column ?? null;
    }

    /**
     * Refresh biometric attendance for the authenticated user.
     * Biometric data is stored in biometric_logs only (Access DB sync has been removed).
     *
     * @return array{success: string}
     */
    public function refreshAttendance(int $employeeId): array
    {
        return ['success' => 'Biometric logs are stored locally only.'];
    }

    public function getBioAdjustmentsPaginated(int $perPage = 8): LengthAwarePaginator
    {
        return $this->biometricRepository->getAdjustmentsPaginated($perPage);
    }

    /**
     * Add time-in (7) or time-out (8) adjustment to biometric_logs.
     * Preserves exact behavior: updates Biometric_logs model by rowid_data.
     *
     * @return array{message: string}
     */
    public function addAdjustment(Request $request): array
    {
        $transaction = (int) $request->input('transaction');
        $rowId = $request->input('rowid_data');
        if ($rowId === null) {
            return ['message' => 'Adjustment has been added.'];
        }

        $adj = Biometric_logs::find($rowId);
        if ($adj === null) {
            return ['message' => 'Adjustment has been added.'];
        }

        $date = date('Y-m-d');
        $employeeName = Auth::user()?->employee_name ?? '';

        $adj->user_id = $request->input('employee_id');
        $adj->transaction_date = $request->input('transaction_date');
        $adj->remarks = 'adjustment';
        $adj->last_date_modified = $date;
        $adj->last_modified_by = $employeeName;

        if ($transaction === 7) {
            $adj->time_in = $request->input('adjusted_time');
            $adj->adj_type = '7';
        } elseif ($transaction === 8) {
            $adj->time_out = $request->input('adjusted_time');
            $adj->adj_type = '8';
        }

        $adj->save();

        return ['message' => 'Adjustment has been added.'];
    }

    /**
     * Delete adjustment from biometrics table by biometric_id.
     *
     * @return array{message: string}
     */
    public function deleteAdjustment(int $biometricId): array
    {
        $this->biometricRepository->deleteAdjustment($biometricId);

        return ['message' => 'Adjustment has been deleted.'];
    }
}
