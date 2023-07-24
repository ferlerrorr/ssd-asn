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


        $slug = $slug;

        // $slug = "ZUELLIG";

        // Retrieve data as arrays 
        $data_D = DB::connection(env('DB_CONNECTION'))
            ->table('d_column_setup')
            ->select('*')
            ->where('D_vendor', 'LIKE', $slug)
            ->get()
            ->toArray();

        $data_H = DB::connection(env('DB_CONNECTION'))
            ->table('h_column_setup')
            ->select('*')
            ->where('H_vendor', 'LIKE', $slug)
            ->get()
            ->toArray();

        $data_L = DB::connection(env('DB_CONNECTION'))
            ->table('l_column_setup')
            ->select('*')
            ->where('L_vendor', 'LIKE', $slug)
            ->get()
            ->toArray();

        $data = [
            $data_D[0],
            $data_H[0],
            $data_L[0]
        ];


        $flattenedData = [];

        // Loop through each sub-array
        foreach ($data as $subArray) {
            // Convert the stdClass object to an array
            $subArray = (array) $subArray;

            // Remove the first two items from the sub-array
            array_splice($subArray, 0, 3);

            // Merge the modified sub-array into the flattenedData array
            $flattenedData = array_merge($flattenedData, $subArray);
        }

        // Define the keys that should be excluded from being converted to null
        $excludedKeys = ['D_InvNo', 'H_InvNo', 'L_InvNo'];

        // Loop through the flattenedData array and convert "0" to null for specified keys
        foreach ($flattenedData as $key => $value) {
            // Check if the value is "0" and the key is not in the excludedKeys array
            if ($value === "0" && !in_array($key, $excludedKeys)) {
                $flattenedData[$key] = null;
            }
        }


        return response()->json($flattenedData);

        // return response()->json($data_D);
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
