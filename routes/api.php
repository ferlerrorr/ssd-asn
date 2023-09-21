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
Route::post('/ssd/asn/export', 'App\Http\Controllers\AsnFileController@export');



//?JDA Routes
Route::get('/ssd/asn/jda/po', 'App\Http\Controllers\JdaController@Po');
Route::get('/ssd/asn/jda/sku', 'App\Http\Controllers\JdaController@Sku');


//?ASN Errlogs Routes
Route::get('/ssd/asn/jda/errlogs/{datetime}/{vendor}', 'App\Http\Controllers\ErrLogsController@geterrlogs');
Route::get('/ssd/asn/jda/loaderrlogs', 'App\Http\Controllers\ErrLogsController@loadErrlogs');
Route::get('/ssd/asn/jda/searcherrlogs/{date}', 'App\Http\Controllers\ErrLogsController@searchErrlogs');


//?Vendor Maintenance Routes
//* Vendor Setup 
Route::get('/ssd/asn/vendorid-setup', 'App\Http\Controllers\VendorMaintenanceController@vendorsSetup');
Route::get('/ssd/asn/vendorid-setup-delete/{vendor_id}', 'App\Http\Controllers\VendorMaintenanceController@vendorsSetupDelete');
Route::put('/ssd/asn/vendorid-setup-update/{vendor_id}', 'App\Http\Controllers\VendorMaintenanceController@vendorsSetupUpdate');
Route::post('/ssd/asn/vendorid-setup-create', 'App\Http\Controllers\VendorMaintenanceController@vendorsSetupCreate');
//* Vendor Setup 
//* Vendor Headers
Route::get('/ssd/asn/vendorhead-setup', 'App\Http\Controllers\VendorMaintenanceController@vendorsHeader');
Route::put('/ssd/asn/vendorhead-setup-update/{vendor_id}', 'App\Http\Controllers\VendorMaintenanceController@headersSetupUpdate');
Route::post('/ssd/asn/vendorhead-setup-create', 'App\Http\Controllers\VendorMaintenanceController@headersSetupCreate');
Route::get('/ssd/asn/vendorhead-setup-delete/{vendor_id}', 'App\Http\Controllers\VendorMaintenanceController@headersSetupDelete');
//* Vendor Headers
//* Vendor Details
Route::get('/ssd/asn/vendordetail-setup', 'App\Http\Controllers\VendorMaintenanceController@vendorsDetails');
Route::put('/ssd/asn/vendordetail-setup-update/{vendor_id}', 'App\Http\Controllers\VendorMaintenanceController@detailsSetupUpdate');
Route::post('/ssd/asn/vendordetail-setup-create', 'App\Http\Controllers\VendorMaintenanceController@detailsSetupCreate');
Route::get('/ssd/asn/vendordetail-setup-delete/{vendor_id}', 'App\Http\Controllers\VendorMaintenanceController@detailsSetupDelete');
//* Vendor Details
//*Vendor Lots
Route::get('/ssd/asn/vendorlots-setup', 'App\Http\Controllers\VendorMaintenanceController@vendorsLots');
Route::post('/ssd/asn/vendorlots-setup-create', 'App\Http\Controllers\VendorMaintenanceController@lotsSetupCreate');
Route::put('/ssd/asn/vendorlots-setup-update/{vendor_id}', 'App\Http\Controllers\VendorMaintenanceController@lotsSetupUpdate');
