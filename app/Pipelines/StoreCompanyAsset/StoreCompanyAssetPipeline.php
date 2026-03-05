<?php

declare(strict_types=1);

namespace App\Pipelines\StoreCompanyAsset;

use App\Pipelines\StoreCompanyAsset\Stages\ProcessAssetImagesStage;
use App\Pipelines\StoreCompanyAsset\Stages\SaveCompanyAssetStage;
use Illuminate\Pipeline\Pipeline;

class StoreCompanyAssetPipeline
{
    public function __construct(
        private readonly Pipeline $pipeline,
    ) {
    }

    public function run(StoreCompanyAssetPayload $payload): StoreCompanyAssetPayload
    {
        return $this->pipeline
            ->send($payload)
            ->through([
                ProcessAssetImagesStage::class,
                SaveCompanyAssetStage::class,
            ])
            ->thenReturn();
    }
}
