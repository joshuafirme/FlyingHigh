<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HubTransfer extends Model
{
    use HasFactory;

    protected $table = 'hub_transfer';

    protected $fillable = [
        'sku',
        'qty_transferred',
        'hub_id',
        'lot_code',
        'warehouse_id'
    ];

    public function getHeaders() {
        return ['SKU', 'Description', 'Qty Transferred', 'Hub', 'Date Time'];
    }

     public function getColumns() {
        return ['sku', 'description', 'qty_transferred', 'hub', 'created_at'];
    }

    public function record($sku, $request, $ctr) {
        self::create([
            'sku' => $sku,
            'lot_code' => $request->lot_code[$ctr],
            'qty_transferred'=> $request->qty_to_transfer[$ctr],
            'hub_id' => $request->hub_id,
            'warehouse_id' => "4803"
        ]);
    }

    public function getAllPaginate($per_page) {
        return self::select('hub_transfer.*', 'P.productDescription as description', 'H.name as hub')
            ->leftJoin('products as P', 'P.itemNumber', '=', 'hub_transfer.sku')
            ->leftJoin('hubs as H', 'H.id', '=', 'hub_transfer.hub_id')
            ->orderBy('hub_transfer.created_at', 'desc')
            ->whereDate('hub_transfer.created_at', date('Y-m-d'))
            ->paginate($per_page);
    }

   public function filterPaginate($per_page) {
        return self::select('hub_transfer.*', 'P.productDescription as description', 'H.name as hub')
            ->leftJoin('products as P', 'P.itemNumber', '=', 'hub_transfer.sku')
            ->leftJoin('hubs as H', 'H.id', '=', 'hub_transfer.hub_id')
            ->orderBy('hub_transfer.created_at', 'desc')
            ->whereBetween(DB::raw('DATE(hub_transfer.created_at)'), [request()->date_from, request()->date_to])
            ->where('hub_id', request()->hub_id)
            ->paginate($per_page);
    }

    public function filter($date_from, $date_to) {
        $date_from = $date_from ? $date_from : date('Y-m-d');
        $date_to = $date_to ? $date_to : date('Y-m-d');
        return self::select('P.itemNumber', 'P.productDescription as description', 'H.name as hub', 'qty_transferred', 'hub_transfer.created_at')
            ->leftJoin('products as P', 'P.itemNumber', '=', 'hub_transfer.sku')
            ->leftJoin('hubs as H', 'H.id', '=', 'hub_transfer.hub_id')
            ->orderBy('hub_transfer.created_at', 'desc')
            ->whereBetween(DB::raw('DATE(hub_transfer.created_at)'), [$date_from, $date_to])
            ->where('hub_id', request()->hub_id)
            ->get();
    }
}
