<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function record($sku, $qty, $action, $remarks_id) {
        self::create([
            'sku' => $sku,
            'qty_adjusted'=> $qty,
            'action' => $action,
            'remarks_id' => $remarks_id
        ]);
    }
}
