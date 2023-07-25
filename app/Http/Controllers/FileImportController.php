<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {


        // Retrieve data as arrays
        $data_D = DB::connection(env('DB_CONNECTION'))
            ->table('d_column_setup')
            ->where('D_vendor', 'LIKE', $slug)
            ->first();

        $data_H = DB::connection(env('DB_CONNECTION'))
            ->table('h_column_setup')
            ->where('H_vendor', 'LIKE', $slug)
            ->first();

        $data_L = DB::connection(env('DB_CONNECTION'))
            ->table('l_column_setup')
            ->where('L_vendor', 'LIKE', $slug)
            ->first();

        $flattenedData = [];


        // Combine the data objects directly without using array_merge
        $flattenedData = (array)$data_D + (array)$data_H + (array)$data_L;

        // Skip the first three items using array_slice with offset
        $flattenedData = array_slice($flattenedData, 3);

        // Convert all elements to integers using array_map
        $flattenedData = array_map('intval', $flattenedData);


        // $data_array = [
        //     'H_InvNo' => $flattenedData['H_InvNo'],
        //     'H_InvDate' => $flattenedData['H_InvDate'],
        //     'H_InvAmt' => $flattenedData['H_InvAmt'],
        //     'H_DiscAmt' => $flattenedData['H_DiscAmt'],
        //     'H_StkFlag' => $flattenedData['H_StkFlag'],
        //     'H_VendorID' => $flattenedData['H_VendorID'],
        //     'H_VendorName' => $flattenedData['H_VendorName'],
        //     'H_PORef' => $flattenedData['H_PORef'],
        //     'H_SupCode' => $flattenedData['H_SupCode'],
        //     'D_InvNo' => $flattenedData['D_InvNo'],
        //     'D_ItemCode' => $flattenedData['D_Itemcode'],
        //     'D_ItemName' => $flattenedData['D_ItemName'],
        //     'D_ConvFact2' => $flattenedData['D_ConvFact2'],
        //     'D_UOM' => $flattenedData['D_UOM'],
        //     'D_UnitCost' => $flattenedData['D_UnitCost'],
        //     'D_QtyShip' => $flattenedData['D_QtyShip'],
        //     'D_QtyFree' => $flattenedData['D_QtyFree'],
        //     'D_GrossAmt' => $flattenedData['D_GrossAmt'],
        //     'D_PldAmt' => $flattenedData['D_PldAmt'],
        //     'D_NetAmt' => $flattenedData['D_NetAmt'],
        //     'D_SupCode' => $flattenedData['D_SupCode'],
        //     'D_prefix' => $flattenedData['D_Prefix'],
        //     'L_InvNo' => $flattenedData['L_InvNo'],
        //     'L_ItemCode' => $flattenedData['L_ItemCode'],
        //     'L_LotNo' => $flattenedData['L_LotNo'],
        //     'L_ExpiryDD' => $flattenedData['L_ExpiryDD'],
        //     'L_ExpiryMM' => $flattenedData['L_ExpiryMM'],
        //     'L_ExpiryYYYY' => $flattenedData['L_ExpiryYYYY'],
        //     'L_Qty' => $flattenedData['L_Qty'],
        //     'L_SupCode' => $flattenedData['L_SupCode'],
        // ];

        if ($_FILES["import_excel"]["name"] != '') {
            $allowed_extension = array('xls', 'csv', 'xlsx', 'txt', 'CSV');
            $file_array = explode(".", $_FILES["import_excel"]["name"]);
            $file_extension = end($file_array);

            if (!in_array($file_extension, $allowed_extension)) {
                $excel_data = [["Invalid file: Must be  xls , csv , xlsx File."]];
                return response()->json($excel_data, 403);
            }

            if (in_array($file_extension, $allowed_extension)) {
                $file_name = time() . '.' . $file_extension;
                move_uploaded_file($_FILES['import_excel']['tmp_name'], $file_name);
                $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
                $spreadsheet = $reader->load($file_name);
                unlink($file_name);

                $excel_data = $spreadsheet->getActiveSheet()->toArray();



                if ($file_extension === 'txt') {


                    $converted_data = [];

                    foreach ($excel_data as $sub_array) {
                        foreach ($sub_array as $csv_string) {
                            $csv_values = str_getcsv($csv_string);
                            $converted_data[] = $csv_values;
                        }
                    }

                    $excel_data = $converted_data;






                    // $data_array = [
                    //     'H_InvNo' => $flattenedData['H_InvNo'],
                    //     'H_InvDate' => $flattenedData['H_InvDate'],
                    //     'H_InvAmt' => $flattenedData['H_InvAmt'],
                    //     'H_DiscAmt' => $flattenedData['H_DiscAmt'],
                    //     'H_StkFlag' => $flattenedData['H_StkFlag'],
                    //     'H_VendorID' => $flattenedData['H_VendorID'],
                    //     'H_VendorName' => $flattenedData['H_VendorName'],
                    //     'H_PORef' => $flattenedData['H_PORef'],
                    //     'H_SupCode' => $flattenedData['H_SupCode'],
                    //     'D_InvNo' => $flattenedData['D_InvNo'],
                    //     'D_ItemCode' => $flattenedData['D_Itemcode'],
                    //     'D_ItemName' => $flattenedData['D_ItemName'],
                    //     'D_ConvFact2' => $flattenedData['D_ConvFact2'],
                    //     'D_UOM' => $flattenedData['D_UOM'],
                    //     'D_UnitCost' => $flattenedData['D_UnitCost'],
                    //     'D_QtyShip' => $flattenedData['D_QtyShip'],
                    //     'D_QtyFree' => $flattenedData['D_QtyFree'],
                    //     'D_GrossAmt' => $flattenedData['D_GrossAmt'],
                    //     'D_PldAmt' => $flattenedData['D_PldAmt'],
                    //     'D_NetAmt' => $flattenedData['D_NetAmt'],
                    //     'D_SupCode' => $flattenedData['D_SupCode'],
                    //     'D_prefix' => $flattenedData['D_Prefix'],
                    //     'L_InvNo' => $flattenedData['L_InvNo'],
                    //     'L_ItemCode' => $flattenedData['L_ItemCode'],
                    //     'L_LotNo' => $flattenedData['L_LotNo'],
                    //     'L_ExpiryDD' => $flattenedData['L_ExpiryDD'],
                    //     'L_ExpiryMM' => $flattenedData['L_ExpiryMM'],
                    //     'L_ExpiryYYYY' => $flattenedData['L_ExpiryYYYY'],
                    //     'L_Qty' => $flattenedData['L_Qty'],
                    //     'L_SupCode' => $flattenedData['L_SupCode'],
                    // ];



                    // $preserve_keys = ["H_InvNo", "D_InvNo", "L_InvNo", "H_Code", "D_Code", "L_Code"];
                    // // Loop through each item (sub-array)
                    // foreach ($excel_data as $item) {


                    //     // Filter out elements with value 0, except for the preserved keys
                    //     $import = array_filter($data_array, function ($value, $key) use ($item, $preserve_keys) {
                    //         return ($value !== 0 || in_array($key, $preserve_keys)) && isset($item[$value]);
                    //     }, ARRAY_FILTER_USE_BOTH);
                    //     $result[] = array_values($import); // Re-index the array and add to the result
                    // }


                    // foreach ($data_array as $key => $value) {
                    //     if ($value === 0 && !in_array($key, $preserve_keys)) {
                    //         unset($data_array[$key]);
                    //     }
                    // }


                    // $result = [];

                    // // Loop through each item (sub-array)
                    // foreach ($excel_data as $item) {



                    //     $import = []; // Initialize the $import array

                    //     // Loop through each index name in $data_array
                    //     foreach ($data_array as $index => $index_name) {
                    //         // Append the index (key) to the $import array instead of the value
                    //         $import[$index] = $item[$data_array[$index]];
                    //     }
                    //     $result[] =  $import;
                    // }



                    foreach ($excel_data as $item) {
                        $firstItemValue = $item[0];

                        switch ($firstItemValue) {
                            case 'H':
                                $H[] = $item;
                                break;
                            case 'D':
                                $D[] = $item;
                                break;
                            case 'L':
                                $L[] = $item;
                                break;
                                // If the first item doesn't match any case, you can handle it as needed.
                                // For example, skip it or add it to a separate array for unknown cases.
                        }
                    }




                    return response()->json($H, 200);
                } else {


                    $data_array = [
                        'H_InvNo' => $flattenedData['H_InvNo'],
                        'H_InvDate' => $flattenedData['H_InvDate'],
                        'H_InvAmt' => $flattenedData['H_InvAmt'],
                        'H_DiscAmt' => $flattenedData['H_DiscAmt'],
                        'H_StkFlag' => $flattenedData['H_StkFlag'],
                        'H_VendorID' => $flattenedData['H_VendorID'],
                        'H_VendorName' => $flattenedData['H_VendorName'],
                        'H_PORef' => $flattenedData['H_PORef'],
                        'H_SupCode' => $flattenedData['H_SupCode'],
                        'D_InvNo' => $flattenedData['D_InvNo'],
                        'D_ItemCode' => $flattenedData['D_Itemcode'],
                        'D_ItemName' => $flattenedData['D_ItemName'],
                        'D_ConvFact2' => $flattenedData['D_ConvFact2'],
                        'D_UOM' => $flattenedData['D_UOM'],
                        'D_UnitCost' => $flattenedData['D_UnitCost'],
                        'D_QtyShip' => $flattenedData['D_QtyShip'],
                        'D_QtyFree' => $flattenedData['D_QtyFree'],
                        'D_GrossAmt' => $flattenedData['D_GrossAmt'],
                        'D_PldAmt' => $flattenedData['D_PldAmt'],
                        'D_NetAmt' => $flattenedData['D_NetAmt'],
                        'D_SupCode' => $flattenedData['D_SupCode'],
                        'D_prefix' => $flattenedData['D_Prefix'],
                        'L_InvNo' => $flattenedData['L_InvNo'],
                        'L_ItemCode' => $flattenedData['L_ItemCode'],
                        'L_LotNo' => $flattenedData['L_LotNo'],
                        'L_ExpiryDD' => $flattenedData['L_ExpiryDD'],
                        'L_ExpiryMM' => $flattenedData['L_ExpiryMM'],
                        'L_ExpiryYYYY' => $flattenedData['L_ExpiryYYYY'],
                        'L_Qty' => $flattenedData['L_Qty'],
                        'L_SupCode' => $flattenedData['L_SupCode'],
                    ];


                    array_shift($excel_data);

                    $preserve_keys = ["H_InvNo", "D_InvNo", "L_InvNo"];
                    $result = [];

                    // Loop through each item (sub-array)
                    foreach ($excel_data as $item) {
                        // Filter out elements with value 0, except for the preserved keys
                        $import = array_filter($data_array, function ($value, $key) use ($item, $preserve_keys) {
                            return ($value !== 0 || in_array($key, $preserve_keys)) && isset($item[$value]);
                        }, ARRAY_FILTER_USE_BOTH);
                        $result[] = array_values($import); // Re-index the array and add to the result
                    }


                    $preserve_keys = ["H_InvNo", "D_InvNo", "L_InvNo"];

                    foreach ($data_array as $key => $value) {
                        if ($value === 0 && !in_array($key, $preserve_keys)) {
                            unset($data_array[$key]);
                        }
                    }


                    $result = [];

                    // Loop through each item (sub-array)
                    foreach ($excel_data  as $item) {



                        $import = []; // Initialize the $import array

                        // Loop through each index name in $data_array
                        foreach ($data_array as $index => $index_name) {
                            // Append the index (key) to the $import array instead of the value
                            $import[$index] = $item[$data_array[$index]];
                        }
                        $result[] =  $import;
                    }

                    return response()->json($result, 200);
                }
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //




    }
}
