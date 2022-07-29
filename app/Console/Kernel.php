<?php

namespace App\Console;

use App\Models\EnvatoSetting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\FetchSales::class,
        Commands\FetchProductDetails::class,
        Commands\FetchUserAccountDetails::class,
        Commands\FetchUserBadges::class,
        Commands\HideCoreJobMessage::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('fetch-user-details')->everyFifteenMinutes();
        $schedule->command('fetch-user-badges')->weekly()->mondays()->at('06:00');
        $schedule->command('fetch-sales')->everyTwoMinutes();
        $schedule->command('fetch-product-details')->everyFifteenMinutes();
        $schedule->command('send-daily-sales-email')->dailyAt('06:00');
        $schedule->command('hide-cron-message')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
