<?php

namespace App\Exports;

use App\Models\StockTransfer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockTransferExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $st = new StockTransfer;
        return $st->filterExport();
    }

    public function headings(): array {
        return ['Tracking #','SKU','Description','Pending Qty','Qty Received','UOM','Order date','Delivery date','Remarks','Status'];
    }
}
