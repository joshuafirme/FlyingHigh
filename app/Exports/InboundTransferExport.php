<?php

namespace App\Exports;

use App\Models\InboundTransfer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InboundTransferExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $ib = new InboundTransfer;
        return $ib->filter(request()->date_from, request()->date_to);
    }

    
    public function headings(): array
    {
        return [
            'Tracking #','SKU','Lot Code','Description','Qty','Date time transferred'
        ];
    }
}
