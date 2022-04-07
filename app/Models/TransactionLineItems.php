<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLineItems extends Model
{
    use HasFactory;

    protected $table = 'transaction_line_items';

    protected $fillable = [
        "orderNumber",
        "orderType",
        "lineNumber",
        "itemNumber",
        "qtyRcdGood",
        "qtyRcdBad",
        "billOfLading",
        "rcvComments",
        "palletId",
        "unitOfMeasure",
        "location",
        "lotNumber",
        "receiptDate",
        "lotExpiration",
    ];
}