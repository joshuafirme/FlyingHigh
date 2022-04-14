<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use DB;

class HubInventory extends Model
{
    use HasFactory;

    protected $table = 'hub_inventory';

    protected $fillable = [
        'sku',
        'hub_id',
        'stock',
    ];

    public function isSkuExistsInHub($sku, $hub_id) 
    {
        $res = self::where('sku', $sku)
                ->where('hub_id', $hub_id)->get();
        return count($res) > 0 ? true : false;
    }

    public function isAllStockEnough($all_sku, $hub_id) {
        $product = new Product;
        $has_enough_stock = true;
        $sku_list = [];
        foreach ($all_sku as $item) {  

            $bundles = $product->getBundlesBySKU($item->partNumber, $hub_id);
            if ($this->isAllBundleStockEnough($bundles, $item->quantity, $hub_id)) {}
            else {
                array_push($sku_list, $item->partNumber);
                $has_enough_stock = false;
            }

            if ($this->hasStock($item->partNumber, $item->quantity, $hub_id)) {
                // enough stock, do nothing...
            }
            else {
                array_push($sku_list, $item->partNumber);
                $has_enough_stock = false;
            }
        }
        return json_encode([
            'result' => $has_enough_stock,
            'sku' => $sku_list
        ]);
    }

     public function isAllBundleStockEnough($all_sku, $qty, $hub_id) {
        $has_enough_stock = true;
        foreach ($all_sku as $key => $sku) {  
            if ($this->hasStock($sku, $qty, $hub_id)) {
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
    public function incrementStock($sku, $qty, $hub_id) {
        $bundles = $product->getBundlesBySKU($sku, $hub_id);
        $this->incrementBundleSKU($bundles, $qty);
        self::where('sku', $sku)
        ->where('hub_id', $hub_id)->update([
            'stock' => DB::raw('stock + ' . $qty)
        ]);
    }

    public function decrementStock($sku, $qty, $hub_id) {
        $product = new Product;
        $bundles = $product->getBundlesBySKU($sku, $hub_id);
        $this->decrementBundleSKU($bundles, $qty);
        self::where('sku', $sku)
        ->where('hub_id', $hub_id)->update([
            'stock' => DB::raw('stock - ' . $qty)
        ]);
    }


    public function hasStock($sku, $qty, $hub_id) 
    {
        $current_qty = self::where('sku', $sku)->where('hub_id', $hub_id)->value('stock');
        if ($current_qty >= $qty) {
            return true;
        }
        return false;
    }
}
