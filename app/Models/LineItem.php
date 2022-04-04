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
        "lineItemTotal"
    ];

    public function getLineItems($orderId) {
        return self::leftJoin('products', 'products.sku', '=', 'line_items.partNumber')
        ->where('orderId', $orderId)->get();
    }
}
