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
                $json_data = json_encode($excel_data);


                if ($file_extension === 'txt' && $slug == 'UNILAB') {


                    $output = str_replace(['/', '\\', '"', '[', ']'], '', $json_data);



                    // Split the data by lines
                    $lines = explode("\n", $output);
                    $output = '';

                    // Pre-compile the regular expression pattern for better performance
                    // $pattern = '/\b(H|D|L),/';
                    $pattern = '/(?<!\.)\b(?<!PH)([LDH],)/';


                    foreach ($lines as $line) {
                        // Check if the line contains 'H,', 'D,', or 'L,' as separate words
                        if (preg_match($pattern, $line)) {
                            // Replace 'H,', 'D,', or 'L,' with '"H,', '"D,', or '"L,'
                            $line = preg_replace($pattern, '"$1,', $line);
                        }
                        $output .= $line . "\n";
                    }

                    // Pre-compile the regular expression pattern for better performance


                    $items = array_filter(explode('"', trim($output)), 'strlen');

                    // Convert the array into a multidimensional array with comma-separated values
                    $finalArray = array_map(function ($item) {
                        return explode(',', $item);
                    }, $items);

                    // Reindex the array with numeric keys
                    $dataArr = array_values($finalArray);



                    $H = [];
                    $D = [];
                    $L = [];

                    foreach ($dataArr as $item) {
                        $firstLetter = $item[0];
                        switch ($firstLetter) {
                            case 'H':
                                $H[] = $item;
                                break;
                            case 'D':
                                $D[] = $item;
                                break;
                            case 'L':
                                $L[] = $item;
                                break;
                            default:
                                // Handle if a different first letter is encountered (optional).
                                break;
                        }
                    }




                    // // Iterate through each item in the array $H
                    // foreach ($H as $item) {
                    //     // Extract required elements from the current item in $H
                    //     $H_InvNo = $item[1];
                    //     $H_PORef = $item[8];

                    //     // Create a new associative array with the extracted values
                    //     $data = array(
                    //         'H_InvNo' => $H_InvNo,
                    //         'H_PORef' => $H_PORef
                    //     );

                    //     // // Add the data array to the $result_h
                    //     $result_h[] = $data;
                    // }



                    // $insertData_H = [];
                    // foreach ($result_h as &$data_record) {
                    //     $insertData_H[] = [
                    //         'InvNo' => $data_record["H_InvNo"],
                    //         'POref' => $data_record["H_PORef"],
                    //         'TransactionCode' => ($data_record["H_InvNo"] . $data_record["H_PORef"])
                    //         // If needed, add more columns and their corresponding values here
                    //     ];
                    // }

                    // // Use the query builder to insert the data and ignore duplicates
                    // foreach (array_chunk($insertData_H, 1000) as &$data) {
                    //     DB::table('inv_hdr')->insertOrIgnore($data);
                    // }





                    // // Iterate through each item in the array $D
                    // foreach ($D as $item) {
                    //     // Extract required elements from the current item in $D
                    //     $D_InvNo = $item[1];
                    //     $D_ItemCode = $item[3];

                    //     // Create a new associative array with the extracted values
                    //     $data = array(
                    //         'D_InvNo' => $D_InvNo,
                    //         'D_ItemCode' => $D_ItemCode
                    //     );

                    //     // Add the filteredItem array to the $result

                    //     $result_d[] = $data;
                    // }




                    // $insertData_D = [];
                    // foreach ($result_d as &$data_record) {
                    //     $insertData_D[] = [
                    //         'InvNo' => $data_record["D_InvNo"],
                    //         'ItemCode' => $data_record["D_ItemCode"],
                    //         'TransactionCode' => ($data_record["D_InvNo"] . $data_record["D_ItemCode"])
                    //         // If needed, add more columns and their corresponding values here
                    //     ];
                    // }

                    // // Use the query builder to insert the data and ignore duplicates
                    // foreach (array_chunk($insertData_D, 1000) as &$data) {
                    //     DB::table('inv_dtl')->insertOrIgnore($data);
                    // }



                    // // // Iterate through each item in the array $L
                    // foreach ($L as $item) {

                    //     if (count($item) >= 6) {
                    //         $L_InvNo = $item[1];
                    //         $L_ItemCode = $item[3];
                    //         $L_LotNo = $item[4];
                    //         $L_ExpiryDD = "1";
                    //         $L_ExpiryMM = $item[5];
                    //         $L_ExpiryMM = $item[5];
                    //         $L_ExpiryYYYY = $item[6];
                    //         $L_Qty = $item[7];

                    //         $data = array(
                    //             'L_InvNo' => $L_InvNo,
                    //             'L_ItemCode' =>  $L_ItemCode,
                    //             'L_LotNo' => $L_LotNo,
                    //             'L_ExpiryDD' => $L_ExpiryDD,
                    //             'L_ExpiryMM' => $L_ExpiryMM,
                    //             'L_ExpiryMM' => $L_ExpiryMM,
                    //             'L_ExpiryYYYY' => $L_ExpiryYYYY,
                    //             'L_Qty' =>   $L_Qty
                    //         );

                    //         $result_l[] = $data;
                    //     }
                    // }


                    // // Prepare the data for mass insertion
                    // $insertData_L = [];
                    // foreach ($result_l as &$data_record) {
                    //     $insertData_L[] = [
                    //         'InvNo' => $data_record["L_InvNo"],
                    //         'ItemCode' => $data_record["L_ItemCode"],
                    //         'LotNo' => $data_record["L_LotNo"],
                    //         'ExpiryMM' => $data_record["L_ExpiryMM"],
                    //         'ExpiryDD' => "01",
                    //         'ExpiryYYYY' => $data_record["L_ExpiryYYYY"],
                    //         'Qty' => $data_record["L_Qty"],
                    //         'TransactionCode' => ($data_record["L_InvNo"] . $data_record["L_ItemCode"] . $data_record["L_LotNo"] . $data_record["L_ExpiryMM"] . "01" . $data_record["L_ExpiryYYYY"] . $data_record["L_Qty"])
                    //         // If needed, add more columns and their corresponding values here
                    //     ];
                    // }
                    // // // $itemCount = count($insertData);

                    // $itemCount = count($dataArr);


                    // // Use the query builder to insert the data and ignore duplicates
                    // foreach (array_chunk($insertData_L, 1000) as &$data) {
                    //     DB::table('inv_lot')->insertOrIgnore($data);
                    // }






                    // $res = [
                    //     // 'Item count' => $itemCount,
                    //     // 'data' => $insertData
                    //     'data' =>    $output
                    // ];






                    // return response()->json($output, 200);

                    return response($dataArr);

                    // } elseif ($file_extension !== 'txt' && $slug != 'UNILAB') {


                    //     $flattenedData = [];

                    //     // Combine the data objects directly without using array_merge
                    //     $flattenedData = (array)$data_D + (array)$data_H + (array)$data_L;

                    //     // Skip the first three items using array_slice with offset
                    //     $flattenedData = array_slice($flattenedData, 3);

                    //     // Convert all elements to integers using array_map
                    //     $flattenedData = array_map('intval', $flattenedData);




                    //     $data_array = [
                    //         'H_InvNo' => $flattenedData['H_InvNo'],
                    //         'H_InvDate' => $flattenedData['H_InvDate'],
                    //         'H_InvAmt' => $flattenedData['H_InvAmt'],
                    //         'H_DiscAmt' => $flattenedData['H_DiscAmt'],
                    //         'H_StkFlag' => $flattenedData['H_StkFlag'],
                    //         'H_VendorID' => $flattenedData['H_VendorID'],
                    //         'H_VendorName' => $flattenedData['H_VendorName'],
                    //         'H_PORef' => $flattenedData['H_PORef'],
                    //         'H_SupCode' => $flattenedData['H_SupCode'],
                    //         'D_InvNo' => $flattenedData['D_InvNo'],
                    //         'D_ItemCode' => $flattenedData['D_Itemcode'],
                    //         'D_ItemName' => $flattenedData['D_ItemName'],
                    //         'D_ConvFact2' => $flattenedData['D_ConvFact2'],
                    //         'D_UOM' => $flattenedData['D_UOM'],
                    //         'D_UnitCost' => $flattenedData['D_UnitCost'],
                    //         'D_QtyShip' => $flattenedData['D_QtyShip'],
                    //         'D_QtyFree' => $flattenedData['D_QtyFree'],
                    //         'D_GrossAmt' => $flattenedData['D_GrossAmt'],
                    //         'D_PldAmt' => $flattenedData['D_PldAmt'],
                    //         'D_NetAmt' => $flattenedData['D_NetAmt'],
                    //         'D_SupCode' => $flattenedData['D_SupCode'],
                    //         'D_prefix' => $flattenedData['D_Prefix'],
                    //         'L_InvNo' => $flattenedData['L_InvNo'],
                    //         'L_ItemCode' => $flattenedData['L_ItemCode'],
                    //         'L_LotNo' => $flattenedData['L_LotNo'],
                    //         'L_ExpiryDD' => $flattenedData['L_ExpiryDD'],
                    //         'L_ExpiryMM' => $flattenedData['L_ExpiryMM'],
                    //         'L_ExpiryYYYY' => $flattenedData['L_ExpiryYYYY'],
                    //         'L_Qty' => $flattenedData['L_Qty'],
                    //         'L_SupCode' => $flattenedData['L_SupCode'],
                    //     ];


                    //     array_shift($excel_data);
                    //     $result = [];


                    // $preserve_keys = ["H_InvNo", "D_InvNo", "L_InvNo"];


                    // // Loop through each item (sub-array)
                    // foreach ($excel_data as $item) {
                    //     // Filter out elements with value 0, except for the preserved keys
                    //     $import = array_filter($data_array, function ($value, $key) use ($item, $preserve_keys) {
                    //         return ($value !== 0 || in_array($key, $preserve_keys)) && isset($item[$value]);
                    //     }, ARRAY_FILTER_USE_BOTH);
                    //     $result[] = array_values($import); // Re-index the array and add to the result
                    // }
                    //         $preserve_keys = ["H_InvNo", "D_InvNo", "L_InvNo"];
                    //         $result = [];

                    //         // Loop through each item (sub-array)
                    //         foreach ($excel_data as $item) {
                    //             $import = [];

                    //             // Filter out elements with value 0, except for the preserved keys
                    //             foreach ($data_array as $key => $value) {
                    //                 if (($value !== 0 || in_array($key, $preserve_keys)) && isset($item[$value])) {
                    //                     $import[] = $item[$value];
                    //                 }
                    //             }

                    //             $result[] = $import;
                    //         }

                    //         $preserve_keys = ["H_InvNo", "D_InvNo", "L_InvNo"];

                    //         foreach ($data_array as $key => $value) {
                    //             if ($value === 0 && !in_array($key, $preserve_keys)) {
                    //                 unset($data_array[$key]);
                    //             }
                    //         }


                    //         $result = [];

                    //         // Loop through each item (sub-array)
                    //         foreach ($excel_data  as $item) {



                    //             $import = []; // Initialize the $import array

                    //             // Loop through each index name in $data_array
                    //             foreach ($data_array as $index => $index_name) {
                    //                 // Append the index (key) to the $import array instead of the value
                    //                 $import[$index] = $item[$data_array[$index]];
                    //             }
                    //             $result[] =  $import;
                    //         }

                    //         return response()->json($result, 200);
                    //     } else {
                    //         $msg = [

                    //             'message' => 'Please input the correct file or settings'

                    //         ];

                    //         return response()->json($msg, 403);
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
