<?php

namespace App\Console;

use App\Console\Commands\AnalyzeOhlc;
use App\Console\Commands\CollectOhlc;
use App\Console\Commands\CollectPairs;
use App\Console\Commands\NotificationAnalyzeOhlc;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        CollectPairs::class,
        CollectOhlc::class,
        AnalyzeOhlc::class,
        NotificationAnalyzeOhlc::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command(CollectPairs::class)->dailyAt('00:00');
        $schedule->command(CollectOhlc::class)->hourly();

        $schedule->command(AnalyzeOhlc::class)->cron("10 * * * *");
        $schedule->command(NotificationAnalyzeOhlc::class)->cron("20 * * * *");
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
