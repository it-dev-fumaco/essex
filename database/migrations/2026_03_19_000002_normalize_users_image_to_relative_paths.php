<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration {
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('users') || ! DB::getSchemaBuilder()->hasColumn('users', 'image')) {
            return;
        }

        $diskUrl = (string) config('filesystems.disks.upcloud.url', '');
        $bucket = (string) config('filesystems.disks.upcloud.bucket', '');

        $updated = 0;
        $skipped = 0;

        DB::table('users')
            ->select('id', 'image')
            ->whereNotNull('image')
            ->where('image', 'like', 'http%')
            ->orderBy('id')
            ->chunkById(200, function ($rows) use (&$updated, &$skipped, $diskUrl, $bucket) {
                foreach ($rows as $row) {
                    $original = (string) $row->image;
                    $key = $this->extractUpcloudKey($original, $diskUrl, $bucket);

                    if (! $key) {
                        $skipped++;
                        continue;
                    }

                    DB::table('users')->where('id', $row->id)->update(['image' => $key]);
                    $updated++;
                }
            });

        Log::info('Normalized users.image UpCloud URLs to keys', [
            'updated' => $updated,
            'skipped' => $skipped,
        ]);
    }

    public function down(): void
    {
        // No rollback: original full URLs are not recoverable from keys.
    }

    private function extractUpcloudKey(string $url, string $diskUrl, string $bucket): ?string
    {
        $url = trim($url);
        if ($url === '' || ! str_starts_with($url, 'http')) {
            return null;
        }

        // If URL matches configured base URL, strip it.
        if ($diskUrl !== '' && str_starts_with($url, $diskUrl)) {
            $key = ltrim(substr($url, strlen($diskUrl)), '/');
            $key = strtok($key, '?#') ?: $key;
            return $key !== '' ? $key : null;
        }

        // Otherwise parse and try to remove "/{bucket}/" prefix (path-style URLs).
        $parts = parse_url($url);
        if (! is_array($parts)) {
            return null;
        }

        $path = (string) ($parts['path'] ?? '');
        $path = ltrim($path, '/');

        if ($bucket !== '' && str_starts_with($path, $bucket.'/')) {
            $path = substr($path, strlen($bucket) + 1);
        }

        $path = strtok($path, '?#') ?: $path;
        return $path !== '' ? $path : null;
    }
};

