<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class InboundTransfer extends Model
{
    use HasFactory;

    public function getHeaders() {
        return ['Tracking #','SKU','Lot Code','Description','Qty','Date time transferred'];
    }

    public function getColumns() {
        return [        
            'orderNumber',
            'orderType',
            'orderDate',
            'vendorNo',
            'vendorName',
            'shipFromAddress',
            'shipFromCountry',
            'transactionReferenceNumber',
            'receiptDate',
            'status'
        ];
    }

}
