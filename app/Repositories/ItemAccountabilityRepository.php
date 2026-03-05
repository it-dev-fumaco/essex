<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\ItemAccountabilityRepositoryInterface;
use App\Models\ItemAccountability;
use Illuminate\Support\Facades\DB;

final class ItemAccountabilityRepository implements ItemAccountabilityRepositoryInterface
{
    public function getLastItemId(): ?int
    {
        $result = DB::table('issued_item')->orderBy('item_id', 'DESC')->value('item_id');
        return $result !== null ? (int) $result : null;
    }
}
