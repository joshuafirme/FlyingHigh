<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Inventory;
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
        if ($row[0] != 'SKU') {
            $lot_code = new Inventory;
            $exp_date = ExcelDate::excelToDateTimeObject($row[3]);
            // lot code
            if (($row[1] != "" || $row[1] != null) && !$lot_code->isSKUAndLotCodeExists($row[0],$row[1])) {
                $lot_code->createLotCode($row[0], $row[1], $exp_date, 20);
            }
            
            if ($row[1] == "" || $row[1] == null) {
                $lot_code->createLotCode($row[0], 0, "", 20);
            }
    
            $product = new Product;
            if (!$product->isSkuExists($row[0])) {
                return new Product([
                    'sku' => $row[0],
                    'supplier_lot_code' => $row[2],
                    'description' => $row[4],
                ]);
            }

        }
    }
}
