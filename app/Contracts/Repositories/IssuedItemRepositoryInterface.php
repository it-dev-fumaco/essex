<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface IssuedItemRepositoryInterface
{
    public function getByIssuedTo(string $userId): Collection;
}
