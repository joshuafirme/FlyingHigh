<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $p = new Product;
        return collect($p->getAll());
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Description',
            'Stock',
            'Buffer Stock',
        ];
    }
}
