<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\LookupRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class LookupRepository implements LookupRepositoryInterface
{
    private const TTL_SECONDS = 3600;

    public function getAllDepartments(): Collection
    {
        return Cache::remember('lookup.departments', self::TTL_SECONDS, fn () => DB::table('departments')->get());
    }

    public function getAllDesignations(): Collection
    {
        return Cache::remember('lookup.designations', self::TTL_SECONDS, fn () => DB::table('designation')->get());
    }

    public function getLeaveTypes(): Collection
    {
        return Cache::remember('lookup.leave_types', self::TTL_SECONDS, fn () => DB::table('leave_types')->get());
    }

    public function getHolidays(): Collection
    {
        return Cache::remember('lookup.holidays', self::TTL_SECONDS, fn () => DB::table('holidays')->get());
    }

    public function getFiscalYears(): Collection
    {
        return Cache::remember('lookup.fiscal_year', self::TTL_SECONDS, fn () => DB::table('fiscal_year')->get());
    }

    public function getAttendanceRules(): Collection
    {
        return Cache::remember('lookup.attendance_rules', self::TTL_SECONDS, fn () => DB::table('attendance_rules')->get());
    }

    public function getBranches(): Collection
    {
        return Cache::remember('lookup.branch', self::TTL_SECONDS, fn () => DB::table('branch')->get());
    }

    public function getShifts(): Collection
    {
        return Cache::remember('lookup.shifts', self::TTL_SECONDS, fn () => DB::table('shifts')->get());
    }

    public function getShiftGroups(): Collection
    {
        return Cache::remember('lookup.shift_groups', self::TTL_SECONDS, fn () => DB::table('shift_groups')->get());
    }

    public function clearCache(): void
    {
        $keys = [
            'lookup.departments', 'lookup.designations', 'lookup.leave_types', 'lookup.holidays',
            'lookup.fiscal_year', 'lookup.attendance_rules', 'lookup.branch', 'lookup.shifts', 'lookup.shift_groups',
        ];
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
