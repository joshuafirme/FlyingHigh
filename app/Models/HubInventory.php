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

    public function getByHub($hub_id, $per_page) 
    {
        return self::select('P.*', $this->table . '.stock', 'hub_id')
            ->leftJoin('products as P', 'P.sku', '=', $this->table . '.sku')
            ->where($this->table . '.hub_id', $hub_id)
            ->orderBy($this->table . '.updated_at', 'desc')
            ->paginate($per_page);
    }

    public function searchByHub($hub_id, $per_page) 
    { 
        return self::select('P.*', $this->table . '.stock')
            ->leftJoin('products as P', 'P.sku', '=', $this->table . '.sku')
            ->where($this->table . '.hub_id', $hub_id)
            ->where(function ($query) {
               $query->where($this->table . '.sku', 'LIKE', '%' . request()->key . '%')
                     ->orWhere('description', 'LIKE', '%' . request()->key . '%');
            })
            ->orderBy($this->table . '.updated_at', 'desc')
            ->paginate($per_page);
    }

    public function isSkuExistsInHub($sku, $hub_id) 
    {
        $res = self::where('sku', $sku)
                ->where('hub_id', $hub_id)->get();
        return count($res) > 0 ? true : false;
    }

    public function ignoreOtherSKU($partNumber) {
        $ignore = false;
        if ($partNumber == "32666" || $partNumber == "COD" || $partNumber == "PHVT" || $partNumber == "SHIPTAX") {
            $ignore = true;
        }
        return $ignore;
    }

    public function isAllStockEnough($all_sku, $hub_id) {
        $product = new Product;
        $has_enough_stock = true;
        $sku_list = [];
        foreach ($all_sku as $item) {  
            if ($this->ignoreOtherSKU($item->partNumber)) {
                continue;
            }
            $bundles = $product->getBundlesBySKU($item->partNumber);
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

    public function incrementBundleSKU($bundles, $qty, $hub_id) {
        if (count($bundles) > 0) {
            foreach ($bundles as $sku) {
                if ($this->isSkuExistsInHub($sku, $hub_id)) {
                    $this->incrementStock($sku, $qty, $hub_id);
                }
                else {
                    self::create([
                        'sku' => $sku,
                        'stock' => $qty,
                        'hub_id' => $hub_id
                    ]);
                }
            }
        }
    }

    public function decrementBundleSKU($bundles, $qty, $hub_id) {
        if (count($bundles) > 0) {
            foreach ($bundles as $sku) {
                $this->decrementStock($sku, $qty, $hub_id);
            }
        }
    }
    public function incrementStock($sku, $qty, $hub_id) {
        $product = new Product;
        $bundles = $product->getBundlesBySKU($sku);
        $this->incrementBundleSKU($bundles, $qty, $hub_id);
        self::where('sku', $sku)
        ->where('hub_id', $hub_id)->update([
            'stock' => DB::raw('stock + ' . $qty)
        ]);
    }

    public function decrementStock($sku, $qty, $hub_id) {
        $product = new Product;
        $bundles = $product->getBundlesBySKU($sku);
        $this->decrementBundleSKU($bundles, $qty, $hub_id);
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

    public function getBundleQtyList($sku, $hub_id) 
    {
        $sku_list = array();
        $product = new Product;
        $bundles = $product->getBundlesBySKU($sku);

        foreach ($bundles as $sku){
            $data = self::select($this->table . '.sku','description', 'stock')
                ->leftJoin('products as P', 'P.sku', '=', $this->table . '.sku')
                ->where($this->table . '.sku', $sku)
                ->where('hub_id', $hub_id)->first();

            array_push($sku_list, [
                "sku" => $data->sku,
                "description" => $data->description,
                "stock" => $data->stock,
            ]);
        }

        return $sku_list;
    }
}
