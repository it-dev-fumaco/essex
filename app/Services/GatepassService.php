<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\GatepassRepositoryInterface;
use App\Contracts\Repositories\SessionDetailRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

final class GatepassService
{
    public function __construct(
        private readonly GatepassRepositoryInterface $gatepassRepository,
        private readonly SessionDetailRepositoryInterface $sessionDetailRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function getAllGatepasses(): Collection
    {
        return $this->gatepassRepository->all();
    }

    public function storeGatepass(Request $request): string
    {
        $item = $this->gatepassRepository->create([
            'user_id' => $request->user_id,
            'date_filed' => $request->date_filed,
            'returned_on' => $request->returned_on,
            'company_name' => $request->company_name,
            'time' => $request->time,
            'address' => $request->address,
            'purpose' => $request->purpose,
            'purpose_type' => $request->purpose_type,
            'tel_no' => $request->tel_no,
            'item_description' => $request->item_description,
            'remarks' => $request->remarks,
            'status' => 'FOR APPROVAL',
        ]);

        return 'Gatepass no. <b>'.$item->gatepass_id.'</b>';
    }

    public function updateStatus(Request $request): string
    {
        $item = $this->gatepassRepository->find((int) $request->gatepass_id);
        if (! $item) {
            return '';
        }
        $attrs = [
            'status' => $request->status,
            'item_type' => $request->item_type,
            'last_modified_by' => Auth::user()->employee_name,
        ];
        if ($request->item_type == 'Returnable') {
            $attrs['item_status'] = 'Unreturned';
        }
        $this->gatepassRepository->update($item, $attrs);

        return 'Gatepass no. <b>'.$item->gatepass_id.'</b> has been <b>'.$request->status.'</b>.';
    }

    public function destroy(int $id): void
    {
        $this->gatepassRepository->delete($id);
    }

    public function getGatepassesForApprovalPaginated(int $perPage = 8): LengthAwarePaginator
    {
        return $this->gatepassRepository->getForApprovalPaginated($perPage);
    }

    public function getUnreturnedItems(): Collection
    {
        return $this->gatepassRepository->getUnreturnedItems();
    }

    public function getFetchGatepassesPaginated(int $userId, int $perPage = 8): LengthAwarePaginator
    {
        return $this->gatepassRepository->getByUserIdPaginated($userId, $perPage);
    }

    public function getGatepassDetails(int $gatepassId): ?object
    {
        return $this->gatepassRepository->getDetailsById($gatepassId);
    }

    public function updateGatepassDetails(Request $request): string
    {
        $gatepass = $this->gatepassRepository->find((int) $request->gatepass_id);
        if (! $gatepass) {
            return '';
        }
        $this->gatepassRepository->update($gatepass, [
            'date_filed' => $request->date_filed,
            'returned_on' => $request->returned_on,
            'company_name' => $request->company_name,
            'time' => $request->time,
            'address' => $request->address,
            'purpose' => $request->purpose,
            'purpose_type' => $request->purpose_type,
            'tel_no' => $request->tel_no,
            'item_description' => $request->item_description,
            'remarks' => $request->remarks,
            'last_modified_by' => Auth::user()->employee_name,
        ]);

        return 'Gatepass no.<b>'.$gatepass->gatepass_id.'</b> has been updated.';
    }

    public function cancelGatepass(Request $request): string
    {
        $gatepass = $this->gatepassRepository->find((int) $request->id);
        if (! $gatepass) {
            return '';
        }
        $this->gatepassRepository->update($gatepass, [
            'status' => 'CANCELLED',
            'last_modified_by' => Auth::user()->employee_name,
        ]);

        return 'Gatepass no. <b>'.$gatepass->gatepass_id.'</b> has been cancelled.';
    }

    public function getGatepassesFilteredPaginated(Request $request, int $perPage = 7): LengthAwarePaginator
    {
        return $this->gatepassRepository->getFilteredPaginated($request, $perPage);
    }

    public function getPrintGatepass(int $id)
    {
        return $this->gatepassRepository->getPrintModel($id);
    }

    public function getUnreturnedGatepassPaginated(Request $request, int $perPage = 8): LengthAwarePaginator
    {
        return $this->gatepassRepository->getUnreturnedPaginated($request, $perPage);
    }

    public function updateUnreturnedGatepass(Request $request): string
    {
        $gatepass = $this->gatepassRepository->find((int) $request->gatepass_id);
        if (! $gatepass) {
            return '';
        }
        $this->gatepassRepository->update($gatepass, [
            'item_status' => 'Returned',
            'last_modified_by' => Auth::user()->employee_name,
        ]);

        return 'Gatepass no. <b>'.$gatepass->gatepass_id.'</b> has been returned.';
    }

    public function countPendingGatepass(): int
    {
        return $this->gatepassRepository->countPending();
    }

    public function getSessionDetail(string $column)
    {
        $userId = Auth::user()->user_id ?? '';
        $detail = $this->sessionDetailRepository->getByUserId($userId);

        return $detail->$column ?? null;
    }

    public function getAnalyticsTotals(): array
    {
        $gatepass = $this->gatepassRepository->getApprovedForAnalytics();
        $total_gatepass = $gatepass->count();
        $total_unreturned = $gatepass->where('item_type', '=', 'Returnable')->where('item_status', '=', 'Unreturned')->count();
        $total_pending = $gatepass->where('status', 'FOR APPROVAL')->count();

        return [
            'gatepass' => $total_gatepass,
            'unreturned_items' => $total_unreturned,
            'pending' => $total_pending,
        ];
    }

    public function getPurposeRateChartData(int $year): array
    {
        $gatepass = $this->gatepassRepository->getPurposeRateChartData($year);
        $total_gatepass = $gatepass->count();
        $divisor = $total_gatepass ?: 1;
        $servicing = $gatepass->where('purpose_type', 'For Servicing')->count();
        $company_activity = $gatepass->where('purpose_type', 'For Company Activity')->count();
        $personal_use = $gatepass->where('purpose_type', 'For Personal Use')->count();
        $others = $gatepass->where('purpose_type', 'Others')->count();

        return [
            ['purpose_type' => 'For Servicing', 'percentage' => round(($servicing / $divisor) * 100, 2)],
            ['purpose_type' => 'For Company Activity', 'percentage' => round(($company_activity / $divisor) * 100, 2)],
            ['purpose_type' => 'For Personal Use', 'percentage' => round(($personal_use / $divisor) * 100, 2)],
            ['purpose_type' => 'Others', 'percentage' => round(($others / $divisor) * 100, 2)],
        ];
    }

    public function getGatepassPerDeptChart(?string $purpose, int $year): Collection
    {
        return $this->gatepassRepository->getPerDeptChart($purpose, $year);
    }

    public function getShowGatepassHistoryData(): array
    {
        $designation = $this->getSessionDetail('designation');
        $department = $this->getSessionDetail('department');
        $employees = $this->userRepository->getEmployeesOrdered();

        return compact('designation', 'department', 'employees');
    }

    public function getShowUnreturnedItemsData(): array
    {
        $designation = $this->getSessionDetail('designation');
        $department = $this->getSessionDetail('department');
        $employees = $this->userRepository->getEmployeesOrdered();

        return compact('designation', 'department', 'employees');
    }

    public function getItemsIssuedToEmployee(string $userId): Collection
    {
        return $this->gatepassRepository->getItemsIssuedToEmployee($userId);
    }
}
