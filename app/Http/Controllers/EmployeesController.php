<?php

namespace App\Http\Controllers;

use App\Events\EmployeeLifecycleActionTriggered;
use App\Models\Department;
use App\Models\Designation;
use App\Models\ItemAccountability;
use App\Models\User;
use App\Traits\EmailsTrait;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class EmployeesController extends Controller
{
    use EmailsTrait;

    private function triggerWelcomeEmail(User $employee): void
    {
        if (! $employee->id) {
            return;
        }

        event(new EmployeeLifecycleActionTriggered((int) $employee->id, 'welcome'));
    }

    private function triggerOnboardingEmail(User $employee): void
    {
        if (! $employee->id) {
            return;
        }

        event(new EmployeeLifecycleActionTriggered((int) $employee->id, 'onboarding'));
    }

    private function triggerOffboardingEmail(User $employee): void
    {
        if (! $employee->id) {
            return;
        }

        event(new EmployeeLifecycleActionTriggered((int) $employee->id, 'offboarding'));
    }

    /**
     * Build display name as "First Middle Last" from split name columns.
     */
    private function composeEmployeeFullName(?string $first, ?string $middle, ?string $last): string
    {
        $parts = [];
        if ($first !== null && trim($first) !== '') {
            $parts[] = trim($first);
        }
        if ($middle !== null && trim((string) $middle) !== '') {
            $parts[] = trim((string) $middle);
        }
        if ($last !== null && trim($last) !== '') {
            $parts[] = trim($last);
        }

        return implode(' ', $parts);
    }

    /**
     * `users.telephone` is int(10); strip non-digits and store as integer or null.
     */
    private function normalizeUsersTelephoneInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        $digits = preg_replace('/\D/', '', (string) $value);
        if ($digits === '') {
            return null;
        }
        $n = (int) $digits;
        if ($n > 2147483647) {
            $n = (int) substr($digits, 0, 9);
        }

        return $n;
    }

    private function truncateUtf8(?string $value, int $max): ?string
    {
        if ($value === null) {
            return null;
        }
        if (mb_strlen($value) <= $max) {
            return $value;
        }

        return mb_substr($value, 0, $max);
    }

    public function index()
    {
        $employees = DB::table('users')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->join('designation', 'designation.des_id', '=', 'users.designation_id')
            // ->select("users.*", "departments.department", 'designation.designation')
            ->where('users.user_type', '=', 'Employee')->orderBy('users.employee_name', 'ASC')
            ->get();

        $departments = DB::table('departments')->get();
        $designations = DB::table('designation')->get();
        $shifts = DB::table('shifts')->get();
        $branch = DB::table('branch')->get();

        return view('admin.employee.index', compact('employees', 'departments', 'designations', 'shifts', 'branch'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'string', 'max:255'],
            'employee_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:4'],
            'email' => ['required', 'email', 'max:255'],
            'department' => ['nullable'],
            'designation' => ['nullable'],
            'shift' => ['nullable'],
            'branch' => ['nullable'],
            'employment_status' => ['nullable', 'in:Regular,Contractual,Probationary'],
            'civil_status' => ['nullable', 'in:Single,Married,Widowed'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'contact_no' => ['nullable', 'string', 'max:30'],
            'date_joined' => ['nullable', 'date'],
            'company' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $employee = new User;
            $employee->user_id = $request->user_id;
            $employee->department_id = $request->department;
            $employee->shift_group_id = $request->shift;
            $employee->password = bcrypt($request->password);
            $employee->employee_name = $request->employee_name;
            $employee->nick_name = $request->nickname;
            $employee->designation_id = $request->designation;
            $employee->branch = $request->branch;
            $employee->telephone = $this->normalizeUsersTelephoneInt($request->telephone);
            $employee->email = $request->email;
            $employee->user_type = 'Employee';
            $employee->employment_status = $request->employment_status;
            $employee->address = $request->address;
            $employee->contact_no = $this->truncateUtf8(trim((string) $request->contact_no), 190);
            $employee->sss_no = $request->sss_no;
            $employee->tin_no = $request->tin_no;
            $employee->user_group = $request->user_group;
            $employee->birth_date = $request->birthdate;
            $employee->civil_status = $request->civil_status ?: 'Single';
            $employee->payroll_type = $request->payroll_type;
            $employee->company = $this->truncateUtf8($request->company ?: 'FUMACO Inc.', 100);
            $employee->status = 'Active';
            $employee->save();

            $department = Department::find($employee->department_id);
            $designation = Designation::find($employee->designation_id);

            // Welcome email trigger (listener handles queue/later + safeguards).
            $this->triggerWelcomeEmail($employee);

            DB::commit();

            return redirect()->back()->with(['message' => 'Employee <b>'.$employee->employee_name.'</b>  has been successfully added!']);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Employee store failed.', [
                'user_id' => $request->user_id ?? null,
                'employee_name' => $request->employee_name ?? null,
                'error' => $th->getMessage(),
            ]);

            // throw $th;
            return redirect()->back()->with(['message' => 'An error occured. Please try again.']);
        }

    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $image_path = $request->user_image;
            if ($request->hasFile('empImage')) {
                $request->validate([
                    'empImage' => ['file', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
                ]);

                $file = $request->file('empImage');

                // get filename with extension
                $filenamewithextension = $file->getClientOriginalName();
                // get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                // get file extension
                $extension = $file->getClientOriginalExtension();
                // filename to store
                $filenametostore = $request->user_id.'.'.$extension;

                try {
                    $disk = Storage::disk('upcloud');

                    $image = Image::make($file->getRealPath())
                        ->resize(500, 350, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->encode($extension, 85);

                    $disk->put('employees/'.$filenametostore, (string) $image, [
                        'visibility' => 'public',
                    ]);

                    // Store only relative key in DB
                    $image_path = 'employees/'.$filenametostore;
                } catch (\Throwable $e) {
                    Log::error('UpCloud upload failed (employee update photo)', [
                        'user_id' => $request->user_id ?? null,
                        'original_name' => $filenamewithextension,
                        'error' => $e->getMessage(),
                    ]);

                    return redirect()->back()->with(['message' => 'Image upload failed. Please try again.']);
                }
            }

            $employee = User::find($request->id);
            $previousStatus = (string) ($employee->status ?? '');
            $employee->user_id = $request->user_id;
            $employee->department_id = $request->department;
            $employee->shift_group_id = $request->shift;
            $employee->employee_name = $request->employee_name;
            $employee->nick_name = $request->nickname;
            $employee->designation_id = $request->designation;
            $employee->branch = $request->branch;
            $employee->telephone = $this->normalizeUsersTelephoneInt($request->telephone);
            $employee->email = $request->email;
            $employee->employment_status = $request->employment_status;
            $employee->address = $request->address;
            $employee->contact_no = $this->truncateUtf8(trim((string) $request->contact_no), 190);
            $employee->sss_no = $request->sss_no;
            $employee->tin_no = $request->tin_no;
            $employee->gender = $request->gender;
            $employee->user_group = $request->user_group;
            $employee->birth_date = $request->birthdate;
            $employee->civil_status = $request->civil_status ?: 'Single';
            $employee->status = $request->status;
            $employee->date_joined = $request->date_joined;
            $employee->contact_person = $request->contact_person;
            $employee->contact_person_no = $request->contact_person_no;
            $employee->pagibig_no = $request->pagibig_no;
            $employee->philhealth_no = $request->philhealth_no;
            $employee->employee_id = $request->employee_id;
            $employee->image = $image_path;
            $employee->id_security_key = $request->id_key;
            $employee->designation_name = $request->designation_name;
            $employee->last_modified_by = Auth::user()->employee_name;
            $employee->payroll_type = $request->payroll_type;

            $employee->separation_date = $request->filled('separation_date') ? $request->separation_date : null;
            $employee->separation_type = $request->filled('separation_type')
                ? $this->truncateUtf8(trim((string) $request->separation_type), 50)
                : null;
            $employee->separation_reason = $request->filled('separation_reason')
                ? $this->truncateUtf8(trim((string) $request->separation_reason), 65535)
                : null;
            $employee->clearance_status = $request->filled('clearance_status')
                ? $this->truncateUtf8(trim((string) $request->clearance_status), 20)
                : null;

            if ($request->status == 'Resigned') {
                $employee->resignation_date = $request->resignation_date;

                $department = DB::table('departments')->where('department_id', $employee->department_id)->pluck('department')->first();
                $designation = DB::table('designation')->where('des_id', $employee->designation_id)->pluck('designation')->first();
                $branch = DB::table('branch')->where('branch_id', $employee->branch)->pluck('branch_name')->first();
                $reporting_to = DB::table('users')->where('id', $employee->reporting_to)->pluck('employee_name')->first();

                $data = [
                    'employee_id' => $employee->user_id,
                    'biometric_id' => null,
                    'name' => $employee->employee_name,
                    'department' => $department,
                    'designation' => $designation,
                    'reporting_to' => $reporting_to,
                    'location' => $branch,
                    'resignation_date' => $employee->resignation_date,
                ];

                $log = [
                    'type' => 'Resigned Employee Notice',
                    'recipient' => env('MAIL_RECIPIENT', 'it@fumaco.local'),
                    'subject' => '[Action Required] Resigned Employee',
                    'template' => 'admin.email_template.resigned_employee',
                    'template_data' => json_encode($data),
                ];

                try {
                    $mail = $this->send_mail('WELCOME EMAIL ['.strtoupper($employee->employee_name).']', 'admin.email_template.resigned_employee', $employee->email, $data, $log);
                } catch (\Throwable $th) {
                }
            } else {
                $employee->resignation_date = null;
            }

            $employee->save();

            // Offboarding email: trigger when status becomes "For Offboarding" (queued).
            $newStatus = (string) ($employee->status ?? '');
            if (
                strtoupper(trim($previousStatus)) !== 'FOR OFFBOARDING'
                && strtoupper(trim($newStatus)) === 'FOR OFFBOARDING'
                && empty($employee->offboarding_email_sent_at)
            ) {
                $this->triggerOffboardingEmail($employee);
            }

            // Re-trigger welcome if joining date was added/updated and not yet sent.
            $this->triggerWelcomeEmail($employee);

            DB::commit();

            return redirect()->back()->with(['message' => 'Employee <b>'.$employee->employee_name.'</b>  has been successfully updated!']);
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();

            return redirect()->back()->with(['message' => 'An error occured. Please try again later.']);
        }

    }

    public function delete(Request $request)
    {
        $employee = User::find($request->id);
        $employee->delete();

        return redirect()->back()->with(['message' => 'Employee <b>'.$request->employee_name.'</b>  has been successfully deleted!']);
    }

    public function reset_password(Request $request)
    {
        $employee = User::find($request->id);
        $employee->password = bcrypt('fumaco');
        $employee->last_modified_by = Auth::user()->employee_name;
        $employee->save();

        return redirect()->back()->with(['message' => 'Employee password for <b>'.$request->employee_name.'</b>  has been successfully reset to <b>"fumaco"</b>!']);
    }

    public function reset_leaves(Request $request)
    {
        $reset_leave = DB::table('employee_leaves')->where('employee_id', $request->id)->update(['remaining' => DB::raw('total')]);

        return redirect()->back()->with(['message' => 'Remaining no. of leave(s) for <b>'.$request->employee_name.'</b>  has been reset!']);
    }

    public function adminList()
    {
        $admins = DB::table('admins')->get();

        return view('admin.admin.index')->with('admins', $admins);
    }

    public function storeAdmin(Request $request)
    {
        $data = [
            'name' => $request->username,
            'access_id' => $request->access_id,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ];

        $admin = DB::table('admins')->insert($data);

        return redirect()->back()->with(['message' => 'Admin <b>'.$request->username.'</b>  has been added!']);
    }

    public function updateAdmin(Request $request)
    {
        $data = [
            'name' => $request->username,
            'access_id' => $request->access_id,
            'email' => $request->email,
        ];

        $admin = DB::table('admins')->where('id', $request->id)->update($data);

        return redirect()->back()->with(['message' => 'Admin <b>'.$request->username.'</b>  has been updated!']);
    }

    public function deleteAdmin(Request $request)
    {
        $admin = DB::table('admins')->where('id', $request->id)->delete();

        return redirect()->back()->with(['message' => 'Admin <b>'.$request->username.'</b>  has been deleted!']);
    }

    public function reset_admin_password(Request $request)
    {
        $admin = DB::table('admins')->where('id', $request->id)->update(['password' => bcrypt('fumaco')]);

        return redirect()->back()->with(['message' => 'Admin password for <b>'.$request->username.'</b>  has been successfully reset to <b>"fumaco"</b>!']);
    }

    public function sessionDetails($column)
    {
        $detail = DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', Auth::user()->user_id)
            ->first();

        return $detail->$column;
    }

    public function showEmployees()
    {
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $employees = DB::table('users')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->join('designation', 'designation.des_id', '=', 'users.designation_id')
            ->select('users.*', 'departments.department', 'designation.designation')
            ->where('users.user_type', '=', 'Employee')->orderBy('users.employee_name', 'ASC')
            ->get();

        $departments = DB::table('departments')->get();
        $designations = DB::table('designation')->get();
        $shifts = DB::table('shift_groups')->get();
        $branch = DB::table('branch')->get();

        $companies = DB::connection('mysql_erp')->table('tabCompany')->pluck('company_name');

        $departmentHeadUserIds = DB::table('department_head_list')->pluck('employee_id')->unique()->values();
        $regular_employees = collect($employees)
            ->filter(function ($employee) use ($departmentHeadUserIds) {
                $isActiveEmployee = ($employee->status ?? null) === 'Active' && ($employee->user_type ?? null) === 'Employee';
                $isRegular = ($employee->employment_status ?? null) === 'Regular';
                $isDepartmentHead = $departmentHeadUserIds->contains($employee->user_id);

                return $isActiveEmployee && ($isRegular || $isDepartmentHead);
            })
            ->sortBy('employee_name')
            ->values();

        $data = [
            'employees' => $employees,
            'departments' => $departments,
            'designations' => $designations,
            'shifts' => $shifts,
            'branch' => $branch,
            'department' => $department,
            'designation' => $designation,
            'regular_employees' => $regular_employees,
            'companies' => $companies,
        ];

        return view('client.modules.human_resource.employees.index', $data);
    }

    public function getEmployeeDetails($id)
    {
        $details = DB::table('users')->where('id', $id)->first();

        return response()->json($details);
    }

    public function employeeCreate(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'string', 'max:10'],
            'employee_id' => ['required', 'string', 'max:20'],
            'employee_first_name' => ['required', 'string', 'max:2000'],
            'employee_middle_name' => ['nullable', 'string', 'max:2000'],
            'employee_last_name' => ['required', 'string', 'max:2000'],
            'nick_name' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:4'],
            'email' => ['required', 'email', 'max:191'],
            'gender' => ['required', 'in:Male,Female'],
            'civil_status' => ['required', 'in:Single,Married,Widowed'],
            'employment_status' => ['required', 'in:Regular,Contractual,Probationary'],
            'user_group' => ['required', 'in:Employee,Manager,HR Personnel,Editor'],
            'date_joined' => ['required', 'date'],
            'company' => ['required', 'string', 'max:100'],
            'department_id' => ['required'],
            'designation_id' => ['required'],
            'shift_group_id' => ['required'],
            'branch' => ['required'],
            'reporting_to' => ['required', 'string', 'max:10'],
            'payroll_type' => ['required', 'in:Weekly,Monthly'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'contact_no' => ['required', 'string', 'max:190'],
            'contact_person' => ['required', 'string', 'max:100'],
            'contact_person_no' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date'],
            'address' => ['required', 'string', 'max:190'],
            'barangay' => ['required', 'string', 'max:2000'],
            'city' => ['required', 'string', 'max:2000'],
        ]);

        DB::beginTransaction();
        try {
            if (User::where('user_id', $request->user_id)->exists()) {
                return redirect()->back()->with('error', 'User ID already exists.')->withInput();
            }

            if (Str::contains($request->email, '@fumaco.local') && User::where('email', $request->email)->exists()) {
                return redirect()->back()->with('error', 'Email already exists.')->withInput();
            }

            if (User::where('employee_id', $request->employee_id)->exists()) {
                return redirect()->back()->with('error', 'Employee ID already exists.')->withInput();
            }

            $image_path = null;
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => ['file', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
                ]);

                $file = $request->file('image');

                // get filename with extension
                $filenamewithextension = $file->getClientOriginalName();
                // get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                // get file extension
                $extension = $file->getClientOriginalExtension();
                // filename to store
                $filenametostore = $request->user_id.'.'.$extension;

                try {
                    $disk = Storage::disk('upcloud');

                    $image = Image::make($file->getRealPath())
                        ->resize(500, 350, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->encode($extension, 85);

                    $disk->put('employees/'.$filenametostore, (string) $image, [
                        'visibility' => 'public',
                    ]);

                    // Store only relative key in DB
                    $image_path = 'employees/'.$filenametostore;
                } catch (\Throwable $e) {
                    Log::error('UpCloud upload failed (employee create photo)', [
                        'user_id' => $request->user_id ?? null,
                        'original_name' => $filenamewithextension,
                        'error' => $e->getMessage(),
                    ]);

                    return redirect()->back()->with(['message' => 'Image upload failed. Please try again.']);
                }
            }

            $first = trim((string) $request->input('employee_first_name'));
            $middleRaw = $request->employee_middle_name;
            $middle = (is_string($middleRaw) && trim($middleRaw) !== '') ? trim($middleRaw) : null;
            $last = trim((string) $request->input('employee_last_name'));

            $fullName = $this->composeEmployeeFullName($first, $middle, $last);
            if ($fullName === '') {
                return redirect()->back()->with('error', 'Employee full name could not be built from first and last name.')->withInput();
            }

            $designationName = $request->designation_name;
            if (! is_string($designationName) || trim($designationName) === '') {
                $designationName = Designation::where('des_id', $request->designation_id)->value('designation');
            }
            $designationName = $this->truncateUtf8(is_string($designationName) ? trim($designationName) : null, 100);

            $employee = new User;
            $employee->user_id = $this->truncateUtf8(trim((string) $request->user_id), 10);
            $employee->department_id = $request->department_id;
            $employee->shift_group_id = $request->shift_group_id;
            $employee->password = bcrypt($request->password);
            $employee->employee_first_name = $this->truncateUtf8($first, 2000);
            $employee->employee_middle_name = $middle !== null ? $this->truncateUtf8($middle, 2000) : null;
            $employee->employee_last_name = $this->truncateUtf8($last, 2000);
            $employee->employee_name = $this->truncateUtf8($fullName, 191);
            $employee->nick_name = $this->truncateUtf8(trim((string) $request->input('nick_name')), 100);
            $employee->designation_id = $request->designation_id;
            $employee->branch = $request->branch;
            $employee->telephone = $this->normalizeUsersTelephoneInt($request->telephone);
            $employee->email = $this->truncateUtf8(trim((string) $request->email), 191);
            $employee->user_type = 'Employee';
            $employee->gender = $request->gender;
            $employee->employment_status = $request->employment_status;
            $employee->address = $this->truncateUtf8(trim((string) $request->address), 190);
            $employee->barangay = $this->truncateUtf8(trim((string) $request->barangay), 2000);
            $employee->city = $this->truncateUtf8(trim((string) $request->city), 2000);
            $employee->contact_no = $this->truncateUtf8(trim((string) $request->contact_no), 190);
            $employee->user_group = $request->user_group;
            $employee->birth_date = $request->birth_date;
            $employee->civil_status = $request->civil_status ?: 'Single';
            $employee->date_joined = $request->date_joined;
            $employee->contact_person = $this->truncateUtf8(trim((string) $request->contact_person), 100);
            $employee->contact_person_no = $this->truncateUtf8(trim((string) $request->contact_person_no), 100);
            $employee->pagibig_no = $this->truncateUtf8($request->filled('pagibig_no') ? trim((string) $request->pagibig_no) : null, 20);
            $employee->philhealth_no = $this->truncateUtf8($request->filled('philhealth_no') ? trim((string) $request->philhealth_no) : null, 20);
            $employee->employee_id = $this->truncateUtf8(trim((string) $request->employee_id), 20);
            $employee->reporting_to = $this->truncateUtf8(trim((string) $request->reporting_to), 10);
            $employee->image = $image_path;
            $employee->designation_name = $designationName;
            $employee->status = 'Active';
            $employee->id_security_key = $this->truncateUtf8($request->filled('id_security_key') ? trim((string) $request->id_security_key) : null, 100);
            $employee->payroll_type = $this->truncateUtf8($request->filled('payroll_type') ? trim((string) $request->payroll_type) : null, 255);
            $employee->company = $this->truncateUtf8($request->filled('company') ? trim((string) $request->company) : 'FUMACO Inc.', 100);
            $employee->sss_no = $this->truncateUtf8($request->filled('sss_no') ? trim((string) $request->sss_no) : null, 20);
            $employee->tin_no = $this->truncateUtf8($request->filled('tin_no') ? trim((string) $request->tin_no) : null, 20);
            $employee->save();

            $department = Department::find($employee->department_id);
            $designation = Designation::find($employee->designation_id);
            $reporting_to = User::find($employee->reporting_to);
            $branch = DB::table('branch')->where('branch_id', $employee->branch)->pluck('branch_name')->first();

            // Welcome email trigger (listener handles queue/later + safeguards).
            $this->triggerWelcomeEmail($employee);

            // Onboarding email trigger.
            $this->triggerOnboardingEmail($employee);

            DB::commit();

            return redirect()->back()->with(['message' => 'Employee <b>'.$employee->employee_name.'</b>  has been successfully added!']);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Employee create failed.', [
                'user_id' => $request->user_id ?? null,
                'employee_first_name' => $request->employee_first_name ?? null,
                'employee_last_name' => $request->employee_last_name ?? null,
                'error' => $th->getMessage(),
                'exception' => $th,
            ]);

            $msg = 'An error occurred while saving the employee.';
            if (app()->environment('local') || config('app.debug')) {
                $msg .= ' '.$th->getMessage();
            }

            return redirect()->back()->with('error', $msg)->withInput();
        }

    }

    public function employeeUpdate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $image_path = $request->user_image;
            if ($request->hasFile('empImage')) {
                $request->validate([
                    'empImage' => ['file', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
                ]);
                $file = $request->file('empImage');

                // get filename with extension
                $filenamewithextension = $file->getClientOriginalName();
                // get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                // get file extension
                $extension = $file->getClientOriginalExtension();
                // filename to store
                $filenametostore = $request->user_id.'.'.$extension;
                try {
                    $disk = Storage::disk('upcloud');

                    $image = Image::make($file->getRealPath())
                        ->resize(500, 350, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->encode($extension, 85);

                    $disk->put('employees/'.$filenametostore, (string) $image, [
                        'visibility' => 'public',
                    ]);

                    // Store only relative key in DB
                    $image_path = 'employees/'.$filenametostore;
                } catch (\Throwable $e) {
                    Log::error('UpCloud upload failed (employeeUpdate photo)', [
                        'id' => $id,
                        'user_id' => $request->user_id ?? null,
                        'original_name' => $filenamewithextension,
                        'error' => $e->getMessage(),
                    ]);

                    return redirect()->back()->with(['message' => 'Image upload failed. Please try again.']);
                }
            }

            $employee = User::find($id);
            $previousStatus = (string) ($employee->status ?? '');
            $employee->user_id = $request->user_id;
            $employee->department_id = $request->department;
            $employee->shift_group_id = $request->shift;
            $employee->employee_name = $request->employee_name;
            $employee->nick_name = $request->nickname;
            $employee->designation_id = $request->designation;
            $employee->branch = $request->branch;
            $employee->telephone = $this->normalizeUsersTelephoneInt($request->telephone);
            $employee->email = $request->email;
            $employee->employment_status = $request->employment_status;
            $employee->address = $request->address;
            $employee->contact_no = $this->truncateUtf8(trim((string) $request->contact_no), 190);
            $employee->sss_no = $request->sss_no;
            $employee->tin_no = $request->tin_no;
            $employee->user_group = $request->user_group;
            $employee->birth_date = $request->birthdate;
            $employee->civil_status = $request->civil_status ?: 'Single';
            $employee->status = $request->status;
            $employee->gender = $request->gender;
            $employee->date_joined = $request->date_joined;
            $employee->contact_person = $request->contact_person;
            $employee->contact_person_no = $request->contact_person_no;
            $employee->pagibig_no = $request->pagibig_no;
            $employee->philhealth_no = $request->philhealth_no;
            $employee->employee_id = $request->employee_id;
            $employee->reporting_to = $request->reporting_to;
            $employee->image = $image_path;
            $employee->id_security_key = $request->id_key;
            $employee->designation_name = $request->designation_name;
            $employee->last_modified_by = Auth::user()->employee_name;
            $employee->company = $request->company ?: ($employee->company ?: 'FUMACO Inc.');

            $employee->separation_date = $request->filled('separation_date') ? $request->separation_date : null;
            $employee->separation_type = $request->filled('separation_type')
                ? $this->truncateUtf8(trim((string) $request->separation_type), 50)
                : null;
            $employee->separation_reason = $request->filled('separation_reason')
                ? $this->truncateUtf8(trim((string) $request->separation_reason), 65535)
                : null;
            $employee->clearance_status = $request->filled('clearance_status')
                ? $this->truncateUtf8(trim((string) $request->clearance_status), 20)
                : null;

            if ($request->status == 'Resigned') {
                $employee->resignation_date = $request->resignation_date;

                $department = DB::table('departments')->where('department_id', $employee->department_id)->pluck('department')->first();
                $designation = DB::table('designation')->where('des_id', $employee->designation_id)->pluck('designation')->first();
                $branch = DB::table('branch')->where('branch_id', $employee->branch)->pluck('branch_name')->first();
                $reporting_to = DB::table('users')->where('id', $employee->reporting_to)->pluck('employee_name')->first();

                $data = [
                    'employee_id' => $employee->employee_id,
                    'biometric_id' => $employee->user_id,
                    'name' => $employee->employee_name,
                    'department' => $department,
                    'designation' => $designation,
                    'reporting_to' => $reporting_to,
                    'location' => $branch,
                    'resignation_date' => $employee->resignation_date,
                ];

                $log = [
                    'type' => 'Resigned Employee Notice',
                    'recipient' => env('MAIL_RECIPIENT', 'it@fumaco.local'),
                    'subject' => '[Action Required] Resigned Employee',
                    'template' => 'admin.email_template.resigned_employee',
                    'template_data' => json_encode($data),
                ];

                try {
                    $mail = $this->send_mail($log['subject'], $log['template'], $log['recipient'], $data, $log);
                } catch (\Throwable $th) {
                }
            } else {
                $employee->resignation_date = null;
            }

            $employee->save();

            // Offboarding email: trigger when status becomes "For Offboarding" (queued).
            $newStatus = (string) ($employee->status ?? '');
            if (
                strtoupper(trim($previousStatus)) !== 'FOR OFFBOARDING'
                && strtoupper(trim($newStatus)) === 'FOR OFFBOARDING'
                && empty($employee->offboarding_email_sent_at)
            ) {
                $this->triggerOffboardingEmail($employee);
            }

            // Re-trigger welcome if joining date was added/updated and not yet sent.
            $this->triggerWelcomeEmail($employee);

            DB::commit();

            return redirect()->back()->with(['message' => 'Employee <b>'.$employee->employee_name.'</b>  has been successfully updated!']);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Employee update failed.', [
                'id' => $id,
                'user_id' => $request->user_id ?? null,
                'employee_name' => $request->employee_name ?? null,
                'error' => $th->getMessage(),
            ]);

            return redirect()->back()->with(['message' => 'An error occured. Please try again.']);
        }

    }

    public function employeeDelete(Request $request, $id)
    {
        $employee = User::find($id);
        $employee->delete();

        return redirect()->back()->with(['message' => 'Employee <b>'.$request->employee_name.'</b>  has been successfully deleted!']);
    }

    public function employeeProfile(Request $request, $user_id)
    {
        $employee_profile = User::join('departments', 'users.department_id', 'departments.department_id')
            ->join('designation', 'users.designation_id', 'designation.des_id')
            ->where('user_id', $user_id)
            ->select('users.*', 'departments.department', 'designation.designation')
            ->first();

        $approvers = DB::table('department_approvers')
            ->join('users', 'users.user_id', 'department_approvers.employee_id')
            ->join('designation', 'designation.des_id', 'users.designation_id')
            ->where('department_approvers.department_id', $employee_profile->department_id)->get();

        $regular_shift = DB::table('shifts')
            ->join('users', 'shifts.shift_id', '=', 'users.shift_group_id')
            ->where('user_id', $user_id)
            ->select('shift_schedule')
            ->first();

        $shifts = DB::table('shift_groups')->get();
        $branch = DB::table('branch')->get();

        $pending_notices = DB::table('notice_slip')
            ->join('leave_types', 'notice_slip.leave_type_id', 'leave_types.leave_type_id')
            ->where('user_id', $user_id)
            ->where('status', 'For Approval')
            ->select('notice_slip.*', 'leave_type')
            ->get();

        $pending_gatepasses = DB::table('gatepass')
            ->where('user_id', $user_id)
            ->where('status', 'For Approval')
            ->get();

        $unreturned_items = DB::table('gatepass')
            ->where('user_id', $user_id)
            ->where('item_status', 'Unreturned')
            ->get();

        $departments = DB::table('departments')->get();
        $designations = DB::table('designation')->get();

        $itemlist = DB::table('issued_to_employee')
            ->where('issued_to', $user_id)
            ->get();

        $employee_leaves = DB::table('employee_leaves')
            ->join('users', 'employee_leaves.employee_id', '=', 'users.user_id')
            ->join('leave_types', 'employee_leaves.leave_type_id', '=', 'leave_types.leave_type_id')
            ->select('employee_leaves.*', 'users.employee_name', 'leave_types.leave_type');

        $employees = DB::table('users')->get();
        $leave_types = DB::table('leave_types')->get();

        $end_year = date('Y') + 1;
        $year_list = [];
        for ($x = 2018; $x <= $end_year; $x++) {
            array_push($year_list, $x);
        }

        if ($request->ajax()) {
            $employee_leaves = $employee_leaves->where('year', $request->year)->get();

            return response()->json($employee_leaves);
        }

        $training = DB::table('training')
            ->join('training_attendees', 'training_attendees.training_id', '=', 'training.training_id')
            ->select('training.training_title', 'training.training_desc', 'training.training_date', 'training.date_submitted', 'training.proposed_by', 'training.status', 'training.training_id', 'training.department_name')
            ->where('training_attendees.user_id', $user_id)
            ->where('training.status', 'Implemented')
            ->get();

        $code = new ItemAccountability;
        $lastcodeID = $code->orderBy('item_id', 'DESC')->pluck('item_id')->first();
        $newcodeID = $lastcodeID + 1;
        $neww = date('Y').'00000';
        $newly = $neww + $newcodeID;
        $newwwly = 'FUM'.'-'.$newly;

        $companies = DB::connection('mysql_erp')->table('tabCompany')->pluck('company_name');
        $departmentHeadUserIds = DB::table('department_head_list')->pluck('employee_id')->unique()->values();
        $regular_employees = collect($employees)
            ->filter(function ($employee) use ($departmentHeadUserIds) {
                $isActiveEmployee = ($employee->status ?? null) === 'Active' && ($employee->user_type ?? null) === 'Employee';
                $isRegular = ($employee->employment_status ?? null) === 'Regular';
                $isDepartmentHead = $departmentHeadUserIds->contains($employee->user_id);

                return $isActiveEmployee && ($isRegular || $isDepartmentHead);
            })
            ->sortBy('employee_name')
            ->values();
        $data = [
            'employee_profile' => $employee_profile,
            'regular_shift' => $regular_shift,
            'designation' => $this->sessionDetails('designation'),
            'department' => $this->sessionDetails('department'),
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
            'approvers' => $approvers,
            'year_list' => $year_list,
            'training' => $training,
            'companies' => $companies,
            'regular_employees' => $regular_employees,
        ];

        return view('client.modules.human_resource.employees.profile')->with($data);
    }

    public function updateEmployeeProfilePhoto(Request $request, $user_id)
    {
        $authUser = Auth::user();
        if (! $authUser) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        $validated = $request->validate([
            'empImage' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // 5MB
        ]);

        $file = $request->file('empImage');
        if (! $file) {
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded.',
            ], 422);
        }

        // Store only the relative path in DB (not full URL).
        // Encode as JPG for a consistent extension.
        $path = 'employees/profile/'.(string) $user_id.'.jpg';

        try {
            $disk = Storage::disk('upcloud');

            $employee = User::where('user_id', $user_id)->first();
            $oldImage = $employee?->image ? (string) $employee->image : null;

            $image = Image::make($file->getRealPath())
                ->resize(500, 350, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

            $encoded = $image->encode('jpg', 85);

            $disk->put($path, (string) $encoded, [
                'visibility' => 'public',
                'ContentType' => 'image/jpeg',
            ]);

            $imageUrl = $disk->url($path).'?v='.time();

            // Delete previous UpCloud file if it was a relative key and changed.
            // (If it was a full URL, we don't attempt deletion here.)
            if (
                $oldImage
                && ! Str::startsWith($oldImage, ['http://', 'https://', '/storage/', 'storage/'])
                && ltrim($oldImage, '/') !== ltrim($path, '/')
            ) {
                try {
                    $deleted = $disk->delete(ltrim($oldImage, '/'));
                    if (! $deleted) {
                        Log::warning('UpCloud delete returned false (employee profile photo)', [
                            'user_id' => $user_id,
                            'old_image' => $oldImage,
                        ]);
                    }
                } catch (\Throwable $deleteEx) {
                    Log::error('UpCloud delete failed (employee profile photo)', [
                        'user_id' => $user_id,
                        'old_image' => $oldImage,
                        'error' => $deleteEx->getMessage(),
                    ]);
                }
            }

            if ($employee) {
                $employee->image = $path;
                $employee->last_modified_by = $authUser->employee_name ?? null;
                $employee->save();
            }

            return response()->json([
                'success' => true,
                'image_url' => $imageUrl,
                'path' => $path,
            ]);
        } catch (\Throwable $e) {
            Log::error('UpCloud upload failed (employee profile photo)', [
                'user_id' => $user_id,
                'exception' => get_class($e),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Image upload failed. Please try again.',
                'error_detail' => app()->environment('local') || config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function hireApplicant(Request $request, $id)
    {
        $request->validate([
            'user_id' => ['required', 'string', 'max:255'],
            'employee_id' => ['required', 'string', 'max:255'],
            'employee_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:4'],
            'email' => ['required', 'email', 'max:255'],
            'department' => ['required'],
            'designation' => ['required'],
            'shift' => ['required'],
            'branch' => ['required'],
            'date_joined' => ['required', 'date'],
            'gender' => ['required', 'in:Male,Female'],
            'civil_status' => ['required', 'in:Single,Married,Widowed'],
            'employment_status' => ['required', 'in:Regular,Contractual,Probationary'],
            'user_group' => ['required', 'in:Employee,Manager,HR Personnel,Editor'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'contact_no' => ['required', 'string', 'max:190'],
            'contact_person' => ['required', 'string', 'max:100'],
            'contact_person_no' => ['required', 'string', 'max:100'],
            'birthdate' => ['required', 'date'],
        ]);

        $image_path = $request->user_image;
        if ($request->hasFile('empImage')) {
            $request->validate([
                'empImage' => ['file', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            ]);
            $file = $request->file('empImage');

            // get filename with extension
            $filenamewithextension = $file->getClientOriginalName();
            // get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            // get file extension
            $extension = $file->getClientOriginalExtension();
            // filename to store
            $filenametostore = $request->userid.'.'.$extension;

            // Storage::put('public/employees/'. $filenametostore, fopen($file, 'r+'));
            try {
                $disk = Storage::disk('upcloud');

                $image = Image::make($file->getRealPath())
                    ->resize(500, 350, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->encode($extension, 85);

                $disk->put('employees/'.$filenametostore, (string) $image, [
                    'visibility' => 'public',
                ]);

                // Store only relative key in DB
                $image_path = 'employees/'.$filenametostore;
            } catch (\Throwable $e) {
                Log::error('UpCloud upload failed (hire applicant photo)', [
                    'id' => $id,
                    'user_id' => $request->userid ?? null,
                    'original_name' => $filenamewithextension,
                    'error' => $e->getMessage(),
                ]);

                return redirect()->back()->with(['message' => 'Image upload failed. Please try again.']);
            }
        }

        $employee = User::find($id);
        $employee->user_id = $request->user_id;
        $employee->department_id = $request->department;
        $employee->shift_group_id = $request->shift;
        $employee->password = bcrypt($request->password);
        $employee->employee_name = $this->truncateUtf8(trim((string) $request->employee_name), 191);
        $employee->nick_name = $request->nickname;
        $employee->designation_id = $request->designation;
        $employee->branch = $request->branch;
        $employee->telephone = $this->normalizeUsersTelephoneInt($request->telephone);
        $employee->email = $this->truncateUtf8(trim((string) $request->email), 191);
        $employee->gender = $request->gender;
        $employee->employment_status = $request->employment_status;
        $employee->address = $this->truncateUtf8($request->filled('address') ? trim((string) $request->address) : null, 190);
        $employee->contact_no = $this->truncateUtf8(trim((string) $request->contact_no), 190);
        $employee->sss_no = $this->truncateUtf8($request->filled('sss_no') ? trim((string) $request->sss_no) : null, 20);
        $employee->tin_no = $this->truncateUtf8($request->filled('tin_no') ? trim((string) $request->tin_no) : null, 20);
        $employee->user_group = $request->user_group;
        $employee->birth_date = $request->birthdate;
        $employee->civil_status = $request->civil_status ?: 'Single';
        $employee->status = 'Active';
        $employee->date_joined = $request->date_joined;
        $employee->contact_person = $this->truncateUtf8(trim((string) $request->contact_person), 100);
        $employee->contact_person_no = $this->truncateUtf8(trim((string) $request->contact_person_no), 100);
        $employee->pagibig_no = $this->truncateUtf8($request->filled('pagibig_no') ? trim((string) $request->pagibig_no) : null, 20);
        $employee->philhealth_no = $this->truncateUtf8($request->filled('philhealth_no') ? trim((string) $request->philhealth_no) : null, 20);
        $employee->employee_id = $this->truncateUtf8(trim((string) $request->employee_id), 20);
        $employee->image = $image_path;
        $employee->designation_name = $this->truncateUtf8($request->filled('designation_name') ? trim((string) $request->designation_name) : null, 100);
        $employee->last_modified_by = Auth::user()->employee_name;
        $employee->id_security_key = $this->truncateUtf8($request->filled('id_key') ? trim((string) $request->id_key) : null, 100);
        $employee->company = $this->truncateUtf8($request->filled('company') ? trim((string) $request->company) : ($employee->company ?: 'FUMACO Inc.'), 100);
        $employee->resignation_date = null;
        $employee->applicant_status = 'Hired';
        $employee->user_type = 'Employee';
        $employee->save();

        // Trigger-based welcome + onboarding for applicants hired as employees.
        $this->triggerWelcomeEmail($employee);
        $this->triggerOnboardingEmail($employee);

        return redirect('/client/employee/profile/'.$request->user_id)->with(['message' => 'Employee <b>'.$employee->employee_name.'</b>  has been successfully registered as employee!']);
    }

    public function indexbio($user_id, $datefrom, $dateto)
    {
        $format = 'Y-m-d';
        $mytime = Carbon::now();
        $mytime->modify('+1 day');
        $current = $mytime->format($format);
        $begin = new Carbon($datefrom);
        $end = new Carbon($dateto);
        $end->modify('+1 day');
        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $dates = [];
        $range = [];

        foreach ($dateRange as $datess) {
            $datte = $datess->format($format);
            $day = $datess->format('l');
            $timein = $this->bioTimein($user_id, $datte);
            $timeout = $this->bioTimeout($user_id, $datte);
            $shift_timein = $this->ShiftSpecial_timein($day, $datte, $user_id);
            $shift_timeout = $this->ShiftSpecial_timeout($day, $datte, $user_id);
            $grace_period = $this->graceperiod($day, $datte, $user_id) + 1;
            $statuss = $this->setStatus($timein, $shift_timein, $grace_period, $timeout, $datte, $datess, $user_id);
            $stat = $this->overallStatus($timein, $timeout, $datte, $datess, $user_id);
            $breaktime_by_hour = $this->breaktime_by_hour($day, $datte, $user_id);
            $gettotalworkhrs = $this->calculateTwh($timein, $shift_timein, $timeout, $breaktime_by_hour);
            $getovertime = $this->calculateOvertime($timein, $shift_timeout, $timeout);
            $late_in_minutes = $this->getTotalLates($timein, $shift_timein, $grace_period, $timeout, $datte, $datess, $user_id);
            $deduction = $this->attendanceRules($timein, $shift_timein, $grace_period);

            if ($datte < $current) {
                $dates[] = [
                    'range' => $datess->format('Y-m-d'),
                    'late_in_minutes' => $late_in_minutes,
                    'deduction' => $deduction,
                    'day' => $day,
                    'status' => $statuss,
                    'stat' => $stat,
                    'hrs_worked' => $gettotalworkhrs,
                    'ot' => $getovertime,
                    'shift_timein' => $this->ShiftSpecial_timein($day, $datte, $user_id),
                    'shift_timeout' => $this->ShiftSpecial_timeout($day, $datte, $user_id),
                    'graceperiod' => $this->graceperiod($day, $datte, $user_id),
                    'bio_date' => $this->biometricsfunc($user_id, $datte),
                    'timein' => $this->bioTimein($user_id, $datte),
                    'timeout' => $this->bioTimeout($user_id, $datte),
                    'location_in' => $this->bioLocin($user_id, $datte),
                    'location_out' => $this->bioLocout($user_id, $datte),
                ];

            } else {
                break;
            }

            $sorted = $dates;
            asort($sorted);
            $sortedDesc = array_reverse(array_values($sorted));
        }

        return $sortedDesc;
    }

    public function getWorkingDays($begin, $end)
    {
        $start = new DateTime($begin);
        $end = new DateTime($end);
        $end->modify('+1 day');

        $holidays = DB::table('holidays')->select('holiday_date')->get();

        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
        $days = 0;
        foreach ($period as $day) {
            $dayOfWeek = $day->format('N');
            if ($dayOfWeek < 7) {
                $format = $day->format('Y-m-d');
                $days++;
                foreach ($holidays as $hol) {
                    if ($format == $hol->holiday_date) {
                        $days--;
                    }
                }
            }
        }

        return $days;
    }

    public function checkNotices($datte, $user_id)
    {
        $datte = Carbon::parse($datte);

        $notices = DB::table('notice_slip')->join('leave_types', 'leave_types.leave_type_id', 'notice_slip.leave_type_id')
            ->where('user_id', $user_id)->where('status', 'APPROVED')->get();

        $absence_dates = [];
        $data = null;
        foreach ($notices as $i => $row) {
            $start = new DateTime($row->date_from);
            $end = new DateTime($row->date_to);
            $end->modify('+1 day');

            $period = new DatePeriod($start, new DateInterval('P1D'), $end);

            foreach ($period as $absent_date) {
                $absence_dates[] = [
                    'date' => $absent_date->format('Y-m-d'),
                ];
            }

            $absence_dates = array_column($absence_dates, 'date');

            if (in_array($datte->format('Y-m-d'), $absence_dates)) {
                $data = [
                    'notice_id' => $row->notice_id,
                    'absence_type' => $row->leave_type,
                    'status' => $row->status,
                ];
            }
        }

        return $data;
    }

    public function checkHoliday($datte)
    {
        $date = Carbon::parse($datte);

        return DB::table('holidays')->where('holiday_date', $datte)->count();
    }

    public function calculateOvertime($timein, $shift_timeout, $timeout)
    {
        if (empty($timein) or empty($timeout)) {
            $overtime = 0;
        } elseif ($shift_timeout > $timeout) {
            $overtime = 0;
        } else {
            $overtime = $this->calculateHrs($shift_timeout, $timeout);
        }

        return $overtime;
    }

    public function calculateHrs($timein, $timeout)
    {
        $start = Carbon::parse($timein);
        $end = Carbon::parse($timeout);
        $hrs = $end->diffInHours($start);

        return $hrs;
    }

    public function calculateTwh($timein, $shift_timein, $timeout, $breaktime_by_hour)
    {
        if (empty($timein) or empty($timeout)) {
            $hrs_worked = 0;
        } else {
            $hrs_worked = $this->calculateHrs($timein, $timeout) - $breaktime_by_hour;
        }

        return $hrs_worked;
    }

    public function overallStatus($timein, $timeout, $datte, $datess, $user_id)
    {
        $time_in = Carbon::parse($timein);
        $time_out = Carbon::parse($timeout);
        $notice = $this->checkNotices($datte, $user_id);
        $notice_id = $notice['notice_id'];
        $notice_status = $notice['status'];

        $isHoliday = $this->checkHoliday($datte);

        if ($notice['absence_type']) {
            $status = $notice['absence_type'];
        } elseif (! empty($timein) or ! empty($timeout)) {
            $status = 'Present';
        } elseif ($isHoliday) {
            $status = 'Holiday';
        } elseif ($datess->format('N') == 7) {
            $status = 'Sunday';
        } else {
            $status = 'Unfiled Absence';
        }

        return $status;
    }

    public function setStatus($timein, $shift_timein, $grace_period, $timeout, $datte, $datess, $user_id)
    {
        $status = $this->overallStatus($timein, $timeout, $datte, $datess, $user_id);
        $timein = Carbon::parse($timein);
        $shift_timein = Carbon::parse($shift_timein);

        $grace_period = $shift_timein->addMinutes($grace_period)->format('H:i:s');
        $grace_period = Carbon::parse($grace_period);

        if (empty($timeout)) {
            $status = null;
        } elseif ($status == 'Half Day Absence') {
            $status = 'on time';
        } elseif ($timein > $grace_period) {
            $status = 'late';
        } else {
            $status = 'on time';
        }

        return $status;
    }

    public function getTotalLates($timein, $shift_timein, $grace_period, $timeout, $datte, $datess, $user_id)
    {
        $status = $this->overallStatus($timein, $timeout, $datte, $datess, $user_id);
        $time_in = Carbon::parse($timein);
        $shift_in = Carbon::parse($shift_timein)->addMinutes((int) $grace_period - 1);

        if (empty($timein)) {
            $late_in_minutes = 0;
        } elseif ($status == 'Half Day Absence') {
            $late_in_minutes = 0;

        } elseif ($time_in > $shift_in) {
            $late_in_minutes = $time_in->diffInMinutes($shift_in);
        } else {
            $late_in_minutes = 0;
        }

        return $late_in_minutes;
    }

    public function graceperiod($day, $datte, $user_id)
    {
        $shifts = DB::table('shift_schedule')
            ->join('users', 'shift_schedule.shift_id', '=', 'users.shift_group_id')
            ->join('shift_groups', 'shift_schedule.shift_id', '=', 'shift_groups.id')
            ->where('user_id', $user_id)
            ->where('sched_date', $datte)
            ->first();

        if (empty($shifts)) {
            $gracep = $this->grace($day, $datte, $user_id);
        } else {
            $gracep = $shifts->grace_period_in_mins;
        }

        return $gracep;
    }

    public function grace($day, $datte, $user_id)
    {
        $detail = DB::table('shifts')
            ->join('users', 'shifts.shift_group_id', '=', 'users.shift_group_id')
            ->join('shift_groups', 'shifts.shift_group_id', '=', 'shift_groups.id')
            ->where('user_id', $user_id)
            ->where('day_of_week', $day)
            ->first();

        if (empty($detail)) {
            $var = 0;
        } else {
            $var = $detail->grace_period_in_mins;
        }

        return $var;
    }

    public function breaktime_by_hour($day, $datte, $user_id)
    {
        $detail = DB::table('shift_schedule')
            ->join('users', 'shift_schedule.shift_id', '=', 'users.shift_group_id')
            ->join('shift_groups', 'shift_schedule.shift_id', '=', 'shift_groups.id')
            ->where('user_id', $user_id)->where('sched_date', $datte)->first();

        if (empty($detail)) {
            $var = $this->breaktime_by_hour_shift($day, $datte, $user_id);
        } else {
            $var = $detail->breaktime_by_hr;
        }

        return $var;
    }

    public function breaktime_by_hour_shift($day, $datte, $user_id)
    {
        $detail = DB::table('shifts')
            ->join('users', 'shifts.shift_group_id', '=', 'users.shift_group_id')
            ->join('shift_groups', 'shifts.shift_group_id', '=', 'shift_groups.id')
            ->where('user_id', $user_id)->where('day_of_week', $day)->first();

        if (empty($detail)) {
            $var = '0';
        } else {
            $var = $detail->breaktime_by_hour;
        }

        return $var;
    }

    public function ShiftSpecial_timein($day, $datte, $user_id)
    {
        $detail = DB::table('shift_schedule')
            ->join('users', 'shift_schedule.shift_id', '=', 'users.shift_group_id')
            ->join('shift_groups', 'shift_schedule.shift_id', '=', 'shift_groups.id')
            ->where('user_id', $user_id)->where('sched_date', $datte)->first();

        if (empty($detail)) {
            $var = $this->Shifttime_in($day, $datte, $user_id);
        } else {
            $var = $detail->time_in;
        }

        return $var;
    }

    public function ShiftSpecial_timeout($day, $datte, $user_id)
    {
        $detail = DB::table('shift_schedule')
            ->join('users', 'shift_schedule.shift_id', '=', 'users.shift_group_id')
            ->join('shift_groups', 'shift_schedule.shift_id', '=', 'shift_groups.id')
            ->where('user_id', $user_id)->where('sched_date', $datte)->first();

        if (empty($detail)) {
            $var = $this->Shifttime_out($day, $datte, $user_id);
        } else {
            $var = $detail->time_out;
        }

        return $var;
    }

    public function Shifttime_in($day, $datte, $user_id)
    {
        $detail = DB::table('shifts')
            ->join('users', 'shifts.shift_group_id', '=', 'users.shift_group_id')
            ->join('shift_groups', 'shifts.shift_group_id', '=', 'shift_groups.id')
            ->where('user_id', $user_id)->where('day_of_week', $day)->first();

        if (empty($detail)) {
            $var = '00:00:00';
        } else {
            $var = $detail->time_in;
        }

        return $var;
    }

    public function Shifttime_out($day, $datte, $user_id)
    {
        $detail = DB::table('shifts')
            ->join('users', 'shifts.shift_group_id', '=', 'users.shift_group_id')
            ->join('shift_groups', 'shifts.shift_group_id', '=', 'shift_groups.id')
            ->where('user_id', $user_id)->where('day_of_week', $day)->first();

        if (empty($detail)) {
            $var = '00:00:00';
        } else {
            $var = $detail->time_out;
        }

        return $var;
    }

    public function attendanceRules($timein, $shift_timein, $grace_period)
    {
        $time_in = Carbon::parse($timein)->format('H:i:s');

        $rules = DB::table('attendance_rules')->get();

        $deduction = 0;

        foreach ($rules as $key => $row) {
            $from = Carbon::parse(Carbon::parse($shift_timein)->addMinutes($row->from_minute))->format('H:i:s');
            $to = Carbon::parse(Carbon::parse($shift_timein)->addMinutes($row->to_minute + 1))->format('H:i:s');
            if ($time_in >= $from && $time_in <= $to) {
                $deduction = $row->deduction_in_mins;
                break;
            }
        }

        return $deduction;
    }

    public function biometricsfunc($user_id, $datte)
    {
        $biometric = DB::table('biometrics')->select('bio_date')
            ->where('employee_id', $user_id)
            ->where('bio_date', $datte)
            ->first();

        if (empty($biometric)) {
            $var = 'empty';
        } else {
            $var = $biometric->bio_date;
        }

        return $var;
    }

    public function bioTimein($user_id, $datte)
    {
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', $user_id)
            ->where('bio_date', $datte)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->first();

        if (empty($biometric)) {
            $var = null;
        } elseif ($biometric->timein == '0') {
            $var = null;
        } else {
            $var = $biometric->timein;
        }

        return $var;
    }

    public function bioTimeout($user_id, $datte)
    {
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', $user_id)
            ->where('bio_date', $datte)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->first();

        if (empty($biometric)) {
            $var = null;
        } elseif ($biometric->timeout == '0') {
            $var = null;
        } else {
            $var = $biometric->timeout;
        }

        return $var;
    }

    public function bioLocin($user_id, $datte)
    {
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', $user_id)
            ->where('bio_date', $datte)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->first();

        if (empty($biometric)) {
            $var = 'empty';
        } else {
            $var = $biometric->locin;
        }

        return $var;
    }

    public function bioLocout($user_id, $datte)
    {
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', $user_id)
            ->where('bio_date', $datte)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->first();

        if (empty($biometric)) {
            $var = 'empty';
        } else {
            $var = $biometric->locout;
        }

        return $var;
    }

    public function attendanceindex(Request $request)
    {
        $policy = DB::table('attendance_rules')->get();

        $working_days = $this->getWorkingDays($request->start, $request->end);

        $reqHrs = $working_days * 8;

        $dates = $this->indexbio($request->user_id, $request->start, $request->end);

        $late_in_minutess = collect($dates)->sum('late_in_minutes');
        $ot = collect($dates)->sum('ot');
        $hrs_worked = collect($dates)->sum('hrs_worked');
        $deduction = collect($dates)->sum('deduction');

        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Create a new Laravel collection from the array data
        $itemCollection = collect($dates);

        // Define how many items we want to be visible in each page
        $perPage = 8;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);

        // set url path for generted links
        $paginatedItems->setPath($request->url());

        $dates = $paginatedItems;

        return view('client.modules.human_resource.employees.tables.employee_attendance_table', compact('dates'));
    }

    public function checkEmployeeBirthday(Request $request)
    {
        return DB::table('users')
            ->where('user_type', 'Employee')
            ->when($request->user_id, function ($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })
            ->whereMonth('birth_date', date('m'))
            ->whereDay('birth_date', date('d'))
            ->select('user_id', 'employee_name')->get();
    }
}
