<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class EmployeeLifecycleActionTriggered
{
    use Dispatchable, SerializesModels;

    /**
     * @param  array<string, mixed>  $context
     */
    public function __construct(
        public readonly int $employeeId,
        public readonly string $action, // welcome | onboarding | offboarding
        public readonly array $context = [],
    ) {}
}

