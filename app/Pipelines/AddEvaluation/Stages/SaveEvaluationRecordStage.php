<?php

declare(strict_types=1);

namespace App\Pipelines\AddEvaluation\Stages;

use App\Pipelines\AddEvaluation\AddEvaluationPayload;
use Closure;
use Illuminate\Support\Facades\DB;

class SaveEvaluationRecordStage
{
    public function handle(AddEvaluationPayload $payload, Closure $next): AddEvaluationPayload
    {
        $data = [
            [
                'employee_id' => $payload->employeeId,
                'title' => $payload->title,
                'evaluation_file' => $payload->evaluationFileStored,
                'evaluation_date' => $payload->evaluationDate,
                'evaluated_by' => $payload->evaluatedBy,
                'remarks' => $payload->remarks,
            ],
        ];

        DB::table('evaluation_files')->insert($data);

        return $next($payload);
    }
}
