<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemAccountabilityUpdateRequest;
use App\Models\CompanyAsset;
use App\Models\ItemAccountability;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class ItemAccountabilityController extends Controller
{
    public function sessionDetails($column)
    {
        $detail = DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', Auth::user()->user_id)
            ->first();

        return $detail->$column;
    }

    public function index($user_id)
    {
        $itemlist = DB::table('issued_item')
            ->select('item_id', 'item_code', 'item_desc', 'date_issued', 'issued_by', 'status', 'itemclass', 'brand', 'qty', 'model', 'serial_no', 'mcaddress')
            ->get();
        $issued_to = DB::table('issued_to_employee')
            ->select('users.employee_name', 'users.employment_status', 'issued_to_employee.id', 'issued_to_employee.item_code', 'issued_to_employee.name', 'issued_to_employee.desc', 'issued_to_employee.category', 'issued_to_employee.class', 'issued_to_employee.purchase_orderno', 'issued_to_employee.item_status', 'issued_to_employee.issued_to', 'issued_to_employee.issued_by', 'issued_to_employee.issued_by_name', 'issued_to_employee.purchase_date', 'issued_to_employee.status', 'issued_to_employee.brand', 'issued_to_employee.brand', 'issued_to_employee.qty', 'issued_to_employee.model', 'issued_to_employee.serial_no', 'issued_to_employee.mcaddress', 'issued_to_employee.plate_no', 'issued_to_employee.filename', 'issued_to_employee.filepath', 'issued_to_employee.issued_date', 'issued_to_employee.color', 'issued_to_employee.engine', 'issued_to_employee.chasis', 'issued_to_employee.driver_license', 'issued_to_employee.rc_no', 'issued_to_employee.dl_type', 'issued_to_employee.updated_at', 'issued_to_employee.last_modified_by')
            ->join('users', 'users.user_id', '=', 'issued_to_employee.issued_to')
            ->where('issued_to_employee.issued_to', $user_id)
            ->get();

        $issued_by = DB::table('issued_to_employee')
            ->select('users.employee_name')
            ->join('users', 'users.user_id', '=', 'issued_to_employee.issued_by')
            ->where('issued_to_employee.issued_to', $user_id)
            ->first();

        $detail = DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', Auth::user()->user_id)
            ->first();

        $employee_profile = DB::table('users')
            ->join('designation', 'users.designation_id', '=', 'designation.des_id')
            ->join('departments', 'users.department_id', '=', 'departments.department_id')
            ->where('user_id', $user_id)
            ->first();

        $user = DB::table('users')
            ->select('user_id', 'employee_name')
            ->where('user_type', 'Employee')
            ->get();

        $gatepass = DB::table('gatepass')->join('users', 'users.user_id', 'gatepass.user_id')->select(DB::raw('COUNT(*) as total_issued_items'), 'users.employee_name', 'gatepass.user_id')->where('gatepass.user_id', $user_id)->groupBy('user_id', 'employee_name')->get();

        // $code = new ItemAccountability();
        // $lastcodeID = $code->orderBy('item_id', 'DESC')->pluck('item_id')->first();
        // $newcodeID = $lastcodeID + 1;
        // $neww= date('Y').'00000';
        // $newly=$neww + $newcodeID;
        // $newwwly='FUM'.'-'.$newly;
        $status = $employee_profile->employment_status;
        $employee_name = $employee_profile->employee_name;
        $employee_id = $employee_profile->user_id;
        $desig = $employee_profile->designation;
        $depart = $employee_profile->department;

        $designation = $detail->designation;
        $department = $detail->department;

        return view('client.item_accountability.item_accountability', compact('designation', 'department', 'itemlist', 'issued_to', 'desig', 'depart', 'issued_by', 'status', 'employee_name', 'employee_profile', 'user', 'employee_id', 'gatepass'));

    }
    //  public function store(Request $request){
    //   $this->validate($request, [
    //         'itemclass' => 'required',
    //         'item_code' => 'required',
    //         'brand' => 'required',
    //         'qty' => 'required',
    //         'model' => 'required',
    //         'serial' => 'required',
    //         'mcaddress' => 'required',
    //         'itemdesc' => 'required']);
    //     $itemissued = new ItemAccountability;
    //     $status="Active";
    //     $date=date('Y-d-m');
    //     $itemissued->itemclass = $request->itemclass;
    //     $itemissued->item_code = $request->item_code;
    //     $itemissued->brand = $request->brand;
    //     $itemissued->qty = $request->qty;
    //     $itemissued->model = $request->model;
    //     $itemissued->serial_no = $request->serial;
    //     $itemissued->mcaddress = $request->mcaddress;
    //     $itemissued->item_desc = $request->itemdesc;
    //     $itemissued->status = $status;
    //     $itemissued->date_issued = $date;
    //     $itemissued->issued_by = $request->issuedby;
    //     $itemissued->issued_byID = $request->issuedbyid;
    //     $itemissued->issued_byID = $request->issuedbyid;
    //     $itemissued->issued_to = $request->issuedto;
    //     $itemissued->save();

    //     return redirect()->back()->with(["message" => "Item code - <b>" . $itemissued->item_code . "</b> has been successfully added!"]);
    // }
    public function updateAsset(Request $request, $user_id)
    {
        $date = date('Y-m-d');
        if ($request->hasFile('imageFile')) {

            foreach ($request->file('imageFile') as $file) {
                // get filename with extension
                $filenamewithextension = $file->getClientOriginalName();

                // get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                // get file extension
                $extension = $file->getClientOriginalExtension();

                // filename to store
                $safeBase = Str::slug($filename) ?: 'asset';
                $filenametostore = $safeBase.'_'.Str::uuid().'.'.$extension;

                try {
                    $disk = Storage::disk('upcloud');

                    $disk->put('uploads/assetpicture/'.$filenametostore, fopen($file->getRealPath(), 'r'), [
                        'visibility' => 'public',
                    ]);

                    $thumbnail = Image::make($file->getRealPath())
                        ->resize(750, 500, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->encode($extension, 85);

                    $disk->put('uploads/assetpicture/thumbnail/'.$filenametostore, (string) $thumbnail, [
                        'visibility' => 'public',
                    ]);
                } catch (\Throwable $e) {
                    Log::error('UpCloud upload failed (item accountability asset update)', [
                        'user_id' => $user_id ?? null,
                        'original_name' => $filenamewithextension,
                        'error' => $e->getMessage(),
                    ]);

                    return redirect()->back()->with(['message' => 'Upload failed. Please try again.']);
                }

                $path = 'uploads/assetpicture/thumbnail/'.$filenametostore;

                $asset = CompanyAsset::find($user_id);
                $date = date('Y-m-d');
                $asset->category = $request->updatecategory;
                $asset->item_code = $request->updateitemcode;
                $asset->name = $request->updatename;
                $asset->desc = $request->updatedescc;
                $asset->class = $request->updateitem_category;
                $asset->purchase_orderno = $request->updatepurchase_order;
                $asset->item_status = $request->updatestatus;
                $asset->issued_to = $request->issued_to;
                $asset->issued_by = $request->issued_by;
                $asset->purchase_date = $request->updatepurchase_date;
                $asset->status = $request->updatestat;
                $asset->qty = $request->updateqty;
                $asset->model = $request->updatemodel;
                $asset->brand = $request->updatebrand;
                $asset->serial_no = $request->updateserial_no;
                $asset->mcaddress = $request->updatemcaddress;
                $asset->plate_no = $request->updateplate;
                $asset->color = $request->updatecolor;
                $asset->chasis = $request->updatechasis;
                $asset->engine = $request->updateengine;
                $asset->driver_license = $request->updatedln;
                $asset->dl_type = $request->updatedl_type;
                $asset->rc_no = $request->updaterc_no;
                $asset->filename = $filenametostore;
                $asset->filepath = $path;
                $asset->last_modified_by = Auth::user()->employee_name;

                $asset->save();

                return redirect()->back()->with(['message' => 'Item has been successfully updated!']);
                // return $asset;
            }
        } else {
            $asset = CompanyAsset::find($user_id);

            $date = date('Y-m-d');
            $asset->category = $request->updatecategory;
            $asset->item_code = $request->updateitemcode;
            $asset->name = $request->updatename;
            $asset->desc = $request->updatedescc;
            $asset->class = $request->updateitem_category;
            $asset->purchase_orderno = $request->updatepurchase_order;
            $asset->item_status = $request->updatestatus;
            $asset->issued_to = $request->issued_to;
            $asset->issued_by = $request->issued_by;
            $asset->purchase_date = $request->updatepurchase_date;
            $asset->status = $request->updatestat;
            $asset->qty = $request->updateqty;
            $asset->model = $request->updatemodel;
            $asset->brand = $request->updatebrand;
            $asset->serial_no = $request->updateserial_no;
            $asset->mcaddress = $request->updatemcaddress;
            $asset->plate_no = $request->updateplate;
            $asset->color = $request->updatecolor;
            $asset->chasis = $request->updatechasis;
            $asset->engine = $request->updateengine;
            $asset->driver_license = $request->updatedln;
            $asset->dl_type = $request->updatedl_type;
            $asset->rc_no = $request->updaterc_no;
            $asset->last_modified_by = Auth::user()->employee_name;

            $asset->save();

            return redirect()->back()->with(['message' => 'Item has been successfully updated!']);

        }

        // return $data;

    }

    public function storeAsset(Request $request)
    {
        $data = [];
        $status = 'Active';
        $date = date('Y-m-d');
        $category = $request->category;
        $finalcat = $this->cat($category);
        if ($request->hasFile('imageFile')) {

            foreach ($request->file('imageFile') as $file) {
                // get filename with extension
                $filenamewithextension = $file->getClientOriginalName();

                // get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                // get file extension
                $extension = $file->getClientOriginalExtension();

                // filename to store
                $safeBase = Str::slug($filename) ?: 'asset';
                $filenametostore = $safeBase.'_'.Str::uuid().'.'.$extension;

                try {
                    $disk = Storage::disk('upcloud');

                    $disk->put('uploads/assetpicture/'.$filenametostore, fopen($file->getRealPath(), 'r'), [
                        'visibility' => 'public',
                    ]);

                    $thumbnail = Image::make($file->getRealPath())
                        ->resize(750, 500, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->encode($extension, 85);

                    $disk->put('uploads/assetpicture/thumbnail/'.$filenametostore, (string) $thumbnail, [
                        'visibility' => 'public',
                    ]);
                } catch (\Throwable $e) {
                    Log::error('UpCloud upload failed (item accountability asset create)', [
                        'original_name' => $filenamewithextension,
                        'error' => $e->getMessage(),
                    ]);

                    return redirect()->back()->with(['message' => 'Upload failed. Please try again.']);
                }

                $path = 'uploads/assetpicture/thumbnail/'.$filenametostore;

                $data[] = [
                    'category' => $finalcat,
                    'item_code' => $request->item_code,
                    'name' => $request->name,
                    'desc' => $request->desc,
                    'class' => $request->item_category,
                    'purchase_orderno' => $request->purchase_order,
                    'item_status' => $request->status,
                    'issued_to' => $request->issued_to,
                    'issued_by' => $request->issued_by,
                    'issued_by_name' => $request->issued_by_name,
                    'purchase_date' => $request->purchase_date,
                    'status' => $status,
                    'qty' => (int) $request->qty,
                    'brand' => $request->brand,
                    'serial_no' => $request->serial_no,
                    'mcaddress' => $request->mcaddress,
                    'plate_no' => $request->plate,
                    'model' => $request->model,
                    'color' => $request->color,
                    'chasis' => $request->chasis,
                    'engine' => $request->engine,
                    'driver_license' => $request->dln,
                    'dl_type' => $request->dl_type,
                    'rc_no' => $request->rc_no,

                    'issued_date' => $date,
                    'filename' => $filenametostore,
                    'filepath' => $path,
                ];
                // $asset = new CompanyAsset;
                // $status="Active";
                // $date=date('Y-d-m');
                // $asset->category = $request->category;
                // $asset->item_code = $request->item_code;
                // $asset->name = $request->name;
                // $asset->desc = $request->desc;
                // $asset->class = $request->item_category;
                // $asset->purchase_orderno = $request->purchase_order;
                // $asset->item_status = $request->status;
                // $asset->issued_to = $request->issued_to;
                // $asset->issued_by = $request->issued_by;
                // $asset->purchase_date = $request->purchase_date;
                // $asset->status = $status;
                // $asset->qty = $request->qty;
                // $asset->brand = $request->brand;
                // $asset->serial_no = $request->serial_no;
                // $asset->mcaddress = $request->mcaddress;
                // $asset->plate_no = $request->plate;
                // $asset->issued_date = $date;
                // $asset->filename=$filenametostore;
                // $asset->filepath=$path;
                // $asset->save();

                // return redirect()->back()->with(["message" => "Item code - <b>" . $asset->item_code ."-".$asset->name."</b>  has been successfully added!"]);
                // dd($asset);
            }
        } else {
            $data[] = [
                'category' => $finalcat,
                'item_code' => $request->item_code,
                'name' => $request->name,
                'desc' => $request->desc,
                'class' => $request->item_category,
                'purchase_orderno' => $request->purchase_order,
                'item_status' => $request->status,
                'issued_to' => $request->issued_to,
                'issued_by' => $request->issued_by,
                'issued_by_name' => $request->issued_by_name,
                'purchase_date' => $request->purchase_date,
                'status' => $status,
                'qty' => (int) $request->qty,
                'brand' => $request->brand,
                'serial_no' => $request->serial_no,
                'mcaddress' => $request->mcaddress,
                'plate_no' => $request->plate,
                'model' => $request->model,
                'color' => $request->color,
                'chasis' => $request->chasis,
                'engine' => $request->engine,
                'driver_license' => $request->dln,
                'dl_type' => $request->dl_type,
                'rc_no' => $request->rc_no,

                'issued_date' => $date,
            ];
        }

        $asset = DB::table('issued_to_employee')->insert($data);

        return redirect()->back()->with(['message' => 'Item has been successfully added!']);
        // return $data;
    }

    public function update(ItemAccountabilityUpdateRequest $request)
    {
        $itemissued = ItemAccountability::find($request->id);
        $status = 'Active';
        $date = date('Y-d-m');
        $itemissued->itemclass = $request->itemclass;
        $itemissued->item_code = $request->item_code;
        $itemissued->brand = $request->brand;
        $itemissued->qty = $request->qty;
        $itemissued->model = $request->model;
        $itemissued->serial_no = $request->serial;
        $itemissued->mcaddress = $request->mcaddress;
        $itemissued->item_desc = $request->itemdesc;
        $itemissued->status = $status;
        $itemissued->date_issued = $date;
        $itemissued->issued_by = $request->issuedby;
        $itemissued->issued_byID = $request->issuedbyid;
        $itemissued->issued_to = $request->issuedto;
        $itemissued->save();

        return redirect()->back()->with(['message' => 'Item code - <b>'.$itemissued->item_code.'</b> has been successfully updated!']);
    }

    public function delete(Request $request)
    {
        $itemissued = CompanyAsset::find($request->id);
        $itemissued->delete();

        return redirect()->back()->with(['message' => 'Item code - <b>'.$itemissued->item_code.'</b>  has been successfully deleted!']);
    }

    public function print($user_id)
    {
        $employee_profile = User::join('departments', 'users.department_id', 'departments.department_id')
            ->join('designation', 'users.designation_id', 'designation.des_id')
            ->where('user_id', $user_id)
            ->select('users.*', 'departments.department', 'designation.designation')
            ->first();

        $departments = DB::table('departments')->get();
        $designations = DB::table('designation')->get();

        $itemlist = DB::table('issued_to_employee')
                        // ->select('item_id', 'item_code','item_desc','date_issued','issued_by','status','itemclass','brand','qty','model','serial_no','mcaddress')
            ->where('issued_to', $user_id)
            ->get();
        foreach ($itemlist as $key) {
            $data = [];
            $issuedby = User::join('departments', 'users.department_id', 'departments.department_id')
                ->join('designation', 'users.designation_id', 'designation.des_id')
                ->where('user_id', $key->issued_by)
                ->select('employee_name')
                ->first();
        }
        $issued_by_name = $issuedby->employee_name;
        $data = [
            'employee_profile' => $employee_profile,
            'designation' => $this->sessionDetails('designation'),
            'department' => $this->sessionDetails('department'),
            'departments' => $departments,
            'designations' => $designations,
            'itemlist' => $itemlist,
            'user_id' => $user_id,
            'issued_byy' => $issued_by_name,
        ];

        return view('client.item_accountability.print')->with($data);
    }

    public function cat($category)
    {
        if ($category == 'tabAsset') {
            $cat = 'Fix Asset';
        } else {
            $cat = 'Item';
        }

        return $cat;
    }
}
