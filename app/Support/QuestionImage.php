<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class QuestionImage
{
    /**
     * Public URL for an exam question image stored under questions/ on UpCloud.
     */
    public static function url(?string $filename): string
    {
        $filename = trim((string) $filename);
        if ($filename === '') {
            return '';
        }

        $filename = basename(str_replace('\\', '/', $filename));

        return Storage::disk('upcloud')->url('questions/'.$filename);
    }

    /**
     * Public URL for an exam option image stored under options/ on UpCloud.
     */
    public static function optionUrl(?string $filename): string
    {
        $filename = trim((string) $filename);
        if ($filename === '') {
            return '';
        }

        $filename = basename(str_replace('\\', '/', $filename));

        return Storage::disk('upcloud')->url('options/'.$filename);
    }
}
