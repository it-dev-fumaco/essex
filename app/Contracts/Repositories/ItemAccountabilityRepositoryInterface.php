<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

interface ItemAccountabilityRepositoryInterface
{
    /**
     * Get the last item_id (max) for code generation.
     */
    public function getLastItemId(): ?int;
}
