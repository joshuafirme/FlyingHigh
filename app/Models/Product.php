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

    public function hasStock($sku, $qty) 
    {
        $current_qty = self::where('sku', $sku)->value('qty');
        if ($current_qty >= $qty) {
            return true;
        }
        return false;
    }

    public function isAllStockEnough($all_sku, $qty) {
        $has_enough_stock = true;
        $sku_list = [];
        foreach ($all_sku as $key => $sku) {  

            $bundles = $this->getBundlesBySKU($sku);
            if ($this->isAllBundleStockEnough($bundles, $qty[$key])) {}
            else {
                array_push($sku_list, $sku);
                $has_enough_stock = false;
            }

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

     public function isAllBundleStockEnough($all_sku, $qty) {
        $has_enough_stock = true;
        foreach ($all_sku as $key => $sku) {  
            if ($this->hasStock($sku, $qty)) {
                // enough stock, do nothing...
            }
            else {
                $has_enough_stock = false;
            }
        }
        return $has_enough_stock;
    }

    public function incrementBundleSKU($bundles, $qty) {
        if (count($bundles) > 0) {
            foreach ($bundles as $sku) {
                $this->incrementStock($sku, $qty);
            }
        }
    }

    public function decrementBundleSKU($bundles, $qty) {
        if (count($bundles) > 0) {
            foreach ($bundles as $sku) {
                $this->decrementStock($sku, $qty);
            }
        }
    }

    public function getBundlesBySKU($sku) {
        $bundles = self::where('sku', $sku)->value('bundles');
        return $bundles ? explode(',', $bundles) : [];
    }

    public function incrementStock($sku, $qty) {

        $bundles = $this->getBundlesBySKU($sku);
        $this->incrementBundleSKU($bundles, $qty);

        self::where('sku', $sku)->update(['qty' => DB::raw('qty + ' . $qty)]);
    }

    public function decrementStock($sku, $qty) {

        $bundles = $this->getBundlesBySKU($sku);
        $this->decrementBundleSKU($bundles, $qty);

        self::where('sku', $sku)->update(['qty' => DB::raw('qty - ' . $qty)]);
    }

    public function getBundleQtyList($sku) 
    {
        $sku_list = array();
        $bundles = $this->getBundlesBySKU($sku);

        foreach ($bundles as $sku){
            $data = DB::table('products')->select('sku','description','qty')->where('sku', $sku)->first();
            array_push($sku_list, [
                "sku" => $data->sku,
                "description" => $data->description,
                "qty" => $data->qty,
            ]);
        }

        return $sku_list;
    }
}
