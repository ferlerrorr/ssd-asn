<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

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
        $context = stream_context_create(['http' => ['timeout' => 60 * 60 * 5]]);

        $schedule->call(function () use ($context) {
            try {
                file_get_contents('http://10.60.14.57:8800/api/ssd/asn/jda/po', false, $context);

                // Process the response or handle success
                // ...
            } catch (\Exception $e) {
                // Log any errors
                Log::error('Error during scheduled task: ' . $e->getMessage());
            }
        })->dailyAt('06:00'); // Time - 04:30 am


        $schedule->call(function () use ($context) {
            try {
                file_get_contents('http://10.60.14.57:8800/api/ssd/asn/jda/sku', false, $context);

                // Process the response or handle success
                // ...
            } catch (\Exception $e) {
                // Log any errors
                Log::error('Error during scheduled task: ' . $e->getMessage());
            }
        })->dailyAt('06:02'); //Time - 06:30 am
        //everyTwoHours(); - For Usage in Upscaling
        //

        $schedule->call(function () use ($context) {
            try {
                file_get_contents('http://10.60.14.57:8800/api/ssd/asn/duplicate-po-update', false, $context);

                // Process the response or handle success
                // ...
            } catch (\Exception $e) {
                // Log any errors
                Log::error('Error during scheduled task: ' . $e->getMessage());
            }
        })->dailyAt('06:04'); //Time - 07:30 am to 09:00 am


        $schedule->call(function () use ($context) {
            try {
                file_get_contents('http://10.60.14.57:8800/api/ssd/asn/jda/poref', false, $context);

                // Process the response or handle success
                // ...
            } catch (\Exception $e) {
                // Log any errors
                Log::error('Error during scheduled task: ' . $e->getMessage());
            }
        })->everyFiveMinutes(); //Time - 07:30 am to 09:00 am

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
