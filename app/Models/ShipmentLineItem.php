<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentLineItem extends Model
{
    use HasFactory;
 protected $table = 'shipment_line_items';

    protected $fillable = [
        "orderId",
        "shipmentId",
        "orderLineNumber",
        "partNumber",
        "lotNumber",
        "trackingNo",
        "qtyOrdered",
        "qtyShipped",
        "reasonCode",
        "shipDateTime",
        "status",
        'qty_returned',
        'rma_number',
        'return_reason'
    ];

    public function getLineItems($shipmentId) {
        return self::select(
                    "P.itemNumber", 
                    "P.productDescription",
                    "orderId",
                    "shipmentId",
                    "orderLineNumber",
                    "partNumber",
                    "trackingNo",
                    "qtyOrdered",
                    "qtyShipped",
                    "reasonCode",
                    "shipDateTime",
                    "lotNumber",
                    $this->table.".status"
                )
        ->leftJoin('products as P', 'P.itemNumber', '=', $this->table.'.partNumber')
        ->where('shipmentId', $shipmentId)
        ->get();
            
    }

    public function changeStatus($shipmentId, $partNumber, $status) {
        self::where('shipmentId', $shipmentId)->where('partNumber', $partNumber)->update(['status' => $status]);
    }

    public function doPickUp($request, $status) {
        self::where('orderId', $request->orderId)->where('partNumber', $request->partNumber)->update(['status' => $status]);
    }
    
                
}
