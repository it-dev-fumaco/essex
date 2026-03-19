<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpcloudStorageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:20480'],
            'folder' => ['nullable', 'string'],
        ]);

        $file = $request->file('file');
        $folder = trim((string) $request->input('folder', 'documents'), '/');
        $original = $file->getClientOriginalName();
        $ext = strtolower($file->getClientOriginalExtension() ?: 'bin');
        $base = Str::slug(pathinfo($original, PATHINFO_FILENAME)) ?: 'file';
        $filename = $base.'_'.Str::uuid().'.'.$ext;
        $path = $folder.'/'.$filename;

        try {
            Storage::disk('upcloud')->put($path, fopen($file->getRealPath(), 'r'), [
                'visibility' => 'public',
            ]);
        } catch (\Throwable $e) {
            Log::error('UpCloud upload failed', [
                'path' => $path,
                'original_name' => $original,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => Storage::disk('upcloud')->url($path),
        ]);
    }

    public function url(Request $request)
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);

        $path = ltrim((string) $request->input('path'), '/');

        return response()->json([
            'success' => true,
            'url' => Storage::disk('upcloud')->url($path),
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);

        $path = ltrim((string) $request->input('path'), '/');

        try {
            $deleted = Storage::disk('upcloud')->delete($path);
        } catch (\Throwable $e) {
            Log::error('UpCloud delete failed', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Delete failed.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'deleted' => (bool) $deleted,
        ]);
    }
}

