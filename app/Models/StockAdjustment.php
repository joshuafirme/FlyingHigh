<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdjustmentRemarks;
use DB;
use Auth;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $table = 'stock_adjustment';

    protected $fillable = [
        'sku',
        'lot_code',
        'qty_adjusted',
        'action',
        'user_id',
        'remarks_id'
    ];

    public function getHeaders() {
        return ['SKU', 'Lot Code', 'Description', 'Action', 'Qty Adjusted', 'Adjusted by', 'Remarks', 'Date Time Adjusted'];
    }

     public function getColumns() {
        return ['sku', 'lot_code', 'description', 'action', 'qty_adjusted', 'adjusted_by', 'remarks', 'created_at'];
    }

    public function record($sku, $lot_code, $qty, $action, $remarks_id) {
        self::create([
            'sku' => $sku,
            'lot_code' => $lot_code,
            'qty_adjusted'=> $qty,
            'action' => $action,
            'user_id' => Auth::id(),
            'remarks_id' => $remarks_id
        ]);
    }

    public function getAllPaginate($per_page) {
        return self::select('stock_adjustment.*', 'P.productDescription', 'AR.name as remarks', 'U.name as adjusted_by')
            ->leftJoin('users as U', 'U.id', '=', 'stock_adjustment.user_id')
            ->leftJoin('products as P', 'P.lineNumber', '=', 'stock_adjustment.lineNumber')
            ->leftJoin('adjustment_remarks as AR', 'AR.id', '=', 'stock_adjustment.remarks_id')
            ->orderBy('stock_adjustment.created_at', 'desc')
            ->whereDate('stock_adjustment.created_at', date('Y-m-d'))
            ->paginate($per_page);
    }

   public function filterPaginate($per_page) {
        $remarks = [request()->remarks_id];
        if (!request()->remarks_id) {
            $remarks = AdjustmentRemarks::select('id')->where('status', 1)->get();
        }
        return self::select('stock_adjustment.*', 'P.productDescription', 'AR.name as remarks', 'U.name as adjusted_by')
            ->leftJoin('users as U', 'U.id', '=', 'stock_adjustment.user_id')
            ->leftJoin('products as P', 'P.lineNumber', '=', 'stock_adjustment.lineNumber')
            ->leftJoin('adjustment_remarks as AR', 'AR.id', '=', 'stock_adjustment.remarks_id')
            ->orderBy('stock_adjustment.created_at', 'desc')
            ->whereBetween(DB::raw('DATE(stock_adjustment.created_at)'), [request()->date_from, request()->date_to])
            ->whereIn('remarks_id', $remarks)
            ->paginate($per_page);
    }

    public function filter($date_from, $date_to) {
        $date_from = $date_from ? $date_from : date('Y-m-d');
        $date_to = $date_to ? $date_to : date('Y-m-d');
        $remarks = [request()->remarks_id];
        if (!request()->remarks_id) {
            $remarks = AdjustmentRemarks::select('id')->where('status', 1)->get();
        }
        return self::select('P.lineNumber', 'P.productDescription', 'action', 'qty_adjusted', 'AR.name as remarks', 'stock_adjustment.created_at', 'U.name as adjusted_by', 'stock_adjustment.lot_code')
            ->leftJoin('users as U', 'U.id', '=', 'stock_adjustment.user_id')
            ->leftJoin('products as P', 'P.lineNumber', '=', 'stock_adjustment.lineNumber')
            ->leftJoin('adjustment_remarks as AR', 'AR.id', '=', 'stock_adjustment.remarks_id')
            ->orderBy('stock_adjustment.created_at', 'desc')
            ->whereBetween(DB::raw('DATE(stock_adjustment.created_at)'), [$date_from, $date_to])
            ->whereIn('remarks_id', $remarks)
            ->get();
    }
    
}
