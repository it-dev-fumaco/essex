<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Resolves UpCloud (or legacy) employee avatar URLs from `users.image` and `user_id`.
 */
final class EmployeeAvatarUrlResolver
{
    /**
     * @param  string|null  $imageValue  Raw `users.image` (relative key, legacy storage path, or absolute URL).
     * @param  string|null  $userId  HR access / directory `user_id`.
     * @param  bool  $skipExistsCheck  When true, skip remote exists() (directory listing); DB key must be tried first.
     * @param  int|null  $cacheBusterTimestamp  e.g. `updated_at` unix timestamp appended as `?v=` / `&v=`.
     */
    public function resolve(
        ?string $imageValue,
        ?string $userId = null,
        bool $skipExistsCheck = false,
        ?int $cacheBusterTimestamp = null,
    ): string {
        $default = asset('storage/img/user.png');

        $image = $imageValue ? trim((string) $imageValue) : '';
        if ($image === '') {
            return $default;
        }

        if (Str::startsWith($image, ['http://', 'https://'])) {
            try {
                $parts = parse_url($image);
                $imageHost = strtolower((string) ($parts['host'] ?? ''));
                $imagePath = (string) ($parts['path'] ?? '');

                $appHost = strtolower((string) (parse_url((string) config('app.url'), PHP_URL_HOST) ?: ''));
                $assetHost = strtolower((string) (parse_url((string) config('app.asset_url'), PHP_URL_HOST) ?: ''));

                $isLocalHost = ($imageHost !== '' && ($imageHost === $appHost || ($assetHost !== '' && $imageHost === $assetHost)));

                if ($isLocalHost && Str::startsWith($imagePath, ['/storage/', 'storage/'])) {
                    $image = ltrim($imagePath, '/');
                } else {
                    $id = $userId ? trim((string) $userId) : null;
                    if ($id !== null && $id !== '') {
                        $upcloudProfile = $this->resolveFromCandidates(
                            $this->buildCandidateKeys('', '', '', $id),
                            false,
                            $default
                        );
                        if ($upcloudProfile !== $default) {
                            return $this->withCacheBuster($upcloudProfile, $cacheBusterTimestamp);
                        }
                    }

                    return $this->withCacheBuster($image, $cacheBusterTimestamp);
                }
            } catch (\Throwable) {
                return $this->withCacheBuster($image, $cacheBusterTimestamp);
            }
        }

        if (Str::startsWith($image, ['/storage/', 'storage/'])) {
            $image = str_replace(['storage/', '/storage/'], '', $image);
        }

        $normalized = ltrim(str_replace('\\', '/', $image), '/');
        $basename = pathinfo($normalized, PATHINFO_BASENAME);
        $basenameNoExt = pathinfo($basename, PATHINFO_FILENAME);
        $ext = strtolower((string) pathinfo($basename, PATHINFO_EXTENSION));

        $id = $userId ? trim((string) $userId) : null;
        if ($id === '' || $id === null) {
            $id = $basenameNoExt;
        }

        $candidateKeys = $this->buildCandidateKeys($normalized, $basename, $ext, $id);
        $candidateKeys = array_values(array_unique($candidateKeys));

        $resolved = $this->resolveFromCandidates($candidateKeys, $skipExistsCheck, $default);

        return $this->withCacheBuster($resolved, $cacheBusterTimestamp);
    }

    /**
     * @return list<string>
     */
    private function buildCandidateKeys(
        string $normalized,
        string $basename,
        string $ext,
        ?string $id,
    ): array {
        $keys = [];

        // Prefer the exact key stored in DB when it is an employees/* object path (fixes directory listing fast-path).
        if ($normalized !== '' && Str::startsWith($normalized, 'employees/')) {
            $keys[] = $normalized;
        }

        $profileBasenameKeys = [];
        if ($basename !== '' && $ext !== '') {
            $profileBasenameKeys[] = 'employees/profile/'.$basename;
        }
        if ($id !== null && $id !== '') {
            foreach (['jpg', 'jpeg', 'png', 'webp'] as $e) {
                $profileBasenameKeys[] = 'employees/profile/'.$id.'.'.$e;
            }
        }

        $employeesBasenameKeys = [];
        if ($basename !== '' && $ext !== '') {
            $employeesBasenameKeys[] = 'employees/'.$basename;
        }
        if ($id !== null && $id !== '') {
            foreach (['jpg', 'jpeg', 'png', 'webp'] as $e) {
                $employeesBasenameKeys[] = 'employees/'.$id.'.'.$e;
            }
        }

        $isProfileStored = Str::startsWith($normalized, 'employees/profile/');

        if ($isProfileStored) {
            $keys = array_merge($keys, $profileBasenameKeys, $employeesBasenameKeys);
        } else {
            // HR modal uploads use employees/{user_id}.ext — try those before speculative profile/* guesses.
            $keys = array_merge($keys, $employeesBasenameKeys, $profileBasenameKeys);
        }

        return $keys;
    }

    /**
     * @param  list<string>  $candidateKeys
     */
    private function resolveFromCandidates(array $candidateKeys, bool $skipExistsCheck, string $default): string
    {
        if ($candidateKeys === []) {
            return $default;
        }

        if ($skipExistsCheck) {
            try {
                $disk = Storage::disk('upcloud');
                foreach ($candidateKeys as $key) {
                    $url = $disk->url($key);
                    if ($url) {
                        return $url;
                    }
                }
            } catch (\Throwable) {
                // ignore
            }

            return $default;
        }

        try {
            $disk = Storage::disk('upcloud');
            foreach ($candidateKeys as $key) {
                if ($disk->exists($key)) {
                    return $disk->url($key);
                }
            }
        } catch (\Throwable) {
            // ignore
        }

        try {
            $disk = Storage::disk('upcloud');
            foreach ($candidateKeys as $key) {
                $url = $disk->url($key);
                if ($url) {
                    return $url;
                }
            }
        } catch (\Throwable) {
            // ignore
        }

        return $default;
    }

    private function withCacheBuster(string $url, ?int $cacheBusterTimestamp): string
    {
        if ($cacheBusterTimestamp === null || $cacheBusterTimestamp <= 0) {
            return $url;
        }

        $sep = str_contains($url, '?') ? '&' : '?';

        return $url.$sep.'v='.$cacheBusterTimestamp;
    }
}
