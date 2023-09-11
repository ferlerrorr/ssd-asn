<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorMaintenanceController extends Controller
{
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
                "edit-link" => "http://localhost:8800/api/ssd/asn/jda/vsetupedit/$vendor->v_vid",
                "delete-link" => "http://localhost:8800/api/ssd/asn/jda/vsetupdelete/$vendor->v_vid"
            ];
        }

        return response()->json($filteredData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
