<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class JdaController extends Controller
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
}
