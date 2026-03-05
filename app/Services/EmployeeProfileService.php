<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\DepartmentApproverRepositoryInterface;
use App\Contracts\Repositories\GatepassRepositoryInterface;
use App\Contracts\Repositories\IssuedItemRepositoryInterface;
use App\Contracts\Repositories\ItemAccountabilityRepositoryInterface;
use App\Contracts\Repositories\LookupRepositoryInterface;
use App\Contracts\Repositories\NoticeSlipRepositoryInterface;
use App\Contracts\Repositories\SessionDetailRepositoryInterface;
use App\Contracts\Repositories\ShiftRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\AbsentNotice;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class EmployeeProfileService
{
    public function __construct(
        private readonly SessionDetailRepositoryInterface $sessionDetailRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly DepartmentApproverRepositoryInterface $departmentApproverRepository,
        private readonly GatepassRepositoryInterface $gatepassRepository,
        private readonly ShiftRepositoryInterface $shiftRepository,
        private readonly NoticeSlipRepositoryInterface $noticeSlipRepository,
        private readonly LookupRepositoryInterface $lookupRepository,
        private readonly IssuedItemRepositoryInterface $issuedItemRepository,
        private readonly ItemAccountabilityRepositoryInterface $itemAccountabilityRepository
    ) {}

    public function fetchProfilesPaginated(Request $request): LengthAwarePaginator
    {
        $userId = Auth::user()->user_id ?? '';
        $details = $this->sessionDetailRepository->getByUserId($userId);
        $designation = $details->designation ?? '';

        if (in_array($designation, ['Human Resources Head', 'Director of Operations', 'President', 'HR Payroll Assistant'])) {
            return $this->userRepository->getEmployeeProfilesPaginated(null, $request->q, 10);
        }

        $departments = $this->departmentApproverRepository->getByEmployeeId($userId);
        $depts = [];
        foreach ($departments as $row) {
            $depts[] = $row->department_id;
        }

        return $this->userRepository->getEmployeeProfilesPaginated($depts === [] ? null : $depts, $request->q, 10);
    }

    public function getViewProfileData(string $user_id): array
    {
        $employee_profile = $this->userRepository->getWithDepartmentDesignation($user_id);
        $regular_shift = $this->shiftRepository->getRegularShiftByUserId($user_id);
        $shifts = $this->shiftRepository->getAllShifts();
        $branch = $this->shiftRepository->getAllBranch();
        $pending_notices = $this->noticeSlipRepository->getPendingByUserId($user_id);
        $pending_gatepasses = $this->gatepassRepository->getPendingByUserId($user_id);
        $unreturned_items = $this->gatepassRepository->getUnreturnedByUserId($user_id);
        $departments = $this->lookupRepository->getAllDepartments();
        $designations = $this->lookupRepository->getAllDesignations();
        $itemlist = $this->issuedItemRepository->getByIssuedTo($user_id);

        $lastcodeID = $this->itemAccountabilityRepository->getLastItemId() ?? 0;
        $newcodeID = $lastcodeID + 1;
        $neww = date('Y').'00000';
        $newly = (int) $neww + $newcodeID;
        $newwwly = 'FUM'.'-'.$newly;

        $userId = Auth::user()->user_id ?? '';
        $sessionDetail = $this->sessionDetailRepository->getByUserId($userId);

        return [
            'employee_profile' => $employee_profile,
            'regular_shift' => $regular_shift,
            'designation' => $sessionDetail->designation ?? null,
            'department' => $sessionDetail->department ?? null,
            'pending_notices' => $pending_notices,
            'pending_gatepasses' => $pending_gatepasses,
            'unreturned_items' => $unreturned_items,
            'departments' => $departments,
            'designations' => $designations,
            'shifts' => $shifts,
            'itemlist' => $itemlist,
            'newwwly' => $newwwly,
            'user_id' => $user_id,
            'branch' => $branch,
        ];
    }

    public function getSessionDetail(string $column)
    {
        $userId = Auth::user()->user_id ?? '';
        $detail = $this->sessionDetailRepository->getByUserId($userId);

        return $detail->$column ?? null;
    }

    public function resetEmployeePassword(string $user_id): void
    {
        $user = User::where('user_id', $user_id)->first();
        if ($user) {
            $user->password = bcrypt('fumaco');
            $user->save();
        }
    }

    public function updateEmployeeProfile(Request $request): void
    {
        $employee = User::where('user_id', $request->user_id)->first();
        if (! $employee) {
            return;
        }
        $employee->employee_name = $request->employee_name;
        $employee->birth_date = $request->birth_date;
        $employee->address = $request->address;
        $employee->contact_no = $request->contact_no;
        $employee->sss_no = $request->sss_no;
        $employee->tin_no = $request->tin_no;
        $employee->civil_status = $request->civil_status;
        $employee->nick_name = $request->nick_name;
        $employee->designation_id = $request->designation;
        $employee->department_id = $request->department;
        $employee->employment_status = $request->employment_status;
        $employee->telephone = $request->telephone;
        $employee->email = $request->email;
        $employee->status = $request->status;
        $employee->user_group = $request->user_group;
        $employee->save();
    }

    public function approveAbsentNotice(int $notice_id): void
    {
        $notice = AbsentNotice::find($notice_id);
        if ($notice) {
            $notice->status = 'Approved';
            $notice->save();
        }
    }

    /** @return array{success: bool, logout: bool} */
    public function changePassword(Request $request): array
    {
        $employee = User::where('user_id', Auth::user()->user_id)->first();
        if (! $employee || ! Hash::check($request->current_pass, $employee->password)) {
            return ['success' => false, 'logout' => false];
        }
        $employee->password = bcrypt($request->new_pass);
        $employee->save();

        return ['success' => true, 'logout' => true];
    }

    public function getNotices(string $employee_id): Collection
    {
        return DB::table('notice_slip')
            ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
            ->where('notice_slip.user_id', '=', $employee_id)
            ->orderBy('notice_slip.notice_id', 'desc')
            ->select('users.*', 'notice_slip.*', 'departments.department', 'leave_types.leave_type')
            ->get();
    }

    public function getGatepass(string $employee_id): Collection
    {
        return DB::table('gatepass')
            ->join('users', 'users.user_id', '=', 'gatepass.user_id')
            ->where('gatepass.user_id', '=', $employee_id)
            ->orderBy('gatepass.gatepass_id', 'desc')
            ->select('users.*', 'gatepass.*')
            ->get();
    }

    public function getLeaves(string $employee_id, string $year): Collection
    {
        return DB::table('employee_leaves')
            ->join('leave_types', 'leave_types.leave_type_id', '=', 'employee_leaves.leave_type_id')
            ->where('employee_leaves.employee_id', '=', $employee_id)
            ->where('employee_leaves.year', '=', $year)
            ->get();
    }

    public function getExams(string $employee_id): Collection
    {
        return DB::table('exams')
            ->join('examinee', 'examinee.exam_id', '=', 'exams.exam_id')
            ->join('users', 'examinee.user_id', '=', 'users.id')
            ->join('exam_group', 'exams.exam_group_id', '=', 'exam_group.exam_group_id')
            ->where('examinee.user_id', $employee_id)
            ->orderBy('validity_date', 'desc')
            ->orderBy('date_of_exam', 'desc')
            ->get();
    }

    public function getEvaluations(string $employee_id): Collection
    {
        return DB::table('evaluation_files')
            ->join('users', 'users.user_id', '=', 'evaluation_files.employee_id')
            ->where('employee_id', $employee_id)
            ->select('users.employee_name', 'evaluation_files.*', DB::raw('(SELECT employee_name FROM users WHERE user_id = evaluation_files.evaluated_by) as evaluated_by'))
            ->orderBy('id', 'desc')
            ->get();
    }
}
