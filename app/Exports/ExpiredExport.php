<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Inventory;

class ExpiredExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $lc = new Inventory;
        return $lc->getExpiredFilter(request()->date_from, request()->date_to);
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Lot Code',
            'Description',
            'Qty',
            'Expiration',
        ];
    }
}
