<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ProductApi;
use App\Http\Controllers\Api\LotCodeApi;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('transaction/{transNum}', [TransactionController::class, 'transaction']);
Route::get('get-all-sku', [ProductApi::class, 'getAllSKU']);
Route::get('product/sku/{sku}', [ProductApi::class, 'getBySKU']);
Route::get('product/barcode/{barcode}', [ProductApi::class, 'getByBarcode']);
Route::get('product/bundle-qty-list/{sku}', [ProductApi::class, 'getBundleQtyList']);

Route::get('lotcode/{sku}', [LotCodeApi::class, 'getLotCode']);