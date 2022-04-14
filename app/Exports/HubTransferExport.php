<?php

namespace App\Exports;

use App\Models\HubInventory;
use Maatwebsite\Excel\Concerns\FromCollection;

class HubTransferExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return HubInventory::all();
    }
}
