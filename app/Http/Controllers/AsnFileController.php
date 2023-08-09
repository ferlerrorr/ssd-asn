<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;

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




        //Todo data Validations -- 

        // Todo  Remove Unilab Qty().) to right onli (UNILAB)

        //! Todo after validation if (Qty_Free is not null Qty_free + Qty_shipped)  -- Logic Error Must be Address must be clear and communicated



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
            return $key !== 'D_id' && $key !== 'L_id' && $key !== 'H_id' && $key !== 'H_file_type' && $key !== 'D_file_type' && $key !== 'L_file_type' && $key !== 'L_vid' && $key !== 'L_vendor' && $key !== 'D_vid' && $key !== 'D_vendor' && $key !== 'H_vid' && $key !== 'H_vendor';
        }, ARRAY_FILTER_USE_BOTH);


        // Convert each property value to an integer
        foreach ($filteredData as $key => &$value) {
            $filteredData[$key] = intval($value);
        }
        unset($value);

        $specialKeys = array("H_InvNo", "D_InvNo", "L_InvNo");

        foreach ($filteredData as $key => $value) {
            if ($value === 0 && !in_array($key, $specialKeys)) {
                $filteredData[$key] = null;
            }
        }


        if ($vid == "200") {

            //Todo data Validations --  
            // Loop through each item in the $data[] array
            // Check if the first index of the item is equal to "H", "D", or "L"
            // If it matches, add the item to the respective $data_h, $data_d, or $data_l array

            // Initialize arrays to store items based on index 0 values
            $data_h = array(); // Array to store items with index 0 equal to "H"
            $data_d = array(); // Array to store items with index 0 equal to "D"
            $data_l = array(); // Array to store items with index 0 equal to "L"

            // Loop through the original data and categorize items based on index 0 value
            foreach ($data as $item) {
                if ($item[0] == "H") {
                    $data_h[] = $item; // Add item to $data_h array
                } elseif ($item[0] == "D") {
                    $data_d[] = $item; // Add item to $data_d array
                } elseif ($item[0] == "L") {
                    $data_l[] = $item; // Add item to $data_l array
                }
            }


            $validate = [
                $data_h,
                $data_d,
                $data_l,
            ];


            // return response()->json($validate);

            //! working line

            $failedItems = [];
            $passedItems = [];

            foreach ($validate[0] as $index => $item) {
                $itemValidator = Validator::make(['item_0' => $item], [
                    'item_0.3' => 'max:20',
                    'item_0.10' => 'max:10',
                ]);

                if ($itemValidator->passes()) {
                    $passedItems[0][] = $item;
                } else {
                    $failedItem = [
                        $item,
                        array_values($itemValidator->errors()->toArray()),
                    ];
                    $failedItems[] = $failedItem;
                }
            }

            foreach ($validate[1] as $index => $item) {
                $itemValidator = Validator::make(['item_1' => $item], [
                    'item_1.1' => 'max:20',
                    'item_1.3' => 'max:15',
                ]);

                if ($itemValidator->passes()) {
                    $passedItems[] = $item;
                } else {
                    $failedItem = [
                        $item,
                        array_values($itemValidator->errors()->toArray()),
                    ];
                    $failedItems[] = $failedItem;
                }
            }

            foreach ($validate[2] as $index => $item) {
                $itemValidator = Validator::make(['item_2' => $item], [
                    'item_2.1' => 'max:20',
                    'item_2.3' => 'max:15',
                    'item_2.4' => 'max:20',
                ]);

                if ($itemValidator->passes()) {
                    $passedItems[2][] = $item;
                } else {
                    $failedItem = [
                        $item,
                        array_values($itemValidator->errors()->toArray()),
                    ];
                    $failedItems[] = $failedItem;
                }
            }

            $ferror = $failedItems;

            if ($failedItems == !null) {

                $response = [
                    // 'passed_items' =>  array_values($passedItems),
                    'failed_items' => $ferror,
                ];
                return response()->json($response, 202);
            } else {

                //! working line
                // Process the data with index 0 equal to "H"
                $result_h = [];

                // Loop through each item (sub-array) in $data_h
                foreach ($data_h as $item) {
                    $import_h = []; // Initialize the $import_h array to store filtered data for each item

                    // Loop through each index name in $fdata_h
                    foreach ($filteredData as $index => $index_name) {
                        // Check if the value is not null before adding it to the $import_h array
                        if (isset($item[$index_name]) && $item[$index_name] !== null) {
                            $import_h[$index] = $item[$index_name];
                        }
                    }

                    // Check if $import_h array is not empty before adding it to the $result_h array
                    if (!empty($import_h)) {
                        $result_h[] = $import_h;
                    }
                }



                // Prepare data for insertion into the database for table 'inv_hdr'
                $insertData_H = [];
                foreach ($result_h as &$data_record) {
                    $insertData_H[] = [
                        'InvNo' => isset($data_record["H_InvNo"]) ? $data_record["H_InvNo"] : null,
                        'POref' => isset($data_record["H_PORef"]) ? $data_record["H_PORef"] : null,
                        'InvDate' => isset($data_record["H_InvDate"]) ? $data_record["H_InvDate"] : null,
                        'InvAmt' => isset($data_record["H_InvAmt"]) ? $data_record["H_InvAmt"] : null,
                        'DiscAmt' => isset($data_record["H_DiscAmt"]) ? $data_record["H_DiscAmt"] : null,
                        'StkFlag' => isset($data_record["H_StkFlag"]) ? $data_record["H_StkFlag"] : null,
                        'VendorID' => isset($data_record["H_VendorID"]) ? $data_record["H_VendorID"] : null,
                        'VendorName' => isset($data_record["H_VendorName"]) ? $data_record["H_VendorName"] : null,
                        'SupCode' => isset($data_record["H_SupCode"]) ? $data_record["H_SupCode"] : null,
                    ];
                }

                // Use the query builder to insert the data and ignore duplicates
                foreach (array_chunk($insertData_H, 1000) as &$data) {
                    DB::table('inv_hdr')->insertOrIgnore($data);
                }

                $result_l = [];        // Initialize an empty array $result_d to store the filtered data
                // Loop through each item (sub-array) in the original data
                foreach ($data_l as $item) {
                    $import = []; // Initialize the $import array to store filtered data for each item

                    // Loop through each index name in $filteredData
                    foreach ($filteredData as $index => $index_name) {
                        // Check if the value is not null before adding it to the $import array
                        if (isset($item[$index_name]) && $item[$index_name] !== null) {
                            $import[$index] = $item[$index_name];
                        }
                    }

                    // Check if $import array is not empty before adding it to the $result_l array
                    if (!empty($import)) {
                        $result_l[] = $import;
                    }
                }

                $insertData_L = [];
                $L_Count = 0;

                foreach ($result_l as &$data_record) {
                    $insertData_L[] = [
                        'InvNo' => isset($data_record["L_InvNo"]) ? $data_record["L_InvNo"] : null,
                        'ItemCode' => isset($data_record["L_ItemCode"]) ? $data_record["L_ItemCode"] : null,
                        'LotNo' => isset($data_record["L_LotNo"]) ? $data_record["L_LotNo"] : null,
                        'ExpiryMM' => isset($data_record["L_ExpiryMM"]) ? $data_record["L_ExpiryMM"] : null,
                        'ExpiryDD' => "01",
                        'ExpiryYYYY' => isset($data_record["L_ExpiryYYYY"]) ? $data_record["L_ExpiryYYYY"] : null,
                        'Qty' => isset($data_record["L_Qty"]) ? $data_record["L_Qty"] : null,
                        'SupCode' => isset($data_record["L_SupCode"]) ? $data_record["L_SupCode"] : null,
                        'TransactionCode' => (
                            (isset($data_record["L_InvNo"]) ? $data_record["L_InvNo"] : null) .
                            (isset($data_record["L_ItemCode"]) ? $data_record["L_ItemCode"] : null) .
                            (isset($data_record["L_LotNo"]) ? $data_record["L_LotNo"] : null) .
                            (isset($data_record["L_ExpiryMM"]) ? $data_record["L_ExpiryMM"] : null) .
                            (isset($data_record["L_ExpiryYYYY"]) ? $data_record["L_ExpiryYYYY"] : null) .
                            (isset($data_record["L_Qty"]) ? $data_record["L_Qty"] : null) .
                            $L_Count
                        )
                    ];
                    $L_Count++; // Increment the $L_Count after creating the TransactionCode for each record
                }

                // Use the query builder to insert the data and ignore duplicates
                foreach (array_chunk($insertData_L, 1000) as &$data) {
                    DB::table('inv_lot')->insertOrIgnore($data);
                }


                $result_d = [];        // Initialize an empty array $result_d to store the filtered data
                // Loop through each item (sub-array)
                foreach ($data_d as $item) {
                    $import = []; // Initialize the $import array

                    // Loop through each index name in $filteredData
                    foreach ($filteredData as $index => $index_name) {
                        // Check if the value is not null before adding it to the $import array
                        if (isset($item[$filteredData[$index]]) && $item[$filteredData[$index]] !== null) {
                            $import[$index] = $item[$filteredData[$index]];
                        }
                    }

                    // Check if $import array is not empty before adding it to the $result array
                    if (!empty($import)) {
                        $result_d[] = $import;
                    }
                }

                $D_Count = 0;
                $insertData_D = [];


                foreach ($result_d as &$data_record) {
                    $insertData_D[] = [
                        'InvNo' => isset($data_record["D_InvNo"]) ? $data_record["D_InvNo"] : null,
                        'ItemCode' => isset($data_record["D_ItemCode"]) ? $data_record["D_ItemCode"] : null,
                        'ItemName' => isset($data_record["D_ItemName"]) ? $data_record["D_ItemName"] : null,
                        'ConvFact2' => isset($data_record["D_ConvFact2"]) ? $data_record["D_ConvFact2"] : null,
                        'UOM' => isset($data_record["D_UOM"]) ? $data_record["D_UOM"] : null,
                        'UnitCost' => isset($data_record["D_UnitCost"]) ? $data_record["D_UnitCost"] : null,
                        'QtyShip' => isset($data_record["D_QtyShip"]) ? $data_record["D_QtyShip"] : null,
                        'QtyFree' => isset($data_record["D_QtyFree"]) ? $data_record["D_QtyFree"] : null,
                        'GrossAmt' => isset($data_record["D_GrossAmt"]) ? $data_record["D_GrossAmt"] : null,
                        'PldAmt' => isset($data_record["D_PldAmt"]) ? $data_record["D_PldAmt"] : null,
                        'NetAmt' => isset($data_record["D_NetAmt"]) ? $data_record["D_NetAmt"] : null,
                        'SupCode' => isset($data_record["D_SupCode"]) ? $data_record["D_SupCode"] : null,
                        'TransactionCode' => (
                            (isset($data_record["D_InvNo"]) ? $data_record["D_InvNo"] : null) .
                            (isset($data_record["D_ItemCode"]) ? $data_record["D_ItemCode"] : null) .
                            (isset($data_record["D_ItemName"]) ? $data_record["D_ItemName"] : null) .
                            (isset($data_record["D_ConvFact2"]) ? $data_record["D_ConvFact2"] : null) .
                            (isset($data_record["D_UOM"]) ? $data_record["D_UOM"] : null) .
                            (isset($data_record["D_UnitCost"]) ? $data_record["D_UnitCost"] : null) .
                            (isset($data_record["D_QtyShip"]) ? $data_record["D_QtyShip"] : null) .
                            (isset($data_record["D_QtyFree"]) ? $data_record["D_QtyFree"] : null) .
                            (isset($data_record["D_GrossAmt"]) ? $data_record["D_GrossAmt"] : null) .
                            (isset($data_record["D_PldAmt"]) ? $data_record["D_PldAmt"] : null) .
                            (isset($data_record["D_NetAmt"]) ? $data_record["D_NetAmt"] : null) .
                            (isset($data_record["D_SupCode"]) ? $data_record["D_SupCode"] : null) .
                            $D_Count++
                        ),
                    ];
                }
                // Use the query builder to insert the data and ignore duplicates
                foreach (array_chunk($insertData_D, 1000) as &$data) {
                    DB::table('inv_dtl')->insertOrIgnore($data);
                }
                $response = [
                    // 'passed_items' =>  array_values($passedItems),
                    'message' => "transaction successful",
                ];
                return response()->json($response, 200);
            }
        } else {
            $result = [];

            // Loop through each item (sub-array)
            foreach ($data as $item) {
                $import = []; // Initialize the $import array

                // Loop through each index name in $filteredData
                foreach ($filteredData as $index => $index_name) {
                    // Check if the value is not null before adding it to the $import array
                    if (isset($item[$filteredData[$index]]) && $item[$filteredData[$index]] !== null) {
                        $import[$index] = $item[$filteredData[$index]];
                    }
                }

                // Check if $import array is not empty before adding it to the $result array
                if (!empty($import)) {
                    $result[] = $import;
                }
            }



            $insertData_H = [];


            foreach ($result as &$data_record) {
                $insertData_H[] = [
                    'InvNo' => isset($data_record["H_InvNo"]) ? $data_record["H_InvNo"] : null,
                    'POref' => isset($data_record["H_PORef"]) ? $data_record["H_PORef"] : null,
                    'InvDate' => isset($data_record["H_InvDate"]) ? $data_record["H_InvDate"] : null,
                    'InvAmt' => isset($data_record["H_InvAmt"]) ? $data_record["H_InvAmt"] : null,
                    'DiscAmt' => isset($data_record["H_DiscAmt"]) ? $data_record["H_DiscAmt"] : null,
                    'StkFlag' => isset($data_record["H_StkFlag"]) ? $data_record["H_StkFlag"] : null,
                    'VendorID' => isset($data_record["H_VendorID"]) ? $data_record["H_VendorID"] : null,
                    'VendorName' => isset($data_record["H_VendorName"]) ? $data_record["H_VendorName"] : null,
                    'SupCode' => isset($data_record["H_SupCode"]) ? $data_record["H_SupCode"] : null,
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
                    'InvNo' => isset($data_record["L_InvNo"]) ? $data_record["L_InvNo"] : null,
                    'ItemCode' => isset($data_record["L_ItemCode"]) ? $data_record["L_ItemCode"] : null,
                    'LotNo' => isset($data_record["L_LotNo"]) ? $data_record["L_LotNo"] : null,
                    'ExpiryMM' => isset($data_record["L_ExpiryMM"]) ? $data_record["L_ExpiryMM"] : null,
                    'ExpiryDD' => "01",
                    'ExpiryYYYY' => isset($data_record["L_ExpiryYYYY"]) ? $data_record["L_ExpiryYYYY"] : null,
                    'Qty' => isset($data_record["L_Qty"]) ? $data_record["L_Qty"] : null,
                    'SupCode' => isset($data_record["L_SupCode"]) ? $data_record["L_SupCode"] : null,
                    'TransactionCode' => (
                        (isset($data_record["L_InvNo"]) ? $data_record["L_InvNo"] : null) .
                        (isset($data_record["L_ItemCode"]) ? $data_record["L_ItemCode"] : null) .
                        (isset($data_record["L_LotNo"]) ? $data_record["L_LotNo"] : null) .
                        (isset($data_record["L_ExpiryMM"]) ? $data_record["L_ExpiryMM"] : null) .
                        (isset($data_record["L_ExpiryYYYY"]) ? $data_record["L_ExpiryYYYY"] : null) .
                        (isset($data_record["L_Qty"]) ? $data_record["L_Qty"] : null) .
                        $L_Count++
                    )
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
                    'InvNo' => isset($data_record["D_InvNo"]) ? $data_record["D_InvNo"] : null,
                    'ItemCode' => isset($data_record["D_ItemCode"]) ? $data_record["D_ItemCode"] : null,
                    'ItemName' => isset($data_record["D_ItemName"]) ? $data_record["D_ItemName"] : null,
                    'ConvFact2' => isset($data_record["D_ConvFact2"]) ? $data_record["D_ConvFact2"] : null,
                    'UOM' => isset($data_record["D_UOM"]) ? $data_record["D_UOM"] : null,
                    'UnitCost' => isset($data_record["D_UnitCost"]) ? $data_record["D_UnitCost"] : null,
                    'QtyShip' => isset($data_record["D_QtyShip"]) ? $data_record["D_QtyShip"] : null,
                    'QtyFree' => isset($data_record["D_QtyFree"]) ? $data_record["D_QtyFree"] : null,
                    'GrossAmt' => isset($data_record["D_GrossAmt"]) ? $data_record["D_GrossAmt"] : null,
                    'PldAmt' => isset($data_record["D_PldAmt"]) ? $data_record["D_PldAmt"] : null,
                    'NetAmt' => isset($data_record["D_NetAmt"]) ? $data_record["D_NetAmt"] : null,
                    'SupCode' => isset($data_record["D_SupCode"]) ? $data_record["D_SupCode"] : null,
                    'TransactionCode' => (
                        (isset($data_record["D_InvNo"]) ? $data_record["D_InvNo"] : null) .
                        (isset($data_record["D_ItemCode"]) ? $data_record["D_ItemCode"] : null) .
                        (isset($data_record["D_ItemName"]) ? $data_record["D_ItemName"] : null) .
                        (isset($data_record["D_ConvFact2"]) ? $data_record["D_ConvFact2"] : null) .
                        (isset($data_record["D_UOM"]) ? $data_record["D_UOM"] : null) .
                        (isset($data_record["D_UnitCost"]) ? $data_record["D_UnitCost"] : null) .
                        (isset($data_record["D_QtyShip"]) ? $data_record["D_QtyShip"] : null) .
                        (isset($data_record["D_QtyFree"]) ? $data_record["D_QtyFree"] : null) .
                        (isset($data_record["D_GrossAmt"]) ? $data_record["D_GrossAmt"] : null) .
                        (isset($data_record["D_PldAmt"]) ? $data_record["D_PldAmt"] : null) .
                        (isset($data_record["D_NetAmt"]) ? $data_record["D_NetAmt"] : null) .
                        (isset($data_record["D_SupCode"]) ? $data_record["D_SupCode"] : null) .
                        $D_Count++
                    ),
                ];
            }
            // Use the query builder to insert the data and ignore duplicates
            foreach (array_chunk($insertData_D, 1000) as &$data) {
                DB::table('inv_dtl')->insertOrIgnore($data);
            }
        }

        //exit response
        return response()->json($insertData_H);
    }
}
