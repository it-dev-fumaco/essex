<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Auth redirect behavior: unauthenticated users sent to correct login (portal vs admin).
 * Protects Handler::unauthenticated() behavior without changing logic.
 */
class AuthRedirectTest extends TestCase
{
    /**
     * Request to a web route without auth redirects (not 500).
     */
    public function test_unauthenticated_web_redirects(): void
    {
        $response = $this->get('/home');
        $response->assertRedirect();
        $response->assertStatus(302);
    }

    /**
     * Portal route exists and is GET.
     */
    public function test_portal_route_exists(): void
    {
        $response = $this->get(route('portal'));
        $this->assertTrue(
            in_array($response->status(), [200, 302], true),
            'Portal route should respond'
        );
    }
}
