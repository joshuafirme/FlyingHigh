<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LotCode;
use App\Exports\ExpiredExport;
use Maatwebsite\Excel\Facades\Excel;
use Utils;

class ExpiredController extends Controller
{
    public function index(LotCode $lc) {
        $products = $lc->getExpired(10);
        return view('reports.expired.index', compact('products'));
    }

    public function filter(LotCode $lc) {
        $products = $lc->getExpiredFilterPaginate(10);
        return view('reports.expired.index', compact('products'));
    }

    public function previewReport($date_from, $date_to, LotCode $lc){
        
        $items = Utils::objectToArray($lc->getExpiredFilter($date_from, $date_to));
        $title = "Expired Report";
        $headers = $lc->getHeaders();
        $columns = $lc->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns, $date_from, $date_to);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->stream($date_from.'-to-'.$date_to.'.pdf');
    }
    
    public function downloadReport($date_from, $date_to, LotCode $lc)
    {
        $items = Utils::objectToArray($lc->getExpiredFilter($date_from, $date_to));
        $title = "Expired Report";
        $headers = $lc->getHeaders();
        $columns = $lc->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns, $date_from, $date_to);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download('expired-list-'.$date_from.'-to-'.$date_to.'.pdf');
    }

    public function exportReport()
    {
        return Excel::download(
            new ExpiredExport, 'expired-list-'.request()->date_from.'-to-'.request()->date_to.'.xlsx'
        );
    }

}
