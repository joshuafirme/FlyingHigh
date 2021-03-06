<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_order';

    protected $fillable = [
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

    public function savePurchaseOrders($purchaseOrder, $transactionReferenceNumber) {
        $this->orderNumber = $purchaseOrder->orderNumber;
        $this->orderType = $purchaseOrder->orderType;
        $this->orderDate = $purchaseOrder->orderDate;
        $this->vendorNo = $purchaseOrder->vendorNo;
        $this->vendorName = $purchaseOrder->vendorName;
        $this->shipFromAddress = $purchaseOrder->shipFromAddress;
        $this->shipFromCountry = $purchaseOrder->shipFromCountry;
        $this->transactionReferenceNumber = $transactionReferenceNumber;
        $this->save();
    }

    public function isPurchaseOrderExists($orderNumber) {
        $res = self::where('orderNumber', $orderNumber)->get();
        return count($res) > 0 ? true : false;
    }
}
