<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use Cache;
use Utils;
use Http;

class ProductApi extends Controller
{

    public function getAllSKU(Product $product) {
        return $product->getAllSKU();
    }

    public function getByBarcode($barcode, Product $product) {
        return response()->json($product->getByBarcode($barcode));
    }

    public function getBySKU($sku, Product $product) {
        return $product->getBySKU($sku);
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
