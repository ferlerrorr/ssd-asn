<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Env;

class JdaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Po()
    {
        $date = Carbon::now()->subDays(30)->toDateString('Y-m-d');
        $modifiedDate = substr(str_replace("-", "", $date), 2);

        //Delimiter for PO
        // string varDate = DateTime.Now.AddDays(-60).ToString("yyMMdd");

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

        // Use the query builder to insert the data and ignore duplicates
        foreach (array_chunk($insertData, 1000) as &$data) {
            DB::table('jda_pomhdr')->upsert($data, ['jp_PONUMB']);
        }


        $currentValue = Env::get('ENVCRON');
        $incrementedValue = intval($currentValue) + 1;

        // Update the ENVCRON variable with the incremented value
        $newContent = File::get(base_path('.env'));
        $newContent = preg_replace('/(ENVCRON=)(.*)/', 'ENVCRON=' . $incrementedValue, $newContent);

        // Write the updated content back to the .env file
        File::put(base_path('.env'), $newContent);

        return response()->json([
            'count' => $rowCount,
            'data' => $data,
        ], 200);
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Sku()
    {

        $data = DB::connection(env('DB2_CONNECTION'))
            ->table('MM770SSL.INVMST AS M')
            ->select('M.INUMBR', 'M.IVNDPN', 'V.IVVNDN')
            ->leftJoin('MM770SSL.INVVEN AS V', 'M.INUMBR', '=', 'V.INUMBR')
            ->get();

        // return response($data);

        // Prepare the data for mass insertion
        $insertData = [];
        foreach ($data as $data_record) {
            $insertData[] = [
                'ji_INUMBR' => trim($data_record->inumbr),
                'ji_IMFGNO' => trim($data_record->ivndpn),
                'ji_IVVNDN' => trim($data_record->ivvndn),
                'TransactionCode' => trim($data_record->inumbr) . trim($data_record->ivndpn) . trim($data_record->ivvndn),
                // If needed, add more columns and their corresponding values here
            ];
        }

        // Use the insertOrIgnore method to achieve insert-ignore behavior
        foreach (array_chunk($insertData, 1000) as $chunk) {
            DB::table('jda_invmst')->insertOrIgnore($chunk);
        }

        return response()->json([
            'data' =>  $insertData,
        ], 200);
    }
}
