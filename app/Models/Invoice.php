<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'invoiceType',
        'invoiceDetail',
        'shipmentId',
        'orderId',
    ];

    function getInvoiceDetails($shipmentId) {
        return self::where('shipmentId', $shipmentId)->get();
    }
           
}
