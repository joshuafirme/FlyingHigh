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
        "orderSource"
    ];

    public function isOrderExists($orderId) {
        $res = self::where('orderId', $orderId)->get();
        return count($res) > 0 ? true : false;
    }
}
