<?php

declare(strict_types=1);

namespace App\Pipelines\AddEvaluation\Stages;

use App\Pipelines\AddEvaluation\AddEvaluationPayload;
use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            $safeBase = Str::slug($filename) ?: 'evaluation';
            $filenametostore = $safeBase.'_'.Str::uuid().'.'.$extension;

            try {
                Storage::disk('upcloud')->put('uploads/evaluations/'.$filenametostore, $file->get(), [
                    'visibility' => 'public',
                ]);
            } catch (\Throwable $e) {
                Log::error('UpCloud upload failed (add evaluation)', [
                    'employee_id' => $payload->employeeId ?? null,
                    'original_name' => $filenamewithextension,
                    'error' => $e->getMessage(),
                ]);

                throw $e;
            }
        }

        $payload->evaluationFileStored = $filenametostore;

        return $next($payload);
    }
}
