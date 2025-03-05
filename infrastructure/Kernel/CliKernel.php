<?php

declare(strict_types=1);

namespace Infrastructure\Kernel;

use App\Console\DeleteOldestUserInvites;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class CliKernel extends ConsoleKernel
{
    protected $commands = [
        DeleteOldestUserInvites::class,
    ];

    /**
     * Define the application's command schedule.
     */
    #[\Override]
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('deleteOldestInvite')->hourly()->withoutOverlapping();
    }
}
