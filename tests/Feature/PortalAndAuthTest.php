<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Protection tests for critical ERP entry points.
 * No business logic changes; asserts current behavior (redirects and status codes).
 */
class PortalAndAuthTest extends TestCase
{
    /**
     * Portal home (/) is reachable by guest (200 or redirect to login).
     */
    public function test_portal_home_is_reachable(): void
    {
        $response = $this->get('/');
        $this->assertTrue(
            in_array($response->status(), [200, 302], true),
            'Portal home should return 200 or 302'
        );
    }

    /**
     * Admin login page is reachable.
     */
    public function test_admin_login_page_is_reachable(): void
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
    }

    /**
     * Unauthenticated access to /home redirects to login (portal or login route).
     */
    public function test_home_requires_authentication(): void
    {
        $response = $this->get(route('home'));
        $response->assertRedirect();
        $location = $response->headers->get('Location') ?? '';
        $this->assertTrue(
            strpos($location, 'login') !== false || strpos($location, 'portal') !== false,
            'Should redirect to login or portal'
        );
    }

    /**
     * Unauthenticated access to attendance redirects.
     */
    public function test_attendance_requires_authentication(): void
    {
        $response = $this->get('/attendance');
        $response->assertRedirect();
    }
}
