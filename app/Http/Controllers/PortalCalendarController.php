<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PortalCalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    /**
     * FullCalendar v3 GETs events with `start` and `end` query params.
     * It typically sends Unix timestamps (seconds), not ISO strings — Carbon::parse() cannot parse those and will throw on PHP 8.3+.
     */
    private function parseFullCalendarQueryDate(string $value): Carbon
    {
        $value = trim($value);
        if ($value === '') {
            throw new \InvalidArgumentException('Empty FullCalendar date param.');
        }

        if (is_numeric($value)) {
            $n = (float) $value;
            // jQuery/FC may send millis (13 digits)
            if ($n > 20000000000) {
                $n = (int) round($n / 1000);
            } else {
                $n = (int) $n;
            }

            return Carbon::createFromTimestamp($n, config('app.timezone'));
        }

        return Carbon::parse($value, config('app.timezone'));
    }

    /**
     * Correct common data-entry typos like 0025-02-24 (meant 2025-02-24). Carbon otherwise
     * parses year 0025 as 25 AD and produces wrong anniversary labels.
     */
    private function normalizeDateJoinedString(string $raw): string
    {
        $raw = trim($raw);
        if (preg_match('/^00(\d{2}-\d{2}-\d{2})$/', $raw, $m)) {
            return '20'.$m[1];
        }

        return $raw;
    }

    public function events(Request $request)
    {
        // FullCalendar (or a proxy) can produce URLs like `/path??start=...&end=...`; PHP then
        // registers the first param as `?start` instead of `start`, so we accept both keys.
        $q = $request->query();
        $startRaw = (string) ($q['start'] ?? $q['?start'] ?? '');
        $endRaw = (string) ($q['end'] ?? '');

        try {
            if ($startRaw !== '' && $endRaw !== '') {
                $rangeStart = $this->parseFullCalendarQueryDate($startRaw)->startOfDay();
                // `end` is exclusive (first instant after the visible range).
                $endExclusive = $this->parseFullCalendarQueryDate($endRaw);
                $rangeEnd = $endExclusive->copy()->subSecond()->endOfDay();
                if ($rangeEnd->lt($rangeStart)) {
                    $rangeEnd = $rangeStart->copy()->endOfDay();
                }
            } else {
                $rangeStart = Carbon::today()->startOfMonth()->startOfDay();
                $rangeEnd = Carbon::today()->endOfMonth()->endOfDay();
            }
        } catch (\Throwable $e) {
            $rangeStart = Carbon::today()->startOfMonth()->startOfDay();
            $rangeEnd = Carbon::today()->endOfMonth()->endOfDay();
        }

        $rangeStartDate = $rangeStart->toDateString();
        $rangeEndDate = $rangeEnd->toDateString();

        $isPublic = ! Auth::check();
        $userGroup = Auth::check() ? (Auth::user()->user_group ?? null) : null;
        $isManager = $userGroup === 'Manager';
        $isHr = $userGroup === 'HR Personnel';

        $events = [];

        // Birthdays + Work anniversaries (all employees)
        $employees = DB::table('users')
            ->where('status', 'Active')
            ->where('user_type', 'Employee')
            ->select('user_id', 'employee_name', 'birth_date', 'date_joined')
            ->get();

        // Add yearly repeating events within the visible range (by month/day).
        $cursor = $rangeStart->copy();
        while ($cursor->lte($rangeEnd)) {
            $month = (int) $cursor->month;
            $day = (int) $cursor->day;
            $ymd = $cursor->format('Y-m-d');

            foreach ($employees as $emp) {
                if (! empty($emp->birth_date)) {
                    $bday = Carbon::parse($emp->birth_date);
                    if ((int) $bday->month === $month && (int) $bday->day === $day) {
                        $events[] = [
                            'title' => $emp->employee_name . ' - Birthday',
                            'start' => $ymd,
                            'allDay' => true,
                            'color' => '#F1C40F',
                        ];
                    }
                }

                if (! empty($emp->date_joined)) {
                    $djRaw = (string) $emp->date_joined;
                    $djNormalized = $this->normalizeDateJoinedString($djRaw);
                    $join = Carbon::parse($djNormalized, config('app.timezone'));
                    if ((int) $join->month === $month && (int) $join->day === $day) {
                        $years = $join->diffInYears($cursor);
                        // Work anniversary = at least one full year since hire (not the hire date itself).
                        if ($years < 1) {
                            continue;
                        }
                        $suffix = ' ('.$years.' yr)';
                        $events[] = [
                            'title' => $emp->employee_name . ' - Work Anniversary'.$suffix,
                            'start' => $ymd,
                            'allDay' => true,
                            'color' => '#8E44AD',
                        ];
                    }
                }
            }

            $cursor->addDay();
        }

        // OOO events (leave data) - only for logged-in users
        if (! $isPublic) {
            $ooo = DB::table('notice_slip')
                ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
                ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                // DB has mixed casing over time (see HomeController getLeaves: APPROVED vs Portal: Approved)
                ->whereIn('notice_slip.status', ['Approved', 'APPROVED'])
                ->whereDate('notice_slip.date_from', '<=', $rangeEndDate)
                ->whereDate('notice_slip.date_to', '>=', $rangeStartDate)
                ->select(
                    'notice_slip.notice_id',
                    'users.user_id',
                    'users.employee_name',
                    'notice_slip.date_from',
                    'notice_slip.date_to',
                    'leave_types.leave_type'
                );

            if ($isManager && ! $isHr) {
                $directReportIds = DB::table('users')
                    ->where('reporting_to', Auth::user()->user_id)
                    ->where('user_type', 'Employee')
                    ->pluck('user_id')
                    ->all();
                $ooo->whereIn('notice_slip.user_id', $directReportIds);
            }

            // HR + normal employees see all OOO
            $ooo = $ooo->get();

            foreach ($ooo as $leave) {
                $end = new DateTime($leave->date_to);
                $end->modify('+1 day');
                $events[] = [
                    'id' => $leave->notice_id,
                    'title' => $leave->employee_name . ' - OOO (' . $leave->leave_type . ')',
                    'start' => $leave->date_from,
                    'end' => $end->format('Y-m-d'),
                    'color' => '#E74C3C',
                ];
            }
        }

        return response()->json($events);
    }
}

