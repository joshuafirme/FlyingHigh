<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\HubController;
use App\Http\Controllers\HubInventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\AdjustmentRemarksController;
use App\Http\Controllers\ReturnReasonController;
use App\Http\Controllers\Reports\StockAdjustmentController;
use App\Http\Controllers\Reports\HubTransferController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('/users', UserController::class);
    Route::post('/user/update/{id}', [UserController::class, 'update']);
    Route::get('/user/search', [UserController::class, 'search'])->name('searchUser');

    Route::resource('/role', RoleController::class);
    Route::post('/role/update/{id}', [RoleController::class, 'update']);

    Route::get('/hubs/{hub_id}/search', [HubInventoryController::class, 'searchProduct'])->name('searchProductHub');
    Route::get('/hubs/{hub_id}', [HubInventoryController::class, 'hubInventory']);
    Route::get('/hub/bundle-qty-list/{sku}/{hub_id}', [HubInventoryController::class, 'getBundleQtyList']);

    Route::post('/hub/update/{id}', [HubController::class, 'update']);
    Route::get('/hub/search', [HubController::class, 'search'])->name('searchHub');
    Route::resource('/hub', HubController::class);

    Route::post('/return-reason/delete/{id}', [ReturnReasonController::class, 'delete']);
    Route::post('/return-reason/update/{id}', [ReturnReasonController::class, 'update']);
    Route::resource('return-reason', ReturnReasonController::class);

    Route::post('/adjustment-remarks/delete/{id}', [AdjustmentRemarksController::class, 'delete']);
    Route::post('/adjustment-remarks/update/{id}', [AdjustmentRemarksController::class, 'update']);
    Route::get('/adjustment-remarks/search', [AdjustmentRemarksController::class, 'search'])->name('searchRemarks');
    Route::resource('adjustment-remarks', AdjustmentRemarksController::class);

    Route::get('/pickup/{status}/search', [PickupController::class, 'search'])->name('searchPickup');
    Route::post('/pickup/tag-as-overdue/{shipmentId}', [PickupController::class, 'tagAsOverdue']);
    Route::post('/pickup/tag-as-picked-up/{shipmentId}', [PickupController::class, 'tagAsPickedUp']);
    Route::post('/pickup/return/{shipmentId}', [PickupController::class, 'tagAsReturned']);
    Route::get('/pickup/{status}', [PickupController::class, 'index']);
    Route::get('/get-line-items/{orderId}', [PickupController::class, 'getLineItems']);

    //Route::get('/pickedup-list', [PickupController::class, 'pickedUpList']);
    
    Route::post('/client/update/{id}', [ClientController::class, 'update']);
    Route::get('/client/search', [ClientController::class, 'search'])->name('searchClient');
    Route::resource('/client', ClientController::class);

    Route::get('/product/hubs/{sku}', [ProductController::class, 'getHubsStockBySku']);
    Route::get('/product/api/import', [ProductController::class, 'importAPI'])->name('importAPI');
    Route::post('/product-import', [ProductController::class, 'importProduct'])->name('importProduct');
    Route::post('/product/adjust', [ProductController::class, 'adjustStock']);
    Route::post('/product/transfer', [ProductController::class, 'transfer']);
    Route::post('/product/bulk-transfer', [ProductController::class, 'bulkTransfer']);
    Route::post('/product/update/{id}', [ProductController::class, 'update']);
    Route::get('/product/search', [ProductController::class, 'search'])->name('searchProduct');
    Route::resource('/product', ProductController::class);

    // Reports
    Route::get('/reports/hub-transfer', [HubTransferController::class, 'index']);
    Route::get('/reports/hub-transfer/filter', [HubTransferController::class, 'filterHubTransfer'])->name('filterHubTransfer');
    Route::get('/reports/hub-transfer/preview/{date_from}/{date_to}', [HubTransferController::class, 'previewReport']);
    Route::get('/reports/hub-transfer/download/{date_from}/{date_to}', [HubTransferController::class, 'downloadReport']);
    Route::get('/reports/hub-transfer/export/{date_from}/{date_to}', [HubTransferController::class, 'exportReport']);

    Route::get('/reports/stock-adjustment', [StockAdjustmentController::class, 'index']);
    Route::get('/reports/stock-adjustment/filter', [StockAdjustmentController::class, 'filterStockAdjustment'])->name('filterStockAdjustment');
    Route::get('/reports/stock-adjustment/preview/{date_from}/{date_to}', [StockAdjustmentController::class, 'previewReport']);
    Route::get('/reports/stock-adjustment/download/{date_from}/{date_to}', [StockAdjustmentController::class, 'downloadReport']);
    Route::get('/reports/stock-adjustment/export/{date_from}/{date_to}', [StockAdjustmentController::class, 'exportReport']);

    Route::post('increment-stock', [ProductController::class, 'incrementStock']);
});


Route::get('/fetch-pickup', [PickupController::class, 'fetchPickupData']);


Auth::routes(['register' => false]);
