<?php

namespace App\Exports;
use App\Models\Pickup;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReturnExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }
}
