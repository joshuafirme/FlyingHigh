<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StockTransferImport;
use App\Exports\StockTransferExport;
use App\Models\StockTransfer;
use App\Models\InboundTransfer;
use App\Models\LotCode;
use App\Models\Product;
use Utils;

class StockTransferController extends Controller
{
    public function index(StockTransfer $tr) 
    {
        $transfer_request = $tr->getDataTodayPaginate(50);
        return view('stock-transfer.index', compact('transfer_request'));
    }

    public function search(StockTransfer $tr) 
    {
        $transfer_request = $tr->search();
        return view('stock-transfer.index', compact('transfer_request'));
    }

    public function filter(StockTransfer $tr) 
    {
        $transfer_request = $tr->filterPaginate(50);
        return view('stock-transfer.index', compact('transfer_request'));
    }

    public function transfer(StockTransfer $tr, Product $product, LotCode $lc, InboundTransfer $inbound_transfer) 
    { 
        $sku = request()->sku;
        $expiration = request()->expiration;
        $qty_transfer = request()->qty_transfer;
        $id = request()->id;
        $qty_pending = $tr->getQtyPending($id);

        if ($id && $qty_transfer) {
            if ($qty_pending >= $qty_transfer) {
                $tr->transfer($id, $qty_transfer);
                $lot_code = request()->lot_code;
                if (request()->old_lot_code && request()->old_lot_code == "on") {
                    $lc->incrementStock($sku, $lot_code, $qty_transfer);
                }
                else {
                    if (!$product->isSkuExists($sku)) {
                        $product->createProduct(request());
                    }
                    $lc->createLotCode($sku, $lot_code, $expiration, $qty_transfer);
                }
                $tr->changeStatus($id);
                $inbound_transfer->record(request());
                return response()->json([ 'success' => true ], 200);
            }
            return response()->json([ 'success' => false, 'message' => 'invalid_qty' ], 200);
        }
        else {
            return response()->json([ 'success' => false, 'message' => 'input_required'], 200);
        }
    }

    public function import(Request $request) 
    {
        Excel::import(new StockTransferImport, $request->file('file')->store('temp'));
        return redirect()->back()->with('success', 'Data was successfully imported.');
    }

    public function export()
    {
        return Excel::download(
            new StockTransferExport, 'stock-transfer-'.request()->date_from.'-to-'.request()->date_to.'.xlsx'
        );
    }

    public function previewReport(StockTransfer $tr, $date_from, $date_to){
        
        $items = Utils::objectToArray($tr->filter());
  
        $title = "Stock Transfer Report";
        $headers = $tr->getHeaders();
        $columns = $tr->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns, $date_from, $date_to);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->stream('report.pdf');
    }
    
    public function downloadReport(StockTransfer $tr, $date_from, $date_to){
        
        $items = Utils::objectToArray($tr->filter());
  
        $title = "Stock Transfer Report";
        $headers = $tr->getHeaders();
        $columns = $tr->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns, $date_from, $date_to);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download($title . "-" . $date_from . "-" . $date_to .'.pdf');
    }
}
