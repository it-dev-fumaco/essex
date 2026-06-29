<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\AbsentNoticeStatusChanged;
use App\Services\AbsentNoticeOwnerNotificationService;

final class SendAbsentNoticeOwnerStatusEmail
{
    public function __construct(
        private readonly AbsentNoticeOwnerNotificationService $ownerNotificationService,
    ) {}

    public function handle(AbsentNoticeStatusChanged $event): void
    {
        if (strtoupper($event->toStatus) !== 'APPROVED') {
            return;
        }

        $this->ownerNotificationService->sendApprovedNotice($event->noticeId);
    }
}
