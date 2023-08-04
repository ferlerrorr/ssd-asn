<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AsnFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vendors()
    {

        $data = DB::connection(env('DB_CONNECTION'))
            ->table('h_column_setup')
            ->select('H_vendor', 'H_vid')
            ->get();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function headers()
    {

        $data1 = (array) DB::table('d_column_setup')
            ->where('D_vid', 200)
            ->first() ?? [];
        $data2 = (array) DB::table('l_column_setup')
            ->where('L_vid', 200)
            ->first() ?? [];
        $data3 = (array) DB::table('h_column_setup')
            ->where('H_vid', 200)
            ->first() ?? [];


        $combinedArray = array_merge($data3, $data1, $data2);

        $filteredData = array_filter((array) $combinedArray, function ($value, $key) {
            return !is_null($value) && $key !== 'D_id' && $key !== 'L_id' && $key !== 'H_id' && $key !== 'H_file_type' && $key !== 'D_file_type' && $key !== 'L_file_type' && $key !== 'L_vid' && $key !== 'L_vendor' && $key !== 'D_vid' && $key !== 'D_vendor';
        }, ARRAY_FILTER_USE_BOTH);


        // return response()->json(
        //     $filteredData
        // );


        // Prepare the data for mass insertion
        $insertData_L = [];
        foreach ($filteredData as &$data_record) {
            $I_Count = 0;
            $insertData_L[] = [
                'InvNo' => $data_record["L_InvNo"],
                'ItemCode' => $data_record["L_ItemCode"],
                'LotNo' => $data_record["L_LotNo"],
                'ExpiryMM' => $data_record["L_ExpiryMM"],
                'ExpiryDD' => "01",
                'ExpiryYYYY' => $data_record["L_ExpiryYYYY"],
                'Qty' => $data_record["L_Qty"],
                'TransactionCode' => ($data_record["L_InvNo"] . $data_record["L_ItemCode"] . $data_record["L_LotNo"] . $data_record["L_ExpiryMM"] . $data_record["L_ExpiryYYYY"] . $data_record["L_Qty"] . $I_Count++)
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $vid)
    {

        $vid = $vid;

        $data = $request->toArray();

        $data = array_values(array_filter($data, function ($subArray) {
            return count(array_filter($subArray, fn ($item) => $item !== null)) > 0;
        }));


        $data1 = (array) DB::table('d_column_setup')
            ->where('D_vid', $vid)
            ->first() ?? [];
        $data2 = (array) DB::table('l_column_setup')
            ->where('L_vid', $vid)
            ->first() ?? [];
        $data3 = (array) DB::table('h_column_setup')
            ->where('H_vid', $vid)
            ->first() ?? [];


        $combinedArray = array_merge($data3, $data1, $data2);

        $filteredData = array_filter((array) $combinedArray, function ($value, $key) {
            return !is_null($value) && $key !== 'D_id' && $key !== 'L_id' && $key !== 'H_id' && $key !== 'H_file_type' && $key !== 'D_file_type' && $key !== 'L_file_type' && $key !== 'L_vid' && $key !== 'L_vendor' && $key !== 'D_vid' && $key !== 'D_vendor' && $key !== 'H_vid' && $key !== 'H_vendor';
        }, ARRAY_FILTER_USE_BOTH);


        // Convert each property value to an integer
        foreach ($filteredData as $key => &$value) {
            $value = intval($value);
        }
        unset($value);
        // Unset the reference to avoid potential side effects

        $result = [];

        // Loop through each item (sub-array)
        foreach ($data  as $item) {
            $import = []; // Initialize the $import array

            // Loop through each index name in $data_array
            foreach ($filteredData as $index => $index_name) {
                // Append the index (key) to the $import array instead of the value
                $import[$index] = $item[$filteredData[$index]];
            }
            $result[] =  $import;
        }



        $H_Count = 0;
        $insertData_H = [];
        foreach ($result as &$data_record) {
            $insertData_H[] = [
                'InvNo' => $data_record["H_InvNo"],
                'POref' => $data_record["H_PORef"],
                'TransactionCode' => ($data_record["H_InvNo"] . $data_record["H_PORef"] . $H_Count++)
                // If needed, add more columns and their corresponding values here
            ];
        }

        // Use the query builder to insert the data and ignore duplicates
        foreach (array_chunk($insertData_H, 1000) as &$data) {
            DB::table('inv_hdr')->insertOrIgnore($data);
        }




        $insertData_L = [];
        $L_Count = 0;
        foreach ($result as &$data_record) {

            $insertData_L[] = [
                'InvNo' => $data_record["L_InvNo"],
                'ItemCode' => $data_record["L_ItemCode"],
                'LotNo' => $data_record["L_LotNo"],
                'ExpiryMM' => $data_record["L_ExpiryMM"],
                'ExpiryDD' => "01",
                'ExpiryYYYY' => $data_record["L_ExpiryYYYY"],
                'Qty' => $data_record["L_Qty"],
                'TransactionCode' => ($data_record["L_InvNo"] . $data_record["L_ItemCode"] . $data_record["L_LotNo"] . $data_record["L_ExpiryMM"] . $data_record["L_ExpiryYYYY"] . $data_record["L_Qty"] . $L_Count++)
            ];
        }


        // Use the query builder to insert the data and ignore duplicates
        foreach (array_chunk($insertData_L, 1000) as &$data) {
            DB::table('inv_lot')->insertOrIgnore($data);
        }

        $D_Count = 0;
        $insertData_D = [];
        foreach ($result as &$data_record) {
            $insertData_D[] = [
                'InvNo' => $data_record["D_InvNo"],
                'ItemCode' => $data_record["D_ItemCode"],
                'TransactionCode' => ($data_record["D_InvNo"] . $data_record["D_ItemCode"] . $D_Count++),

            ];
        }

        // Use the query builder to insert the data and ignore duplicates
        foreach (array_chunk($insertData_D, 1000) as &$data) {
            DB::table('inv_dtl')->insertOrIgnore($data);
        }




        return response()->json($result, 200);



        // return response()->json($data);
    }
}
