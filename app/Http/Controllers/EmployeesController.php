<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Image;
use Validator;
use App\User;
use App\ItemAccountability;
use App\Department;
use App\Designation;
use Auth;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail_General;

class EmployeesController extends Controller
{
    public function index(){
        $employees = DB::table("users")
            ->join("departments", "users.department_id", "=", "departments.department_id")
            ->join("designation", "designation.des_id", "=", "users.designation_id")
            // ->select("users.*", "departments.department", 'designation.designation')
            ->where('users.user_type', '=', 'Employee')->orderBy ('users.employee_name', 'ASC')
            ->get(); 

        $departments = DB::table('departments')->get(); 
        $designations = DB::table('designation')->get(); 
        $shifts = DB::table('shifts')->get();
        $branch = DB::table('branch')->get();

        return view('admin.employee.index', compact('employees', 'departments', 'designations', 'shifts', 'branch'));
    }

    public function store(Request $request){
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
            $employee->telephone = $request->telephone;
            $employee->email = $request->email;
            $employee->user_type = 'Employee';
            $employee->employment_status = $request->employment_status;
            $employee->address = $request->address;
            $employee->contact_no = $request->contact_no;
            $employee->sss_no = $request->sss_no;
            $employee->tin_no = $request->tin_no;
            $employee->user_group = $request->user_group;
            $employee->birth_date = $request->birthdate;
            $employee->civil_status = $request->civil_status;
            $employee->status = 'Active';
            $employee->save();

            $department = Department::find($employee->department_id);
            $designation = Designation::find($employee->designation_id);

            switch ($employee->branch) {
                case 3:
                    $file_server = 'xylon';
                    break;
                case 2:
                    $file_server = 'kimberly';
                    break;
                default:
                    $file_server = 'sam';
                    break;
            }
    
            $data = [
                'name' => $employee->employee_name,
                'department' => $department->department,
                'job_title' => $designation->designation,
                'file_server' => $file_server
            ];
    
            $mail = $this->send_mail('WELCOME EMAIL ['.strtoupper($employee->employee_name).']', 'admin.email_template.welcome', $employee->email, $data);
            if(!$mail['success']){
                return response()->json(['success' => 0, 'message' => $mail['message']]);
            }

            DB::commit();
            return redirect()->back()->with(['message' => 'Employee <b>' . $employee->employee_name . '</b>  has been successfully added!']);
        } catch (\Throwable $th) {
            DB::rollback();
            // throw $th;
            return redirect()->back()->with(['message' => 'An error occured. Please try again.']);
        }
        
    }

    public function update(Request $request){
        $image_path = $request->user_image;
        if($request->hasFile('empImage')){
            $file = $request->file('empImage');

            //get filename with extension
            $filenamewithextension = $file->getClientOriginalName();
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            //get file extension
            $extension = $file->getClientOriginalExtension();
            //filename to store
            $filenametostore = $request->user_id.'.'.$extension;
            // Storage::put('public/employees/'. $filenametostore, fopen($file, 'r+'));
            Storage::put('public/employees/'. $filenametostore, fopen($file, 'r+'));
            //Resize image here
            $thumbnailpath = public_path('storage/employees/'.$filenametostore);
            $img = Image::make($thumbnailpath)->resize(500, 350, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);

            $image_path = '/storage/employees/'.$filenametostore;
        }

        $employee = User::find($request->id);
        $employee->user_id = $request->user_id;
        $employee->department_id = $request->department;
        $employee->shift_group_id = $request->shift;
        $employee->employee_name = $request->employee_name;
        $employee->nick_name = $request->nickname;
        $employee->designation_id = $request->designation;
        $employee->branch = $request->branch;
        $employee->telephone = $request->telephone;
        $employee->email = $request->email;
        $employee->employment_status = $request->employment_status;
        $employee->address = $request->address;
        $employee->contact_no = $request->contact_no;
        $employee->sss_no = $request->sss_no;
        $employee->tin_no = $request->tin_no;
        $employee->gender =$request->gender;
        $employee->user_group = $request->user_group;
        $employee->birth_date = $request->birthdate;
        $employee->civil_status = $request->civil_status;
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

        if ($request->status == 'Resigned') {
            $employee->resignation_date = $request->resignation_date;
        }else{
            $employee->resignation_date = null;
        }

        $employee->save();

        return redirect()->back()->with(['message' => 'Employee <b>' . $employee->employee_name . '</b>  has been successfully updated!']);
    }

    public function delete(Request $request){
        $employee = User::find($request->id);
        $employee->delete();
        
        return redirect()->back()->with(['message' => 'Employee <b>' . $request->employee_name . '</b>  has been successfully deleted!']);
    }

    public function reset_password(Request $request){
        $employee = User::find($request->id);
        $employee->password = bcrypt('fumaco');
        $employee->last_modified_by= Auth::user()->employee_name;
        $employee->save();
        
        return redirect()->back()->with(['message' => 'Employee password for <b>' . $request->employee_name . '</b>  has been successfully reset to <b>"fumaco"</b>!']);
    }

    public function reset_leaves(Request $request){
        $reset_leave = DB::table('employee_leaves')->where('employee_id', $request->id)->update(['remaining' => DB::raw('total')]);
        
        return redirect()->back()->with(['message' => 'Remaining no. of leave(s) for <b>' . $request->employee_name . '</b>  has been reset!']);
    }
   
    public function adminList(){
        $admins = DB::table('admins')->get();

        return view('admin.admin.index')->with('admins', $admins);
    }

    public function storeAdmin(Request $request){
        $data = [
            'name' => $request->username,
            'access_id' => $request->access_id,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ];

        $admin = DB::table('admins')->insert($data);

        return redirect()->back()->with(['message' => 'Admin <b>' . $request->username . '</b>  has been added!']);
    }

    public function updateAdmin(Request $request){
        $data = [
            'name' => $request->username,
            'access_id' => $request->access_id,
            'email' => $request->email
        ];

        $admin = DB::table('admins')->where('id', $request->id)->update($data);

        return redirect()->back()->with(['message' => 'Admin <b>' . $request->username . '</b>  has been updated!']);
    }

    public function deleteAdmin(Request $request){
        $admin = DB::table('admins')->where('id', $request->id)->delete();

        return redirect()->back()->with(['message' => 'Admin <b>' . $request->username . '</b>  has been deleted!']);
    }

    public function reset_admin_password(Request $request){
        $admin = DB::table('admins')->where('id', $request->id)->update(['password' => bcrypt('fumaco')]);
       
        return redirect()->back()->with(['message' => 'Admin password for <b>' . $request->username . '</b>  has been successfully reset to <b>"fumaco"</b>!']);
    }

    public function sessionDetails($column){
        $detail = DB::table('users')
                    ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                    ->join('departments', 'users.department_id', '=', 'departments.department_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();

        return $detail->$column;
    }

    public function showEmployees(){
        $designation = $this->sessionDetails('designation');
        $department = $this->sessionDetails('department');

        $employees = DB::table("users")
                        ->join("departments", "users.department_id", "=", "departments.department_id")
                        ->join("designation", "designation.des_id", "=", "users.designation_id")
                        ->select("users.*", "departments.department", 'designation.designation')
                        ->where('users.user_type', '=', 'Employee')->orderBy ('users.employee_name', 'ASC')
                        ->get();

        $departments = DB::table('departments')->get(); 
        $designations = DB::table('designation')->get(); 
        $shifts = DB::table('shift_groups')->get(); 
        $branch = DB::table('branch')->get(); 

        $data = [
            'employees' => $employees,
            'departments' => $departments,
            'designations' => $designations,
            'shifts' => $shifts,
            'branch' => $branch,
            'department' => $department,
            'designation' => $designation,
        ];

        return view('client.modules.human_resource.employees.index', $data);
    }

    public function getEmployeeDetails($id){
        $details = DB::table('users')->where('id', $id)->first();

        return response()->json($details);
    }

    public function employeeCreate(Request $request){
        $image_path = null;
        if($request->hasFile('empImage')){
            $file = $request->file('empImage');

            //get filename with extension
            $filenamewithextension = $file->getClientOriginalName();
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            //get file extension
            $extension = $file->getClientOriginalExtension();
            //filename to store
            $filenametostore = $request->user_id.'.'.$extension;
            // Storage::put('public/employees/'. $filenametostore, fopen($file, 'r+'));
            Storage::put('public/employees/'. $filenametostore, fopen($file, 'r+'));
            //Resize image here
            $thumbnailpath = public_path('storage/employees/'.$filenametostore);
            $img = Image::make($thumbnailpath)->resize(500, 350, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);

            $image_path = '/storage/employees/'.$filenametostore;
        }

        $employee = new User;
        $employee->user_id = $request->user_id;
        $employee->department_id = $request->department;
        $employee->shift_group_id = $request->shift;
        $employee->password = bcrypt($request->password);
        $employee->employee_name = $request->employee_name;
        $employee->nick_name = $request->nickname;
        $employee->designation_id = $request->designation;
        $employee->branch = $request->branch;
        $employee->telephone = $request->telephone;
        $employee->email = $request->email;
        $employee->user_type = 'Employee';
        $employee->gender = $request->gender;
        $employee->employment_status = $request->employment_status;
        $employee->address = $request->address;
        $employee->contact_no = $request->contact_no;
        $employee->sss_no = $request->sss_no;
        $employee->tin_no = $request->tin_no;
        $employee->user_group = $request->user_group;
        $employee->birth_date = $request->birthdate;
        $employee->civil_status = $request->civil_status;
        $employee->date_joined = $request->date_joined;
        $employee->contact_person = $request->contact_person;
        $employee->contact_person_no = $request->contact_person_no;
        $employee->pagibig_no = $request->pagibig_no;
        $employee->philhealth_no = $request->philhealth_no;
        $employee->employee_id = $request->employee_id;
        $employee->image = $image_path;
        $employee->designation_name = $request->designation_name;
        $employee->status = 'Active';
        $employee->id_security_key = $request->id_key;
        $employee->save();

        return redirect()->back()->with(['message' => 'Employee <b>' . $employee->employee_name . '</b>  has been successfully added!']);
    }

    public function employeeUpdate(Request $request, $id){
        $image_path = $request->user_image;
        if($request->hasFile('empImage')){
            $file = $request->file('empImage');

            //get filename with extension
            $filenamewithextension = $file->getClientOriginalName();
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            //get file extension
            $extension = $file->getClientOriginalExtension();
            //filename to store
            $filenametostore = $request->user_id.'.'.$extension;
            // Storage::put('public/employees/'. $filenametostore, fopen($file, 'r+'));
            Storage::put('public/employees/'. $filenametostore, fopen($file, 'r+'));
            //Resize image here
            $thumbnailpath = public_path('storage/employees/'.$filenametostore);
            $img = Image::make($thumbnailpath)->resize(500, 350, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);

            $image_path = '/storage/employees/'.$filenametostore;
        }

        $employee = User::find($id);
        $employee->user_id = $request->user_id;
        $employee->department_id = $request->department;
        $employee->shift_group_id = $request->shift;
        $employee->employee_name = $request->employee_name;
        $employee->nick_name = $request->nickname;
        $employee->designation_id = $request->designation;
        $employee->branch = $request->branch;
        $employee->telephone = $request->telephone;
        $employee->email = $request->email;
        $employee->employment_status = $request->employment_status;
        $employee->address = $request->address;
        $employee->contact_no = $request->contact_no;
        $employee->sss_no = $request->sss_no;
        $employee->tin_no = $request->tin_no;
        $employee->user_group = $request->user_group;
        $employee->birth_date = $request->birthdate;
        $employee->civil_status = $request->civil_status;
        $employee->status = $request->status;
        $employee->gender = $request->gender;
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

        if ($request->status == 'Resigned') {
            $employee->resignation_date = $request->resignation_date;
        }else{
            $employee->resignation_date = null;
        }
        
        $employee->save();

        return redirect()->back()->with(['message' => 'Employee <b>' . $employee->employee_name . '</b>  has been successfully updated!']);
    }

    public function employeeDelete(Request $request, $id){
        $employee = User::find($id);
        $employee->delete();
        
        return redirect()->back()->with(['message' => 'Employee <b>' . $request->employee_name . '</b>  has been successfully deleted!']);
    }

    public function employeeProfile(Request $request, $user_id){
        $employee_profile = User::join('departments','users.department_id','departments.department_id')
                                    ->join('designation','users.designation_id','designation.des_id')
                                    ->where('user_id',$user_id)
                                    ->select('users.*','departments.department','designation.designation')
                                    ->first();

        $approvers = DB::table('department_approvers')
                    ->join('users', 'users.user_id', 'department_approvers.employee_id')
                    ->join('designation', 'designation.des_id', 'users.designation_id')
                    ->where('department_approvers.department_id', $employee_profile->department_id)->get();

        $regular_shift = DB::table('shifts')
                                    ->join('users', 'shifts.shift_id', '=','users.shift_group_id')
                                    ->where('user_id', $user_id)
                                    ->select('shift_schedule')
                                    ->first();

        $shifts = DB::table('shift_groups')->get(); 
        $branch = DB::table('branch')->get(); 

        $pending_notices = DB::table('notice_slip')
                                ->join('leave_types','notice_slip.leave_type_id','leave_types.leave_type_id')
                                ->where('user_id',$user_id)
                                ->where('status','For Approval')
                                ->select('notice_slip.*','leave_type')
                                ->get();

        $pending_gatepasses = DB::table('gatepass')
                                ->where('user_id',$user_id)
                                ->where('status','For Approval')
                                ->get();

        $unreturned_items = DB::table('gatepass')
                                ->where('user_id',$user_id)
                                ->where('item_status','Unreturned')
                                ->get();

        $departments = DB::table('departments')->get();
        $designations = DB::table('designation')->get();

        $itemlist = DB::table('issued_to_employee')
                        ->where('issued_to',$user_id)
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
                    ->join('training_attendees', 'training_attendees.training_id','=','training.training_id')
                    ->select('training.training_title','training.training_desc','training.training_date','training.date_submitted', 'training.proposed_by','training.status','training.training_id','training.department_name')
                    ->where('training_attendees.user_id', $user_id)
                    ->where('training.status', 'Implemented')
                    ->get(); 

        $code = new ItemAccountability;
        $lastcodeID = $code->orderBy('item_id', 'DESC')->pluck('item_id')->first();
        $newcodeID = $lastcodeID + 1;
        $neww= date('Y').'00000';
        $newly=$neww + $newcodeID;
        $newwwly='FUM'.'-'.$newly;

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
            'training' => $training
        ];
        return view('client.modules.human_resource.employees.profile')->with($data);
    }

    public function hireApplicant(Request $request, $id){
        $image_path = $request->user_image;
        if($request->hasFile('empImage')){
            $file = $request->file('empImage');

            //get filename with extension
            $filenamewithextension = $file->getClientOriginalName();
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            //get file extension
            $extension = $file->getClientOriginalExtension();
            //filename to store
            $filenametostore = $request->userid.'.'.$extension;

            // Storage::put('public/employees/'. $filenametostore, fopen($file, 'r+'));
            Storage::put('public/employees/'. $filenametostore, fopen($file, 'r+'));
            //Resize image here
            $thumbnailpath = public_path('storage/employees/'.$filenametostore);
            $img = Image::make($thumbnailpath)->resize(500, 350, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);

            $image_path = '/storage/employees/'.$filenametostore;
        }

        $employee = User::find($id);
        $employee->user_id = $request->user_id;
        $employee->department_id = $request->department;
        $employee->shift_group_id = $request->shift;
        $employee->password = bcrypt($request->password);
        $employee->employee_name = $request->employee_name;
        $employee->nick_name = $request->nickname;
        $employee->designation_id = $request->designation;
        $employee->branch = $request->branch;
        $employee->telephone = $request->telephone;
        $employee->email = $request->email;
        $employee->gender = $request->gender;
        $employee->employment_status = $request->employment_status;
        $employee->address = $request->address;
        $employee->contact_no = $request->contact_no;
        $employee->sss_no = $request->sss_no;
        $employee->tin_no = $request->tin_no;
        $employee->user_group = $request->user_group;
        $employee->birth_date = $request->birthdate;
        $employee->civil_status = $request->civil_status;
        $employee->status = 'Active';
        $employee->date_joined = $request->date_joined;
        $employee->contact_person = $request->contact_person;
        $employee->contact_person_no = $request->contact_person_no;
        $employee->pagibig_no = $request->pagibig_no;
        $employee->philhealth_no = $request->philhealth_no;
        $employee->employee_id = $request->employee_id;
        $employee->image = $image_path;
        $employee->designation_name = $request->designation_name;
        $employee->last_modified_by = Auth::user()->employee_name;
        $employee->id_security_key = $request->id_key;
        $employee->resignation_date = null;
        $employee->applicant_status = 'Hired';
        $employee->user_type = 'Employee';
        $employee->save();

        return redirect('/client/employee/profile/' . $request->user_id)->with(['message' => 'Employee <b>' . $employee->employee_name . '</b>  has been successfully registered as employee!']);
    }
    
    public function indexbio($user_id, $datefrom, $dateto){
        $format = "Y-m-d";
        $mytime = Carbon::now();
        $mytime->modify('+1 day');
        $current=$mytime->format($format);
        $begin = new Carbon($datefrom);
        $end = new Carbon($dateto);
        $end->modify('+1 day');
        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $dates = [];
        $range= [];

        foreach($dateRange as $datess ){
            $datte =$datess->format( $format);
            $day= $datess->format( 'l');
            $timein=$this->bioTimein($user_id,$datte);
            $timeout=$this->bioTimeout($user_id, $datte);
            $shift_timein =$this->ShiftSpecial_timein($day,$datte,$user_id);
            $shift_timeout =$this->ShiftSpecial_timeout($day, $datte, $user_id);
            $grace_period = $this->graceperiod($day, $datte, $user_id) + 1;
            $statuss=$this->setStatus($timein, $shift_timein, $grace_period, $timeout, $datte, $datess, $user_id);
            $stat=$this->overallStatus($timein, $timeout, $datte, $datess, $user_id);
            $breaktime_by_hour=$this->breaktime_by_hour($day, $datte, $user_id);
            $gettotalworkhrs=$this->calculateTwh($timein, $shift_timein, $timeout, $breaktime_by_hour);
            $getovertime=$this->calculateOvertime($timein, $shift_timeout, $timeout);
            $late_in_minutes = $this->getTotalLates($timein, $shift_timein, $grace_period, $timeout, $datte, $datess, $user_id);
            $deduction = $this->attendanceRules($timein, $shift_timein, $grace_period);

            if ($datte < $current) {
                 $dates[] = [
                'range' => $datess->format( 'Y-m-d'),
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
             
            }else{
             break;   
            }

            $sortedDesc = array_reverse(array_sort($dates));
        }

        return $sortedDesc;
    }

    public function getWorkingDays($begin, $end){
        $start = new DateTime($begin);
        $end = new DateTime($end);
        $end->modify('+1 day');

        $holidays = DB::table('holidays')->select('holiday_date')->get();

        $period = new DatePeriod( $start, new DateInterval( 'P1D' ), $end );
        $days = 0;
        foreach($period as $day ){
            $dayOfWeek = $day->format( 'N' );
            if( $dayOfWeek < 7 ){
                $format = $day->format( 'Y-m-d');
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

    public function checkNotices($datte, $user_id){
        $datte = Carbon::parse($datte);

        $notices = DB::table('notice_slip')->join('leave_types', 'leave_types.leave_type_id', 'notice_slip.leave_type_id')
                ->where('user_id', $user_id)->where('status', 'APPROVED')->get();

        $absence_dates = [];
        $data = null;
        foreach ($notices as $i => $row) {
            $start = new DateTime($row->date_from);
            $end = new DateTime($row->date_to);
            $end->modify('+1 day');

            $period = new DatePeriod( $start, new DateInterval( 'P1D' ), $end );

            foreach($period as $absent_date ){
                $absence_dates[] = [
                    'date' => $absent_date->format( 'Y-m-d'),
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

    public function checkHoliday($datte){
        $date = Carbon::parse($datte);

        return DB::table('holidays')->where('holiday_date', $datte)->count();
    }          

    public function calculateOvertime($timein, $shift_timeout, $timeout){
        if(empty($timein) or empty($timeout) ){
            $overtime=0;
        }elseif ($shift_timeout > $timeout){
            $overtime = 0;
        }else{
            $overtime = $this->calculateHrs($shift_timeout, $timeout);
        }

        return $overtime;
    }

    public function calculateHrs($timein, $timeout){
        $start = Carbon::parse($timein);
        $end = Carbon::parse($timeout);
        $hrs = $end->diffInHours($start);

        return $hrs;
    }
    
    public function calculateTwh($timein, $shift_timein, $timeout, $breaktime_by_hour){
        if(empty($timein) or empty($timeout) ){
            $hrs_worked=0;
        }else{
            $hrs_worked = $this->calculateHrs($timein, $timeout)- $breaktime_by_hour;
        }

        return $hrs_worked;
    }

    public function overallStatus($timein, $timeout, $datte, $datess, $user_id){
        $time_in = Carbon::parse($timein);
        $time_out =Carbon::parse($timeout);
        $notice = $this->checkNotices($datte, $user_id);
        $notice_id = $notice['notice_id'];
        $notice_status = $notice['status'];
   
        $isHoliday = $this->checkHoliday($datte);

        if ($notice['absence_type']) {
            $status = $notice['absence_type'];
        }elseif (!empty($timein) or !empty($timeout) ) {
            $status = 'Present';
        }elseif ($isHoliday) {
            $status = 'Holiday';
        }elseif ($datess->format('N') == 7) {
            $status = 'Sunday';
        }else{
            $status = 'Unfiled Absence';
        }

        return $status;
    }

    public function setStatus($timein, $shift_timein, $grace_period, $timeout, $datte, $datess, $user_id){
        $status = $this->overallStatus($timein, $timeout, $datte, $datess, $user_id);
        $timein = Carbon::parse($timein);
        $shift_timein = Carbon::parse($shift_timein);
            
        $grace_period = $shift_timein->addMinutes($grace_period)->format('H:i:s');
        $grace_period = Carbon::parse($grace_period);

        if (empty($timeout)) {
            $status=null;
        }
        elseif($status == 'Half Day Absence'){
          $status = 'on time';
        }elseif($timein > $grace_period) {
            $status = 'late';
        }else{
            $status = 'on time';
        }

        return $status;
    }

    public function getTotalLates($timein, $shift_timein, $grace_period, $timeout, $datte, $datess, $user_id){
        $status = $this->overallStatus($timein, $timeout, $datte, $datess, $user_id);
        $time_in = Carbon::parse($timein);
        $shift_in =Carbon::parse($shift_timein)->addMinutes((int)$grace_period - 1);

        if (empty($timein)) {
            $late_in_minutes = 0;
        }
        elseif($status == 'Half Day Absence'){
          $late_in_minutes = 0;

        }elseif($time_in > $shift_in){
            $late_in_minutes = $time_in->diffInMinutes($shift_in);
        }else{
            $late_in_minutes = 0;
        }

        return $late_in_minutes;
    }

    public function graceperiod($day, $datte, $user_id){
        $shifts = DB::table('shift_schedule')
                ->join('users', 'shift_schedule.shift_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shift_schedule.shift_id','=','shift_groups.id')
                ->where('user_id', $user_id)
                ->where('sched_date', $datte)
                ->first();
        
        if (empty($shifts)) {
            $gracep= $this->grace($day, $datte, $user_id);
        }else{
            $gracep= $shifts->grace_period_in_mins;
        }

        return $gracep;
    }
    
    public function grace($day, $datte, $user_id){
        $detail = DB::table('shifts')
                ->join('users', 'shifts.shift_group_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shifts.shift_group_id','=','shift_groups.id')
                ->where('user_id', $user_id)
                ->where('day_of_week', $day)
                ->first();
        
        if(empty($detail)){
            $var=0;
        }else{
            $var=$detail->grace_period_in_mins;
        }

        return $var;
    }

    public function breaktime_by_hour($day, $datte, $user_id){
        $detail = DB::table('shift_schedule')
                ->join('users', 'shift_schedule.shift_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shift_schedule.shift_id','=','shift_groups.id')
                ->where('user_id', $user_id)->where('sched_date', $datte)->first();

        if (empty($detail)) {
            $var=$this->breaktime_by_hour_shift($day, $datte, $user_id);
        }else {
            $var= $detail->breaktime_by_hr;
        } 
            
        return $var;
    }

    public function breaktime_by_hour_shift($day, $datte, $user_id){
        $detail = DB::table('shifts')
                ->join('users', 'shifts.shift_group_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shifts.shift_group_id','=','shift_groups.id')
                ->where('user_id', $user_id)->where('day_of_week', $day)->first();

        if(empty($detail)){
            $var='0';
        }else{
            $var=$detail->breaktime_by_hour;
        }
        
        return $var;
    }

    public function ShiftSpecial_timein($day, $datte, $user_id){
        $detail = DB::table('shift_schedule')
                ->join('users', 'shift_schedule.shift_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shift_schedule.shift_id','=','shift_groups.id')
                ->where('user_id', $user_id)->where('sched_date', $datte)->first();

        if (empty($detail)) {
            $var=$this->Shifttime_in($day, $datte, $user_id);
        }else {
            $var= $detail->time_in;
        } 
            
        return $var;
    }

    public function ShiftSpecial_timeout($day, $datte, $user_id){
        $detail = DB::table('shift_schedule')
                ->join('users', 'shift_schedule.shift_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shift_schedule.shift_id','=','shift_groups.id')
                ->where('user_id', $user_id)->where('sched_date', $datte)->first();

        if (empty($detail)) {
            $var=$this->Shifttime_out($day, $datte, $user_id);
        }else {
            $var= $detail->time_out;
        }

        return $var;
    }

    public function Shifttime_in($day, $datte, $user_id){
        $detail = DB::table('shifts')
                ->join('users', 'shifts.shift_group_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shifts.shift_group_id','=','shift_groups.id')
                ->where('user_id', $user_id)->where('day_of_week', $day)->first();

        if(empty($detail)){
            $var="00:00:00";
        }else{
            $var=$detail->time_in;
        }

        return $var;
    }

    public function Shifttime_out($day, $datte, $user_id){
        $detail = DB::table('shifts')
                ->join('users', 'shifts.shift_group_id', '=','users.shift_group_id')
                ->join('shift_groups', 'shifts.shift_group_id','=','shift_groups.id')
                ->where('user_id', $user_id)->where('day_of_week', $day)->first();

        if(empty($detail)){
            $var="00:00:00";
        }else{
            $var=$detail->time_out;
        }

        return $var;
    }
   
    public function attendanceRules($timein, $shift_timein, $grace_period){
        $time_in = Carbon::parse($timein)->format('H:i:s');

        $rules = DB::table('attendance_rules')->get();

        $deduction = 0;
        
        foreach ($rules as $key => $row) {
            $from = Carbon::parse(Carbon::parse($shift_timein)->addMinutes($row->from_minute))->format('H:i:s');
            $to = Carbon::parse(Carbon::parse($shift_timein)->addMinutes($row->to_minute + 1))->format('H:i:s');
            if ($time_in >= $from && $time_in <= $to ) {
                $deduction = $row->deduction_in_mins;
                break;
            }
        }

        return $deduction;
    }

    public function biometricsfunc($user_id, $datte){
        $biometric = DB::table('biometrics')->select('bio_date')
            ->where('employee_id', $user_id)
            ->where('bio_date', $datte)
            ->first();

        if (empty($biometric)) {
            $var="empty";
        }else{
            $var= $biometric->bio_date;
        } 

        return $var;
    }

    public function bioTimein($user_id, $datte){
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', $user_id)
            ->where('bio_date', $datte)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->first();

        if(empty($biometric)) {
            $var=null;
        }elseif($biometric->timein == '0') {
            $var=null;
        }else{
            $var= $biometric->timein;
        }
        return $var;
    }

    public function bioTimeout($user_id, $datte){
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', $user_id)
            ->where('bio_date', $datte)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->first();

        if(empty($biometric)) {
            $var=null;
        }elseif($biometric->timeout == '0') {
            $var=null;
        }else{
            $var= $biometric->timeout;
        }

        return $var;
    }

    public function bioLocin($user_id, $datte){
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', $user_id)
            ->where('bio_date', $datte)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->first();

        if (empty($biometric)) {
            $var="empty";
        }else{
            $var= $biometric->locin;
        } 
            
        return $var;
    }

    public function bioLocout($user_id, $datte){
        $biometric = DB::table('biometrics')
            ->select(DB::raw('bio_date, MAX(IF(trans_type = 7, bio_time, 0)) AS timein, MAX(IF(trans_type = 8, bio_time, 0)) AS timeout, MAX(IF(trans_type = 7, unit_name, 0)) as locin, MAX(IF(trans_type = 8, unit_name, 0)) as locout'))
            ->where('employee_id', $user_id)
             ->where('bio_date', $datte)
            ->orderBy('bio_date', 'desc')
            ->groupBy('bio_date')
            ->first();

        if (empty($biometric)) {
            $var="empty";
        }else {
            $var= $biometric->locout;
        } 
            
        return $var;
    }

    public function attendanceindex(Request $request){
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
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
     
        // set url path for generted links
        $paginatedItems->setPath($request->url());

        $dates = $paginatedItems;

        return view('client.modules.human_resource.employees.tables.employee_attendance_table', compact('dates'));
    }

    public function checkEmployeeBirthday(Request $request){
        return DB::table('users')
                ->where('user_type', 'Employee')
                ->when($request->user_id, function($query) use ($request){
                    return $query->where('user_id', $request->user_id);
                })
                ->whereMonth('birth_date', date('m'))
                ->whereDay('birth_date', date('d'))
                ->select('user_id', 'employee_name')->get();
    }

}