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
        "trackingNo",
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

    public function readOne($shipmentId) {
        return self::where('shipmentId', $shipmentId)->first();
    }

    public function getShipment($per_page) {
        return self::select('shipments.*', 'hubs.name as hub')
            ->leftJoin('hubs', 'hubs.receiver', '=', 'shipments.receiver')
            ->paginate($per_page);
    }

    public function getShipmentByHub($hub_id, $per_page) {
        return self::where('hub_id',$hub_id)->whereIn('status', [0])->paginate($per_page);
    }  
    
    public function getCancelledShipmentByHub($hub_id, $per_page) {
        return self::where('hub_id',$hub_id)->whereIn('status', [2])->paginate($per_page);
    } 
    
    public function searchShipment($hub_id, $key, $per_page) {
        
        return self::where('hub_id',$hub_id)
            ->whereIn('status', [0,2,3,4])
            ->where('shipmentId', 'LIKE', '%' . $key . '%')->paginate($per_page);
            
    }

    public function searchDeliveredByReceiver($hub_id, $key, $per_page) {
        return self::where('hub_id',$hub_id) 
            ->where('status', 2)
            ->where('shipmentId', 'LIKE', '%' . $key . '%')->paginate($per_page);
    }


    public function search($key, $per_page) {
        return self::select('shipments.*', 'hubs.name as hub')
            ->leftJoin('hubs', 'hubs.receiver', '=', 'shipments.receiver')
            ->where('shipmentId', 'LIKE', '%' . $key . '%')
            ->orderBy('shipments.created_at', 'desc')
            ->paginate($per_page);
    }

    public function isShipmentExists($shipmentId, $branch_id) {
        $res = self::where('shipmentId', $shipmentId)->where('hub_id', $branch_id)->get();
        return count($res) > 0 ? true : false;
    }

    public function changeStatus($shipmentId, $status) {
        self::where('shipmentId', $shipmentId)->update([ 'status' => $status ]);
    }

}
