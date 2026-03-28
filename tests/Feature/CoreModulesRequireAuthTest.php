<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Core ERP modules require authentication (no business logic change).
 * Ensures critical routes stay protected.
 */
class CoreModulesRequireAuthTest extends TestCase
{
    /**
     * Notice slip fetch requires auth.
     */
    public function test_notice_slip_fetch_requires_auth(): void
    {
        $response = $this->get('/notice_slip/fetch');
        $response->assertRedirect();
    }

    /**
     * Gatepass fetch requires auth.
     */
    public function test_gatepass_fetch_requires_auth(): void
    {
        $response = $this->get('/gatepass/fetch');
        $response->assertRedirect();
    }
}
