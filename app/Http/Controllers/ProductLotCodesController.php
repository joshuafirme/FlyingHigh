<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LotCode;
use Utils;

class ProductLotCodesController extends Controller
{
    public function index(LotCode $lc) {
        $products = $lc->getAllPaginate(10);
        return view('product-lot-codes.index', compact('products'));
    }

    public function previewReport(LotCode $lc){
        
        $items = Utils::objectToArray($lc->getAll());
        $title = "Lot Code List Report";
        $headers = $lc->getHeaders();
        $columns = $lc->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->stream('report.pdf');
    }
    
    public function downloadReport(LotCode $lc){
        
        $items = Utils::objectToArray($lc->getAll());
        $title = "Lot Code List Report";
        $headers = $lc->getHeaders();
        $columns = $lc->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download('report.pdf');
    }

    public function exportReport()
    {
         return Excel::download(new StockAdjustmentExport, 'stock-adjustment-report.xlsx');
    }
}