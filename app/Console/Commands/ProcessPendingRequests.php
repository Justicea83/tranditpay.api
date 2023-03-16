<?php

namespace App\Console\Commands;

use App\Services\Payments\Transaction\ITransactionService;
use Illuminate\Console\Command;

class ProcessPendingRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending_requests:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command processes all pending requests';

    /**
     * Execute the console command.
     */
    public function handle(ITransactionService $transactionService): void
    {
        $transactionService->processPendingRequests();
    }
}
