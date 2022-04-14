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
        'hub_id'
    ];

    public function getHeaders() {
        return ['SKU', 'Description', 'Qty Transferred', 'Hub', 'Date Time'];
    }

     public function getColumns() {
        return ['sku', 'description', 'qty_transferred', 'hub', 'created_at'];
    }

    public function record($sku, $qty, $hub_id) {
        self::create([
            'sku' => $sku,
            'qty_transferred'=> $qty,
            'hub_id' => $hub_id
        ]);
    }

    public function getAllPaginate($paging) {
        return self::select('hub_transfer.*', 'P.description', 'H.name as hub')
            ->leftJoin('products as P', 'P.sku', '=', 'hub_transfer.sku')
            ->leftJoin('hubs as H', 'H.id', '=', 'hub_transfer.hub_id')
            ->orderBy('hub_transfer.created_at', 'desc')
            ->whereDate('hub_transfer.created_at', date('Y-m-d'))
            ->paginate($paging);
    }

   public function filterPaginate($paging) {
        return self::select('hub_transfer.*', 'P.description', 'H.name as hub')
            ->leftJoin('products as P', 'P.sku', '=', 'hub_transfer.sku')
            ->leftJoin('hubs as H', 'H.id', '=', 'hub_transfer.hub_id')
            ->orderBy('hub_transfer.created_at', 'desc')
            ->whereDate('hub_transfer.created_at', date('Y-m-d'))
            ->whereBetween(DB::raw('DATE(hub_transfer.created_at)'), [request()->date_from, request()->date_to])
            ->paginate($paging);
    }

    public function filter($date_from, $date_to) {
        $date_from = $date_from ? $date_from : date('Y-m-d');
        $date_to = $date_to ? $date_to : date('Y-m-d');
        return self::select('hub_transfer.*', 'P.description', 'H.name as hub')
            ->leftJoin('products as P', 'P.sku', '=', 'hub_transfer.sku')
            ->leftJoin('hubs as H', 'H.id', '=', 'hub_transfer.hub_id')
            ->orderBy('hub_transfer.created_at', 'desc')
            ->whereDate('hub_transfer.created_at', date('Y-m-d'))
            ->whereBetween(DB::raw('DATE(hub_transfer.created_at)'), [$date_from, $date_to])
            ->get();
    }
}