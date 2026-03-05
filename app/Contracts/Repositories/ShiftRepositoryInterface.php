<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface ShiftRepositoryInterface
{
    public function getRegularShiftByUserId(string $userId): ?object;

    public function getAllShifts(): Collection;

    public function getAllBranch(): Collection;
}
