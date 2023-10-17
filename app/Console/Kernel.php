<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->call(function () {
            // Make a GET request to your custom endpoint URL
            file_get_contents('http://10.91.100.145:8800/api/ssd/asn/jda/po');
        })->dailyAt('6:00');
        $schedule->call(function () {
            // Make a GET request to your custom endpoint URL
            file_get_contents('http://10.91.100.145:8800/api/ssd/asn/jda/sku');
        })->dailyAt('6:02');

        $schedule->call(function () {
            // Make a GET request to your custom endpoint URL
            file_get_contents('http://10.91.100.145:8800/api/ssd/asn/duplicate-po-update');
        })->dailyAt('6:03');
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
