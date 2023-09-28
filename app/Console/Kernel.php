<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\JdaSku::class,
        \App\Console\Commands\JdaPo::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Use the command's signature (e.g., jda:sku) instead of the command name.
        // $schedule->command('jda:sku')->dailyAt('6:02');
        //$schedule->command('jda:po')->dailyAt('6:00');

        $schedule->call(function () {
            // Make a GET request to your custom endpoint URL
            file_get_contents('http://10.91.100.145:8800/api/ssd/asn/jda/po');
        })->dailyAt('6:00');
        $schedule->call(function () {
            // Make a GET request to your custom endpoint URL
            file_get_contents('http://10.91.100.145:8800/api/ssd/asn/jda/sku');
        })->dailyAt('6:02');
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
