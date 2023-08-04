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


Route::get('/ssd/po-number', 'App\Http\Controllers\TestController@Po');
Route::get('/ssd/sku-number', 'App\Http\Controllers\TestController@Sku');


Route::post('/ssd/file-import/{slug}', 'App\Http\Controllers\FileImportController@index');

Route::get('/ssd/asn/vendors', 'App\Http\Controllers\AsnFileController@vendors');

Route::post('/ssd/asn/upload/{vid}', 'App\Http\Controllers\AsnFileController@store');



Route::get('/ssd/asn/headers', 'App\Http\Controllers\AsnFileController@headers');
