<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DuplicatePoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function duplicateUpdate()
    {
        $po = DB::table('jda_pomhdr')
            ->where('jp_PONOT1', 'like', '%' . "dupl" . '%')
            ->get();

        $poIds = []; // Initialize an array to store the jp_ids

        // Iterate through the results and apply multiple regex replacements
        foreach ($po as $item) {
            $item->jp_PONOT1 = preg_replace(['/.*\s/', '/.*#/', '/.*[Oo]/'], '', $item->jp_PONOT1);

            // Update the table where jp_id matches $item->jp_id
            DB::table('jda_pomhdr')
                ->where('jp_id', $item->jp_id)
                ->update(['jp_PONOT1' => $item->jp_PONOT1, 'jp_remarks' => Carbon::now()->format('Y-m-d')]);

            // Add the jp_id to the array
            $poIds[] = $item->jp_id;
        }

        // Retrieve the updated table for all the jp_ids
        $updatedTable = DB::table('jda_pomhdr')
            ->whereIn('jp_id', $poIds)
            ->get();

        return response()->json($updatedTable);
    }

    /**
     * Load the first 50 unique duplicate logs from the 'jda_pomhdr' table.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadDuplicatesLogs()
    {
        $uniqueTimestamps = [];
        $filteredData = [];

        $entries = DB::table('jda_pomhdr')
            ->select('jp_remarks')
            ->where(function ($query) {
                $query->whereNotNull('jp_remarks')
                    ->orWhere('jp_remarks', '!=', '');
            })
            ->orderBy('jp_remarks', 'desc')
            ->distinct()
            ->take(50)
            ->get();


        foreach ($entries as $entry) {
            $timestamp = $entry->jp_remarks;

            // Check if the timestamp is not null and not an empty string
            if ($timestamp !== null && trim($timestamp) !== '') {
                // Check if the timestamp is not already in the uniqueTimestamps array
                if (!in_array($timestamp, $uniqueTimestamps)) {
                    $filteredData[] = [
                        "placeholder" => "Duplicate PO",
                        "e_time_stamp" => $timestamp,
                        "link" => "http://10.91.100.145:8800/api/ssd/asn/duplicate-po-export/$timestamp"
                    ];

                    // Add the timestamp to the uniqueTimestamps array to track duplicates
                    $uniqueTimestamps[] = $timestamp;
                }
            }
        }

        return response()->json($filteredData);
    }
    public function duplicateExport($date)
    {
        $po = DB::table('jda_pomhdr')
            ->where('jp_remarks', 'like', '%' . $date . '%')
            ->select('jp_PONUMB', 'jp_PONOT1')
            ->get();

        $duplicatePo = $po->toArray();

        // Create an array to store the InvNo values
        $invNumbers = [];

        foreach ($duplicatePo as $poItem) {
            $invNumbersForPo = DB::table('inv_hdr')
                ->where('PORef', $poItem->jp_PONOT1)
                ->pluck('InvNo')
                ->toArray();

            // Merge the InvNo values for the current PO into the result array
            $invNumbers = array_merge($invNumbers, $invNumbersForPo);

            // Update the 'Duplicate_PO' column in 'inv_hdr' with $poItem->jp_PONOT1
            DB::table('inv_hdr')
                ->where('PORef', $poItem->jp_PONUMB)
                ->update(['Duplicate_PO' => $poItem->jp_PONOT1]);
        }

        // return response()->json($invNumbers);
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
            $poRef2 = $poItem->jp_PONUMB;


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
                    $poRef2,
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
