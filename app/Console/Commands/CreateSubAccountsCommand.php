<?php

namespace App\Console\Commands;

use App\Jobs\CreateSubAccountsJob;
use Illuminate\Console\Command;

class CreateSubAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subaccounts:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command creates subaccounts for new merchants';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        CreateSubAccountsJob::dispatch();
    }
}
