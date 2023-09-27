<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Env;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JdaSku extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jda:sku'; // Use lowercase and colon-separated

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

        $data = DB::connection(env('DB2_CONNECTION'))
            ->table('MM770SSL.INVMST AS M')
            ->select('M.INUMBR', 'M.IVNDPN', 'V.IVVNDN')
            ->leftJoin('MM770SSL.INVVEN AS V', 'M.INUMBR', '=', 'V.INUMBR')
            ->distinct()
            ->get();



        $rowCount = $data->count();

        //!->>

        // Count the occurrences of each value
        $valueCounts = array_count_values($data->pluck('inumbr')->toArray());

        // Filter values that are duplicated
        $duplicates = array_filter($valueCounts, function ($count) {
            return $count > 1;
        });

        // Create an array to store duplicated values and their counts
        $duplicatedArray = [];

        foreach ($duplicates as $value => $count) {
            $duplicatedArray[] = [
                'sku' => $value,
                'count' => $count
            ];
        }

        //!->>

        $data = $data->map(function ($item) {
            return (array) $item;
        });

        $data->transform(function ($row) {
            foreach ($row as &$value) {
                $value = trim($value);
                if ($value === '') {
                    $value = null;
                }
            }
            return $row;
        });



        // Prepare the data for mass insertion
        $insertData = [];
        foreach ($data as &$data_record) {
            $insertData[] = [
                'ji_INUMBR' => $data_record["inumbr"],
                'ji_IMFGNO' => $data_record["ivndpn"],
                'ji_IVVNDN' => $data_record["ivvndn"],
                // If needed, add more columns and their corresponding values here
            ];
        }

        // Use the upsert method with the ignore option to achieve upsert-or-ignore behavior
        foreach (array_chunk($insertData, 1000) as &$data) {
            DB::table('jda_invmst')->upsert($data, ['ji_INUMBR']);
        }



        // return Command::SUCCESS;
        // Get the current value of ENVCRON
        // $currentValue = Env::get('ENVCRON');
        // $incrementedValue = intval($currentValue) + 1;

        // Update the ENVCRON variable with the incremented value
        //  $newContent = File::get(base_path('.env'));
        // $newContent = preg_replace('/(ENVCRON=)(.*)/', 'ENVCRON=' . $incrementedValue, $newContent);

        // Write the updated content back to the .env file
        // File::put(base_path('.env'), $newContent);
    }
}
