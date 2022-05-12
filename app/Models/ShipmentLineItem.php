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
                    "P.sku", 
                    "P.description",
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
                )
        ->leftJoin('products as P', 'P.sku', '=', $this->table.'.partNumber')
        ->where('shipmentId', $shipmentId)
        ->get();
            
    }

    public function changeStatus($shipmentId, $partNumber, $status) {
        self::where('shipmentId', $shipmentId)->where('partNumber', $partNumber)->update(['status' => $status]);
    }
                
}
