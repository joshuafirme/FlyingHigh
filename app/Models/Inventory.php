<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'sku',
        'lot_code',
        'stock',
        'expiration',
        'type',
        'uom',
        'location',
        'palletId',
        'status',
    ];

    function getHeaders() {
        return ["SKU","Lot Code","Description","Stock","Expiration"];
    }

    function getColumns() {
        return ["sku","lot_code","productDescription","stock","expiration"];
    }

    public function getAllPaginate($per_page) {

        $no_lot_code_list = self::select($this->table . '.*', 'productDescription', 'bufferStock')
            ->leftJoin('products as P', DB::raw('CONCAT(P.itemNumber, P.baseUOM)'), '=', DB::raw('CONCAT(sku, uom)'))
            ->where('expiration', null);

        return self::select($this->table . '.*', 'productDescription', 'bufferStock')
            ->leftJoin('products as P', DB::raw('CONCAT(P.itemNumber, P.baseUOM)'), '=', DB::raw('CONCAT(sku, uom)'))
            ->whereDate('expiration', '>', date('Y-m-d'))
            ->union($no_lot_code_list)
            ->orderBy('expiration','desc')
            ->paginate($per_page);
    }

    public function searchPaginate($key, $per_page) {
        
        $no_lot_code_list = self::select($this->table . '.*', 'productDescription')
            ->leftJoin('products as P', DB::raw('CONCAT(P.itemNumber, P.baseUOM)'), '=', DB::raw('CONCAT(sku, uom)'))
            ->where('P.itemNumber', 'LIKE', '%' . $key . '%')
            ->orWhere('productDescription', 'LIKE', '%' . $key . '%')
            ->where('expiration', null);

        return self::select($this->table . '.*', 'productDescription')
            ->leftJoin('products as P', DB::raw('CONCAT(P.itemNumber, P.baseUOM)'), '=', DB::raw('CONCAT(sku, uom)'))
            ->orderBy($this->table . '.expiration')
            ->whereDate('expiration', '>', date('Y-m-d'))
            ->where('P.itemNumber', 'LIKE', '%' . $key . '%')
            ->orWhere('productDescription', 'LIKE', '%' . $key . '%')
            ->union($no_lot_code_list)
            ->paginate($per_page);
    }

    public function getAll() {
        return self::select($this->table . '.*', 'P.productDescription')
            ->leftJoin('products as P', 'P.itemNumber', '=', $this->table . '.sku')
            ->orderBy($this->table . '.created_at', 'desc')
            ->get();
    }
    
    public function getExpired($per_page) {
        return self::select($this->table . '.*', 'P.productDescription')
            ->leftJoin('products as P', 'P.itemNumber', '=', $this->table . '.sku')
            ->whereDate($this->table . '.expiration', '=', date('Y-m-d'))
            ->where('lot_code', '!=', null)
            ->orderBy($this->table . '.expiration', 'desc')
            ->paginate($per_page);
    }

    public function getExpiredFilterPaginate($per_page) {
        $date_from = request()->date_from ? request()->date_from : date('Y-m-d');
        $date_to = request()->date_to ? request()->date_to : date('Y-m-d');
        return self::select($this->table . '.*', 'P.productDescription')
            ->leftJoin('products as P', 'P.itemNumber', '=', $this->table . '.sku')
            ->whereDate($this->table . '.expiration', '<', date('Y-m-d'))
            ->where('lot_code', '!=', null)
            ->orderBy($this->table . '.expiration', 'desc')
            ->whereBetween(DB::raw('DATE(' . $this->table . '.expiration)'), [$date_from, $date_to])
            ->paginate($per_page);
    }

    public function getExpiredFilter($date_from, $date_to) {
        $date_from = $date_from ? $date_from : date('Y-m-d');
        $date_to = $date_to ? $date_to : date('Y-m-d');
        $tbl = $this->table;
        return self::select($tbl . '.sku', $tbl . '.lot_code', 'P.productDescription', $tbl . '.stock', $tbl . '.expiration')
            ->leftJoin('products as P', 'P.itemNumber', '=', $this->table . '.sku')
            ->whereDate($this->table . '.expiration', '<', date('Y-m-d'))
            ->where('lot_code', '!=', null)
            ->orderBy($this->table . '.expiration', 'desc')
            ->whereBetween(DB::raw('DATE(' . $this->table . '.expiration)'), [$date_from, $date_to])
            ->get();
    }

    public function createLotCode($sku, $lot_code, $expiration, $qty) {
         Inventory::create([
            'sku' => $sku,
            'lot_code' => $lot_code,
            'expiration' => $expiration,
            'stock' => $qty,
        ]);
    }

    public function archiveLotCode($id) {
        return self::where('id', $id)->update(['status' => 0]);
    }

    public function getOneLotCode($sku, $lot_code) {
        return self::where('sku', $sku)->where('lot_code', $lot_code)->value('stock');
    }

    public function getFirstExpiry($sku) {
        return self::where('sku', $sku)
            ->where(function ($query) {
                $query->whereDate('expiration', '>', date('Y-m-d'))
                ->orWhere('lot_code', null);
            })
            ->orderBy('expiration', 'asc')
            ->where('status', 1)
            ->value('lot_code');
    }

    public function getExpiration($lot_code) {
        return self::where('lot_code', $lot_code)->value('expiration');
    }

    public function getLotCode($sku, $uom) {
        return self::select('id', 'lot_code', 'stock', 'expiration')
            ->where('sku', $sku)
            ->where('uom', $uom)
            ->where(function ($query) {
               $query->whereDate('expiration', '>', date('Y-m-d'))
                     ->orWhere('lot_code', null);
            })
            ->orderBy('expiration', 'asc')
            ->where('status', 1)
            ->get();
    }
    
    public function isLotCodeExists($sku, $lot_code, $unitOfMeasure) {
        $res = self::where('sku', $sku)
            ->where('lot_code', $lot_code)
            ->where('uom', $unitOfMeasure)
            ->where('status', 1)->get();
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

    public function getAllStock($sku, $baseUOM) {
        return self::where('sku', $sku)
            //->where(function ($query) {
            //    $query->whereDate('expiration', '>', date('Y-m-d'));
            //})
            ->where('uom', $baseUOM)
            ->where($this->table . '.status', 1)
            ->sum($this->table . '.stock');
    }

    public function incrementStock($sku, $lot_code, $qty) { 
        $lot_code = $lot_code ? $lot_code : null;
        self::where('sku', $sku)->where('lot_code',$lot_code)->increment('stock', $qty);
    }

    public function decrementStock($sku, $lot_code, $qty) {
        $lot_code = $lot_code ? $lot_code : null;
        self::where('sku', $sku)
            ->where('lot_code', $lot_code)
            ->update(['stock' => DB::raw('stock - ' . $qty)]);
    }

    public function isAllStockEnough($sku, $lot_codes, $qty) {
        $has_enough_stock = true;
        $sku_list = [];
        foreach ($lot_codes as $key => $lot_code) {  

            if ($this->hasStock($sku[$key], $lot_code, $qty[$key])) {
                // enough stock, do nothing...
            }
            else {
                array_push($sku_list, $lot_code);
                $has_enough_stock = false;
            }
        }
        return json_encode([
            'result' => $has_enough_stock,
            'lot_codes' => $sku_list
        ]);
    }

    public function validateStock($line_items) {
        $has_enough_stock = true;
        $sku_list = [];
        foreach ($line_items as $key => $item) {  

            if ($this->validateLotCode($item->partNumber, $item->lotNumber, $item->qtyShipped)) {
                // enough stock, do nothing...
            }
            else {
                array_push($sku_list, [
                    'sku' => $item->partNumber,
                    'lot_code' => $item->lotNumber
                ]);
                $has_enough_stock = false;
            }
        }
        return json_encode([
            'result' => $has_enough_stock,
            'sku_list' => $sku_list
        ]);
    }

    public function validateLotCode($sku, $lot_code, $qty) 
    {
        if (!$lot_code) {
            $lot_code = 0;
        }
        $stock = self::where('sku', $sku)->where('lot_code', $lot_code)->value('stock');
        if ($stock >= $qty) {
            return true;
        }
        return false;
    }

    public function hasStock($sku, $lot_code, $qty) 
    {
        $stock = $this->getOneLotCode($sku, $lot_code);

        if ($stock >= $qty) {
            return true;
        }
        return false;
    }
}
