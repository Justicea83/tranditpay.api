<?php

namespace App\Providers;

use App\Services\Auth\AuthService;
use App\Services\Auth\IAuthService;
use App\Services\Collection\CollectionService;
use App\Services\Collection\ICollectionService;
use App\Services\Merchant\IMerchantService;
use App\Services\Merchant\MerchantService;
use App\Services\UserManagement\IUserManagementService;
use App\Services\UserManagement\UserManagementService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(IAuthService::class, AuthService::class);
        $this->app->singleton(ICollectionService::class, CollectionService::class);
        $this->app->singleton(IUserManagementService::class, UserManagementService::class);
        $this->app->singleton(IMerchantService::class, MerchantService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
