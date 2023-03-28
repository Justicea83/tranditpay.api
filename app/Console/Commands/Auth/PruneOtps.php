<?php

namespace App\Console\Commands\Auth;

use App\Services\Auth\IAuthService;
use Illuminate\Console\Command;

class PruneOtps extends Command
{
    protected $signature = 'otp:prune';

    protected $description = 'Prune sent OTPs';

    public function handle(IAuthService $authService): void
    {
        $authService->pruneOtps();
    }
}
