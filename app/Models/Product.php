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
        "itemNumber",
        "lotCode",
        "stock",
        "bufferStock",
        "actionCode",
        "baseUOM",
        "conversionFactor",
        "height",
        "width",
        "depth",
        "itemDimensionUnit",
        "weight",
        "weightUnit",
        "volume",
        "volumeUom",
        "productDescription",
        "harmonizedCode",
        "hazardous",
        "food",
        "refrigerated",
        "retailPrice",
        "willMelt",
        "willFreeze",
        "specialShippingCode",
        "isBarcoded",
        "barCodeNumber",
        "currencyCode",
        "lineType",
        "unHazardCode",
        "isLotControlled",
        "status"
    ];

    public function storeProduct($item) {
        $this->itemNumber = $item->itemNumber;
        $this->actionCode = $item->actionCode;
        $this->baseUOM = $item->baseUOM;
        $this->conversionFactor = $item->conversionFactor;
        $this->height = $item->height;
        $this->width = $item->width;
        $this->depth = $item->depth;
        $this->itemDimensionUnit = $item->itemDimensionUnit;
        $this->weight = $item->weight;
        $this->weightUnit = $item->weightUnit;
        $this->volume = $item->volume;
        $this->volumeUom = $item->volumeUom;
        $this->productDescription = $item->productDescription;
        $this->harmonizedCode = $item->harmonizedCode;
        $this->hazardous = $item->hazardous;
        $this->food = $item->food;
        $this->refrigerated = $item->refrigerated;
        $this->retailPrice = $item->retailPrice;
        $this->willMelt = $item->willMelt;
        $this->willFreeze = $item->willFreeze;
        $this->specialShippingCode = $item->specialShippingCode;
        $this->isBarcoded = $item->isBarcoded;
        $this->barCodeNumber = $item->barCodeNumber;
        $this->currencyCode = $item->currencyCode;
        $this->lineType = $item->lineType;
        $this->unHazardCode = $item->unHazardCode;
        $this->isLotControlled = $item->isLotControlled;
        $this->save(); 
    }

    public function createProduct($request) {
        self::create([
            'sku' => $request->sku,
            'description' => $request->description,
            'qty' => $request->qty_transfer,
            'buffer_stock' => 0
        ]);
    }
        
    public function isLotControlled($sku) {
        $res = self::where('itemNumber', $sku)->limit(1)->value('isLotControlled');
        if ($res) {
            return $res == 'T' ? true : false;
        }
        else {
            return "SKU not found.";
        }
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
        return self::select('id','itemNumber','productDescription', 'baseUOM')->where('status', 1)->get();
    }

    public function getByBarcode($barcode) {
        return self::select('id','sku','description','qty')
                    ->where('status', 1)
                    ->where('barcode', $barcode)->first();
    }

    public function getBySKU($sku, $baseUOM) {
        $data = self::select('id','itemNumber','productDescription', 'baseUOM')
            ->where('itemNumber', $sku)
            ->where('baseUOM', $baseUOM)
            ->first();
        $lc = new LotCode;
        return json_encode([
            'id' => $data->id,
            'sku' => $data->itemNumber,
            'description' => $data->productDescription,
            'baseUOM' => $data->baseUOM,
            'stock' => $lc->getAllStock($data->sku, $data->baseUOM),
            'lot_codes' => $lc->getLotCode($data->sku, $data->baseUOM)
        ]);
    }

    public function isSkuExists($sku, $baseUOM) {
        $res = self::where([
            ['itemNumber', '=', $sku],
            ['baseUOM', '=', $baseUOM],
        ])->get();
        return count($res) > 0 ? true : false;
    }

    public function isItemExists($item) {
        $res = self::where([
            ['itemNumber', '=', $item->itemNumber],
            ['baseUOM', '=', $item->baseUOM],
        ])->get();

        return count($res) > 0 ? true : false;
    }

    public function isBarcodeExists($barcode) {
        $res = self::where('barcode', $barcode)->get();
        return count($res) > 0 ? true : false;
    }

    public function getHubsStockBySku($sku) {
        $data = self::select('HI.itemNumber', 'HI.stock', 'description', 'H.name as hub', 'HI.lot_code')
            ->leftJoin('hub_inventory as HI', 'HI.itemNumber', '=', 'products.itemNumber')
            ->leftJoin('hubs as H', 'H.id', '=', 'HI.hub_id')
            ->where('HI.itemNumber', $sku)
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
