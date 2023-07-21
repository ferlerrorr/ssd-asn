<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

error_reporting(E_ERROR | E_PARSE);

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $qry = DB::connection(env('DB2_CONNECTION'))->select('SELECT POSTAT FROM MM770SSL.POMHDR WHERE POEDAT >= 230717');
        return response()->json($qry);
    }


    public function store()
    {
        $data = null;
        if ($_FILES["import_excel_ms"]["name"] != '') {
            $allowed_extension = array('xls', 'csv', 'xlsx', 'txt');
            $file_array = explode(".", $_FILES["import_excel_ms"]["name"]);
            $file_extension = end($file_array);

            //! This Block of Handles Data Stream Validtation  
            if (!in_array($file_extension, $allowed_extension)) {
                $data = [["Invalid file: Must be a Manual SOA File."]];
                return response()->json(
                    $data,
                    403
                );
            }
            //! This Block of Handles Data Stream Validtation 

            if (in_array($file_extension, $allowed_extension)) {
                $file_name = time() . '.' . $file_extension;
                move_uploaded_file($_FILES['import_excel_ms']['tmp_name'], $file_name);
                $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

                $spreadsheet = $reader->load($file_name);

                unlink($file_name);

                $data = $spreadsheet->getActiveSheet()->toArray();

                //! This Block of Handles Data Stream Import to array

                //! Validation

                $excel_header_value = $data[0];

                if (($excel_header_value[0]) != "Date" && ($excel_header_value[1]) != "Debit" && ($excel_header_value[2]) != "Credit" && ($excel_header_value[3]) != "Status") {
                    $data = null;
                    $data = [["Invalid file: Must be a Manual SOA File."]];
                    return response()->json(
                        $data,
                        403
                    );
                    //! Validation
                } else {

                    array_shift($data); //? Remove the first row


                    foreach ($data as &$row) {
                        foreach ($row as &$value) {
                            if ($value === "" || $value === " " || $value === "  " || $value === "   ") {
                                $value = null;
                            }
                        }
                    }  //? Remove all "" , " " , "  " , "   " & Replace with Null Value

                    $data = array_values($data); //? Reset array keys


                    // ! This Block of Handles Database Query

                    $query = "
                          INSERT INTO manual_soa
                          (Date_of_transaction, Debit, Credit, Status_field, Balance) 
                          VALUES (:Date_of_transaction, :Debit, :Credit, :Status_field, :Balance)
                       ";


                    foreach ($data as &$row) {
                        DB::statement($query, [
                            ':Date_of_transaction' => $row[0],
                            ':Debit' => $row[1],
                            ':Credit' => $row[2],
                            ':Status_field' => $row[3],
                            ':Balance' => $row[4]
                        ]);
                    }

                    // ! This Block of Handles Database Query


                    return response()->json(
                        $data,
                        200
                    );
                }
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Po()
    {

        //Delimiter for PO
        // string varDate = DateTime.Now.AddDays(-60).ToString("yyMMdd");

        $data = DB::connection(env('DB2_CONNECTION'))
            ->table('MM770SSL.POMHDR')
            ->select('PONUMB', 'POSTAT', 'PONOT1', 'POVNUM', 'POEDAT')
            ->where('POEDAT', '>=', 230717)
            ->orderByDesc('PONUMB')
            ->get();


        $data = &$data->map(function (&$item) {
            return (array) $item;
        });

        $rowCount = $data->count();

        $data->transform(function (&$row) {
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
            DB::table('jda_pomhdr')->insertOrIgnore($data);
        }


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
            ->table('MM770SSL.INVMST')
            ->select('INUMBR', 'IVNDPN')
            ->get();


        $data = &$data->map(function (&$item) {
            return (array) $item;
        });

        $data->transform(function (&$row) {
            foreach ($row as &$value) {
                $value = trim($value);
                if ($value === '') {
                    $value = null;
                }
            }
            return $row;
        });

        $rowCount = $data->count();
        // Prepare the data for mass insertion
        $insertData = [];
        foreach ($data as &$data_record) {
            $insertData[] = [
                'ji_INUMBR' => $data_record["inumbr"],
                'ji_IMFGNO' => $data_record["ivndpn"],
                // If needed, add more columns and their corresponding values here
            ];
        }

        // Use the upsert method with the ignore option to achieve upsert-or-ignore behavior
        foreach (array_chunk($insertData, 1000) as &$data) {
            DB::table('jda_invmst')->upsert($data, ['ji_INUMBR']);
        }


        return response()->json([
            'count' => $rowCount,
            'data' => $data,
        ], 200);
    }
}
