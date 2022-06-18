<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POLineItems extends Model
{
    use HasFactory;

      protected $table = 'po_line_items';

    protected $fillable = [
        'orderNumber',
        'transactionAction',
        'lineNumber',
        'itemNumber',
        'quantityOrdered',
        'quantityOpen',
        'shipDate',
        'unitOfMeasure',
        'location',
        'lotNumber',
        'countryOfOrigin',
        'expectedDate',
        'description',
        'extWeight',
        'wtUom',
        'holdCode',
        'vendorLotNo',
        'lotExp',
        'iOfLading',
        'shipMethod',
        'carrierName',
        'palletId'
    ];

    public function saveItem() {
        $this->orderNumber = $purchaseOrder->orderNumber;
        $this->transactionAction = $lineItems->transactionAction;
        $this->lineNumber = $lineItems->lineNumber;
        $this->itemNumber = $lineItems->itemNumber;
        $this->quantityOrdered = $lineItems->quantityOrdered;
        $this->quantityOpen = $lineItems->quantityOpen;
        $this->shipDate = $lineItems->shipDate;
        $this->unitOfMeasure = $lineItems->unitOfMeasure;
        $this->location = $lineItems->location;
        $this->lotNumber = $lineItems->lotNumber;
        $this->countryOfOrigin = $lineItems->countryOfOrigin;
        $this->expectedDate = $lineItems->expectedDate;
        $this->description = $lineItems->description;
        $this->extWeight = $lineItems->extWeight;
        $this->wtUom = $lineItems->wtUom;
        $this->holdCode = $lineItems->holdCode;
        $this->vendorLotNo = $lineItems->vendorLotNo;
        $this->lotExp = $lineItems->lotExp;
        $this->iOfLading = isset($lineItems->iOfLading) ? $lineItems->iOfLading : null;
        $this->shipMethod = $lineItems->shipMethod;
        $this->carrierName = $lineItems->carrierName;
        $this->palletId = $lineItems->palletId;
        $this->save();
    }
                
}
