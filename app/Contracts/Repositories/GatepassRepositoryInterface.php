<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Gatepass;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface GatepassRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Gatepass;

    public function create(array $attributes): Gatepass;

    public function update(Gatepass $gatepass, array $attributes): void;

    public function delete(int $id): void;

    public function getForApprovalPaginated(int $perPage = 8): LengthAwarePaginator;

    public function getUnreturnedItems(): Collection;

    public function getByUserIdPaginated(int $userId, int $perPage = 8): LengthAwarePaginator;

    public function getDetailsById(int $gatepassId): ?object;

    public function getFilteredPaginated(Request $request, int $perPage = 7): LengthAwarePaginator;

    public function getPrintModel(int $id): ?Gatepass;

    public function getUnreturnedPaginated(Request $request, int $perPage = 8): LengthAwarePaginator;

    public function countPending(): int;

    public function getApprovedForAnalytics(?int $year = null): Collection;

    public function getPurposeRateChartData(int $year): Collection;

    public function getPerDeptChart(?string $purpose, int $year): Collection;

    public function getItemsIssuedToEmployee(string $userId): Collection;

    public function getPendingByUserId(string $userId): Collection;

    public function getUnreturnedByUserId(string $userId): Collection;
}
