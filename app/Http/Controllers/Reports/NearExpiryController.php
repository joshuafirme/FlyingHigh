<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Exports\ExpiredExport;
use Maatwebsite\Excel\Facades\Excel;
use Utils;

class NearExpiryController extends Controller
{
    public function index(Inventory $lc) {
        $products = $lc->getNearExpiry(10);
        return view('reports.near-expiry.index', compact('products'));
    }

    public function filter(Inventory $lc) {
        $products = $lc->getExpiredFilterPaginate(10);
        return view('reports.near-expiry.index', compact('products'));
    }

    public function previewReport(Inventory $lc){
        
        $items = Utils::objectToArray($lc->getNearExpiryNoPaging());
        $title = "Near Expiry Report";
        $headers = $lc->getHeaders();
        $columns = $lc->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->stream('near-expiry.pdf');
    }
    
    public function downloadReport(Inventory $lc)
    {
        $items = Utils::objectToArray($lc->getNearExpiryNoPaging());
        $title = "Near Expiry Report";
        $headers = $lc->getHeaders();
        $columns = $lc->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download('near-expiry.pdf');
    }

    public function exportReport()
    {
        return Excel::download(
            new ExpiredExport, 'near-expiry-'.request()->date_from.'-to-'.request()->date_to.'.xlsx'
        );
    }
}
