<?php

declare(strict_types=1);

namespace App\Services;

final class PostLoginCommandRunner
{
    public function runForUser(int $userId): void
    {
        $path = env('BASE_PATH');
        $path = ($path !== null && $path !== '') ? $path : base_path();

        try {
            $id = (string) $userId;
            exec('cd '.escapeshellarg($path).' && php artisan emails:birthday --id='.escapeshellarg($id));
            exec('cd '.escapeshellarg($path).' && php artisan emails:worksary --id='.escapeshellarg($id));
        } catch (\Throwable $e) {
            // Preserve original behavior: silent catch
        }
    }
}
