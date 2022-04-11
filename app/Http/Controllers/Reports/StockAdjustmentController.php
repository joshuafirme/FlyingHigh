<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockAdjustment;
use DB;

class StockAdjustmentController extends Controller
{
    public function index() {
        $stock_adjustments = StockAdjustment::select('stock_adjustment.*', 'P.description', 'AR.name as remarks')
            ->leftJoin('products as P', 'P.sku', '=', 'stock_adjustment.sku')
            ->leftJoin('adjustment_remarks as AR', 'AR.id', '=', 'stock_adjustment.remarks_id')
            ->orderBy('stock_adjustment.created_at', 'desc')
            ->whereDate('stock_adjustment.created_at', date('Y-m-d'))
            ->paginate(10);
        return view('reports.stock-adjustment.index', compact('stock_adjustments'));
    }

    public function filterStockAdjustment() {

        $stock_adjustments = StockAdjustment::select('stock_adjustment.*', 'P.description', 'AR.name as remarks')
            ->leftJoin('products as P', 'P.sku', '=', 'stock_adjustment.sku')
            ->leftJoin('adjustment_remarks as AR', 'AR.id', '=', 'stock_adjustment.remarks_id')
            ->orderBy('stock_adjustment.created_at', 'desc')
            ->whereBetween(DB::raw('DATE(stock_adjustment.created_at)'), [request()->date_from, request()->date_to])
            ->paginate(10);
        return view('reports.stock-adjustment.index', compact('stock_adjustments'));
    }
}
