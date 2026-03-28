<?php

declare(strict_types=1);

namespace App\Pipelines\StoreCompanyAsset\Stages;

use App\Pipelines\StoreCompanyAsset\StoreCompanyAssetPayload;
use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProcessAssetImagesStage
{
    public function handle(StoreCompanyAssetPayload $payload, Closure $next): StoreCompanyAssetPayload
    {
        $request = $payload->request;

        if ($request->hasFile('imageFile')) {
            foreach ($request->file('imageFile') as $file) {
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
                    Log::error('UpCloud upload failed (store company asset pipeline)', [
                        'original_name' => $filenamewithextension,
                        'error' => $e->getMessage(),
                    ]);

                    throw $e;
                }

                $payload->filenametostore = $filenametostore;
                $payload->path = 'uploads/assetpicture/thumbnail/'.$filenametostore;
            }
        }

        return $next($payload);
    }
}
