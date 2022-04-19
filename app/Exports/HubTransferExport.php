<?php

namespace App\Exports;

use App\Models\HubTransfer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HubTransferExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $h = new HubTransfer;
        return $h->filter(request()->date_from, request()->date_to);
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Description',
            'Hub',
            'Qty Transferred',
            'Date time',
        ];
    }
}
