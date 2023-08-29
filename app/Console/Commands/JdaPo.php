<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Env;

class JdaPo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jda:po';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // Get the current value of ENVCRON
        $currentValue = Env::get('ENVCRON');
        $incrementedValue = intval($currentValue) + 1;

        // Update the ENVCRON variable with the incremented value
        $newContent = File::get(base_path('.env'));
        $newContent = preg_replace('/(ENVCRON=)(.*)/', 'ENVCRON=' . $incrementedValue, $newContent);

        // Write the updated content back to the .env file
        File::put(base_path('.env'), $newContent);
    }
}
