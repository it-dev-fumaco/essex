<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Services\CompanyAssetService;
use App\Services\GatepassService;
use Illuminate\Http\Request;
use Auth;

class GatepassesController extends Controller
{
    public function __construct(
        private readonly GatepassService $gatepassService,
        private readonly CompanyAssetService $companyAssetService
    ) {
    }

    public function index(Request $request)
    {
        $gatepasses = $this->gatepassService->getFilteredPaginated($request, 20);
        return view('admin.gatepasses')->with('gatepasses', $gatepasses);
    }

    public function store(Request $request)
    {
        $message = $this->gatepassService->storeGatepass($request);
        return response()->json(['message' => $message]);
    }

    public function updateStatus(Request $request)
    {
        $message = $this->gatepassService->updateStatus($request);
        return response()->json(['message' => $message]);
    }

    public function destroy($id)
    {
        $this->gatepassService->destroy((int) $id);
        return redirect('/admin/gatepasses')->with('message', 'Gatepass successfully deleted');
    }

    public function gatepassesForApproval(Request $request)
    {
        if ($request->ajax()) {
            $pending_gatepasses = $this->gatepassService->getGatepassesForApprovalPaginated(8);
            return view('client.tables.gatepasses_for_approval_table', compact('pending_gatepasses'))->render();
        }
    }

    public function unreturnedItems()
    {
        $unreturned_items = $this->gatepassService->getUnreturnedItems();
        return view('admin.unreturned_items')->with('unreturned_items', $unreturned_items);
    }

    public function fetchGatepasses(Request $request)
    {
        if ($request->ajax()) {
            $gatepasses = $this->gatepassService->getFetchGatepassesPaginated((int) Auth::user()->user_id, 8);
            return view('client.tables.gatepasses_table', compact('gatepasses'))->render();
        }
    }

    public function getGatepassDetails(Request $request)
    {
        $gatepass = $this->gatepassService->getGatepassDetails((int) $request->id);
        return response()->json($gatepass);
    }

    public function updateGatepassDetails(Request $request)
    {
        $message = $this->gatepassService->updateGatepassDetails($request);
        return response()->json(['message' => $message]);
    }

    public function cancelGatepass(Request $request)
    {
        $message = $this->gatepassService->cancelGatepass($request);
        return response()->json(['message' => $message]);
    }

    public function getGatepasses(Request $request)
    {
        if ($request->ajax()) {
            $filteredGatepass = $this->gatepassService->getGatepassesFilteredPaginated($request, 7);
            return view('client.tables.manage_gatepass_table', compact('filteredGatepass'))->render();
        }
    }

    public function printGatepass(Request $request, $id)
    {
        $gatepass = $this->gatepassService->getPrintGatepass((int) $id);
        return view('client.print.printGatepass', compact('gatepass'));
    }

    public function getUnreturnedGatepass(Request $request)
    {
        if ($request->ajax()) {
            $unreturned_gatepass = $this->gatepassService->getUnreturnedGatepassPaginated($request, 8);
            return view('client.tables.unreturned_gatepass_table', compact('unreturned_gatepass'))->render();
        }
    }

    public function updateUnreturnedGatepass(Request $request)
    {
        $message = $this->gatepassService->updateUnreturnedGatepass($request);
        return response()->json(['message' => $message]);
    }

    public function countPendingGatepass(Request $request)
    {
        if ($request->ajax()) {
            return $this->gatepassService->countPendingGatepass();
        }
    }

    public function sessionDetails($column)
    {
        return $this->gatepassService->getSessionDetail($column);
    }

    public function showAnalytics()
    {
        $designation = $this->gatepassService->getSessionDetail('designation');
        $department = $this->gatepassService->getSessionDetail('department');
        $totals = $this->gatepassService->getAnalyticsTotals();
        return view('client.modules.gatepass.analytics', compact('designation', 'department', 'totals'));
    }

    public function purposeRateChart(Request $request)
    {
        $data = $this->gatepassService->getPurposeRateChartData((int) $request->year);
        return response()->json($data);
    }

    public function gatepassPerDeptChart(Request $request)
    {
        $gatepass_per_dept = $this->gatepassService->getGatepassPerDeptChart($request->purpose, (int) $request->year);
        return $gatepass_per_dept;
    }

    public function showGatepassHistory()
    {
        $data = $this->gatepassService->getShowGatepassHistoryData();
        return view('client.modules.gatepass.history', $data);
    }

    public function showUnreturnedItems()
    {
        $data = $this->gatepassService->getShowUnreturnedItemsData();
        return view('client.modules.gatepass.unreturned_items', $data);
    }

    public function storeAsset(StoreAssetRequest $request)
    {
        $result = $this->companyAssetService->storeAsset($request);
        return redirect()->back()->with($result);
    }

    public function updateAsset(UpdateAssetRequest $request)
    {
        $asset = $this->companyAssetService->updateAsset($request);
        if ($asset === null) {
            return redirect()->back();
        }
        return redirect()->back()->with(['message' => 'Asset code - <b>' . $asset->asset_code . '</b> has been successfully updated!']);
    }

    public function deleteAsset(Request $request)
    {
        $result = $this->companyAssetService->deleteAsset((int) $request->id);
        if ($result === null) {
            return redirect()->back();
        }
        return redirect()->back()->with($result);
    }

    public function uploadImage(Request $request)
    {
        return redirect()->back()->with('message', 'Image(s) has been successfully uploaded!');
    }

    public function showCompanyAsset()
    {
        $data = $this->companyAssetService->getCompanyAssetViewData();
        return view('client.modules.gatepass.company_asset.company_asset', $data);
    }

    public function getItemsIssuedtoEmployee($user_id)
    {
        $items = $this->gatepassService->getItemsIssuedToEmployee($user_id);
        return response()->json($items);
    }

    public function showAccountability(Request $request)
    {
        $assets = $this->companyAssetService->getAccountabilityByItemCode($request);
        return response()->json($assets);
    }

    public function showCateg(Request $request)
    {
        $output = $this->companyAssetService->getCategoryOptions($request);
        return response()->json($output);
    }

    public function showEmployeeAccountability(Request $request)
    {
        $data = $this->companyAssetService->getEmployeeAccountabilityViewData();
        return view('client.modules.gatepass.employee_accountability.index', $data);
    }
}