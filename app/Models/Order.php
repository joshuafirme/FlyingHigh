<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        "shipmentId",
        "customerEmail",
        "custId",
        "custName",
        "shipPhone",
        "shipName",
        "shipAddr1",
        "shipAddr2",
        "shipAddr3",
        "shipAddr4",
        "shipCity",
        "shipState",
        "shipZip",
        "shipCountryIso",
        "shipMethod",
        "shipCarrier",
        "batchId",
        "contractDate",
        "orderId",
        "govInvoiceNumber",
        "dateTimeSubmittedIso",
        "shippingChargeAmount",
        "customerTIN",
        "salesTaxAmount",
        "shippingTaxTotalAmount",
        "packageTotal",
        "orderSource",
        "hub_id"
    ];

    public function getHeaders() {
        return ['Shipment Id', 'OrderID', 'BatchID', 'Customer Info', 'Date time submitted'];
    }

    public function getColumns() {
        return ['shipmentId', 'orderId', 'batchId', 'custName', 'dateTimeSubmittedIso'];
    }

    public function getOrderDetails($shipmentId) {
        return self::where('shipmentId', $shipmentId)->first();
    }

    public function getAllPaginate($per_page, $status) {
        return self::select('orders.*','orders.status','orders.updated_at','hubs.name as hub','return_reasons.reason')
            ->leftJoin('hubs', 'hubs.id', '=', 'orders.hub_id')
            ->leftJoin('return_reasons', 'return_reasons.id', '=', 'orders.return_reason')
            ->orderBy('orders.updated_at', 'desc')
            ->where('orders.status', $status)
            ->whereDate('orders.updated_at', date('Y-m-d'))
            ->paginate($per_page);
    }

    public function filterPaginate($per_page) {
        return self::select('orders.*','orders.status','orders.updated_at','hubs.name as hub','return_reasons.reason')
            ->leftJoin('hubs', 'hubs.id', '=', 'orders.hub_id')
            ->leftJoin('return_reasons', 'return_reasons.id', '=', 'orders.return_reason')
            ->orderBy('dateTimeSubmittedIso', 'desc')
            ->whereBetween(DB::raw('DATE(orders.dateTimeSubmittedIso)'), [request()->date_from, request()->date_to])
            ->paginate($per_page);
    }

    public function filter($date_from, $date_to, $status) {
        $date_from = $date_from ? $date_from : date('Y-m-d');
        $date_to = $date_to ? $date_to : date('Y-m-d');
        return self::select('orders.*','orders.status','orders.updated_at','hubs.name as hub','return_reasons.reason')
            ->leftJoin('hubs', 'hubs.id', '=', 'orders.hub_id')
            ->leftJoin('return_reasons', 'return_reasons.id', '=', 'orders.return_reason')
            ->orderBy('orders.updated_at', 'desc')
            ->where('orders.status', $status)
            ->whereBetween(DB::raw('DATE(orders.updated_at)'), [$date_from, $date_to])
            ->get();
    }

    public function getOrder($per_page) {
        return self::select('orders.*','orders.status','orders.updated_at','hubs.name as hub','return_reasons.reason')
            ->leftJoin('hubs', 'hubs.id', '=', 'orders.hub_id')
            ->leftJoin('return_reasons', 'return_reasons.id', '=', 'orders.return_reason')
            ->where(DB::raw('DATE(orders.dateTimeSubmittedIso)'), date('Y-m-d'))
            ->orderBy('dateTimeSubmittedIso', 'desc')
            ->paginate($per_page);
    }
    

    public function searchOrder($key, $per_page) {
        return self::select('orders.*','orders.status','orders.updated_at','hubs.name as hub')
            ->leftJoin('hubs', 'hubs.id', '=', 'orders.hub_id')
            ->where('shipmentId', 'LIKE', '%' . $key . '%')
            ->orWhere('orderId', 'LIKE', '%' . $key . '%')
            ->paginate($per_page);
    }

    public function isOrderExists($orderId) {
        $res = self::where('orderId', $orderId)->get();
        return count($res) > 0 ? true : false;
    }

    public function getOrderIdByShipmentId($shipmentId) {
        return self::where('shipmentId', $shipmentId)->value('orderId');
    }

    public function changeStatus($shipmentId, $status, $hub_id = 0) {
        self::where('shipmentId', $shipmentId)
        ->update([
            'hub_id' => $hub_id,
            'status' => $status,
        ]);
    }

}
