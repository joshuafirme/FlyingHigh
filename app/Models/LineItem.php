<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineItem extends Model
{
    use HasFactory;

    protected $table = 'line_items';

    protected $fillable = [
        "lineNumber",
        "orderId",
        "partNumber",
        "quantity",
        "name",
        "lineType",
        "parentKitItem",
        "remarks",
        "pv",
        "itemUnitPrice",
        "itemExtendedPrice",
        "salesPrice",
        "taxableAmount",
        "lineItemTotal",
        "status",
        "return_reason",
        "qty_returned",
        "rma_number",
        "lot_code"
    ];

    public function getLineItems($orderId) {
        return self::select('P.sku', 'P.description', 'quantity', 'line_items.status', 'orderId','partNumber', 'qty_returned')
        ->leftJoin('products as P', 'P.sku', '=', 'line_items.partNumber', 'line_items.lot_number')
        ->where('orderId', $orderId)->get();
    }

    
    public function getReturnedList($per_page) {
        return self::select('P.sku', 'P.description', 'quantity', 'line_items.status', 'orderId','partNumber', 
        'qty_returned','orderId','return_reason',$this->table . '.updated_at', 'R.reason','rma_number')
        ->leftJoin('products as P', 'P.sku', '=', $this->table . '.partNumber')
        ->leftJoin('return_reasons as R', 'R.id', '=', $this->table . '.return_reason')
        ->orderBy($this->table.'.updated_at', 'desc')
        ->where($this->table . '.status', 2)->paginate($per_page);
    }
}
