<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\AdjustmentRemarks;
use App\Models\StockAdjustment;
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

    public function index(Inventory $inventory) {
        $products = $inventory->getAllPaginate(15);
        $remarks = AdjustmentRemarks::where('status', 1)->get();
        return view('inventory.index', compact('products','remarks'));
    }

    public function search(Inventory $inventory) {
        $products = $inventory->searchPaginate(request()->sku, 15);
        $remarks = AdjustmentRemarks::where('status', 1)->get();
        return view('inventory.index', compact('products','remarks'));
    }

    public function updateExpiration($id)
    {
        Inventory::where('id', $id)->update(['expiration' => request()->expiration]);

        return response()->json([
            'success' =>  true,
            'message' => 'Lot Expiration was upated successfully.'
        ], 200);
    }

    public function previewReport(Inventory $inventory){
        
        $items = Utils::objectToArray($inventory->getAll());
        $title = "Inventory Report";
        $headers = $inventory->getHeaders();
        $columns = $inventory->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'portrait');
    
        return $pdf->stream('report.pdf');
    }
    
    public function downloadReport(Inventory $inventory){
        
        $items = Utils::objectToArray($inventory->getAll());
        $title = "Inventory Report";
        $headers = $inventory->getHeaders();
        $columns = $inventory->getColumns();
        
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
