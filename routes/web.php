<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\HubController;
use App\Http\Controllers\PickUpLocationController;
use App\Http\Controllers\HubInventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShipmentsController;
use App\Http\Controllers\AdjustmentRemarksController;
use App\Http\Controllers\ReturnReasonController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\HubTransferController;
use App\Http\Controllers\Reports\StockAdjustmentController;
use App\Http\Controllers\Reports\HubTransferReportController;
use App\Http\Controllers\Reports\PickupReportController;
use App\Http\Controllers\Reports\InboundTransferController;
use App\Http\Controllers\Reports\ExpiredController;
use App\Http\Controllers\Reports\NearExpiryController;
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

    
    Route::resource('/attributes', AttributeController::class);
    Route::post('/attributes/update/{id}', [AttributeController::class, 'update']);
    Route::post('/attributes/delete/{id}', [AttributeController::class, 'delete']);

    Route::post('/hub/update/{id}', [HubController::class, 'update']);
    Route::get('/hub/search', [HubController::class, 'search'])->name('searchHub');
    Route::resource('/hub', HubController::class);

    Route::post('/pickup-location/update/{id}', [PickUpLocationController::class, 'update']);
    Route::get('/pickup-location/search', [PickUpLocationController::class, 'search']);
    Route::resource('/pickup-location', PickUpLocationController::class);
    

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
    
    Route::get('/orders/{branch_id}', [OrderController::class, 'index']);
    Route::get('/orders/filter/{branch_id}', [OrderController::class, 'filterPaginate']);
    Route::get('/orders/search/{branch_id}', [OrderController::class, 'search']);
    Route::get('/order/pickup/{branch_id}/{shipmentId}', [OrderController::class, 'pickup']);

    Route::post('/order/do-ship', [OrderController::class, 'doShip']);
    Route::post('/order/cancel/{shipmentId}', [OrderController::class, 'cancelOrder']);
    Route::get('/order/{shipmentId}', [OrderController::class, 'getOneOrder']);
    

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

    Route::get('/hub-transfer/list', [HubTransferController::class, 'getTransferList']);
    Route::post('/hub-transfer/{id}', [HubTransferController::class, 'remove']);
    Route::get('/hub-transfer/search', [HubTransferController::class, 'search']);
    Route::post('/do-transfer', [HubTransferController::class, 'transfer']);
    Route::resource('/hub-transfer', HubTransferController::class);

    Route::post('/product/lotcode/archive/{id}', [ProductController::class, 'archiveLotCode']);
    Route::get('/product/export', [ProductController::class, 'export']);
    Route::get('/product/hubs/{sku}', [ProductController::class, 'getHubsStockBySku']);
    Route::get('/product/api/import', [ProductController::class, 'importAPI'])->name('importAPI');
    Route::post('/product-import', [ProductController::class, 'importProduct'])->name('importProduct');
    Route::post('/product/adjust', [ProductController::class, 'adjustStock']);
    Route::post('/product/transfer', [ProductController::class, 'transfer']);
    Route::post('/product/bulk-transfer', [ProductController::class, 'bulkTransfer']);
    Route::post('/product/update/{id}', [ProductController::class, 'update']);
    Route::post('/product/delete/{id}', [ProductController::class, 'delete']);
    Route::get('/product/search', [ProductController::class, 'search'])->name('searchProduct');
    Route::resource('/product', ProductController::class);

    // Reports
    Route::get('/reports/hub-transfer', [HubTransferReportController::class, 'index']);
    Route::get('/reports/hub-transfer/filter', [HubTransferReportController::class, 'filterHubTransfer'])->name('filterHubTransfer');
    Route::get('/reports/hub-transfer/preview/{date_from}/{date_to}/{hub_id}', [HubTransferReportController::class, 'previewReport']);
    Route::get('/reports/hub-transfer/download/{date_from}/{date_to}/{hub_id}', [HubTransferReportController::class, 'downloadReport']);
    Route::get('/reports/hub-transfer/export/{date_from}/{date_to}/{hub_id}', [HubTransferReportController::class, 'exportReport']);

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
    Route::get('/reports/expired', [ExpiredController::class, 'index']);

    Route::get('/reports/near-expiry', [NearExpiryController::class, 'index']);
    Route::get('/reports/near-expiry/preview', [NearExpiryController::class, 'previewReport']);
    Route::get('/reports/near-expiry/download', [NearExpiryController::class, 'downloadReport']);
    Route::get('/reports/near-expiry/export', [NearExpiryController::class, 'exportReport']);

    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::get('/inventory/search', [InventoryController::class, 'search']);
    Route::post('/inventory/update-expiration/{id}', [InventoryController::class, 'updateExpiration']);
    Route::get('/inventory/preview', [InventoryController::class, 'previewReport']);
    Route::get('/inventory/download', [InventoryController::class, 'downloadReport']);
    Route::get('/inventory/export', [InventoryController::class, 'exportReport']);

    Route::get('/stock-transfer', [StockTransferController::class, 'index']);
    Route::get('/stock-transfer/asn/{orderNumber}', [StockTransferController::class, 'readOneOrder']);
    Route::get('/stock-transfer/po-list/{transactionRef}', [StockTransferController::class, 'getPOListByTransaction']);
    Route::get('/stock-transfer/search', [StockTransferController::class, 'search']);
    Route::get('/stock-transfer/filter', [StockTransferController::class, 'filter']);
    Route::post('/stock-transfer/transfer', [StockTransferController::class, 'transfer']);
    Route::post('/stock-transfer/transfer/{orderNumber}/{receiptDate}', [StockTransferController::class, 'transferByOrderNo']);
    Route::post('/stock-transfer/import', [StockTransferController::class, 'import']);
    Route::get('/stock-transfer/preview/{date_from}/{date_to}', [StockTransferController::class, 'previewReport']);
    Route::get('/stock-transfer/download/{date_from}/{date_to}', [StockTransferController::class, 'downloadReport']);
    Route::get('/stock-transfer/export/{date_from}/{date_to}', [StockTransferController::class, 'export']);
    
    Route::get('/transactions', [TransactionController::class, 'index']);

    Route::post('increment-stock', [ProductController::class, 'incrementStock']);
});

Route::get('/orders/generate-collection-receipt/{shipmentId}/{orderId}', [OrderController::class, 'generateCollectionReceipt']);
Route::get('/orders/generate-delivery-receipt/{shipmentId}/{orderId}', [OrderController::class, 'generateDeliveryReceipt']);
Route::get('/order/generate/{shipmentId}/{orderId}', [OrderController::class, 'generateSalesInvoice']);

Route::get('/fetch-orders', [OrderController::class, 'fetchOrdersData']);
Route::get('/fetch-shipments', [ShipmentsController::class, 'fetchShipments']);


Auth::routes(['register' => false]);
