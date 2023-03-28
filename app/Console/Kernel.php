<?php

namespace App\Console;

use App\Console\Commands\Auth\PruneOtps;
use App\Console\Commands\CreateSubAccountsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(CreateSubAccountsCommand::class)->everyFiveMinutes()->withoutOverlapping()->runInBackground();
        $schedule->command(PruneOtps::class)->everyFiveMinutes()->withoutOverlapping()->runInBackground();
        $schedule->command('passport:purge')->hourly();
        $schedule->command('queue:work --tries=3 --timeout=3000')->everyMinute()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
