<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class StorageUrl
{
    /**
     * Public URL for a file on the UpCloud disk (e.g. img/logo5.png).
     */
    public static function get(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return Storage::disk('upcloud')->url($path);
    }

    /**
     * Shortcut for UI icons under img/.
     */
    public static function img(string $filename): string
    {
        return self::get('img/'.ltrim($filename, '/'));
    }
}
