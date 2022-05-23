<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class InboundTransfer extends Model
{
    use HasFactory;

        protected $table = 'inbound_transfers';

    protected $fillable = [
        'tracking_no',
        'sku',
        'qty',
        'lot_code',
        'status'
    ];

    public function record($request) {
        self::create([
            'tracking_no' => $request->tracking_no,
            'sku' => $request->sku,
            'qty'=> $request->qty_transfer,
            'lot_code' => $request->lot_code,
        ]);
    }

    public function getHeaders() {
        return ['Tracking #','SKU','Lot Code','Description','Qty','Date time transferred'];
    }

    public function getColumns() {
        return ['tracking_no','sku','lot_code','description','qty','created_at'];
    }

    public function getDataTodayPaginate($per_page) {
        return self::select($this->table.'.*', 'P.description')
            ->leftJoin('products as P', 'P.sku', '=', $this->table.'.sku')
            ->whereDate($this->table.'.created_at', date('Y-m-d'))->paginate($per_page);
    }

    public function filterPaginate($per_page) {
        return self::select($this->table.'.*', 'P.description')
            ->leftJoin('products as P', 'P.sku', '=', $this->table.'.sku')
            ->whereBetween(DB::raw('DATE('.$this->table.'.created_at)'), [request()->date_from, request()->date_to])->paginate($per_page);
    }

    public function filter() {
        return self::select('tracking_no','P.sku','lot_code','P.description',$this->table.'.qty',$this->table.'.created_at')
            ->leftJoin('products as P', 'P.sku', '=', $this->table.'.sku')
            ->whereBetween(DB::raw('DATE('.$this->table.'.created_at)'), [request()->date_from, request()->date_to])->get();
    }

}
