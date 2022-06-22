<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\HubController;
use App\Http\Controllers\HubInventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductLotCodesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShipmentsController;
use App\Http\Controllers\AdjustmentRemarksController;
use App\Http\Controllers\ReturnReasonController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\Reports\StockAdjustmentController;
use App\Http\Controllers\Reports\HubTransferController;
use App\Http\Controllers\Reports\PickupReportController;
use App\Http\Controllers\Reports\InboundTransferController;
use App\Http\Controllers\Reports\ExpiredController;
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
    Route::get('/hubs/{receiver}', [HubInventoryController::class, 'hubInventory']);
    Route::get('/hubs/{receiver}/pickup/{shipmentId}', [HubInventoryController::class, 'pickup']);
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

    Route::get('/orders/{status}/search', [OrderController::class, 'search'])->name('searchPickup');
    Route::post('/orders/tag-as-overdue/{shipmentId}', [OrderController::class, 'tagAsOverdue']);
    Route::post('/orders/tag-as-picked-up/{shipmentId}', [OrderController::class, 'tagAsPickedUp']);
    Route::post('/orders/tag-one-as-picked-up', [OrderController::class, 'tagOneAsPickedUp']);
    Route::post('/orders/return', [OrderController::class, 'tagAsReturned']);
    Route::post('/orders/change-status/{shipmentId}/{status}', [OrderController::class, 'changeStatus']);
    Route::get('/orders/returned', [OrderController::class, 'getReturnedList']);
    Route::get('/get-line-items/{orderId}', [OrderController::class, 'getLineItems']);
    
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/search', [OrderController::class, 'search']);

    Route::post('/order/do-ship', [OrderController::class, 'doShip']);

    Route::get('/shipments', [ShipmentsController::class, 'index']);
    Route::get('/shipments/search', [ShipmentsController::class, 'search']);
    Route::get('/shipment/line-items/{shipmentId}', [ShipmentsController::class, 'getLineItems']);
    Route::post('/shipment/change-status/{shipmentId}/{status}', [ShipmentsController::class, 'changeStatus']);
    Route::post('/shipment/do-ship/{shipmentId}', [ShipmentsController::class, 'doShip']);
    Route::post('/shipment/do-delivered/{shipmentId}', [ShipmentsController::class, 'doDelivered']);
    Route::post('/shipment/do-pickup', [ShipmentsController::class, 'doPickup']);

    //Route::get('/pickedup-list', [OrderController::class, 'pickedUpList']);
    
    Route::post('/client/update/{id}', [ClientController::class, 'update']);
    Route::get('/client/search', [ClientController::class, 'search'])->name('searchClient');
    Route::resource('/client', ClientController::class);

    
    Route::post('/product/lotcode/archive/{id}', [ProductController::class, 'archiveLotCode']);
    Route::get('/product/export', [ProductController::class, 'export']);
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
    Route::get('/reports/hub-transfer/preview/{date_from}/{date_to}/{hub_id}', [HubTransferController::class, 'previewReport']);
    Route::get('/reports/hub-transfer/download/{date_from}/{date_to}/{hub_id}', [HubTransferController::class, 'downloadReport']);
    Route::get('/reports/hub-transfer/export/{date_from}/{date_to}/{hub_id}', [HubTransferController::class, 'exportReport']);

    Route::get('/reports/stock-adjustment', [StockAdjustmentController::class, 'index']);
    Route::get('/reports/stock-adjustment/filter', [StockAdjustmentController::class, 'filterStockAdjustment'])->name('filterStockAdjustment');
    Route::get('/reports/stock-adjustment/preview/{date_from}/{date_to}/{remarks_id}', [StockAdjustmentController::class, 'previewReport']);
    Route::get('/reports/stock-adjustment/download/{date_from}/{date_to}/{remarks_id}', [StockAdjustmentController::class, 'downloadReport']);
    Route::get('/reports/stock-adjustment/export/{date_from}/{date_to}/{remarks_id}', [StockAdjustmentController::class, 'exportReport']);

    Route::get('/reports/pickup/filter', [PickupReportController::class, 'filterPickup'])->name('filterPickup');
    Route::get('/reports/pickup/{status}', [PickupReportController::class, 'index']);
    Route::get('/reports/pickup/preview/{date_from}/{date_to}/{status}', [PickupReportController::class, 'previewReport']);
    Route::get('/reports/pickup/download/{date_from}/{date_to}/{status}', [PickupReportController::class, 'downloadReport']);
    Route::get('/reports/pickup/export/{date_from}/{date_to}/{status}', [PickupReportController::class, 'exportReport']);

    Route::get('/reports/inbound-transfer/filter', [InboundTransferController::class, 'filter']);
    Route::get('/reports/inbound-transfer/preview/{date_from}/{date_to}', [InboundTransferController::class, 'previewReport']);
    Route::get('/reports/inbound-transfer/download/{date_from}/{date_to}', [InboundTransferController::class, 'downloadReport']);
    Route::get('/reports/inbound-transfer/export/{date_from}/{date_to}', [InboundTransferController::class, 'exportReport']);
    Route::get('/reports/inbound-transfer', [InboundTransferController::class, 'index']);

    Route::get('/reports/expired', [ExpiredController::class, 'index']);
    Route::get('/reports/expired/filter', [ExpiredController::class, 'filter']);
    Route::get('/reports/expired/preview/{date_from}/{date_to}', [ExpiredController::class, 'previewReport']);
    Route::get('/reports/expired/download/{date_from}/{date_to}', [ExpiredController::class, 'downloadReport']);
    Route::get('/reports/expired/export/{date_from}/{date_to}', [ExpiredController::class, 'exportReport']);

    Route::get('/product-lot-codes', [ProductLotCodesController::class, 'index']);
    Route::get('/product-lot-codes/preview', [ProductLotCodesController::class, 'previewReport']);
    Route::get('/product-lot-codes/download', [ProductLotCodesController::class, 'downloadReport']);
    Route::get('/product-lot-codes/export', [ProductLotCodesController::class, 'exportReport']);

    Route::get('/stock-transfer', [StockTransferController::class, 'index']);
    Route::get('/stock-transfer/asn/{orderNumber}', [StockTransferController::class, 'readOneOrder']);
    Route::get('/stock-transfer/search', [StockTransferController::class, 'search']);
    Route::get('/stock-transfer/filter', [StockTransferController::class, 'filter']);
    Route::post('/stock-transfer/transfer', [StockTransferController::class, 'transfer']);
    Route::post('/stock-transfer/transfer/order/{orderNumber}', [StockTransferController::class, 'transferByOrderNo']);
    Route::post('/stock-transfer/import', [StockTransferController::class, 'import']);
    Route::get('/stock-transfer/preview/{date_from}/{date_to}', [StockTransferController::class, 'previewReport']);
    Route::get('/stock-transfer/download/{date_from}/{date_to}', [StockTransferController::class, 'downloadReport']);
    Route::get('/stock-transfer/export/{date_from}/{date_to}', [StockTransferController::class, 'export']);

    Route::post('increment-stock', [ProductController::class, 'incrementStock']);
});

Route::get('/orders/generate-collection-receipt/{shipmentId}/{orderId}', [OrderController::class, 'generateCollectionReceipt']);
Route::get('/orders/generate-delivery-receipt/{shipmentId}/{orderId}', [OrderController::class, 'generateDeliveryReceipt']);
Route::get('/order/generate/{shipmentId}/{orderId}', [OrderController::class, 'generateSalesInvoice']);

Route::get('/fetch-orders', [OrderController::class, 'fetchOrdersData']);
Route::get('/fetch-shipments', [ShipmentsController::class, 'fetchShipments']);


Auth::routes(['register' => false]);
