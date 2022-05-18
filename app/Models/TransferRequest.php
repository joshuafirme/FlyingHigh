<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferRequest extends Model
{
    use HasFactory;

    protected $table = 'transfer_request';

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
        'status',
        'remarks',
    ];

    public function getAllPaginate($per_page) {
        return self::paginate($per_page);
    }

    public function getLastID() {
        $res =  self::max('id');
        return $res ? $res : 0;
    }
}
