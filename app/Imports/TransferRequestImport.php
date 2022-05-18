<?php

namespace App\Imports;

use App\Models\TransferRequest;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class TransferRequestImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $tr = new TransferRequest;
        //if ($row[11] != 'SKU') {
            //dd($row);
            $order_date = ExcelDate::excelToDateTimeObject($row[9]);
            $delivery_date = ExcelDate::excelToDateTimeObject($row[1]);
            return new TransferRequest([
                'tracking_no' => request()->tracking_no,
                'sku' => $row[11],
                'description' => $row[14],
                'qty_order' => $row[13],
                'external_line_no' => $row[10],
                'uom' => $row[12],
                'order_date' => $order_date,
                'delivery_date' => $delivery_date,
                'remarks' => $row[23],
            ]);
        //}
    }
    
}
