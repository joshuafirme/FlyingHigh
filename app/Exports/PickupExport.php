<?php

namespace App\Exports;

use App\Models\Pickup;
use Maatwebsite\Excel\Concerns\FromCollection;

class PickupExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pickup::all();
    }
}
