<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Email approval links must work without authentication and must not redirect-loop.
 */
class AbsentNoticeMailApprovalTest extends TestCase
{
    public function test_mail_approval_link_is_public_and_returns_result_page(): void
    {
        $response = $this->get('/notice_slip/updateStatus?'.http_build_query([
            'update_from_mail' => '1',
            'approved' => '1',
            'notice_id' => '99999999',
            'approver_id' => '1',
            'token' => 'invalid-token',
        ]));

        $response->assertOk();
        $response->assertSee('Absent Notice Slip', false);
        $this->assertStringNotContainsString('notice_slip/updateStatus', (string) $response->headers->get('Location'));
    }

    public function test_mail_disapprove_link_is_public_and_returns_result_page(): void
    {
        $response = $this->get('/notice_slip/updateStatus?'.http_build_query([
            'update_from_mail' => '1',
            'notice_id' => '99999997',
            'approver_id' => '1',
            'token' => 'invalid-token',
        ]));

        $response->assertOk();
        $response->assertSee('Absent Notice Slip', false);
        $this->assertStringNotContainsString('notice_slip/updateStatus', (string) $response->headers->get('Location'));
    }

    public function test_mail_approval_link_without_update_from_mail_flag_still_treated_as_mail_flow(): void
    {
        $response = $this->get('/notice_slip/updateStatus?'.http_build_query([
            'approved' => '1',
            'notice_id' => '99999998',
            'approver_id' => '1',
            'token' => 'invalid-token',
        ]));

        $response->assertOk();
        $response->assertSee('not found', false);
    }

    public function test_authenticated_route_does_not_bypass_auth_with_mail_query_params(): void
    {
        $response = $this->get('/home?'.http_build_query([
            'update_from_mail' => '1',
            'notice_id' => '1',
            'approver_id' => '1',
            'token' => 'token',
        ]));

        $response->assertRedirect(route('portal'));
    }
}
