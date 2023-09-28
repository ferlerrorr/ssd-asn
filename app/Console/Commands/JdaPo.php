<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Env;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $date = Carbon::now()->subDays(30)->toDateString('Y-m-d');
        $modifiedDate = substr(str_replace("-", "", $date), 2);

        $data = DB::connection(env('DB2_CONNECTION'))
            ->table('MM770SSL.POMHDR')
            ->select('PONUMB', 'POSTAT', 'PONOT1', 'POVNUM', 'POEDAT')
            ->where('POEDAT', '>=', $modifiedDate)
            ->orderByDesc('PONUMB')
            ->get();


        $data = $data->map(function ($item) {
            return (array) $item;
        });

        $rowCount = $data->count();

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
                'jp_POSTAT' => $data_record["postat"],
                'jp_PONOT1' => $data_record["ponot1"],
                'jp_POVNUM' => $data_record["povnum"],
                'jp_PONUMB' => $data_record["ponumb"],
                // If needed, add more columns and their corresponding values here
            ];
        }

        try {
            // Your existing code here...

            // Use the query builder to insert the data and ignore duplicates
            foreach (array_chunk($insertData, 1000) as $dataChunk) {
                DB::table('jda_pomhdr')->upsert($dataChunk, ['jp_PONUMB']);
            }

            $currentValue = Env::get('ENVCRON');
            $incrementedValue = intval($currentValue) + 1;

            // Update the ENVCRON variable with the incremented value
            $newContent = File::get(base_path('.env'));
            $newContent = preg_replace('/(ENVCRON=)(.*)/', 'ENVCRON=' . $incrementedValue, $newContent);

            // Write the updated content back to the .env file
            File::put(base_path('.env'), $newContent);

            $this->info('Command executed successfully.');
            $this->info('Rows processed: ' . $rowCount);
        } catch (\Exception $e) {
            // Handle any exceptions and log the error
            $this->error('An error occurred: ' . $e->getMessage());
            // You can log the error using Laravel's Log::error() or other methods.
            return 1; // Return a non-zero exit code to indicate failure
        }

        return 0; // Return a zero exit code to indicate success
    }








    // // Use the query builder to insert the data and ignore duplicates
    // foreach (array_chunk($insertData, 1000) as &$data) {
    //     DB::table('jda_pomhdr')->upsert($data, ['jp_PONUMB']);
    //


    // $currentValue = Env::get('ENVCRON');
    // $incrementedValue = intval($currentValue) + 1;

    // // Update the ENVCRON variable with the incremented value
    // $newContent = File::get(base_path('.env'));
    // $newContent = preg_replace('/(ENVCRON=)(.*)/', 'ENVCRON=' . $incrementedValue, $newContent);

    // // Write the updated content back to the .env file
    // File::put(base_path('.env'), $newContent);

    // return response()->json([
    //     'count' => $rowCount,
    //     'data' => $data,
    // ], 200);
}
