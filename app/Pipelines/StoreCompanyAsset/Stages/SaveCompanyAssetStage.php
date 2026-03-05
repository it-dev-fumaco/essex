<?php

declare(strict_types=1);

namespace App\Pipelines\StoreCompanyAsset\Stages;

use App\Models\CompanyAsset;
use App\Pipelines\StoreCompanyAsset\StoreCompanyAssetPayload;
use Closure;

class SaveCompanyAssetStage
{
    public function handle(StoreCompanyAssetPayload $payload, Closure $next): StoreCompanyAssetPayload
    {
        $request = $payload->request;

        $asset = new CompanyAsset;
        $status = 'Active';
        $date = date('Y-d-m');
        $asset->assetclass = $request->assetclass;
        $asset->asset_code = $request->asset_code;
        $asset->brand = $request->brand;
        $asset->qty = $request->qty;
        $asset->model = $request->model;
        $asset->serial_no = $request->serial;
        $asset->mcaddress = $request->mcaddress;
        $asset->asset_desc = $request->assetdesc;
        $asset->status = $status;
        $asset->asset_date = $date;
        $asset->created_by = $request->issuedbyid;
        $asset->filename = $payload->filenametostore;
        $asset->filepath = $payload->path;
        $asset->save();

        $payload->asset = $asset;

        return $next($payload);
    }
}
