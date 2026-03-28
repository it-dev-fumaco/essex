<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface NoticeSlipRepositoryInterface
{
    public function getPendingByUserId(string $userId): Collection;
}
