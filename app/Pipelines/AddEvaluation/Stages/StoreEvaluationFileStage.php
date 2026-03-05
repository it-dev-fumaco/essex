<?php

declare(strict_types=1);

namespace App\Pipelines\AddEvaluation\Stages;

use App\Pipelines\AddEvaluation\AddEvaluationPayload;
use Closure;
use Illuminate\Support\Facades\Storage;

class StoreEvaluationFileStage
{
    public function handle(AddEvaluationPayload $payload, Closure $next): AddEvaluationPayload
    {
        $filenametostore = null;

        if ($payload->evaluationFile !== null) {
            $file = $payload->evaluationFile;
            $filenamewithextension = $file->getClientOriginalName();
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenametostore = $filename . '_' . uniqid() . '.' . $extension;
            Storage::put('public/uploads/evaluations/' . $filenametostore, $file->get());
        }

        $payload->evaluationFileStored = $filenametostore;

        return $next($payload);
    }
}
