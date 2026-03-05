<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\IssuedItemRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class IssuedItemRepository implements IssuedItemRepositoryInterface
{
    public function getByIssuedTo(string $userId): Collection
    {
        return DB::table('issued_item')->where('issued_to', $userId)->get();
    }
}
