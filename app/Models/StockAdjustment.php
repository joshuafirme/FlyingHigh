<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $table = 'stock_adjustment';

    protected $fillable = [
        'sku',
        'qty_adjusted',
        'action',
        'remarks_id'
    ];

    public function getHeaders() {
        return ['SKU', 'Description', 'Action', 'Qty Adjusted', 'Remarks', 'Date Time Adjusted'];
    }

     public function getColumns() {
        return ['sku', 'description', 'action', 'qty_adjusted', 'remarks', 'created_at'];
    }

    public function record($sku, $qty, $action, $remarks_id) {
        self::create([
            'sku' => $sku,
            'qty_adjusted'=> $qty,
            'action' => $action,
            'remarks_id' => $remarks_id
        ]);
    }

    public function getAllPaginate($paging) {
        return self::select('stock_adjustment.*', 'P.description', 'AR.name as remarks')
            ->leftJoin('products as P', 'P.sku', '=', 'stock_adjustment.sku')
            ->leftJoin('adjustment_remarks as AR', 'AR.id', '=', 'stock_adjustment.remarks_id')
            ->orderBy('stock_adjustment.created_at', 'desc')
            ->whereDate('stock_adjustment.created_at', date('Y-m-d'))
            ->paginate($paging);
    }

   public function filterPaginate($paging) {
        return self::select('stock_adjustment.*', 'P.description', 'AR.name as remarks')
            ->leftJoin('products as P', 'P.sku', '=', 'stock_adjustment.sku')
            ->leftJoin('adjustment_remarks as AR', 'AR.id', '=', 'stock_adjustment.remarks_id')
            ->orderBy('stock_adjustment.created_at', 'desc')
            ->whereBetween(DB::raw('DATE(stock_adjustment.created_at)'), [request()->date_from, request()->date_to])
            ->paginate($paging);
    }

    public function filter($date_from, $date_to) {
        return self::select('stock_adjustment.*', 'P.description', 'AR.name as remarks')
            ->leftJoin('products as P', 'P.sku', '=', 'stock_adjustment.sku')
            ->leftJoin('adjustment_remarks as AR', 'AR.id', '=', 'stock_adjustment.remarks_id')
            ->orderBy('stock_adjustment.created_at', 'desc')
            ->whereBetween(DB::raw('DATE(stock_adjustment.created_at)'), [$date_from, $date_to])
            ->get();
    }
    
}
