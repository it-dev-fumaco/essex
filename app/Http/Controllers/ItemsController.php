<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Item;
use DB;

class ItemsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();
                        
        return view('admin.items')->with('items', $items);
    }

    public function store(Request $request){
        $item = new Item;
        $item->item_name = $request->item_name;
        $item->item_type = $request->item_type;
        $item->model = $request->model;
        $item->brand = $request->brand;
        $item->serial_no = $request->serial_no;
        $item->mac_address = $request->mac_address;
        $item->description = $request->description;
        $item->remarks = $request->remarks;
        $item->other_unique_references = $request->references;
        $item->save();

        return redirect('/admin/items')->with('message', 'Item successfully added');
    }

     public function update(Request $request, $id){
        $item = Item::find($id);
        $item->item_name = $request->item_name;
        $item->item_type = $request->item_type;
        $item->model = $request->model;
        $item->brand = $request->brand;
        $item->serial_no = $request->serial_no;
        $item->mac_address = $request->mac_address;
        $item->description = $request->description;
        $item->remarks = $request->remarks;
        $item->other_unique_references = $request->references;
        $item->save();

        return redirect('/admin/items')->with('message', 'Item successfully updated');
    }

    public function destroy($id){
        Item::destroy($id);
        return redirect('/admin/items')->with('message', 'Item successfully deleted');
    }

    public function issuedItems(){
        $issued_items = DB::table('issued_to_employee')
                        ->join('users', 'users.user_id', '=', 'issued_to_employee.user_id')
                        ->join('item_for_employee', 'item_for_employee.item_id', '=', 'issued_to_employee.item_id')
                        ->select('users.employee_name', 'item_for_employee.*', 'issued_to_employee.*')
                        ->get();
        $employees = DB::table('users')->get();
        $items = DB::table('item_for_employee')->get();

        return view('/admin/items_issued', ['issued_items' => $issued_items, 'employees' => $employees, 'items' => $items]);
    }

    public function issueItems(Request $request){
        DB::table('issued_to_employee')
                ->insert([
                    'user_id' => $request->employee,
                    'item_id' => $request->item,
                    'status' => $request->status,
                    'date_issued' => $request->date_issued,
                    'issued_by' => $request->issued_by,
                    'valid_until' => $request->valid_until,
                    'revoke_reason' => $request->revoke_reason,
                    'remarks' => $request->remarks
                ]);

        return redirect('/admin/items_issued')->with('message', 'Item successfully issued');
    }

    public function updateIssuedItems(Request $request, $id){
        DB::table('issued_to_employee')->where('issue_id', '=', $id)->update([
            'user_id' => $request->employee,
            'item_id' => $request->item,
            'status' => $request->status,
            'date_issued' => $request->date_issued,
            'issued_by' => $request->issued_by,
            'valid_until' => $request->valid_until,
            'revoke_reason' => $request->revoke_reason,
            'remarks' => $request->remarks
        ]);

        return redirect('/admin/items_issued')->with('message', 'Item successfully updated');
    }
}