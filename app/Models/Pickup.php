<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Pickup extends Model
{
    use HasFactory;

    protected $table = 'pickups';

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
        return self::select('pickups.*','pickups.status','pickups.updated_at','hubs.name as hub','return_reasons.reason')
            ->leftJoin('hubs', 'hubs.id', '=', 'pickups.hub_id')
            ->leftJoin('return_reasons', 'return_reasons.id', '=', 'pickups.return_reason')
            ->orderBy('pickups.updated_at', 'desc')
            ->where('pickups.status', $status)
            ->whereDate('pickups.updated_at', date('Y-m-d'))
            ->paginate($per_page);
    }

    public function filterPaginate($per_page, $status) {
        return self::select('pickups.*','pickups.status','pickups.updated_at','hubs.name as hub','return_reasons.reason')
            ->leftJoin('hubs', 'hubs.id', '=', 'pickups.hub_id')
            ->leftJoin('return_reasons', 'return_reasons.id', '=', 'pickups.return_reason')
            ->orderBy('pickups.updated_at', 'desc')
            ->where('pickups.status', $status)
            ->whereBetween(DB::raw('DATE(pickups.updated_at)'), [request()->date_from, request()->date_to])
            ->paginate($per_page);
    }

    public function filter($date_from, $date_to, $status) {
        $date_from = $date_from ? $date_from : date('Y-m-d');
        $date_to = $date_to ? $date_to : date('Y-m-d');
        return self::select('pickups.*','pickups.status','pickups.updated_at','hubs.name as hub','return_reasons.reason')
            ->leftJoin('hubs', 'hubs.id', '=', 'pickups.hub_id')
            ->leftJoin('return_reasons', 'return_reasons.id', '=', 'pickups.return_reason')
            ->orderBy('pickups.updated_at', 'desc')
            ->where('pickups.status', $status)
            ->whereBetween(DB::raw('DATE(pickups.updated_at)'), [$date_from, $date_to])
            ->get();
    }

    public function getPickup($per_page) {
        return self::select('pickups.*','pickups.status','pickups.updated_at','hubs.name as hub','return_reasons.reason')
            ->leftJoin('hubs', 'hubs.id', '=', 'pickups.hub_id')
            ->leftJoin('return_reasons', 'return_reasons.id', '=', 'pickups.return_reason')
            ->paginate($per_page);
    }
    

    public function searchPickup($key, $per_page) {
        return self::select('pickups.*','pickups.status','pickups.updated_at','hubs.name as hub')
            ->leftJoin('hubs', 'hubs.id', '=', 'pickups.hub_id')
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

    public function changeStatus($shipmentId, $status, $reason="", $hub_id = 0) {
        self::where('shipmentId', $shipmentId)
        ->update([
            'hub_id' => $hub_id,
            'status' => $status,
            'return_reason' => $reason
        ]);
    }

}
