<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    protected $table = 'shipments';

    protected $fillable = [
        "shipmentId",
        "shipCarrier",
        "shipMethod",
        "totalWeight",
        "freightCharges",
        "qtyPackages",
        "weightUoM",
        "currCode",
        "status"
    ];

    public function getShipment($per_page) {
        return self::paginate($per_page);
    }

    public function searchPickup($key, $status_list, $per_page) {
        return self::whereIn('pickups.status', $status_list)
            ->select('pickups.*','pickups.status','pickups.updated_at','hubs.name as hub')
            ->leftJoin('hubs', 'hubs.id', '=', 'pickups.hub_id')
            ->where('shipmentId', 'LIKE', '%' . $key . '%')
            ->orWhere('orderId', 'LIKE', '%' . $key . '%')
            ->paginate($per_page);
    }

    public function isShipmentExists($shipmentId) {
        $res = self::where('shipmentId', $shipmentId)->get();
        return count($res) > 0 ? true : false;
    }

    public function changeStatus($shipmentId, $status) {
        self::where('shipmentId', $shipmentId)->update([ 'status' => $status ]);
    }

}
