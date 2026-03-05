<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface LookupRepositoryInterface
{
    public function getAllDepartments(): Collection;

    public function getAllDesignations(): Collection;

    public function getLeaveTypes(): Collection;

    public function getHolidays(): Collection;

    public function getFiscalYears(): Collection;

    public function getAttendanceRules(): Collection;

    public function getBranches(): Collection;

    public function getShifts(): Collection;

    public function getShiftGroups(): Collection;

    /** Invalidate cached lookups (e.g. after admin updates). */
    public function clearCache(): void;
}
