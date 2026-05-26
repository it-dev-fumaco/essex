<?php

namespace App\Filament\Plugins;

use App\Filament\Resources\DocumentResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class DocumentManagementPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'document-management';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            DocumentResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
