<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use Illuminate\Http\Request;

interface AuthServiceInterface
{
    /**
     * Attempt to authenticate the user (LDAP or credentials).
     * Updates last_login_date on success.
     *
     * @return int|null User id on success, null on failure
     */
    public function attempt(Request $request): ?int;
}
