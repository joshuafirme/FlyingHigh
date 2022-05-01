<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LotCode extends Model
{
    use HasFactory;

    protected $table = 'product_lot_codes';

    protected $fillable = [
        'sku',
        'lot_code',
        'stock',
        'expiration',
        'type',
        'status',
    ];

    public function createLotCode($sku, $lot_code, $expiration, $qty) {
         LotCode::create([
            'sku' => $sku,
            'lot_code' => $lot_code,
            'expiration' => $expiration,
            'stock' => $qty,
        ]);
    }

    public function getLotCode($sku) {
        return self::select('lot_code', 'stock', 'expiration')->where('sku', $sku)->orderBy('expiration', 'asc')->get();
    }
    
    public function isLotCodeExists($lot_code) {
        $res = self::where('lot_code', $lot_code)->get();
        return count($res) > 0 ? true : false;
    }

    public function isSKUAndLotCodeExists($sku,$lot_code) {
        $res = self::where('lot_code', $lot_code)->where('sku', $sku)->get();
        return count($res) > 0 ? true : false;
    }
    

    public function isSKUExists($sku) {
        $res = self::where('sku', $sku)->get();
        return count($res) > 0 ? true : false;
    }

    public function getAllStock($sku) {
        return self::where('sku', $sku)->sum('stock');
    }
    
    public function incrementStock($sku, $lotNumber, $qty) { 
        self::where('sku', $sku)->where('lot_code', $lotNumber)->update(['stock' => DB::raw('stock + ' . $qty)]);
    }

    public function decrementStock($sku, $lotNumber, $qty) {
        self::where('sku', $sku)->where('lot_code', $lotNumber)->update(['stock' => DB::raw('stock - ' . $qty)]);
    }

    public function hasStock($sku, $qty) 
    {
        $current_qty = self::where('sku', $sku)->value('stock');
        if ($current_qty >= $qty) {
            return true;
        }
        return false;
    }
}
