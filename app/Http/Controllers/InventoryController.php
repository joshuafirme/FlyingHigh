<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\User;
use Utils;

class InventoryController extends Controller
{
       private $page = "Inventory";

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (User::isPermitted($this->page)) { return $next($request); }
            return abort(401);
        });
    }

    public function index(Inventory $lc) {
        $products = $lc->getAllPaginate(15);
        return view('inventory.index', compact('products'));
    }

    public function previewReport(Inventory $lc){
        
        $items = Utils::objectToArray($lc->getAll());
        $title = "Inventory Report";
        $headers = $lc->getHeaders();
        $columns = $lc->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'portrait');
    
        return $pdf->stream('report.pdf');
    }
    
    public function downloadReport(Inventory $lc){
        
        $items = Utils::objectToArray($lc->getAll());
        $title = "Inventory Report";
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
