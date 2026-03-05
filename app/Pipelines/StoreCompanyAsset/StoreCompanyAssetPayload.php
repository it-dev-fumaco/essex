<?php

declare(strict_types=1);

namespace App\Pipelines\StoreCompanyAsset;

use App\Models\CompanyAsset;
use Illuminate\Http\Request;

class StoreCompanyAssetPayload
{
    public ?string $filenametostore = null;

    public ?string $path = null;

    public ?CompanyAsset $asset = null;

    public function __construct(
        public readonly Request $request,
    ) {
    }
}
