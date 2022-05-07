<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\LotCode;
use App\Models\LineItem;
use DB;

class HubInventory extends Model
{
    use HasFactory;

    protected $table = 'hub_inventory';

    protected $fillable = [
        'sku',
        'lot_code',
        'hub_id',
        'stock',
    ];

    public function createLotCode($sku, $lot_code, $qty, $hub_id) {
         self::create([
            'sku' => $sku,
            'lot_code' => $lot_code,
            'hub_id' => $hub_id,
            'stock' => $qty,
        ]);
    }

     public function getAllStock($sku, $hub_id) {
        return self::where('sku', $sku)->where('hub_id', $hub_id)->sum('stock');
    }

    public function getByHub($hub_id, $per_page) 
    {
        return self::select('P.*', $this->table . '.stock', $this->table . '.lot_code', 'hub_id', $this->table . '.updated_at')
            ->leftJoin('products as P', 'P.sku', '=', $this->table . '.sku')
            ->where($this->table . '.hub_id', $hub_id)
            ->orderBy($this->table . '.sku', 'desc')
            ->paginate($per_page);
    }

    public function searchByHub($hub_id, $per_page) 
    { 
        return self::select('P.*', $this->table . '.stock', $this->table . '.lot_code', 'hub_id')
            ->leftJoin('products as P', 'P.sku', '=', $this->table . '.sku')
            ->where($this->table . '.hub_id', $hub_id)
            ->where(function ($query) {
               $query->where($this->table . '.sku', 'LIKE', '%' . request()->key . '%')
                     ->orWhere('description', 'LIKE', '%' . request()->key . '%');
            })
            ->orderBy($this->table . '.updated_at', 'desc')
            ->paginate($per_page);
    }

    public function isLotCodeExistsInHub($sku, $lot_code, $hub_id) 
    {
        $res = self::where('sku', $sku)
                ->where('lot_code', $lot_code)
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

            if ($this->hasStock($item->partNumber, $item->quantity, $hub_id)) {
                // enough stock, do nothing...
            }
            else {
                array_push($sku_list, [
                    'sku' => $item->partNumber,
                    'description' => $item->partNumber . ' - ' . $item->description
                ]);
                $has_enough_stock = false;
            }
        }
        return json_encode([
            'result' => $has_enough_stock,
            'sku_list' => $sku_list
        ]);
    }

    public function incrementStock($lot_code, $qty, $hub_id) {

        self::where('lot_code', $lot_code)
        ->where('hub_id', $hub_id)->update([
            'stock' => DB::raw('stock + ' . $qty)
        ]);
    }

    public function decrementStock($sku, $qty, $hub_id) 
    {
        $lot_codes = $this->getLotCodes($sku);
       
        if (count($lot_codes) < 2) {
            self::where('sku', $sku)
                ->where('hub_id', $hub_id)
                ->where('lot_code', $lot_codes[0]['lot_code'])
                ->update([
                    'stock' => DB::raw('stock - ' . $qty)
                ]);  
        }
        else {
            usort($lot_codes, function($a, $b) {
                return strtotime($a['expiration']) - strtotime($b['expiration']);
            });

            $lot_code_ctr = 0;
            for ($key = 0; $key < $qty; $key++) {

                if ($key == 0) {
                    $stock_temp = $lot_codes[$lot_code_ctr]['stock'];
                }
              
                if ($stock_temp != 0) {

                    self::where('sku', $sku)
                        ->where('hub_id', $hub_id)
                        ->where('lot_code', $lot_codes[$lot_code_ctr]['lot_code'])
                        ->update([
                            'stock' => DB::raw('stock - 1')
                    ]);
                    
                    $stock_temp--;
                    
                }
                else {
                    $lot_code_ctr++;
                    $stock_temp = $lot_codes[$lot_code_ctr]['stock'];
                    $key--;
                }
            }
        }
    }

    public function getLotCodes($sku) {
        $data = self::select('sku', 'lot_code', 'stock')->where('sku', $sku)->get();
        $data_arr = [];
        foreach ($data as $item) {
            array_push($data_arr, [
                'sku' => $item->sku,
                'lot_code' => $item->lot_code,
                'stock' => $item->stock,
                'expiration' => $this->getExpiration($item->lot_code)
            ]);
        }
        return $data_arr;
    }

    public function isLotCodeHasStock($lot_code, $hub_id) {
        $current_qty = self::where('lot_code', $lot_code)->where('hub_id', $hub_id)->value('stock');
        if ($current_qty >= $qty) {
            return true;
        }
        return false;
    }

    public function hasStock($sku, $qty, $hub_id) 
    {
        $current_qty = self::where('sku', $sku)->where('hub_id', $hub_id)->sum('stock');
        if ($current_qty >= $qty) {
            return true;
        }
        return false;
    }

    public function getExpiration($lot_code) {
        $lc = new LotCode;
        return $lc->getExpiration($lot_code);
    }
}