<?php

namespace App\Exports;

use App\Models\StockAdjustment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockAdjustmentExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $sa = new StockAdjustment;
        return $sa->filter(request()->date_from, request()->date_to);
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Description',
            'Action',
            'Qty Adjusted',
            'Remarks',
            'Date time',
        ];
    }
}
