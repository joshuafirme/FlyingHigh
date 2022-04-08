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
        'has_bundle',
        'bundles',
        'status',
    ];

    public function getAllSKU() {
        return self::select('sku','description')->where('status', 1)->get();
    }

    public function isSkuExists($sku) {
        $res = self::where('sku', $sku)->get();
        return count($res) > 0 ? true : false;
    }

    public function hasStock($sku, $qty) 
    {
        $current_qty = self::where('sku', $sku)->value('qty');
        if ($current_qty >= $qty) {
            return true;
        }
        return false;
    }

    public function incrementBundleSKU($bundles, $qty) {
        foreach ($bundles as $sku) {
            $this->incrementStock($sku, $qty);
        }
    }

    public function incrementStock($sku, $qty) {
        self::where('sku', $sku)->update(['qty' => DB::raw('qty + ' . $qty)]);
    }

    public function decrementStock($sku, $qty) {
        self::where('sku', $sku)->update(['qty' => DB::raw('qty - ' . $qty)]);
    }
}
