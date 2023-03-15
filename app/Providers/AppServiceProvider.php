<?php

namespace App\Providers;

use App\Services\Auth\AuthService;
use App\Services\Auth\IAuthService;
use App\Services\Collection\CollectionService;
use App\Services\Collection\ICollectionService;
use App\Services\Merchant\IMerchantService;
use App\Services\Merchant\MerchantService;
use App\Services\Payments\IPaymentService;
use App\Services\Payments\PaymentService;
use App\Services\Payments\Paystack\IPaystackService;
use App\Services\Payments\Paystack\PaystackService;
use App\Services\Payments\Transaction\ITransactionService;
use App\Services\Payments\Transaction\TransactionService;
use App\Services\Settlements\ISettlementService;
use App\Services\Settlements\SettlementService;
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
        $this->app->singleton(ISettlementService::class, SettlementService::class);
        $this->app->singleton(ITransactionService::class, TransactionService::class);
        $this->app->singleton(IPaymentService::class, PaymentService::class);
        $this->app->singleton(IPaystackService::class, PaystackService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
