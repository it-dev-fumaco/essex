<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Logout must land on the public employee portal, not a heavy or error page.
 */
class LogoutRedirectTest extends TestCase
{
    public function test_user_logout_route_redirects_to_named_portal(): void
    {
        $response = $this->get(route('user.logout'));

        $response->assertRedirect(route('portal'));
    }

    public function test_post_logout_route_redirects_to_named_portal(): void
    {
        $response = $this->post(route('logout'));

        $response->assertRedirect(route('portal'));
    }
}
