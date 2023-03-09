<?php

namespace App\Jobs;

use App\Models\Merchant\Merchant;
use App\Services\Settlements\ISettlementService;
use App\Utils\StatusUtils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CreateSubAccountsJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ISettlementService $settlementService): void
    {
        Log::info("Calling " . get_class());
        Merchant::query()->where('status', StatusUtils::ACTIVE)
            ->chunkById(200, function (Collection $merchants) use ($settlementService) {
                $merchants->each(function (Merchant $merchant) use ($settlementService) {
                    $settlementService->addMerchantSubAccounts($merchant);
                });
            });
        Log::info("After calling " . get_class());
    }
}
