<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getPickup($status_list, $per_page) {
        return self::whereIn('pickups.status', $status_list)
            ->select('pickups.*','pickups.status','pickups.updated_at','hubs.name as hub')
            ->leftJoin('hubs', 'hubs.id', '=', 'pickups.hub_id')
            ->paginate($per_page);
    }

    public function searchPickup($key, $status_list, $per_page) {
        return self::whereIn('pickups.status', $status_list)
            ->select('pickups.*','pickups.status','pickups.updated_at','hubs.name as hub')
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

    public function changeStatus($shipmentId, $status, $hub_id = 0) {
        self::where('shipmentId', $shipmentId)
        ->update([
            'hub_id' => $hub_id,
            'status' => $status
        ]);
    }

}
