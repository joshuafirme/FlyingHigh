<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use DateTime;
use Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ProductImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $product = new Product;
        if (!$product->isSkuExists($row[0]) && $row[0] != 'SKU') {
            $exp_date = ExcelDate::excelToDateTimeObject($row[3]);
            
            return new Product([
                'sku' => $row[0],
                'jde_lot_code' => $row[1],
                'supplier_lot_code' => $row[2],
                'expiration' => $exp_date,
                'description' => $row[4],
            ]);
        }
    }
}
