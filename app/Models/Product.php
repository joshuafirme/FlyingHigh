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
        'barcode',
        'status',
    ];

    public function getAllSKU() {
        return self::select('id','sku','description','qty')->where('status', 1)->get();
    }

    public function getByBarcode($barcode) {
        return self::select('id','sku','description','qty')
                    ->where('status', 1)
                    ->where('barcode', $barcode)->first();
    }

    public function getBySKU($sku) {
        return self::select('id','sku','description','qty')->where('sku', $sku)->first();
    }

    public function isSkuExists($sku) {
        $res = self::where('sku', $sku)->get();
        return count($res) > 0 ? true : false;
    }

    public function isBarcodeExists($barcode) {
        $res = self::where('barcode', $barcode)->get();
        return count($res) > 0 ? true : false;
    }

    public function getHubsStockBySku($sku) {
        return self::select('HI.sku', 'HI.stock', 'description', 'H.name as hub')
        ->leftJoin('hub_inventory as HI', 'HI.sku', '=', 'products.sku')
        ->leftJoin('hubs as H', 'H.id', '=', 'HI.hub_id')
        ->where('HI.sku', $sku)
        ->get();
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

    public function isAllSKUExists($all_sku) {
        $sku_exists = true;
        $sku_list = [];
        foreach ($all_sku as $key => $item) {  

            if ($this->isSkuExists($item->itemNumber)) {
                // enough stock, do nothing...
            }
            else {
                array_push($sku_list, $item->itemNumber);
                $sku_exists = false;
            }
        }
        return json_encode([
            'result' => $sku_exists,
            'sku_list' => $sku_list
        ]);
    }
    
}
