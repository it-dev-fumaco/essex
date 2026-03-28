<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class AbsentNoticeStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly int $noticeId,
        public readonly string $fromStatus,
        public readonly string $toStatus,
    ) {}
}

