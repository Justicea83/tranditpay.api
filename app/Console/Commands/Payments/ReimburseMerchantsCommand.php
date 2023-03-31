<?php

namespace App\Console\Commands\Payments;

use App\Services\Payments\Transaction\ITransactionService;
use Illuminate\Console\Command;

class ReimburseMerchantsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchants:reimburse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run this command to send merchants their earnings.';

    /**
     * Execute the console command.
     */
    public function handle(ITransactionService $transactionService): void
    {
        $transactionService->reimburseMerchants();
    }
}
