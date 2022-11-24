<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
// use App\Mail\SendMail_notice;
// use App\Mail\SendMail_gatepass;
// use App\Mail\SendMail_itinerary;
use Validator;
use Carbon\Carbon;
use Auth;
use DB;
use DateTime;
use DatePeriod;
use DateInterval;
use App\DataInputKPI;
use App\KPIResult;
use App\DataInputModel;
use App\Gatepass;
use App\AbsentNotice;
use App\Http\Traits\AttendanceTrait;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class KioskController extends Controller
{
    use AttendanceTrait;

    public function index(){
    	if (!Auth::user()) {
    		return redirect('/kiosk/login');
    	}

        $user_details = DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', Auth::user()->user_id)
            ->first();

        return view('kiosk.sel_module', compact('user_details'));
    }

    public function loginForm(){
    	if (Auth::user()) {
    		return redirect('/kiosk/home');
    	}

    	return view('kiosk.login');
    }

    public function kioskLogin(Request $request){
       	// validate the info, create rules for the inputs
    	$id_rules = array(
		    'id_key' => 'required'
		);

        $access_id_rules = array(
            'access_id' => 'required',
            'password' => 'required',
        );

        $rules = $request->using_access_id == 1 ? $access_id_rules : $id_rules;

		$validator = Validator::make(Input::all(), $rules);

		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)
		        ->withInput(Input::except('id_key', 'password'));
		}else{
			// create our user data for the authentication
		    $id_user_data = array(
		        'id_security_key'  => Input::get('id_key')
		    );

            $access_id_user_data = array(
                'user_id'  => Input::get('access_id'),
                'password'  => Input::get('password'),
            );

            $userdata = $request->using_access_id == 1 ? $access_id_user_data : $id_user_data;

		    // attempt to do the login
            if ($request->using_access_id == 1) {
                if (Auth::attempt($userdata)) {
                    return redirect('/kiosk/home');
                } else {        
                    // validation not successful, send back to form 
                    return redirect()->back()->with('message', 'Invalid credentials.');
                }
            }else{
                $user = DB::table('users')->where('id_security_key', $request->id_key)->orWhere('user_id', $request->id_key)->first();
                if ($user) {
                    if(Auth::loginUsingId($user->id)){
                        return redirect('/kiosk/home');
                    } 
                } else {        
                    // validation not successful, send back to form 
                    return redirect()->back()->with('message', 'Invalid credentials.');
                }
            }
		}
    }

    public function kioskLogout(){
    	Auth::guard('web')->logout();
        return redirect('/kiosk/login');
    }

    public function leaveCalendar(){
        if (!Auth::user()) {
            return redirect('/kiosk/login');
        }

        return view('kiosk.calendar');
    }

    public function noticeTransactSel(){
    	if (!Auth::user()) {
    		return redirect('/kiosk/login');
    	}

        return view('kiosk.absent_notice.index');
    }

    public function noticeForm(){
      if (!Auth::user()) {
       
        return redirect('/kiosk/login');
      }
      $year= date("Y");
      $absence_types = DB::table('leave_types')
                        ->where('applied_to_all', '=', 1)
                        ->get();

      $year= date("Y");
      $leave_types = DB::table('employee_leaves')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'employee_leaves.leave_type_id')
                        ->select('leave_types.leave_type', 'leave_types.leave_type_id', 'employee_leaves.*')
                        ->where('employee_leaves.employee_id', '=', Auth::user()->user_id)
                        ->where('employee_leaves.year', '=', $year)
                        ->get();


        return view('kiosk.absent_notice.form', compact('leave_types','absence_types'));
    }
    public function storenotice(Request $request){
        if (in_array($request->absence_type, [1, 2, 3, 4,7])) {
            $date_from = Carbon::parse($request->date_from);
            $date_to = Carbon::parse($request->date_to);
            $diff_in_days = $date_to->diffInDays($date_from);
            $duration = $diff_in_days + 1;
        }else{
            $time_from = Carbon::parse($request->time_from);
            $time_to = Carbon::parse($request->time_to);
            $diff_in_hours = $time_to->diffInMinutes($time_from);
            $duration = ($diff_in_hours / 60) * 0.125;
        }

        // get number of days absent
        $fdate = $request->date_from;
        $tdate = $request->date_to;
        $datetime1 = new DateTime($fdate);
        $datetime2 = new DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        $days = $days + 1;

        // get total & remaining number of leaves
        $year= date("Y");
        $leave_type = DB::table('employee_leaves')
                        ->where('leave_type_id', '=', $request->absence_type)
                        ->where('employee_id', '=', $request->user_id)
                        ->where('employee_leaves.year', '=', $year)
                        ->select('employee_leaves.*')
                        ->first();
       
        // subtract number of days absent from total
        if (isset($leave_type->remaining)) {
                $remaining = $leave_type->remaining - $days;

                // update remaining number of leaves
                $employee_leave = DB::table('employee_leaves')
                        ->where('leave_id', '=', $leave_type->leave_id)
                        ->where('employee_id', '=', $request->user_id)
                        ->where('employee_leaves.year', '=', $year)
                        ->update([
                            'remaining' => $remaining
                        ]);
        } 

        $notice_slip = new AbsentNotice;
        $notice_slip->user_id = $request->user_id;
        $notice_slip->dept_id = $request->department;
        $notice_slip->leave_type_id = $request->absence_type;
        $notice_slip->date_from = $request->date_from;
        $notice_slip->date_to = $request->date_to;
        $notice_slip->time_from = $request->time_from;
        $notice_slip->time_to = $request->time_to;
        $notice_slip->means = $request->reported_through;
        $notice_slip->reason = $request->reason;
        $notice_slip->time_reported = $request->time_reported;
        $notice_slip->info_by = $request->received_by;
        $notice_slip->status = 'FOR APPROVAL';
        $notice_slip->remarks = $request->remarks;
        $notice_slip->created_by = Auth::user()->employee_name;
        $notice_slip->last_modified_by = Auth::user()->employee_name;
        $notice_slip->duration = $duration;
        $notice_slip->save();

       //  $viewdetails =DB::table('notice_slip')
       //      ->join('leave_types','leave_types.leave_type_id','=','notice_slip.leave_type_id')
       //      ->select('notice_slip.*', 'leave_types.leave_type')
       //      ->orderBy('notice_id', 'desc')
       //      ->where('user_id', Auth::user()->user_id)
       //      ->first();

       //  $notice_id= $viewdetails->notice_id;

       //  $data = array(
       //      'employee_name'      =>  Auth::user()->employee_name,
       //      'year'               => now()->format('Y'),
       //      'slip_id'            => $notice_id
       //  );

       // $leave_appprover= DB::table('department_approvers')
       //              ->join('users', 'users.user_id', '=', 'department_approvers.employee_id')
       //              ->where('department_approvers.department_id',  Auth::user()->department_id)
       //              ->select('department_approvers.*','users.email')
       //              ->get();

       //  foreach ($leave_appprover as $row) {
       //      Mail::to($row->email)->send(new SendMail_notice($data));
       //  }

        


        return redirect('/kiosk/notice/view');
    }

    public function noticeView(){

        return view('kiosk.absent_notice.view');
    }
    public function cancel_notice(){

        $viewdetails =DB::table('notice_slip')
            ->join('leave_types','leave_types.leave_type_id','=','notice_slip.leave_type_id')
            ->select('notice_slip.*', 'leave_types.leave_type')
            ->orderBy('notice_id', 'desc')
            ->where('user_id', Auth::user()->user_id)
            ->first();

        $notice_id= $viewdetails->notice_id;
        // $status= $viewdetails->status;
        $datefrom=$viewdetails->date_from;
        $dateto =$viewdetails->date_to;
        // $timefrom=$viewdetails->time_from;
        // $timeto=$viewdetails->time_to;
        // $reason=$viewdetails->reason;
        $absence_type=$viewdetails->leave_type_id;
        // $duration=$viewdetails->duration;

        $adj = AbsentNotice::find($notice_id);
            $adj->status = 'CANCELLED';
            $adj->save();

        // get number of days absent
        // $fdate = $request->date_from;
        // $tdate = $request->date_to;
        $datetime1 = new DateTime($datefrom);
        $datetime2 = new DateTime($dateto);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        $days = $days + 1;

        // get total & remaining number of leaves
        
        $year= date("Y");
        $leave_type = DB::table('employee_leaves')
                        ->where('leave_type_id', '=', $absence_type)
                        ->where('employee_id', '=', Auth::user()->user_id)
                        ->where('employee_leaves.year', '=', $year)
                        ->select('employee_leaves.*')
                        ->first();
       
        // subtract number of days absent from total
        if (isset($leave_type->remaining)) {
            $remaining = $leave_type->remaining + $days;

            // update remaining number of leaves
            $employee_leave = DB::table('employee_leaves')
                    ->where('leave_id', '=', $leave_type->leave_id)
                    ->where('employee_id', '=', Auth::user()->user_id)
                    ->where('employee_leaves.year', '=', $year)
                    ->update([
                        'remaining' => $remaining
                    ]);
        }

        return response()->json(['message' => 'Notice has been cancelled.']); 
    }
    
    


    public function getnotice_history(Request $request){
            $month = $request->month;
            $year = $request->year;
            $logs = DB::table('notice_slip')
                        ->join('users', 'users.user_id', '=', 'notice_slip.user_id')
                        ->join('departments', 'users.department_id', '=', 'departments.department_id')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'notice_slip.leave_type_id')
                        ->where('notice_slip.user_id', '=', Auth::user()->user_id)
                        ->where(function($query)use ($month){
                                $query->whereMonth('notice_slip.date_from' ,$month);
                                $query->orWhere(function($query2) use ($month){
                                   $query2->whereMonth('notice_slip.date_to', $month); });
                                $query->orWhere(function($query1)use ($month){
                                   $query1->whereMonth('notice_slip.date_from_converted', $month); });

                                })
                        ->where(function($query)use ($year){
                                $query->whereYear('notice_slip.date_from' ,$year);
                                $query->orWhere(function($query2) use ($year){
                                   $query2->whereYear('notice_slip.date_to', $year); });
                                $query->orWhere(function($query1)use ($year){
                                   $query1->whereYear('notice_slip.date_from_converted', $year); });
                                })
                    
                        ->orderBy('notice_slip.notice_id', 'desc')
                        ->select('users.*', 'notice_slip.*', 'departments.department', 'leave_types.leave_type', DB::raw('(SELECT employee_name FROM users WHERE user_id = notice_slip.approved_by) as approver'))
                        ->get();

            $absence_types = DB::table('leave_types')
                        ->where('applied_to_all', '=', 1)
                        ->get();

            $leave_types = DB::table('employee_leaves')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'employee_leaves.leave_type_id')
                        ->select('leave_types.leave_type', 'leave_types.leave_type_id', 'employee_leaves.*')
                        ->where('employee_leaves.employee_id', '=', Auth::user()->user_id)
                        ->get();

            // $notice = $notice_slips;

            // // Get current page form url e.x. &page=1
            // $currentPage = LengthAwarePaginator::resolveCurrentPage();
            // // Create a new Laravel collection from the array data
            // $itemCollection = collect($notice);
            // // Define how many items we want to be visible in each page
            // $perPage = 7;
            // // Slice the collection to get the items to display in current page
            // $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            // // Create our paginator and pass it to the view
            // $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
            // // set url path for generted links
            // $paginatedItems->setPath($request->url());

            // $logs = $paginatedItems;
            // return $logs;
            return view('kiosk.absent_notice.table.notice_historytable', compact('logs', 'absence_types', 'leave_types'));
    
    }
    public function noticeHistory(){
            if (!Auth::user()) {
            return redirect('/kiosk/login');
            }

            return view('kiosk.absent_notice.history');
    }
    public function user_shift(Request $request){
            $dayOfWeek = date("l", strtotime($request->type));
            $data=[];
            $user_shift=DB::table('users')
            ->join('shifts', 'shifts.shift_group_id','=','users.shift_group_id')
            ->where('users.user_id',Auth::user()->user_id)
            ->where('shifts.day_of_week',$dayOfWeek)
            ->first();
            $data= [
              'shift_in' =>  date("g:i A", strtotime($user_shift->time_in)),
              'shift_out' => date("g:i A", strtotime($user_shift->time_out)),
              'day' => $user_shift->day_of_week,
              'date' => $request->type
            ];

           return response()->json($data);

    }
    public function notice_view_table(){
            $viewdetails =DB::table('notice_slip')
            ->join('leave_types','leave_types.leave_type_id','=','notice_slip.leave_type_id')
            ->select('notice_slip.*', 'leave_types.leave_type')
            ->orderBy('notice_id', 'desc')
            ->where('user_id', Auth::user()->user_id)
            ->first();

            $notice_id= $viewdetails->notice_id;
            $status= $viewdetails->status;
            $datefrom=$viewdetails->date_from;
            $dateto =$viewdetails->date_to;
            $timefrom=$viewdetails->time_from;
            $timeto=$viewdetails->time_to;
            $reason=$viewdetails->reason;
            $absence_type=$viewdetails->leave_type;
            $duration=$viewdetails->duration;

            return view('kiosk.absent_notice.table.view_table', compact("notice_id" ,"status","datefrom","dateto","timefrom","timeto","reason","absence_type","duration"));

    }
    public function leaveBalance(){
        if (!Auth::user()) {
            return redirect('/kiosk/login');
        }   
                $year= date("Y");
                $vacation = DB::table('employee_leaves')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'employee_leaves.leave_type_id')
                        ->select('leave_types.leave_type', 'leave_types.leave_type_id', 'employee_leaves.*')
                        ->where('employee_leaves.employee_id', '=', Auth::user()->user_id)
                        ->where('employee_leaves.leave_type_id', '=', 1)
                        ->where('employee_leaves.year', '=', $year)
                        ->get();


                $sick = DB::table('employee_leaves')
                        ->join('leave_types', 'leave_types.leave_type_id', '=', 'employee_leaves.leave_type_id')
                        ->select('leave_types.leave_type', 'leave_types.leave_type_id', 'employee_leaves.*')
                        ->where('employee_leaves.employee_id', '=', Auth::user()->user_id)
                        ->where('employee_leaves.leave_type_id', '=', 2)
                        ->where('employee_leaves.year', '=', $year)
                        ->get();
                $users =DB::table('users')
                        ->where('user_id', Auth::user()->user_id)
                        ->select('gender')
                        ->first();
                $gender = $users->gender;
                // $vacations= $vacation->remaining;
                // $sick= $sicck->remaining;
                
        return view('kiosk.absent_notice.leave_balance',compact('vacation','sick','gender'));
    }
    

    public function gatepassTransactSel(){
    	if (!Auth::user()) {
    		return redirect('/kiosk/login');
    	}

        return view('kiosk.gatepass.index');
    }

    public function gatepassForm(){
    	if (!Auth::user()) {
    		return redirect('/kiosk/login');
    	}

        return view('kiosk.gatepass.form');
    }
    public function storegatepass(Request $request){
        if (!Auth::user()) {
            return redirect('/kiosk/login');
        }

        $item = new Gatepass;
        $item->user_id = $request->user_id;
        $item->date_filed = date('Y-m-d');
        $item->returned_on = $request->returned_on;
        $item->company_name = $request->company_name;
        $item->time = $request->time;
        $item->address = $request->address;
        $item->purpose = $request->purpose;
        $item->purpose_type = $request->purpose_type;
        $item->tel_no = $request->tel_no;
        $item->item_description = $request->item_description;
        // $item->remarks = $request->remarks;
        $item->status = 'FOR APPROVAL';
        $item->created_by = Auth::user()->employee_name;
        $item->save();

      
        $viewdetails =DB::table('gatepass')
        ->orderBy('gatepass_id', 'desc')
        ->where('user_id', Auth::user()->user_id)
        ->first();

        $gatepass_id= $viewdetails->gatepass_id;

        $data = array(
            'employee_name'      => Auth::user()->employee_name,
            'year'               => now()->format('Y'),
            'slip_id'            => $gatepass_id
        );

        $branch= DB::table('branch')->get();
          foreach ($branch as $row) {
            if ($row->branch_id == Auth::user()->branch) {
              if (Auth::user()->branch == '1') {
                $branch_name= "plant 2";
                $approver= DB::table('users')
                ->where('users.department_id', 9)//12
                ->where('users.designation_id', 1)//29
                ->where('users.user_id', 2388)
                ->select('users.email')
                ->get();
                // foreach ($approver as $row) {
                //     Mail::to($row->email)->send(new SendMail_gatepass($data));
                // }

              }elseif (Auth::user()->branch == '2') {
                $branch_name= "plant 1";
                $approver= DB::table('users')
                ->where('users.department_id', 9)//19
                ->where('users.designation_id', 1)//54
                ->where('users.user_id', 2388)
                ->select('users.email')
                ->get();
                // foreach ($approver as $row) {
                //     Mail::to($row->email)->send(new SendMail_gatepass($data));
                // }
              }elseif (Auth::user()->branch == '3') {
                $branch_name= "Showroom";
                $approver= DB::table('users')
                ->where('users.department_id', 9)//12
                ->where('users.designation_id', 1)//30
                ->where('users.user_id', 2388)
                ->select('users.email')
                ->get();
                // foreach ($approver as $row) {
                //     Mail::to($row->email)->send(new SendMail_gatepass($data));
                // }
              }
                    
            }
          }

        
        return redirect('/kiosk/gatepass/view');

    }

    public function gatepassView(){
    	if (!Auth::user()) {
    		return redirect('/kiosk/login');
    	}

        
        return view('kiosk.gatepass.view');
    }
    public function gatepass_view_table(){
        if (!Auth::user()) {
            return redirect('/kiosk/login');
        }

        $viewdetails =DB::table('gatepass')
        ->orderBy('gatepass_id', 'desc')
        ->where('user_id', Auth::user()->user_id)
        ->first();
        $gatepass_id= $viewdetails->gatepass_id;
        $status= $viewdetails->status;
        $purpose_type=$viewdetails->purpose_type;
        $purpose =$viewdetails->purpose;
        $company_name=$viewdetails->company_name;
        $address=$viewdetails->address;
        $contact=$viewdetails->tel_no;
        $items=$viewdetails->item_description;
        $returned_on=$viewdetails->returned_on;
        
        return view('kiosk.gatepass.view_table', compact('gatepass_id','status','purpose_type','purpose','company_name','address','contact','items','returned_on'));
    }

    public function gatepassHistory(){
    	if (!Auth::user()) {
    		return redirect('/kiosk/login');
    	}

        return view('kiosk.gatepass.history');
    }

    public function cancel_gatepass(){

        $viewdetails =DB::table('gatepass')
        ->orderBy('gatepass_id', 'desc')
        ->where('user_id', Auth::user()->user_id)
        ->first();

        $gatepass_id= $viewdetails->gatepass_id;
        // $status= $viewdetails->status;
        // $datefrom=$viewdetails->date_from;
        // $dateto =$viewdetails->date_to;
        // $timefrom=$viewdetails->time_from;
        // $timeto=$viewdetails->time_to;
        // $reason=$viewdetails->reason;
        // $absence_type=$viewdetails->leave_type;
        // $duration=$viewdetails->duration;

        $adj = Gatepass::find($gatepass_id);
            $adj->status = 'CANCELLED';
            $adj->save();

        return response()->json(['message' => 'Gatepass has been cancelled.']); 
    }
    public function getgatepass_history(Request $request){
            $month = $request->month;
            $year = $request->year;
            $logs = DB::table('gatepass')
                        ->join('users', 'users.user_id', '=', 'gatepass.user_id')
                        ->where('gatepass.user_id', '=', Auth::user()->user_id)
                        ->where(function($query)use ($month){
                                $query->whereMonth('gatepass.date_filed' ,$month);
                                $query->orWhere(function($query2) use ($month){
                                   $query2->whereMonth('gatepass.date_filed_converted', $month); });
                                })
                        ->where(function($query)use ($year){
                                $query->whereYear('gatepass.date_filed' ,$year);
                                $query->orWhere(function($query2) use ($year){
                                   $query2->whereYear('gatepass.date_filed_converted', $year); });
                                })
                        ->orderBy('gatepass.gatepass_id', 'desc')
                        ->select('users.*', 'gatepass.*')
                        ->get();
                    
            // $gatepass = $gatepasses;

            // // Get current page form url e.x. &page=1
            // $currentPage = LengthAwarePaginator::resolveCurrentPage();
            // // Create a new Laravel collection from the array data
            // $itemCollection = collect($gatepass);
            // // Define how many items we want to be visible in each page
            // $perPage = 7;
            // // Slice the collection to get the items to display in current page
            // $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            // // Create our paginator and pass it to the view
            // $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
            // // set url path for generted links
            // $paginatedItems->setPath($request->url());

            // $logs = $paginatedItems;

            return view('kiosk.gatepass.table_gatepasshistory', compact('logs'));
    
    }
    public function getunreturned_history(Request $request){
            $month = $request->month;
            $year = $request->year;
            $logs = DB::table('gatepass')
                        ->join('users', 'users.user_id', '=', 'gatepass.user_id')
                        ->where('gatepass.item_type', '=', 'Returnable')
                        ->where('gatepass.item_status', '=', 'Unreturned')
                        ->where('gatepass.status', '=', 'Approved')
                    
                        ->where(function($query)use ($month){
                                $query->whereMonth('gatepass.date_filed' ,$month);
                                $query->orWhere(function($query2) use ($month){
                                   $query2->whereMonth('gatepass.date_filed_converted', $month); });
                                })
                        ->where(function($query)use ($year){
                                $query->whereYear('gatepass.date_filed' ,$year);
                                $query->orWhere(function($query2) use ($year){
                                   $query2->whereYear('gatepass.date_filed_converted', $year); });
                                })

                        ->orderBy('gatepass.gatepass_id', 'desc')
                        ->select('users.*', 'gatepass.*')
                        ->where('gatepass.user_id', Auth::user()->user_id)
                        ->get();
      

            return view('kiosk.gatepass.table_unreturnedtable', compact('logs'));

    }

    public function gatepassUnreturned(){
    	if (!Auth::user()) {
    		return redirect('/kiosk/login');
    	}

        return view('kiosk.gatepass.unreturned');
    }

    public function attendanceTransactSel(){
    	if (!Auth::user()) {
    		return redirect('/kiosk/login');
    	}

        return view('kiosk.attendance.index');
    }

    public function attendanceView(){
        if (!Auth::user()) {
            return redirect('/kiosk/login');
        }

        return view('kiosk.attendance.logs');
    }

    public function attendanceSummary(){
        if (!Auth::user()) {
            return redirect('/kiosk/login');
        }
        $user_id = Auth::user()->user_id;

        
        $days = Carbon::now()->format('d');

        if ($days <= 12 || $days >= 28) {

            $now = Carbon::now();
            $last = Carbon::now();

            if ($days <= 12) {
                if ($now->month == 01) {
                    $year= $now->year;
                    $previous= $last->subYear()->format('m');
                    $previous_year= $last->subYear()->format('y');
                    $month= $now->month;
                    $start = $previous_year.'-'.$previous.'-13';
                    $end = $previous_year."-".$previous."-27";
                }
                else{
                    $year= $now->year;
                    $previous= $last->subMonth()->format('m');
                    $month= $now->month;
                    $start = $year.'-'.$previous.'-13';
                    $end = $year."-".$previous."-27";
                }
            }else{
                $now = Carbon::now();
                $last = Carbon::now();
                $year= $now->year;
                $previous= $last->subMonth()->format('m');
                $month= $now->month;
                $start = $year.'-'.$month.'-13';
                $end = $year."-".$month."-27";
                
            }
        }else{
            $now = Carbon::now();
            $last = Carbon::now();
            if ($now->month == 01) {
                $year= $now->year;
                $previous= $last->subMonth()->format('m');
                $previous_year= $last->subYear()->format('y');
                $month= $now->month;
                $start = $previous_year.'-'.$previous.'-28';
                $end = $year."-".$month."-12";
            }else{
                $now = Carbon::now();
                $last = Carbon::now();
                $year= $now->year;
                $previous= $last->subMonth()->format('m');
                $month= $now->month;
                $start = $year.'-'.$previous.'-28';
                $end = $year."-".$month."-12";
            }
            

        }

        $employee_logs = $this->attendanceLogs($user_id, $start, $end);

        $working_days = $this->getWorkingDays($start, $end);
        $reqHrs = $working_days * 8;
        $total_overtime = collect($employee_logs)->sum('overtime');
        $total_worked_hrs = collect($employee_logs)->sum('hrs_worked');
        $total_late= collect($employee_logs)->sum('late_in_minutes');
        $summary_details = [
            'date_from' => $start,
            'date_to' => $end,
            'working_days' => $working_days,
            'reqHrs' => $reqHrs,
        ];

        $overtime_logs = collect($employee_logs)->where('overtime', '>', 0);
        
        return view('kiosk.attendance.summary', compact('overtime_logs', 'employee_logs', 'summary_details','total_overtime','total_worked_hrs','total_late'));
    }

    public function biometricLogs(Request $request, $employee){
        $logs = $this->attendanceLogs($employee, $request->start, $request->end);

        return view('kiosk.attendance.table', compact('logs'));
    }

    public function itineraryTransactSel(){
        if (!Auth::user()) {
            return redirect('/kiosk/login');
        }

        return view('kiosk.itinerary.index');
    }
    public function itineraryHistory(){
        if (!Auth::user()) {
            return redirect('/kiosk/login');
        }

        return view('kiosk.itinerary.history');
    }

    public function itineraryForm(){
        if (!Auth::user()) {
            return redirect('/kiosk/login');
        }

        return view('kiosk.itinerary.form');
    }

    public function get_itineraryHistory(Request $request){
        $itineraries = DB::connection('mysql_erp')
            ->table('tabItinerary Tab')
            ->join('tabItinerary','tabItinerary.name','=','tabItinerary Tab.parent')
            ->select('tabItinerary.workflow_state','tabItinerary Tab.*')
            ->where(function ($query){
                $query->where('tabItinerary Tab.owner', Auth::user()->email)
                    ->orWhere('tabItinerary Tab.owner', Auth::user()->employee_name);
            })
            ->whereYear('tabItinerary Tab.date', $request->year)
            ->whereMonth('tabItinerary Tab.date', $request->month)
            ->orderBy('creation', 'desc')
            ->get();

        return view('kiosk.itinerary.table', compact('itineraries'));
    }

    public function getEmployees(){
        $list = DB::connection('mysql_erp')
            ->table('tabEmployee')
            ->where('status', 'Active')
            ->orderBy('employee_name', 'asc')
            ->pluck('name', 'employee_name');

        return $list->all();
    }

    public function getDocList($doctype){
        $table = 'tab'.$doctype;
        $list = DB::connection('mysql_erp')
            ->table($table)
            ->orderBy('name', 'asc')
            ->pluck('name');

        return $list;
    }

    public function saveItinerary(Request $request){
        $todays_date = Carbon::now()->format('Y-m-d H:i:s');
        $list = DB::connection('mysql_erp')
            ->table('tabItinerary')->where('name', 'like', '%ITK%')
            ->select(DB::raw('MAX(CAST(SUBSTRING(name, 4, length(name)-3) AS UNSIGNED)) as name'))
            ->first();

        $last_id = $list->name ? $list->name : 0;
        $new_id = 'ITK' . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

        $itk = [
            'name' => $new_id,
            'creation' => $todays_date,
            'modified' => $todays_date,
            'modified_by' => Auth::user()->employee_name,
            'owner' => Auth::user()->employee_name, 
            'docstatus' => 0,
            'workflow_state' => 'For Approval',
            'transaction_date' => date('Y-m-d'),
        ];

        $itk_child= [];
        if ($request->from) {
            DB::connection('mysql_erp')->table('tabItinerary')->insert($itk);
            foreach ($request->from as $i => $row) {
                $customer = ($request->from[$i] == 'Customer') ? $request->destination[$i] : null;
                $lead = ($request->from[$i] == 'Lead') ? $request->destination[$i] : null;
                $supplier = ($request->from[$i] == 'Supplier') ? $request->destination[$i] : null;
                $others = ($request->from[$i] == 'Others') ? $request->destination[$i] : null;

                $itinerary_date = DateTime::createFromFormat('m-d-Y', $request->itinerary_date[$i])->format('Y-m-d');
                $itk_child[] = [
                    'name' => uniqid(date('mdY')),
                    'creation' => $todays_date,
                    'modified' => $todays_date,
                    'modified_by' => Auth::user()->employee_name,
                    'owner' => Auth::user()->employee_name,
                    'docstatus' => 0,
                    'parent' => $new_id, //
                    'parentfield' => 'project',
                    'parenttype' => 'Itinerary',
                    'idx' => $i + 1,
                    'project' => $request->project[$i],
                    'customer' => $customer,
                    'itinerary_date' => $itinerary_date,
                    // 'contact_no' => null,
                    'purpose' => $request->purpose[$i],
                    // 'contact_person' => null,
                    // 'mobile_no' => null,
                    'date' => $itinerary_date,
                    'time' => $request->itinerary_time[$i],
                    'from' => $request->from[$i],
                    'lead' => $lead,
                    // 'lead_name' => null,
                    'supplier' => $supplier,
                    'destination' => $others,
                    // 'remarks' => null,
                    'itinerary_location' => $request->destination[$i],
                    // 'customer_address' => null,
                    // 'address' => null,
                    // 'city' => null,
                    // 'address_line_2' => null,
                    // 'address_line_1' => null,
                ];
            }
            DB::connection('mysql_erp')->table('tabItinerary Tab')->insert($itk_child);
        }
    
        $companion = [];
        if ($request->companion_id) {
            foreach ($request->companion_id as $i => $row) {
                $companion[] = [
                    'name' => uniqid(date('mdY')),
                    'creation' => $todays_date,
                    'modified' => $todays_date,
                    'modified_by' => Auth::user()->employee_name,
                    'owner' => Auth::user()->email,
                    'docstatus' => 0,
                    'parent' => $new_id,
                    'parentfield' => 'companion',
                    'parenttype' => 'Itinerary',
                    'idx' => $i + 1,
                    'companion' => $request->companion_id[$i],
                    'employee_name' => $request->companion_name[$i],
                ];
            }
            DB::connection('mysql_erp')->table('tabCompanion Table')->insert($companion);
        }


        // $data = array(
        //     'employee_name'      =>  Auth::user()->employee_name,
        //     'year'               => now()->format('Y'),
        //     'slip_id'            => $new_id
        // );

        // $appprover= DB::table('users')
        //             ->where('users.department_id', 6)
        //             ->where('users.designation_id', 22)
        //             ->select('users.email')
        //             ->get();

    
        
        // foreach ($appprover as $row) {
        //     Mail::to($row->email)->send(new SendMail_itinerary($data));
        // }



        return redirect('/kiosk/itinerary/result/' . $new_id)->with(['message' => 'Saved.']);
    }

    public function cancelItinerary($id){
        DB::connection('mysql_erp')->table('tabItinerary')
            ->where('name', $id)->update(['workflow_state' => 'Cancelled', 'modified_by' => Auth::user()->user_id]);

        return redirect()->back()->with('message', 'Itinerary has been cancelled.');
    }

    public function itineraryView($id){
        $itr = DB::connection('mysql_erp')->table('tabItinerary')->where('name', $id)->first();
        $itr_chld = DB::connection('mysql_erp')->table('tabItinerary Tab')
                ->where('parent', $id)->get();
        $itr_companion = DB::connection('mysql_erp')->table('tabCompanion Table')
                ->where('parent', $id)->get();

        return view('kiosk.itinerary.view', compact('itr', 'itr_chld', 'itr_companion'));
    }

    public function itineraryResult($id){
        $itinerary_id = $id;
        return view('kiosk.itinerary.result', compact('itinerary_id'));
    }

    public function itineraryResult_table($id){
        $itr = DB::connection('mysql_erp')->table('tabItinerary')->where('name', $id)->first();
        $itr_chld = DB::connection('mysql_erp')->table('tabItinerary Tab')
                ->where('parent', $id)->get();
        $itr_companion = DB::connection('mysql_erp')->table('tabCompanion Table')
                ->where('parent', $id)->get();

        return view('kiosk.itinerary.result_table', compact('itr', 'itr_chld', 'itr_companion'));
    }

    public function stepper_index(){
        
        return view('kiosk.stepper.index');
    }
    public function stepper_notice(){
        
        return view('kiosk.stepper.table.stepper_notice');
    }
    public function stepper_gatepass(){
        
        return view('kiosk.stepper.table.stepper_gatepass');
    }
    public function stepper_itinerary(){
        
        return view('kiosk.stepper.table.stepper_itinerary');
    }
    function fetch_employee_name(Request $request)
    {
     if($request->get('query'))
     {
      $query = $request->get('query');

      $data = DB::table('users')
        ->where('user_type', 'Employee')
        ->where('status', 'Active')
        ->where('employee_name', 'LIKE', "%{$query}%")
        ->orderBy('employee_name')
        ->limit(5)
        ->get();
      $output = '<ul class="dropdown-menu" style="display:block; position:relative;color: black;width:100%;">';
      foreach($data as $row)
      {
       $output .= '
       <li style="padding-left:10%;"><a href="#" style="color:black">'.$row->employee_name.'</a></li>
       ';
      }
      $output .= '</ul>';
      echo $output;
     }
    }
}