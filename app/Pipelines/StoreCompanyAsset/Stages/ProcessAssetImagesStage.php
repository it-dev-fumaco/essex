<?php

declare(strict_types=1);

namespace App\Pipelines\StoreCompanyAsset\Stages;

use App\Pipelines\StoreCompanyAsset\StoreCompanyAssetPayload;
use Closure;
use Illuminate\Support\Facades\Storage;
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
                $filenametostore = $filename . '_' . uniqid() . '.' . $extension;

                Storage::put('public/uploads/assetpicture/' . $filenametostore, fopen($file, 'r+'));
                Storage::put('public/uploads/assetpicture/thumbnail/' . $filenametostore, fopen($file, 'r+'));

                $thumbnailpath = public_path('storage/uploads/assetpicture/thumbnail/' . $filenametostore);
                $img = Image::make($thumbnailpath)->resize(750, 500, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($thumbnailpath);

                $payload->filenametostore = $filenametostore;
                $payload->path = 'uploads/assetpicture/thumbnail/' . $filenametostore;
            }
        }

        return $next($payload);
    }
}
