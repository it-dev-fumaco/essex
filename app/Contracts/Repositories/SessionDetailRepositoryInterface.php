<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

interface SessionDetailRepositoryInterface
{
    /**
     * Get user session detail (users join designation join departments) by user_id.
     *
     * @return object|null StdClass with designation, department, etc.
     */
    public function getByUserId(string $userId): ?object;
}
