<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\GatepassRepositoryInterface;
use App\Models\Gatepass;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GatepassRepository implements GatepassRepositoryInterface
{
    public function all(): Collection
    {
        return Gatepass::all();
    }

    public function find(int $id): ?Gatepass
    {
        return Gatepass::find($id);
    }

    public function create(array $attributes): Gatepass
    {
        $item = new Gatepass;
        foreach ($attributes as $key => $value) {
            $item->$key = $value;
        }
        $item->save();
        return $item;
    }

    public function update(Gatepass $gatepass, array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $gatepass->$key = $value;
        }
        $gatepass->save();
    }

    public function delete(int $id): void
    {
        Gatepass::destroy($id);
    }

    public function getForApprovalPaginated(int $perPage = 8): LengthAwarePaginator
    {
        return DB::table('gatepass')
            ->join('users', 'users.user_id', '=', 'gatepass.user_id')
            ->where('gatepass.status', '=', 'For Approval')
            ->select('gatepass.*', 'users.employee_name')
            ->paginate($perPage);
    }

    public function getUnreturnedItems(): Collection
    {
        return DB::table('gatepass')
            ->join('users', 'users.user_id', '=', 'gatepass.user_id')
            ->where('gatepass.status', '=', 'Unreturned')
            ->select('gatepass.*', 'users.employee_name')
            ->get();
    }

    public function getByUserIdPaginated(int $userId, int $perPage = 8): LengthAwarePaginator
    {
        return DB::table('gatepass')
            ->join('users', 'users.user_id', '=', 'gatepass.user_id')
            ->where('gatepass.user_id', '=', $userId)
            ->orderBy('gatepass.gatepass_id', 'desc')
            ->select('users.*', 'gatepass.*')
            ->paginate($perPage);
    }

    public function getDetailsById(int $gatepassId): ?object
    {
        return DB::table('gatepass')
            ->join('users', 'users.user_id', '=', 'gatepass.user_id')
            ->leftJoin('users as approver', 'approver.user_id', '=', 'gatepass.approved_by')
            ->where('gatepass.gatepass_id', '=', $gatepassId)
            ->select('users.*', 'gatepass.*', 'approver.employee_name as approved_by')
            ->first();
    }

    public function getFilteredPaginated(Request $request, int $perPage = 7): LengthAwarePaginator
    {
        $query = DB::table('gatepass')
            ->join('users', 'users.user_id', '=', 'gatepass.user_id');

        if ($request->employee) {
            $query = $query->where('gatepass.user_id', $request->employee);
        }
        if ($request->item_type) {
            $query = $query->where('gatepass.item_type', $request->item_type);
        }

        return $query->select('gatepass.*', 'users.employee_name')
            ->orderBy('gatepass_id', 'desc')
            ->paginate($perPage);
    }

    public function getPrintModel(int $id): ?Gatepass
    {
        return Gatepass::join('users', 'users.user_id', '=', 'gatepass.user_id')
            ->join('departments', 'departments.department_id', '=', 'users.department_id')
            ->leftJoin('users as approver', 'approver.user_id', '=', 'gatepass.approved_by')
            ->leftJoin('designation as appr_des', 'appr_des.des_id', '=', 'approver.designation_id')
            ->select('users.employee_name', 'gatepass.*', 'departments.department', 'approver.employee_name as approved_by', 'appr_des.designation as appr_designation')
            ->find($id);
    }

    public function getUnreturnedPaginated(Request $request, int $perPage = 8): LengthAwarePaginator
    {
        $query = DB::table('gatepass')
            ->join('users', 'users.user_id', '=', 'gatepass.user_id')
            ->where('gatepass.item_type', '=', 'Returnable')
            ->where('gatepass.item_status', '=', 'Unreturned')
            ->where('gatepass.status', '=', 'Approved');

        if ($request->employee) {
            $query = $query->where('gatepass.user_id', $request->employee);
        }

        return $query->orderBy('gatepass.gatepass_id', 'desc')
            ->select('users.*', 'gatepass.*')
            ->paginate($perPage);
    }

    public function countPending(): int
    {
        return (int) DB::table('gatepass')
            ->join('users', 'users.user_id', '=', 'gatepass.user_id')
            ->where('gatepass.status', '=', 'For Approval')
            ->count();
    }

    public function getApprovedForAnalytics(?int $year = null): Collection
    {
        $query = DB::table('gatepass')->where('status', 'APPROVED');
        if ($year !== null) {
            $query->whereYear(DB::raw('COALESCE(date_filed_converted, date_filed)'), $year);
        }
        return $query->get();
    }

    public function getPurposeRateChartData(int $year): Collection
    {
        return DB::table('gatepass')
            ->where('status', 'APPROVED')
            ->whereRaw('YEAR(COALESCE(date_filed_converted, date_filed)) = ?', [$year])
            ->select(DB::raw('YEAR(COALESCE(date_filed_converted, date_filed)) AS year'), 'purpose_type')
            ->get();
    }

    public function getPerDeptChart(?string $purpose, int $year): Collection
    {
        $query = DB::table('gatepass')
            ->join('users', 'users.user_id', 'gatepass.user_id')
            ->join('departments', 'users.department_id', 'departments.department_id')
            ->select('department', DB::raw('COUNT(users.department_id) as total, YEAR(IFNULL(date_filed_converted, date_filed)) AS year'))
            ->where('gatepass.status', 'APPROVED');

        if ($purpose) {
            $query = $query->where('gatepass.purpose_type', $purpose);
        }

        return $query->having('year', $year)->groupBy('users.department_id', 'department', 'year')->get();
    }

    public function getItemsIssuedToEmployee(string $userId): Collection
    {
        return DB::table('issued_to_employee')->where('issued_to', $userId)->get();
    }

    public function getPendingByUserId(string $userId): Collection
    {
        return DB::table('gatepass')
            ->where('user_id', $userId)
            ->where('status', 'For Approval')
            ->get();
    }

    public function getUnreturnedByUserId(string $userId): Collection
    {
        return DB::table('gatepass')
            ->where('user_id', $userId)
            ->where('item_status', 'Unreturned')
            ->get();
    }
}
