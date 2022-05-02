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

    public function getFirstExpiry($sku) {
        return self::where('sku', $sku)
            ->where(function ($query) {
                $query->whereDate('expiration', '>', date('Y-m-d'))
                ->orWhere('lot_code', 0);
            })
            ->orderBy('expiration', 'asc')
            ->value('lot_code');
    }

    public function getExpiration($lot_code) {
        return self::where('lot_code', $lot_code)->value('expiration');
    }

    public function getLotCode($sku) {
        return self::select('lot_code', 'stock', 'expiration')
            ->where('sku', $sku)
            ->where(function ($query) {
               $query->whereDate('expiration', '>', date('Y-m-d'))
                     ->orWhere('lot_code', 0);
            })
            ->orderBy('expiration', 'asc')
            ->get();
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
        return self::where('sku', $sku)
            ->where(function ($query) {
                $query->whereDate('expiration', '>', date('Y-m-d'))
                ->orWhere('lot_code', 0);
            })
            ->sum('stock');
    }
    
    public function incrementStock($sku, $lotNumber, $qty) { 
        self::where('sku', $sku)->where('lot_code', $lotNumber)->update(['stock' => DB::raw('stock + ' . $qty)]);
    }

    public function decrementStock($sku, $lot_code, $qty) {
        self::where('sku', $sku)
            ->where('lot_code', $lot_code)
            ->update(['stock' => DB::raw('stock - ' . $qty)]);
    }

    public function isAllStockEnough($all_sku, $qty) {
        $has_enough_stock = true;
        $sku_list = [];
        foreach ($all_sku as $key => $sku) {  

            if ($this->hasStock($sku, $qty[$key])) {
                // enough stock, do nothing...
            }
            else {
                array_push($sku_list, $sku);
                $has_enough_stock = false;
            }
        }
        return json_encode([
            'result' => $has_enough_stock,
            'sku' => $sku_list
        ]);
    }

    public function hasStock($sku, $qty) 
    {
        $total_stock = $this->getAllStock($sku);

        if ($total_stock >= $qty) {
            return true;
        }
        return false;
    }
}
