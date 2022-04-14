<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Cache;

class ProductApi extends Controller
{
    public function getAllSKU(Product $product) {
        $cache_data = Cache::get('all_sku_cache');

        if ($cache_data && count($cache_data) > 0) { 
            return $cache_data;
        }
        else {
            
            $fresh_data = $product->getAllSKU();

            Cache::put('all_sku_cache', $fresh_data);
            
            return $fresh_data;
        }
    }

    public function getByBarcode($barcode, Product $product) {
        return response()->json($product->getByBarcode($barcode));
    }

    public function getBySKU($sku, Product $product) {
        return response()->json($product->getBySKU($sku));
    }

    public function getBundleQtyList($sku, Product $product)
    {
        $data = $product->getBundleQtyList($sku);

        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
    }
}
