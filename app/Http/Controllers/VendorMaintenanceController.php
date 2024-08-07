<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorMaintenanceController extends Controller
{

    //!VID

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorsSetup()
    {

        $vendors = DB::table('vdr_id_setup')
            ->orderBy('v_vname')
            ->get(['v_vname', 'v_vid']);

        $filteredData = [];

        foreach ($vendors as $vendor) {
            $filteredData[] = [
                "v_vname" => $vendor->v_vname,
                "v_vid" => $vendor->v_vid,
                "edit-link" => "http://10.91.100.145:8800/api/ssd/asn/jda/vsetupedit/$vendor->v_vid",
                "delete-link" => "http://10.91.100.145:8800/api/ssd/asn/jda/vsetupdelete/$vendor->v_vid"
            ];
        }

        return response()->json($filteredData);
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $vendor_id
     * @return \Illuminate\Http\Response
     */
    public function vendorsSetupDelete($vendor_id)
    {

        // Use the DB facade to delete the record
        DB::table('vdr_id_setup')->where('v_vid', $vendor_id)->delete();

        $response = ["Vendor setup has been deleted"];

        return response()->json($response);
    }


    public function vendorsSetupCreate(Request $request)
    {
        // Validate the incoming request data
        $this->validate($request, [
            'v_vname' => 'required|string|max:255',
            'v_vid' => 'required|numeric',
        ]);

        // Create a new vendor setup record using query builder
        DB::table('vdr_id_setup')->insert([
            'v_vname' => $request->input('v_vname'),
            'v_vid' => $request->input('v_vid'),
        ]);



        // Prepare the response
        $res = [
            'msg' => 'New Vendor Setup has been created',
        ];

        return response()->json($res, 201);
    }



    /**
     * Display a listing of the resource.
     *
     * @param  int  $vendor_id
     * @return \Illuminate\Http\Response
     */
    public function vendorsSetupUpdate($vendor_id, Request $request)
    {

        // Use the DB facade to delete the record
        $data = DB::table('vdr_id_setup')->where('v_vid', $vendor_id);


        $data->update([

            'v_vname' =>  $request->v_vname,
            'v_vid' =>  $request->v_vid,

        ]);

        $res = [

            'msg' => ' Vendor Setup has been updated',

        ];


        return response()->json($res, 200);
    }



    //!VID
    //!Header
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorsHeader()
    {

        $vendors = DB::table('h_column_setup')
            ->orderBy('H_Vendor')
            ->get();

        $filteredData = [];

        foreach ($vendors as $vendor) {
            $filteredData[] = [
                "H_vendor" => $vendor->H_vendor,
                "H_vid" => $vendor->H_vid,
                "H_file_type" => $vendor->H_file_type,
                "H_InvNo" => $vendor->H_InvNo,
                "H_InvDate" => $vendor->H_InvDate,
                "H_InvAmt" => $vendor->H_InvAmt,
                "H_DiscAmt" => $vendor->H_DiscAmt,
                "H_StkFlag" => $vendor->H_StkFlag,
                "H_VendorID" => $vendor->H_VendorID,
                "H_VendorName" => $vendor->H_VendorName,
                "H_PORef" => $vendor->H_PORef,
                "H_SupCode" => $vendor->H_SupCode,




                // "edit-link" => "http://localhost:8800/api/ssd/asn/jda/vsetupedit/$vendor->v_vid",
                // "delete-link" => "http://localhost:8800/api/ssd/asn/jda/vsetupdelete/$vendor->v_vid"
            ];
        }

        return response()->json($filteredData);
    }
    /**
     * Display a listing of the resource.
     *
     * @param  int  $vendor_id
     * @return \Illuminate\Http\Response
     */
    public function headersSetupUpdate($vendor_id, Request $request)
    {

        $data = DB::table('h_column_setup')->where('H_vid', $vendor_id);

        $data->update([
            "H_vendor" => $request->H_vendor,
            "H_vid" => $vendor_id,
            "H_file_type" => $request->H_file_type,
            "H_InvNo" => $request->H_InvNo,
            "H_InvDate" => $request->H_InvDate,
            "H_InvAmt" => $request->H_InvAmt,
            "H_DiscAmt" => $request->H_DiscAmt,
            "H_StkFlag" => $request->H_StkFlag,
            "H_VendorID" => $request->H_VendorID,
            "H_VendorName" => $request->H_VendorName,
            "H_PORef" => $request->H_PORef,
            "H_SupCode" => $request->H_SupCode,

        ]);

        $res = [

            'msg' => ' Header Setup has been updated',

        ];


        return response()->json($res, 200);
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $vendor_id
     * @return \Illuminate\Http\Response
     */
    public function headersSetupCreate(Request $request)
    {


        $v_name = $request->H_vendor;

        $v_id = DB::table('vdr_id_setup')->where('v_vname', $v_name)->get('v_vid');

        $data = [
            "H_vendor" => $request->H_vendor,
            "H_vid" =>  $v_id[0]->v_vid,
            "H_file_type" => $request->H_file_type,
            "H_InvNo" => $request->H_InvNo,
            "H_InvDate" => $request->H_InvDate,
            "H_InvAmt" => $request->H_InvAmt,
            "H_DiscAmt" => $request->H_DiscAmt,
            "H_StkFlag" => $request->H_StkFlag,
            "H_VendorID" => $request->H_VendorID,
            "H_VendorName" => $request->H_VendorName,
            "H_PORef" => $request->H_PORef,
            "H_SupCode" => $request->H_SupCode,
        ];

        DB::table('h_column_setup')->insert($data);

        $res = [
            'msg' => ' Header Setup has been created',
        ];

        return response()->json($res, 200);
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $vendor_id
     * @return \Illuminate\Http\Response
     */
    public function headersSetupDelete($vendor_id)
    {

        // Use the DB facade to delete the record
        DB::table('h_column_setup')->where('H_vid', $vendor_id)->delete();

        $response = ["Header setup has been deleted"];

        return response()->json($response);
    }

    //! Header    
    //! Details

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorsDetails()
    {

        $vendors = DB::table('d_column_setup')
            ->orderBy('D_vendor')
            ->get();

        $filteredData = [];

        foreach ($vendors as $vendor) {
            $filteredData[] = [
                "D_vendor" => $vendor->D_vendor,
                "D_file_type" => $vendor->D_file_type,
                "D_Prefix" => $vendor->D_Prefix,
                "D_vid" => $vendor->D_vid,
                "D_InvNo" => $vendor->D_InvNo,
                "D_ItemCode" => $vendor->D_ItemCode,
                "D_ItemName" => $vendor->D_ItemName,
                "D_ConvFact2" => $vendor->D_ConvFact2,
                "D_UOM" => $vendor->D_UOM,
                "D_UnitCost" => $vendor->D_UnitCost,
                "D_QtyShip" => $vendor->D_QtyShip,
                "D_QtyFree" => $vendor->D_QtyFree,
                "D_GrossAmt" => $vendor->D_GrossAmt,
                "D_PldAmt" => $vendor->D_PldAmt,
                "D_NetAmt" => $vendor->D_NetAmt,
                "D_SupCode" => $vendor->D_SupCode,
                // "edit-link" => "http://localhost:8800/api/ssd/asn/jda/vsetupedit/$vendor->v_vid",
                // "delete-link" => "http://localhost:8800/api/ssd/asn/jda/vsetupdelete/$vendor->v_vid"
            ];
        }

        return response()->json($filteredData);
    }



    /**
     * Display a listing of the resource.
     *
     * @param  int  $vendor_id
     * @return \Illuminate\Http\Response
     */
    public function detailsSetupUpdate($vendor_id, Request $request)
    {

        $data = DB::table('d_column_setup')->where('D_vid', $vendor_id);

        $data->update([
            "D_vendor" => $request->D_vendor,
            "D_file_type" => $request->D_file_type,
            "D_Prefix" => $request->D_Prefix,
            "D_vid" => $vendor_id,
            "D_InvNo" => $request->D_InvNo,
            "D_ItemCode" => $request->D_ItemCode,
            "D_ItemName" => $request->D_ItemName,
            "D_ConvFact2" => $request->D_ConvFact2,
            "D_UOM" => $request->D_UOM,
            "D_UnitCost" => $request->D_UnitCost,
            "D_QtyShip" => $request->D_QtyShip,
            "D_QtyFree" => $request->D_QtyFree,
            "D_GrossAmt" => $request->D_GrossAmt,
            "D_PldAmt" => $request->D_PldAmt,
            "D_NetAmt" => $request->D_NetAmt,
            "D_SupCode" => $request->D_SupCode,
        ]);

        $res = [

            'msg' => ' Details Setup has been updated',

        ];


        return response()->json($res, 200);
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $vendor_id
     * @return \Illuminate\Http\Response
     */
    public function detailsSetupCreate(Request $request)
    {
        $v_name = $request->D_vendor;

        $v_id = DB::table('vdr_id_setup')->where('v_vname', $v_name)->get('v_vid');

        $data = [
            "D_vendor" => $request->D_vendor,
            "D_file_type" => $request->D_file_type,
            "D_Prefix" => $request->D_Prefix,
            "D_vid" => $v_id[0]->v_vid,
            "D_InvNo" => $request->D_InvNo,
            "D_ItemCode" => $request->D_ItemCode,
            "D_ItemName" => $request->D_ItemName,
            "D_ConvFact2" => $request->D_ConvFact2,
            "D_UOM" => $request->D_UOM,
            "D_UnitCost" => $request->D_UnitCost,
            "D_QtyShip" => $request->D_QtyShip,
            "D_QtyFree" => $request->D_QtyFree,
            "D_GrossAmt" => $request->D_GrossAmt,
            "D_PldAmt" => $request->D_PldAmt,
            "D_NetAmt" => $request->D_NetAmt,
            "D_SupCode" => $request->D_SupCode,
        ];

        DB::table('d_column_setup')->insert($data);

        $res = [
            'msg' => ' details Setup has been created',
        ];

        return response()->json($res, 200);
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $vendor_id
     * @return \Illuminate\Http\Response
     */
    public function detailsSetupDelete($vendor_id)
    {

        // Use the DB facade to delete the record
        DB::table('d_column_setup')->where('D_vid', $vendor_id)->delete();

        $response = ["Details setup has been deleted"];

        return response()->json($response);
    }

    //!Details
    //!Lots

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorsLots()
    {

        $vendors = DB::table('l_column_setup')
            ->orderBy('L_vendor')
            ->get();

        $filteredData = [];

        foreach ($vendors as $vendor) {
            $filteredData[] = [
                "L_vendor" => $vendor->L_vendor,
                "L_file_type" => $vendor->L_file_type,
                "L_vid" => $vendor->L_vid,
                "L_InvNo" => $vendor->L_InvNo,
                "L_ItemCode" => $vendor->L_ItemCode,
                "L_LotNo" => $vendor->L_LotNo,
                "L_ExpiryMM" => $vendor->L_ExpiryMM,
                "L_ExpiryDD" => $vendor->L_ExpiryDD,
                "L_ExpiryYYYY" => $vendor->L_ExpiryYYYY,
                "L_Expiry" => $vendor->L_Expiry,
                "L_Qty" => $vendor->L_Qty,
                "L_SupCode" => $vendor->L_SupCode,
                // "edit-link" => "http://localhost:8800/api/ssd/asn/jda/vsetupedit/$vendor->v_vid",
                // "delete-link" => "http://localhost:8800/api/ssd/asn/jda/vsetupdelete/$vendor->v_vid"
            ];
        }

        return response()->json($filteredData);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $vendor_id
     * @return \Illuminate\Http\Response
     */
    public function lotsSetupCreate(Request $request)
    {
        $v_name = $request->L_vendor;

        $v_id = DB::table('vdr_id_setup')->where('v_vname', $v_name)->get('v_vid');

        $data = [
            "L_vendor" => $request->L_vendor,
            "L_file_type" => $request->L_file_type,
            "L_InvNo" => $request->L_InvNo,
            "L_vid" => $v_id[0]->v_vid,
            "L_ItemCode" => $request->L_ItemCode,
            "L_LotNo" => $request->L_LotNo,
            "L_ExpiryMM" => $request->L_ExpiryMM,
            "L_ExpiryDD" => $request->L_ExpiryDD,
            "L_ExpiryYYYY" => $request->L_ExpiryYYYY,
            "L_Qty" => $request->L_Qty,
            "L_SupCode" => $request->L_SupCode,
        ];

        DB::table('l_column_setup')->insert($data);

        $res = [
            'msg' => 'Lots Setup has been created',
        ];

        return response()->json($res, 200);
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $vendor_id
     * @return \Illuminate\Http\Response
     */
    public function lotsSetupUpdate($vendor_id, Request $request)
    {

        $data = DB::table('l_column_setup')->where('L_vid', $vendor_id);

        $data->update([
            "L_vendor" => $request->L_vendor,
            "L_file_type" => $request->L_file_type,
            "L_InvNo" => $request->L_InvNo,
            "L_vid" => $vendor_id,
            "L_ItemCode" => $request->L_ItemCode,
            "L_LotNo" => $request->L_LotNo,
            "L_ExpiryMM" => $request->L_ExpiryMM,
            "L_ExpiryDD" => $request->L_ExpiryDD,
            "L_ExpiryYYYY" => $request->L_ExpiryYYYY,
            "L_Qty" => $request->L_Qty,
            "L_SupCode" => $request->L_SupCode,
        ]);

        $res = [

            'msg' => 'Lots Setup has been updated',

        ];


        return response()->json($res, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $vendor_id
     * @return \Illuminate\Http\Response
     */
    public function lotsSetupDelete($vendor_id)
    {
        // Use the DB facade to delete the record
        DB::table('l_column_setup')->where('L_vid', $vendor_id)->delete();

        $response = ["Lots setup has been deleted"];

        return response()->json($response);
    }

    //!Lots
}
