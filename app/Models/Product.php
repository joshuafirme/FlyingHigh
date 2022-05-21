<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Utils;
use App\Models\LotCode;

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

    public function createProduct($request) {
        self::create([
            'sku' => $request->sku,
            'description' => $request->description,
            'qty' => $request->qty_transfer,
            'buffer_stock' => 0
        ]);
    }

    public function getAll() {
        $data = self::select('sku','description','buffer_stock')->where('status', 1)->get();
        $lc = new LotCode;
        $data_arr = [];
        foreach ($data as $item) {
            $stock = $lc->getAllStock($item->sku);
            array_push($data_arr,[
                'sku' => $item->sku,
                'description' => $item->description,
                'stock' => $stock ? $stock : '0',
                'buffer_stock' => $item->buffer_stock ? $item->buffer_stock : '0',
            ]);
        }
        return $data_arr;
    }

    public function getAllSKU() {
        $data = self::select('id','sku','description')->where('status', 1)->get();
        $lc = new LotCode;
        $data_arr = [];
        foreach ($data as $item) {
            array_push($data_arr,[
                'id' => $item->id,
                'sku' => $item->sku,
                'description' => $item->description,
            ]);
        }
        return $data_arr;
    }

    public function getByBarcode($barcode) {
        return self::select('id','sku','description','qty')
                    ->where('status', 1)
                    ->where('barcode', $barcode)->first();
    }

    public function getBySKU($sku) {
        $data = self::select('id','sku','description','qty')->where('sku', $sku)->first();
        $lc = new LotCode;
        return json_encode([
            'id' => $data->id,
            'sku' => $data->sku,
            'description' => $data->description,
            'stock' => $lc->getAllStock($data->sku),
            'lot_codes' => $lc->getLotCode($data->sku)
        ]);
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
        $data = self::select('HI.sku', 'HI.stock', 'description', 'H.name as hub', 'HI.lot_code')
            ->leftJoin('hub_inventory as HI', 'HI.sku', '=', 'products.sku')
            ->leftJoin('hubs as H', 'H.id', '=', 'HI.hub_id')
            ->where('HI.sku', $sku)
            ->get();

        $data_arr = [];
        foreach ($data as $item) {
            array_push($data_arr,[
                'sku' => $item->sku,
                'lot_code' => $item->lot_code,
                'stock' => $item->stock,
                'hub' => $item->hub,
                'expiration' => Utils::formatDate($this->getExpiration($item->lot_code))
            ]);
        }
        return $data_arr;
    }

        public function getExpiration($lot_code) {
        $lc = new LotCode;
        return $lc->getExpiration($lot_code);
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
