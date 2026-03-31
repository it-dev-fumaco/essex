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

    public function events(Request $request)
    {
        // FullCalendar v3 sends `start` and `end` as ISO strings.
        $startRaw = (string) $request->query('start', '');
        $endRaw = (string) $request->query('end', '');

        $rangeStart = $startRaw !== '' ? Carbon::parse($startRaw)->startOfDay() : Carbon::today()->startOfMonth()->startOfDay();
        $rangeEnd = $endRaw !== '' ? Carbon::parse($endRaw)->endOfDay() : Carbon::today()->endOfMonth()->endOfDay();

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
        while ($cursor <= $rangeEnd) {
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
                    $join = Carbon::parse($emp->date_joined);
                    if ((int) $join->month === $month && (int) $join->day === $day) {
                        $years = $join->diffInYears($cursor);
                        $suffix = $years > 0 ? (' (' . $years . ' yr)') : '';
                        $events[] = [
                            'title' => $emp->employee_name . ' - Work Anniversary' . $suffix,
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
                ->where('notice_slip.status', 'Approved')
                ->whereDate('notice_slip.date_from', '<=', $rangeEnd->toDateString())
                ->whereDate('notice_slip.date_to', '>=', $rangeStart->toDateString())
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

