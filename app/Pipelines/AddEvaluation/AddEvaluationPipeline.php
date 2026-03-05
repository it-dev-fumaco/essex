<?php

declare(strict_types=1);

namespace App\Pipelines\AddEvaluation;

use App\Pipelines\AddEvaluation\Stages\SaveEvaluationRecordStage;
use App\Pipelines\AddEvaluation\Stages\StoreEvaluationFileStage;
use Illuminate\Pipeline\Pipeline;

class AddEvaluationPipeline
{
    public function __construct(
        private readonly Pipeline $pipeline,
    ) {}

    public function run(AddEvaluationPayload $payload): AddEvaluationPayload
    {
        return $this->pipeline
            ->send($payload)
            ->through([
                StoreEvaluationFileStage::class,
                SaveEvaluationRecordStage::class,
            ])
            ->thenReturn();
    }
}
