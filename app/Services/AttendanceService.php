<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Biometric_logs;
use App\Contracts\Repositories\BiometricRepositoryInterface;
use App\Contracts\Repositories\SessionDetailRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class AttendanceService
{
    public function __construct(
        private readonly BiometricRepositoryInterface $biometricRepository,
        private readonly SessionDetailRepositoryInterface $sessionDetailRepository
    ) {
    }

    public function getSessionDetail(string $column)
    {
        $userId = Auth::user()->user_id ?? '';
        $detail = $this->sessionDetailRepository->getByUserId($userId);
        return $detail->$column ?? null;
    }

    /**
     * Refresh biometric logs from Access DB for the authenticated user.
     * Returns the same JSON response as original controller.
     *
     * @return array{success: string}
     */
    public function refreshAttendance(int $employeeId): array
    {
        $existingIds = $this->biometricRepository->getExistingBiometricIdsForEmployee($employeeId);
        $bioIds = '0000';
        if ($existingIds !== []) {
            $bioIds = implode(',', array_map('strval', $existingIds));
            $bioIds = 'AND Transactions.[ID] NOT IN (' . $bioIds . ')';
        }

        $sql = 'SELECT Transactions.[ID], Transactions.[date], Transactions.[time], Transactions.[SerialNo], Transactions.[TransType], Transactions.[pin], Transactions.[ReceivedDate], Transactions.[ReceivedTime], templates.[FirstName], templates.[LastName], UnitSiteQuery.[UnitName] FROM (Transactions LEFT JOIN UnitSiteQuery ON Transactions.Address = UnitSiteQuery.Address) LEFT JOIN templates ON (Transactions.pin = templates.pin) AND (Transactions.finger = templates.finger) WHERE (Transactions.[TransType] = 7 OR Transactions.[TransType] = 8) AND Transactions.[ID] > 704020 AND Transactions.[pin] = ' . (int) $employeeId . ' ' . $bioIds;

        $attendance = DB::connection('access')->select($sql);
        $data = [];
        foreach ($attendance as $row) {
            $data[] = [
                'biometric_id' => $row->ID,
                'bio_date' => $row->date,
                'bio_time' => $row->time,
                'serial_no' => $row->SerialNo,
                'trans_type' => $row->TransType,
                'employee_id' => $row->pin,
                'received_date' => $row->ReceivedDate,
                'received_time' => $row->ReceivedTime,
                'unit_name' => $row->UnitName,
                'type' => 'raw data',
            ];
        }

        $this->biometricRepository->insertRawBiometrics($data);

        return ['success' => 'Updated: Biometric Logs'];
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
