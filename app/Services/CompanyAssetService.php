<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CompanyAsset;
use App\Pipelines\StoreCompanyAsset\StoreCompanyAssetPayload;
use App\Pipelines\StoreCompanyAsset\StoreCompanyAssetPipeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

final class CompanyAssetService
{
    public function __construct(
        private readonly GatepassService $gatepassService,
        private readonly StoreCompanyAssetPipeline $storeCompanyAssetPipeline
    ) {}

    public function storeAsset(Request $request): array
    {
        $payload = new StoreCompanyAssetPayload($request);
        $payload = $this->storeCompanyAssetPipeline->run($payload);

        return [
            'message' => 'Asset code - <b>'.$payload->asset->asset_code.'</b> has been successfully added!',
        ];
    }

    public function updateAsset(Request $request): ?CompanyAsset
    {
        $asset = CompanyAsset::find($request->id);
        if (! $asset) {
            return null;
        }

        if ($request->hasFile('imageFile')) {
            $file = $request->file('imageFile');
            $filenamewithextension = $file->getClientOriginalName();
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
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
                Log::error('UpCloud upload failed (company asset image)', [
                    'asset_id' => $asset->id ?? null,
                    'original_name' => $filenamewithextension,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }

            $asset->filename = $filenametostore;
            $asset->filepath = 'uploads/assetpicture/thumbnail/'.$filenametostore;
        }

        $asset->assetclass = $request->assetclass ?? $asset->assetclass;
        $asset->asset_code = $request->asset_code ?? $asset->asset_code;
        $asset->brand = $request->brand ?? $asset->brand;
        $asset->qty = $request->qty ?? $asset->qty;
        $asset->model = $request->model ?? $asset->model;
        $asset->serial_no = $request->serial ?? $asset->serial_no;
        $asset->mcaddress = $request->mcaddress ?? $asset->mcaddress;
        $asset->asset_desc = $request->assetdesc ?? $asset->asset_desc;
        $asset->status = $request->status ?? $asset->status;
        $asset->asset_date = date('Y-d-m');
        $asset->created_by = $request->issuedbyid ?? $asset->created_by;
        $asset->save();

        return $asset;
    }

    public function deleteAsset(int $id): ?array
    {
        $asset = CompanyAsset::find($id);
        if (! $asset) {
            return null;
        }
        $code = $asset->asset_code;
        $asset->delete();

        return ['message' => 'Asset code - <b>'.$code.'</b>  has been successfully deleted!'];
    }

    public function getCompanyAssetViewData(): array
    {
        $designation = $this->gatepassService->getSessionDetail('designation');
        $department = $this->gatepassService->getSessionDetail('department');
        $assets = null;
        $message = null;

        try {
            $assets = DB::connection('mysql_erp')
                ->table('tabAsset')
                ->whereIn('asset_category', ['Machine and Equipment', 'Car', 'Factory Equipments', 'Office Equipments', 'Tools and Equipment'])
                ->get();
        } catch (\Throwable $e) {
            $message = 'Error fetching data!!';
        }

        return [
            'designation' => $designation,
            'department' => $department,
            'assets' => $assets,
            'me' => $message,
        ];
    }

    public function getAccountabilityByItemCode(Request $request): mixed
    {
        if ($request->category == 'tabAsset') {
            return DB::connection('mysql_erp')
                ->table('tabAsset')
                ->where('item_code', $request->itemcode)
                ->first();
        }
        if ($request->category == 'tabItem') {
            return DB::connection('mysql_athena')
                ->table('item')
                ->join('components', 'components.com_id', '=', 'item.com_id')
                ->join('category', 'category.cat_id', '=', 'item.cat_id')
                ->where('item_code', $request->itemcode)
                ->select(DB::raw('category.cat_name as asset_category, item.description as name, item.serial_no, item.description, item.date_entered as purchase_date, item.image_path, item.image, components.com_type'))
                ->first();
        }

        return null;
    }

    public function getCategoryOptions(Request $request): string
    {
        $output = '';
        if ($request->category == 'tabAsset') {
            $assets = DB::connection('mysql_erp')
                ->table('tabAsset')
                ->whereIn('asset_category', ['Machine and Equipment', 'Car', 'Factory Equipments', 'Office Equipments', 'Tools and Equipment'])
                ->get();
            foreach ($assets as $row) {
                $output .= '<option value="'.$row->item_code.'">'.$row->item_code.'</option>';
            }
        }
        if ($request->category == 'tabItem') {
            $assets = DB::connection('mysql_athena')->table('item')->get();
            foreach ($assets as $row) {
                $output .= '<option value="'.$row->item_code.'">'.$row->item_code.'</option>';
            }
        }

        return $output;
    }

    public function getEmployeeAccountabilityViewData(): array
    {
        $designation = $this->gatepassService->getSessionDetail('designation');
        $department = $this->gatepassService->getSessionDetail('department');
        $user = DB::table('users')->select('user_id', 'employee_name')->where('user_type', 'Employee')->get();
        $employee_accountability = DB::table('issued_to_employee')
            ->join('users', 'users.user_id', 'issued_to_employee.issued_to')
            ->select(DB::raw('COUNT(*) as total_issued_items'), 'users.employee_name', 'issued_to_employee.issued_to')
            ->groupBy('issued_to', 'employee_name')
            ->get();

        return compact('designation', 'department', 'employee_accountability', 'user');
    }
}
