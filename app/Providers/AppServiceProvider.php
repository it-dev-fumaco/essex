<?php

namespace App\Providers;

use App\Contracts\Repositories\BiometricRepositoryInterface;
use App\Contracts\Repositories\DepartmentApproverRepositoryInterface;
use App\Contracts\Repositories\GatepassRepositoryInterface;
use App\Contracts\Repositories\IssuedItemRepositoryInterface;
use App\Contracts\Repositories\ItemAccountabilityRepositoryInterface;
use App\Contracts\Repositories\LookupRepositoryInterface;
use App\Contracts\Repositories\NoticeSlipRepositoryInterface;
use App\Contracts\Repositories\SessionDetailRepositoryInterface;
use App\Contracts\Repositories\ShiftRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\AuthServiceInterface;
use App\Repositories\BiometricRepository;
use App\Repositories\DepartmentApproverRepository;
use App\Repositories\GatepassRepository;
use App\Repositories\IssuedItemRepository;
use App\Repositories\ItemAccountabilityRepository;
use App\Repositories\LookupRepository;
use App\Repositories\NoticeSlipRepository;
use App\Repositories\SessionDetailRepository;
use App\Repositories\ShiftRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(2000);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(BiometricRepositoryInterface::class, BiometricRepository::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(GatepassRepositoryInterface::class, GatepassRepository::class);
        $this->app->bind(SessionDetailRepositoryInterface::class, SessionDetailRepository::class);
        $this->app->bind(DepartmentApproverRepositoryInterface::class, DepartmentApproverRepository::class);
        $this->app->bind(ShiftRepositoryInterface::class, ShiftRepository::class);
        $this->app->bind(NoticeSlipRepositoryInterface::class, NoticeSlipRepository::class);
        $this->app->bind(LookupRepositoryInterface::class, LookupRepository::class);
        $this->app->bind(IssuedItemRepositoryInterface::class, IssuedItemRepository::class);
        $this->app->bind(ItemAccountabilityRepositoryInterface::class, ItemAccountabilityRepository::class);
    }
}
