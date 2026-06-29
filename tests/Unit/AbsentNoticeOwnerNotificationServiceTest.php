<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class AbsentNoticeOwnerNotificationServiceTest extends TestCase
{
    public function test_should_notify_owner_only_on_approved_transition(): void
    {
        $cases = [
            ['FOR APPROVAL', 'APPROVED', true],
            ['For Approval', 'APPROVED', true],
            ['FOR APPROVAL', 'Approved', true],
            ['APPROVED', 'APPROVED', false],
            ['Approved', 'APPROVED', false],
            ['FOR APPROVAL', 'DISAPPROVED', false],
        ];

        foreach ($cases as [$previous, $next, $expected]) {
            $this->assertSame(
                $expected,
                $this->shouldNotify($previous, $next),
                "Failed for transition {$previous} -> {$next}"
            );
        }
    }

    private function shouldNotify(?string $previousStatus, ?string $newStatus): bool
    {
        return strtoupper(trim((string) $previousStatus)) !== 'APPROVED'
            && strtoupper(trim((string) $newStatus)) === 'APPROVED';
    }
}
