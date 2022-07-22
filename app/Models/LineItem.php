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
        return self::where('orderId', $orderId)->orderBy('lineNumber')->get();
    }

    
    public function getReturnedList($per_page) {
        return self::select('P.itemNumber', 'P.productDescription', 'quantity', 'line_items.status', 'orderId','partNumber', 
        'qty_returned','orderId','return_reason',$this->table . '.updated_at', 'R.reason','rma_number')
        ->leftJoin('products as P', 'P.itemNumber', '=', $this->table . '.partNumber')
        ->leftJoin('return_reasons as R', 'R.id', '=', $this->table . '.return_reason')
        ->orderBy($this->table.'.updated_at', 'desc')
        ->where($this->table . '.status', 2)->paginate($per_page);
    }
}
