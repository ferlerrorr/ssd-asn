<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ErrLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function geterrlogs($datetime, $vendor)
    {
        $datetime = urldecode($datetime);
        $dateTimeObject = new Carbon($datetime);
        $named_dt = $dateTimeObject->format('Y-m-d');



        $err_logs = DB::table('tbl_exemption')
            ->where('e_time_stamp', $datetime)->where('e_vendor', $vendor)
            ->get()->toArray();

        $csvData = [];
        foreach ($err_logs as $row) {
            // Convert the object to an associative array
            $rowArray = json_decode(json_encode($row), true);

            // Remove the 'id' key from the array
            unset($rowArray['id']);

            $csvData[] = implode(',', $rowArray);
        }
        $csvContent = implode("\r\n", $csvData);


        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$vendor}-{$named_dt}.csv",
        ];

        return Response::make($csvContent, 200, $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadErrlogs()
    {
        $uniqueTimestamps = [];
        $filteredData = [];

        $entries = DB::table('tbl_exemption')
            ->orderBy('e_time_stamp', 'desc')
            ->distinct()
            ->take(50)
            ->get();

        foreach ($entries as $entry) {
            $timestamp = $entry->e_time_stamp;

            if (!in_array($timestamp, $uniqueTimestamps)) {
                $uniqueTimestamps[] = $timestamp;
                $e_time_stamp = str_replace(' ', 'T', $entry->e_time_stamp); // Replace spaces with %

                $filteredData[] = [
                    "e_vendor" => $entry->e_vendor,
                    "e_time_stamp" => $entry->e_time_stamp,
                    "link" => "http://localhost:8800/api/ssd/asn/jda/errlogs/$e_time_stamp/$entry->e_vendor"
                ];
            }
        }

        return response()->json($filteredData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchErrlogs($date)
    {
        $entries = DB::table('tbl_exemption')
            ->where('e_time_stamp', 'like', '%' . $date . '%')
            ->orderBy('e_time_stamp', 'desc')
            ->get();

        foreach ($entries as &$entry) {
            unset($entry->id);
            unset($entry->e_logs);
        }

        return response()->json($entries);
    }
}
