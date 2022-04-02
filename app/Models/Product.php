<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'sku',
        'description',
        'qty',
        'buffer_stock',
        'jde_lot_code',
        'supplier_lot_code',
        'expiration',
        'status',
    ];

    public function hasStock($sku, $qty) 
    {
        $current_qty = self::where('sku', $sku)->value('qty');
        if ($current_qty > $qty) {
            return true;
        }
        return false;
    }

    public function incrementStock($sku, $qty) {
        self::where('sku', $sku)->update(['qty' => DB::raw('qty + ' . $qty)]);
    }

    public function decrementStock($sku, $qty) {
        self::where('sku', $sku)->update(['qty' => DB::raw('qty - ' . $qty)]);
    }
}
