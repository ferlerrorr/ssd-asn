<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AsnFileController extends Controller
{



    /**
     * ? All Vendor Controller
     *
     * @return \Illuminate\Http\Response
     */
    public function vendors()
    {

        $data = DB::connection(env('DB_CONNECTION'))
            ->table('h_column_setup')
            ->select('H_vendor', 'H_vid')
            ->orderBy('H_vendor', 'asc')
            ->get();

        return response()->json($data);
    }


    /**
     * ? Insert ASN FILE Controller
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $vid)
    {
        ini_set('memory_limit', '10G');

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


        $vendor = $data3['H_vendor'];

        // return response()->json($vendor);


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

            $validate = [];

            // Add $data_h, $data_d, and $data_l
            $validate[] = $data_h;
            $validate[] = $data_d;
            $validate[] = $data_l;


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

            $validate = array_values($validate);

            //! working line

            $failedItems = [];
            $passedItems = [];

            foreach ($validate[0] as $index => $item) {
                $itemValidator = Validator::make(['item_0' => $item], [
                    'item_0.3' => 'max:20|required',
                    'item_0.8' => 'max:10|required',
                ], [
                    'item_0.3.max' => "Invoice Number must not exceed :max characters.",
                    'item_0.8.max' => " PORef must not exceed 10 characters.",
                    'item_0.3.required' => " Invoice Number must not be Null or Missing.",
                    'item_0.8.required' => " PORef must not be Null or Missing.",
                ]);

                if ($itemValidator->fails()) {
                    $errors = $itemValidator->errors();
                    $failedAttributes = array_keys($errors->messages());
                    $errorMessages = $errors->all();

                    $failedItem = [
                        array_map(function ($attribute, $error) use ($item) {
                            $attributeName = substr($attribute, 7);
                            return "$item[$attributeName]" . $error;
                        }, $failedAttributes, $errorMessages),
                        $item,

                    ];
                    $failedItems[] = $failedItem;
                } else {
                    $passedItems[0][] = $item;
                }
            }


            foreach ($validate[1] as $index => $item) {
                $itemValidator = Validator::make(['item_1' => $item], [
                    'item_1.1' => 'max:20|required',
                    'item_1.3' => 'max:15|required',
                    // 'item_1.8' => 'max:10|required',
                ], [
                    'item_1.1.max' => " Invoice Number must not exceed :max characters.",
                    'item_1.3.max' => " Item Code must not exceed :max characters.",
                    'item_1.1.required' => " Invoice Number must not be Null or Missing.",
                    'item_1.3.required' => " Item Code must not be Null or Missing.",
                ]);

                if ($itemValidator->fails()) {
                    $errors = $itemValidator->errors();
                    $failedAttributes = array_keys($errors->messages());
                    $errorMessages = $errors->all();

                    $failedItem = [
                        array_map(function ($attribute, $error) use ($item) {
                            $attributeName = substr($attribute, 7);
                            return "$item[$attributeName]" . $error;
                        }, $failedAttributes, $errorMessages),
                        $item,

                    ];
                    $failedItems[] = $failedItem;
                } else {
                    $passedItems[1][] = $item;
                }
            }

            $vd = $validate[2];

            foreach ($vd as $index => $item) {
                $itemValidator = Validator::make(['item_2' => $item], [
                    'item_2.1' => 'max:20|required',
                    'item_2.3' => 'max:10|required',
                    'item_2.4' => 'max:20|required',
                    'item_2.5' => 'required|max:2',
                    'item_2.6' => 'max:8|required',
                    'item_2.7' => [
                        'required',
                        function ($attribute, $value, $fail) {
                            if (strpos($value, '.') !== false) {
                                $fail("Quantity field cannot contain decimal value.");
                            }
                        },
                    ],
                ], [
                    'item_2.1.max' => "Invoice Number must not exceed :max characters.",
                    'item_2.3.max' => "Item Code must not exceed :max characters.",
                    'item_2.4.max' => "Lot Number must not exceed :max characters.",
                    'item_2.6.max' => "Expiry must not exceed 8 characters.",
                    'item_2.5.max' => "Expiry must not exceed 2 characters.",
                    'item_2.1.required' => "Invoice Number must not be null or missing.",
                    'item_2.3.required' => "Item Code must not be null or missing.",
                    'item_2.4.required' => "Lot Number must not be null or missing.",
                    'item_2.6.required' => "Expiry must not be null or missing.",
                    'item_2.5.required' => "Expiry must not be null or missing.",
                    'item_2.7.required' => "Quantity must not be null or missing.",
                ]);

                if ($itemValidator->fails()) {
                    $errors = $itemValidator->errors();
                    $failedAttributes = array_keys($errors->messages());
                    $errorMessages = $errors->all();

                    $failedItem = [
                        array_map(function ($attribute, $error) use ($item) {
                            $attributeName = substr($attribute, 7);
                            return "$item[$attributeName] $error";
                        }, $failedAttributes, $errorMessages),
                        $item,
                    ];
                    $failedItems[] = $failedItem;
                } else {
                    $passedItems[2][] = $item;
                }
            }




            if ($failedItems == !null) {

                $data_h = [];
                $data_d = [];
                $data_l = [];

                if ($passedItems !== null) {
                    // Array to store items with index 0 equal to "H"
                    if (isset($passedItems[0])) {
                        $data_h = array_values($passedItems[0]);
                    }

                    // Array to store items with index 0 equal to "D"
                    if (isset($passedItems[1])) {
                        $data_d = array_values($passedItems[1]);
                    }

                    // Array to store items with index 0
                    if (isset($passedItems[2])) {
                        $data_l = array_values($passedItems[2]);
                    }
                }
                //Array to store items with index 0 equal to "L"



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

                $result_l = []; // Initialize an empty array $result_d to store the filtered data
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
                    $ExpiryYYYY = isset($data_record["L_ExpiryYYYY"]) ? substr($data_record["L_ExpiryYYYY"], -2) : null;

                    $insertData_L[] = [
                        'InvNo' => isset($data_record["L_InvNo"]) ? $data_record["L_InvNo"] : null,
                        'ItemCode' => isset($data_record["L_ItemCode"]) ? $data_record["L_ItemCode"] : null,
                        'LotNo' => isset($data_record["L_LotNo"]) ? $data_record["L_LotNo"] : null,
                        'ExpiryMM' => isset($data_record["L_ExpiryMM"]) ? $data_record["L_ExpiryMM"] : null,
                        'ExpiryDD' => "01",
                        'ExpiryYYYY' => $ExpiryYYYY,
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
                $insertErrData = [];
                foreach ($failedItems as &$err_record) {
                    // Convert the array to a string representation (using JSON in this case)
                    $e_logs = isset($err_record) ? json_encode($err_record) : null;

                    // Remove [ and ] characters as well as the \ character from the JSON string
                    $e_logs = str_replace(['[', ']', '\\'], '', $e_logs);

                    $insertErrData[] = [
                        'e_logs' => $e_logs,
                        'e_vendor' => $vendor,
                    ];
                }

                // Use the query builder to insert the data and ignore duplicates
                foreach (array_chunk($insertErrData, 1000) as $data) {
                    DB::table('tbl_exemption')->insertOrIgnore($data);
                }
                $mergedArray = [];

                // Merge only at the first level
                foreach ($passedItems  as $subArray) {
                    $mergedArray = array_merge($mergedArray, $subArray);
                }

                $resArrcount = count($mergedArray);
                $response = [
                    'passed_items' =>  $resArrcount,
                    'message' => "transaction successful but has failing records",
                    'failing_records' => $failedItems
                ];

                return response()->json($response, 202);
            } else {

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
                    $ExpiryYYYY = isset($data_record["L_ExpiryYYYY"]) ? substr($data_record["L_ExpiryYYYY"], -2) : null;

                    $insertData_L[] = [
                        'InvNo' => isset($data_record["L_InvNo"]) ? $data_record["L_InvNo"] : null,
                        'ItemCode' => isset($data_record["L_ItemCode"]) ? $data_record["L_ItemCode"] : null,
                        'LotNo' => isset($data_record["L_LotNo"]) ? $data_record["L_LotNo"] : null,
                        'ExpiryMM' => isset($data_record["L_ExpiryMM"]) ? $data_record["L_ExpiryMM"] : null,
                        'ExpiryDD' => "01",
                        'ExpiryYYYY' => $ExpiryYYYY,
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


                $mergedArray = [];

                // Merge only at the first level
                foreach ($passedItems as $subArray) {
                    $mergedArray = array_merge($mergedArray, $subArray);
                }

                $resArrcount = count($mergedArray);

                $response = [
                    'passed_items' =>  $resArrcount,
                    'message' => "Transaction successful",
                ];


                return response()->json($response, 200);
            }
        } else {



            $result = [];

            foreach ($data as $item) {
                $import = [];

                foreach ($filteredData as $index => $index_name) {
                    if (isset($item[$index_name]) && $item[$index_name] !== null) {
                        $import[$index] = $item[$index_name];
                    }
                }

                if (!empty($import)) {
                    $result[] = $import;
                }
            }

            array_shift($result);

            //! New Additions>
            if ($vid == 442 || 9470) {

                foreach ($result as $element) {
                    // Check if the element has more than three properties
                    if (count($element) <= 3) {
                        return response()->json("file is not valid");
                    } else {
                        $passedItems_dd = [];
                        $failedItems_dd = [];


                        foreach ($result as $item) {
                            $itemErrors = []; // Initialize error messages for each item

                            // Check for missing or null data in the specified fields
                            $requiredFields = [
                                'H_InvNo' => 'Invoice Number',
                                'H_PORef' => 'PORef',
                                'L_LotNo' => 'Lot Number',
                                'L_Qty' => 'Quantity',
                                'D_ItemCode' => 'Item Code',
                            ];

                            $fieldsMissing = false;

                            foreach ($requiredFields as $fieldKey => $fieldLabel) {
                                if (!isset($item[$fieldKey]) || is_null($item[$fieldKey])) {
                                    $fieldsMissing = true;
                                    $itemErrors[] = "{$fieldLabel} must not be Null or Missing.";
                                }
                            }

                            if ($fieldsMissing) {
                                // Exclude certain fields from the item data before adding to failedItems
                                $failedItemData = array_values($item);
                                unset($failedItemData[2]);
                                unset($failedItemData[3]);
                                unset($failedItemData[4]);

                                $failedItems_dd[] = [
                                    // Original item data without H_InvNo, L_LotNo, and D_ItemCode
                                    $itemErrors,
                                    array_values($failedItemData), // Error messages for this item
                                ];
                            } else {
                                $itemValidator = Validator::make([
                                    'H_InvNo' => $item['H_InvNo'],
                                    'H_PORef' => $item['H_PORef'],
                                    'L_LotNo' => $item['L_LotNo'],
                                    'L_Qty' => $item['L_Qty'],
                                    'D_ItemCode' => $item['D_ItemCode'],
                                    'L_ExpiryYYYY' => $item['L_ExpiryYYYY'],
                                ], [
                                    'H_InvNo' => 'max:20|required|min:1|not_in:0',
                                    'H_PORef' => 'required|min:1|max:10|not_in:0',
                                    'L_Qty' => [
                                        'required',
                                        'min:1',
                                        'not_in:0',
                                        function ($attribute, $value, $fail) {
                                            if (strpos($value, '.') !== false) {
                                                $fail("Quantity field cannot contain Decimal value.");
                                            }
                                        },
                                    ],
                                    'L_LotNo' => 'max:20|required|min:1',
                                    'D_ItemCode' => 'max:15|required|min:1',
                                    'L_ExpiryYYYY' => 'max:8|required',
                                ], [
                                    'H_InvNo.max' => "{$item['H_InvNo']} Invoice Number must not exceed :max characters.",
                                    'L_ExpiryYYYY.max' => "{$item['L_ExpiryYYYY']}  Expiry must not exceed 8 characters.",
                                    'H_PORef.max' => "{$item['H_PORef']} PORef must not exceed 10 characters.",
                                    'D_ItemCode.max' => "{$item['D_ItemCode']} Item Code must not exceed :max characters.",
                                    'H_InvNo.required' => "{$item['H_InvNo']} Invoice Number is required.",
                                    'H_PORef.required' => "{$item['H_PORef']} PORef is required.",
                                    'L_LotNo.required' => "{$item['L_LotNo']} Lot Number is required.",
                                    'D_ItemCode.required' => "{$item['D_ItemCode']} Item Code is required.",
                                    'H_InvNo.min' => "{$item['H_InvNo']} Invoice Number must not be empty or 0.",
                                    'L_LotNo.min' => "{$item['L_LotNo']} Lot Number must not be empty or 0.",
                                    'D_ItemCode.min' => "{$item['D_ItemCode']} Item Code must not be empty or 0.",
                                    'H_PORef.min' => "{$item['H_PORef']} PORef must not be empty or 0.",
                                    'H_PORef.not_in' => "{$item['H_PORef']} PORef must not be empty or 0 .",
                                    'H_InvNo.not_in' => "{$item['H_InvNo']} Invoice Number must not be empty or 0.",
                                    'L_Qty.not_in' => "{$item['L_Qty']} Quantity must not be empty or 0.",
                                ]);

                                if ($itemValidator->fails()) {
                                    $errors = $itemValidator->errors();

                                    // Collect error messages for this item
                                    foreach ($errors->all() as $errorMessage) {
                                        $itemErrors[] = $errorMessage;
                                    }

                                    // Exclude certain fields from the item data before adding to failedItems
                                    $failedItemData = array_values($item);
                                    unset($failedItemData[2]);
                                    unset($failedItemData[3]);
                                    unset($failedItemData[4]);

                                    $failedItems_dd[] = [
                                        $itemErrors,
                                        array_values($failedItemData), // Original item data without H_InvNo, L_LotNo, and D_ItemCode
                                        // Error messages for this item
                                    ];
                                } else {
                                    $passedItems_dd[] = $item;
                                }
                            }
                        }
                        //! Validations->
                        if ($failedItems_dd == !null) {
                            $insertData_H = [];
                            foreach ($passedItems_dd as &$data_record) {
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


                            foreach ($passedItems_dd as &$data_record) {
                                $ExpiryMM = isset($data_record["L_ExpiryMM"]) ? substr($data_record["L_ExpiryMM"], -4, 2) : null;
                                $ExpiryDD = isset($data_record["L_ExpiryMM"]) ? substr($data_record["L_ExpiryMM"], -2) : null;
                                $ExpiryYYYY = isset($data_record["L_ExpiryMM"]) ? substr($data_record["L_ExpiryMM"], -6, 2) : null;

                                $TransactionCode = (
                                    (isset($data_record["L_InvNo"]) ? $data_record["L_InvNo"] : null) .
                                    (isset($data_record["L_ItemCode"]) ? $data_record["L_ItemCode"] : null) .
                                    (isset($data_record["L_LotNo"]) ? $data_record["L_LotNo"] : null) .
                                    (isset($data_record["L_ExpiryMM"]) ? $data_record["L_ExpiryMM"] : null) .
                                    (isset($data_record["L_ExpiryYYYY"]) ? $data_record["L_ExpiryYYYY"] : null) .
                                    (isset($data_record["L_Qty"]) ? $data_record["L_Qty"] : null) .
                                    $L_Count++
                                );

                                $insertData_L[] = [
                                    'InvNo' => isset($data_record["L_InvNo"]) ? $data_record["L_InvNo"] : null,
                                    'ItemCode' => isset($data_record["L_ItemCode"]) ? $data_record["L_ItemCode"] : null,
                                    'LotNo' => isset($data_record["L_LotNo"]) ? $data_record["L_LotNo"] : null,
                                    'ExpiryMM' => $ExpiryMM,
                                    'ExpiryDD' => $ExpiryDD,
                                    'ExpiryYYYY' => $ExpiryYYYY,
                                    'Qty' => isset($data_record["L_Qty"]) ? $data_record["L_Qty"] : null,
                                    'SupCode' => isset($data_record["L_SupCode"]) ? $data_record["L_SupCode"] : null,
                                    'TransactionCode' => $TransactionCode,
                                ];
                            }



                            // Use the query builder to insert the data and ignore duplicates
                            foreach (array_chunk($insertData_L, 1000) as &$data) {
                                DB::table('inv_lot')->insertOrIgnore($data);
                            }

                            $D_Count = 0;
                            $insertData_D = [];


                            foreach ($passedItems_dd  as &$data_record) {
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


                            $resArrcount = count($passedItems_dd);

                            $response = [
                                'passed_items' =>   $resArrcount,
                                'message' => "transaction successful but has failing records",
                                'failing_records' =>  $failedItems_dd
                            ];

                            $insertErrData = [];
                            foreach ($failedItems_dd as &$err_record) {
                                // Convert the array to a string representation (using JSON in this case)
                                $e_logs = isset($err_record) ? json_encode($err_record) : null;

                                // Remove [ and ] characters as well as the \ character from the JSON string
                                $e_logs = str_replace(['[', ']', '\\'], '', $e_logs);

                                $insertErrData[] = [
                                    'e_logs' => $e_logs,
                                    'e_vendor' => $vendor,
                                ];
                            }

                            // Use the query builder to insert the data and ignore duplicates
                            foreach (array_chunk($insertErrData, 1000) as $data) {
                                DB::table('tbl_exemption')->insertOrIgnore($data);
                            }

                            return response()->json($response, 202);
                        } else {
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
                                $ExpiryMM = isset($data_record["L_ExpiryMM"]) ? substr($data_record["L_ExpiryMM"], -4, 2) : null;
                                $ExpiryDD = isset($data_record["L_ExpiryMM"]) ? substr($data_record["L_ExpiryMM"], -2) : null;
                                $ExpiryYYYY = isset($data_record["L_ExpiryMM"]) ? substr($data_record["L_ExpiryMM"], -6, 2) : null;

                                $TransactionCode = (
                                    (isset($data_record["L_InvNo"]) ? $data_record["L_InvNo"] : null) .
                                    (isset($data_record["L_ItemCode"]) ? $data_record["L_ItemCode"] : null) .
                                    (isset($data_record["L_LotNo"]) ? $data_record["L_LotNo"] : null) .
                                    (isset($data_record["L_ExpiryMM"]) ? $data_record["L_ExpiryMM"] : null) .
                                    (isset($data_record["L_ExpiryYYYY"]) ? $data_record["L_ExpiryYYYY"] : null) .
                                    (isset($data_record["L_Qty"]) ? $data_record["L_Qty"] : null) .
                                    $L_Count++
                                );

                                $insertData_L[] = [
                                    'InvNo' => isset($data_record["L_InvNo"]) ? $data_record["L_InvNo"] : null,
                                    'ItemCode' => isset($data_record["L_ItemCode"]) ? $data_record["L_ItemCode"] : null,
                                    'LotNo' => isset($data_record["L_LotNo"]) ? $data_record["L_LotNo"] : null,
                                    'ExpiryMM' => $ExpiryMM,
                                    'ExpiryDD' => $ExpiryDD,
                                    'ExpiryYYYY' => $ExpiryYYYY,
                                    'Qty' => isset($data_record["L_Qty"]) ? $data_record["L_Qty"] : null,
                                    'SupCode' => isset($data_record["L_SupCode"]) ? $data_record["L_SupCode"] : null,
                                    'TransactionCode' => $TransactionCode,
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


                            $resArrcount = count($passedItems_dd);
                            $response = [
                                'passed_items' =>   $resArrcount,
                                'message' => "transaction successful",
                            ];
                            return response()->json($response, 200);
                        }
                    }
                }
            } else {
                //     //! Validations->


                $passedItems_ss = [];
                $failedItems_ss = [];

                foreach ($result as $item) {
                    $itemErrors = []; // Initialize error messages for each item

                    // Check for missing or null data in the specified fields
                    $requiredFields = [
                        'H_InvNo' => 'Invoice Number',
                        'H_PORef' => 'PORef',
                        'L_LotNo' => 'Lot Number',
                        'L_Qty' => 'Quantity',
                        'D_ItemCode' => 'Item Code',
                    ];

                    $fieldsMissing = false;

                    foreach ($requiredFields as $fieldKey => $fieldLabel) {
                        if (!isset($item[$fieldKey]) || is_null($item[$fieldKey])) {
                            $fieldsMissing = true;
                            $itemErrors[] = "{$fieldLabel} must not be Null or Missing.";
                        }
                    }

                    if ($fieldsMissing) {
                        // Exclude certain fields from the item data before adding to failedItems
                        $failedItemData = array_values($item);
                        unset($failedItemData[2]);
                        unset($failedItemData[3]);
                        unset($failedItemData[4]);

                        $failedItems_ss[] = [
                            $itemErrors, // Error messages for this item
                            array_values($failedItemData), // Original item data without H_InvNo, L_LotNo, and D_ItemCode
                        ];
                    } else {
                        // Fields exist, perform validation
                        $itemValidator = Validator::make([
                            'H_InvNo' => $item['H_InvNo'],
                            'H_PORef' => $item['H_PORef'],
                            'L_LotNo' => $item['L_LotNo'],
                            'L_Qty' => $item['L_Qty'],
                            'D_ItemCode' => $item['D_ItemCode'],
                            'L_ExpiryYYYY' => $item['L_ExpiryYYYY'],
                        ], [
                            'H_InvNo' => 'max:20|required|min:1|not_in:0',
                            'H_PORef' => 'required|min:1|max:10|not_in:0',
                            'L_Qty' => [
                                'required',
                                'min:1',
                                'not_in:0',
                                function ($attribute, $value, $fail) {
                                    if (strpos($value, '.') !== false) {
                                        $fail("Quantity field cannot contain Decimal value.");
                                    }
                                },
                            ],
                            'L_LotNo' => 'max:20|required|min:1',
                            'D_ItemCode' => 'max:15|required|min:1',
                            'L_ExpiryYYYY' => 'max:8|required',
                        ], [
                            'H_InvNo.max' => "{$item['H_InvNo']} Invoice Number must not exceed :max characters.",
                            'L_LotNo.max' => "{$item['L_LotNo']} Lot Number must not exceed 20 characters.",
                            'H_PORef.max' => "{$item['H_PORef']} PORef must not exceed 10 characters.",
                            // 'L_Qty.max' => "{$item['L_Qty']} Quantity must not exceed :max characters.",
                            //'L_ExpiryYYYY.min' => "{$item['L_ExpiryYYYY']} Expiry must not be below 8 characters.",
                            'L_ExpiryYYYY.max' => "{$item['L_ExpiryYYYY']}  Expiry must not exceed 8 characters.",
                            'D_ItemCode.max' => "{$item['D_ItemCode']} Item Code must not exceed :max characters.",
                            'H_InvNo.required' => "{$item['H_InvNo']} Invoice Number is required.",
                            'H_PORef.required' => "{$item['H_PORef']} PORef is required.",
                            'L_LotNo.required' => "{$item['L_LotNo']} Lot Number is required.",
                            'D_ItemCode.required' => "{$item['D_ItemCode']} Item Code is required.",
                            'H_InvNo.min' => "{$item['H_InvNo']} Invoice Number must not be empty or 0.",
                            'L_LotNo.min' => "{$item['L_LotNo']} Lot Number must not be empty or 0.",
                            'D_ItemCode.min' => "{$item['D_ItemCode']} Item Code must not be empty or 0.",
                            'H_PORef.min' => "{$item['H_PORef']} PORef must not be empty or 0.",
                            'H_PORef.not_in' => "{$item['H_PORef']} PORef must not be empty or 0 .",
                            'H_InvNo.not_in' => "{$item['H_InvNo']} Invoice Number must not be empty or 0.",
                            'L_Qty.not_in' => "{$item['L_Qty']} Quantity must not be empty or 0.",
                        ]);

                        if ($itemValidator->fails()) {
                            $errors = $itemValidator->errors();

                            // Collect error messages for this item
                            foreach ($errors->all() as $errorMessage) {
                                $itemErrors[] = $errorMessage;
                            }

                            // Exclude certain fields from the item data before adding to failedItems
                            $failedItemData = array_values($item);
                            unset($failedItemData[2]);
                            unset($failedItemData[3]);
                            unset($failedItemData[4]);

                            $failedItems_ss[] = [
                                array_values($failedItemData), // Original item data without H_InvNo, L_LotNo, and D_ItemCode
                                $itemErrors, // Error messages for this item
                            ];
                        } else {
                            $passedItems_ss[] = $item;
                        }
                    }
                }
                if ($failedItems_ss == !null) {
                    $insertData_H = [];


                    foreach ($passedItems_ss as &$data_record) {
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


                    foreach ($passedItems_ss as &$data_record) {
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


                    foreach ($passedItems_ss as &$data_record) {
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


                    $insertErrData = [];
                    foreach ($failedItems_ss as &$err_record) {
                        // Convert the array to a string representation (using JSON in this case)
                        $e_logs = isset($err_record) ? json_encode($err_record) : null;

                        // Remove [ and ] characters as well as the \ character from the JSON string
                        $e_logs = str_replace(['[', ']', '\\'], '', $e_logs);

                        $insertErrData[] = [
                            'e_logs' => $e_logs,
                            'e_vendor' => $vendor,
                        ];
                    }

                    // Use the query builder to insert the data and ignore duplicates
                    foreach (array_chunk($insertErrData, 1000) as $data) {
                        DB::table('tbl_exemption')->insertOrIgnore($data);
                    }

                    $resArrcount = count($passedItems_ss);
                    $response = [
                        'passed_items' =>  $resArrcount,
                        'message' => "transaction successful but has failing records",
                        'failing_records' =>  $failedItems_ss
                    ];

                    return response()->json($response, 202);
                } else {
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



                    $resArrcount = count($passedItems_ss);


                    $response = [
                        'passed_items' =>  $resArrcount,
                        'message' => "transaction successful",
                    ];
                    return response()->json($response, 200);
                }
            }
        }
    }

    //! Export - --------------------------------------------------------------------------->
    /**
     *  ? Export ASN File
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {

        //? Method Post
        // Assuming $request contains a comma-separated string of values, you can explode it like this:
        $PORefs = explode(",", $request);


        //! Eager loaded
        $invNumbers = DB::table('inv_hdr')
            ->whereIn('PORef', $PORefs)
            ->pluck('InvNo')
            ->toArray();


        $invDetailsMap = [];
        $invLotsMap = [];
        $invHdrsMap = [];
        $jp_POVNUMMap = [];
        $ji_INUMBRMap = [];

        // Fetch invDetails and invLots using eager loading
        $invLots = DB::table('inv_lot')
            ->whereIn('InvNo', $invNumbers)
            ->get()
            ->groupBy('InvNo');

        foreach ($invLots as $invNo => $lots) {
            $invDetailsMap[$invNo] = $lots->pluck('ItemCode')->toArray();
            $invLotsMap[$invNo] = $lots;
        }

        // Fetch invHdrs using eager loading
        $invHdrs = DB::table('inv_hdr')
            ->whereIn('InvNo', $invNumbers)
            ->get()
            ->keyBy('InvNo');

        foreach ($invHdrs as $invNo => $invHdr) {
            $invHdrsMap[$invNo] = $invHdr;
        }

        $dataArray = [];

        foreach ($invNumbers as $invNo) {
            $invLots = $invLotsMap[$invNo];
            $invDetails = $invDetailsMap[$invNo];
            $invHdr = $invHdrsMap[$invNo];
            $poRef = $invHdr->PORef;

            if (!isset($jp_POVNUMMap[$poRef])) {
                $jp_POVNUMMap[$poRef] = DB::table('jda_pomhdr')
                    ->where('jp_PONUMB', $poRef)
                    ->value('jp_POVNUM');
            }

            $jp_POVNUM = $jp_POVNUMMap[$poRef];

            $itemArray = [];

            foreach ($invLots as $invLot) {
                $itemCode = $invLot->ItemCode;

                if (!isset($ji_INUMBRMap[$itemCode])) {
                    $ji_INUMBRMap[$itemCode] = DB::table('jda_invmst')
                        ->where('ji_IMFGNO', $itemCode)
                        ->orWhere('ji_IVVNDN', $itemCode)
                        ->value('ji_INUMBR');
                }

                $ji_INUMBR = $ji_INUMBRMap[$itemCode];

                $item = [
                    $jp_POVNUM,
                    $poRef,
                    $ji_INUMBR,
                    $invLot->Qty,
                    $invLot->LotNo,
                    $invLot->ExpiryMM . $invLot->ExpiryDD . $invLot->ExpiryYYYY,
                ];

                $itemArray[] = $item;
            }

            // Append itemArray to dataArray
            $dataArray = array_merge($dataArray, $itemArray);
        }


        // $dd = count($dataArray);
        // return response()->json([$dd, $dataArray]);


        $csvData = [];
        foreach ($dataArray as &$row) {
            $csvData[] = implode(',', $row);
        }
        $csvContent = implode("\r\n", $csvData);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="MSSASN1.csv"',
        ];

        return Response::make($csvContent, 200, $headers);
    }
}
