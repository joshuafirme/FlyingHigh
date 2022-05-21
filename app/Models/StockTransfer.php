<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class StockTransfer extends Model
{
    use HasFactory;

    protected $table = 'stock_transfer';

    protected $fillable = [
        'tracking_no',
        'sku',
        'description',
        'lot_code',
        'external_line_no',
        'uom',
        'qty_order',
        'qty_received',
        'order_date',
        'delivery_date',
        'date_delivered',
        'status',
        'remarks',
    ];

    public function getHeaders() {
        return ['Tracking #','SKU','Description','Pending Qty','Qty Received','UOM','Order date','Delivery date','Remarks','Status'];
    }

    public function getColumns() {
        return ['tracking_no','sku','description', 'qty_order','qty_received', 'uom', 'delivery_date','delivery_date','remarks','status'];
    }

    public function getDataTodayPaginate($per_page) {
        return self::whereDate('delivery_date', date('Y-m-d'))->paginate($per_page);
    }

    public function search() {
        return self::where('tracking_no', request()->key)->paginate(20);
    }

    public function filterPaginate($per_page) {
        return self::whereBetween(DB::raw('DATE(delivery_date)'), [request()->date_from, request()->date_to])->paginate($per_page);
    }

    public function filter() {
        return self::whereBetween(DB::raw('DATE(delivery_date)'), [request()->date_from, request()->date_to])->get();
    }

    public function filterExport() {
        return self::select('tracking_no','sku','description', 'qty_order','qty_received', 'uom', 'order_date','delivery_date','remarks','status')
        ->whereBetween(DB::raw('DATE(delivery_date)'), [request()->date_from, request()->date_to])->get();
    }

    public function transfer($id, $qty_transfer) {
        self::where('id', $id)->increment('qty_received', $qty_transfer);
        self::where('id', $id)->decrement('qty_order', $qty_transfer);
    }

    public function getQtyPending($id) {
        return self::where('id', $id)->value('qty_order');
    }

    public function changeStatus($id) {
        $qty_pending = $this->getQtyPending($id);
        self::where('id', $id)->update([ 'status' => 1 ]);
        if ($qty_pending == 0) {
            self::where('id', $id)->update([ 'status' => 2 ]);
        }
    }

    public function getLastID() {
        $res =  self::max('id');
        return $res ? $res : 0;
    }
}
