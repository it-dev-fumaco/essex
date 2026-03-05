<?php

declare(strict_types=1);

namespace App\Pipelines\AddEvaluation;

use Illuminate\Http\UploadedFile;

class AddEvaluationPayload
{
    public ?string $evaluationFileStored = null;

    public function __construct(
        public readonly int|string $employeeId,
        public readonly string $title,
        public readonly ?UploadedFile $evaluationFile,
        public readonly string $evaluationDate,
        public readonly int|string $evaluatedBy,
        public readonly ?string $remarks = null,
    ) {
    }
}
