<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/ssd/asn', 'App\Http\Controllers\TestController@index');


// Route::get('/ssd/po-number', 'App\Http\Controllers\TestController@Po');
// Route::get('/ssd/sku-number', 'App\Http\Controllers\TestController@Sku');
// Route::get('/ssd/asn/headers', 'App\Http\Controllers\AsnFileController@headers');

//?ASN Routes
Route::get('/ssd/asn/vendors', 'App\Http\Controllers\AsnFileController@vendors');
Route::post('/ssd/asn/upload/{vid}', 'App\Http\Controllers\AsnFileController@store');
Route::get('/ssd/asn/export', 'App\Http\Controllers\AsnFileController@export');



//?JDA Routes
Route::get('/ssd/asn/jda/po', 'App\Http\Controllers\JdaController@Po');
Route::get('/ssd/asn/jda/sku', 'App\Http\Controllers\JdaController@Sku');


//?ASN Errlogs Routes
Route::get('/ssd/asn/jda/errlogs/{datetime}/{vendor}', 'App\Http\Controllers\ErrLogsController@geterrlogs');
Route::get('/ssd/asn/jda/loaderrlogs', 'App\Http\Controllers\ErrLogsController@loadErrlogs');
Route::get('/ssd/asn/jda/searcherrlogs/{date}', 'App\Http\Controllers\ErrLogsController@searchErrlogs');


//?Vendor Maintenance Routes
Route::get('/ssd/asn/jda/vendor-setup', 'App\Http\Controllers\VendorMaintenanceController@vendorsSetup');
