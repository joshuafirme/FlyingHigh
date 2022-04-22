<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InboundTransfer implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $sa = new Transaction;
        return $sa->filter(request()->date_from, request()->date_to);
    }

    
    public function headings(): array
    {
        return [
            'Transaction Reference Number', 'Transaction Type', 'Date time'
        ];
    }
}
