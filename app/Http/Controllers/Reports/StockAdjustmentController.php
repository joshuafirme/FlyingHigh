<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockAdjustment;
use App\Models\AdjustmentRemarks;
use App\Exports\StockAdjustmentExport;
use Maatwebsite\Excel\Facades\Excel;
use Utils;

class StockAdjustmentController extends Controller
{
    public function index(StockAdjustment $sa) 
    {
        if (request()->sku) {
            $stock_adjustments = $sa->getSKUHistoryAdjustment(10);
        }
        else {
            $stock_adjustments = $sa->getAllPaginate(10);
        }
        $adjustment_remarks = AdjustmentRemarks::where('status', 1)->get();
        return view('reports.stock-adjustment.index', compact('stock_adjustments', 'adjustment_remarks'));
    }

    public function filterStockAdjustment(StockAdjustment $sa) 
    {

        $stock_adjustments = $sa->filterPaginate(10);
        $adjustment_remarks = AdjustmentRemarks::where('status', 1)->get();
        return view('reports.stock-adjustment.index', compact('stock_adjustments', 'adjustment_remarks'));
    }

    public function previewReport($date_from, $date_to, StockAdjustment $sa)
    {
        
        $items = Utils::objectToArray($sa->filter($date_from, $date_to));
        $title = "Stock Adjustment Report";
        $headers = $sa->getHeaders();
        $columns = $sa->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns, $date_from, $date_to);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->stream($date_from.'-to-'.$date_to.'.pdf');
    }
    
    public function downloadReport($date_from, $date_to, StockAdjustment $sa)
    {
        
        $items = Utils::objectToArray($sa->filter($date_from, $date_to));
        $title = "Stock Adjustment Report";
        $headers = $sa->getHeaders();
        $columns = $sa->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns, $date_from, $date_to);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download('stock-adjustment-report-'.$date_from.'-to-'.$date_to.'.pdf');
    }

    public function exportReport()
    {
         return Excel::download(new StockAdjustmentExport, 'stock-adjustment-report.xlsx');
    }

    
}
