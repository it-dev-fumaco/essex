<?php

namespace App\Http\Controllers;

use App\Traits\AttendanceTrait;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
    use AttendanceTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.panel');
    }

    public function addShiftForm()
    {
        return view('admin.forms.add_shift_form');
    }

    public function addDepartmentForm()
    {
        return view('admin.forms.add_department_form');
    }

    public function addApplicantForm()
    {
        return view('admin.forms.add_applicant_form');
    }

    // // ATTENDANCE
    // public function updateAttendanceLogs($employee){
    //     $for_delete = DB::table('biometric_logs')->where('user_id', $employee)
    //             ->where(function($q) {
    //                 $q->where('time_in', null)->orWhere('time_out', null);
    //             })->pluck('id');

    //     $delete = DB::table('biometric_logs')->whereIn('id', $for_delete)->delete();

    //     $complete_logs = DB::table('biometric_logs')->where('user_id', $employee)
    //             ->whereNotIn('id', $for_delete)->pluck('transaction_date');

    //     $c_logs = [];
    //     foreach ($complete_logs as $d) {
    //         $c_logs[] = ['date' => Carbon::parse($d)->format('Y-m-d H:i:s')];
    //     }

    //     $biometrics = DB::connection('access')->select("SELECT Transactions.[pin], Transactions.[date], MAX(iif (Transactions.[TransType] = 7, Transactions.[time], 0)) AS time_in, MAX(iif (Transactions.[TransType] = 8, Transactions.[time], 0)) AS time_out, MAX(iif (Transactions.[TransType] = 7, UnitSiteQuery.[UnitName], 0)) AS loc_in, MAX(iif (Transactions.[TransType] = 8, UnitSiteQuery.[UnitName], 0)) AS loc_out FROM (Transactions LEFT JOIN UnitSiteQuery ON Transactions.Address = UnitSiteQuery.Address) LEFT JOIN templates ON (Transactions.pin = templates.pin) AND (Transactions.finger = templates.finger) WHERE Transactions.[ID] > 704020 AND Transactions.[pin] = ".$employee." GROUP BY Transactions.[date], Transactions.[pin]");

    //     $biometrics = collect($biometrics)->whereNotIn('date', array_column($c_logs, 'date'));

    //     $logs = [];
    //     foreach ($biometrics as $row) {
    //         $logs[] = [
    //             'user_id' => $row->pin,
    //             'transaction_date' => Carbon::parse($row->date)->format('Y-m-d'),
    //             'time_in' => $row->loc_in != '0' ? Carbon::parse($row->time_in)->format('H:i:s') : null,
    //             'time_out' => $row->loc_out != '0' ? Carbon::parse($row->time_out)->format('H:i:s') : null,
    //             'location_in' => $row->loc_in,
    //             'location_out' => $row->loc_out
    //         ];
    //     }

    //     DB::table('biometric_logs')->insert($logs);

    //     return response()->json(['success' => 'Updated: Biometric Logs']);
    // }

    public function employeeAttendanceHistory(Request $request, $employee)
    {
        $employee_logs = $this->attendanceLogs($employee, $request->start, $request->end);

        $working_days = $this->getWorkingDays($request->start, $request->end);
        $reqHrs = $working_days * 8;

        $summary_details = [
            'date_from' => $request->start,
            'date_to' => $request->end,
            'working_days' => $working_days,
            'reqHrs' => $reqHrs,
        ];

        return view('client.tables.attendance_history_table', compact('employee_logs', 'summary_details'));
    }

    public function employeeAttendanceDashboard(Request $request, $employee)
    {
        $employee_logs = $this->attendanceLogs($employee, $request->start, $request->end);

        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // Create a new Laravel collection from the array data
        $itemCollection = collect($employee_logs);
        // Define how many items we want to be visible in each page
        $perPage = 8;
        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);
        // set url path for generted links
        $paginatedItems->setPath($request->url());

        $logs = $paginatedItems;

        return view('client.tables.attendance_table', compact('logs'));
    }

    public function employeeLateDeductions($employee)
    {
        $date_from = Carbon::parse('first day of this month')->format('Y-m-d');
        $date_to = Carbon::parse('last day of this month')->format('Y-m-d');

        $emp_lates = $this->attendanceLogs($employee, $date_from, $date_to);

        return collect($emp_lates)->sum('late_in_minutes');
    }
}
