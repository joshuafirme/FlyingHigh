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
        "status",
        "sender",
        "receiver",
    ];

    public function getShipment($per_page) {
        return self::select('shipments.*', 'hubs.name as hub')
            ->leftJoin('hubs', 'hubs.receiver', '=', 'shipments.receiver')
            ->paginate($per_page);
    }

    public function getDeliveredByReceiver($receiver, $per_page) {
        return self::where('receiver',$receiver)->where('status', 2)->paginate($per_page);
    }

    public function search($key, $per_page) {
        return self::where('shipmentId', 'LIKE', '%' . $key . '%')
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
