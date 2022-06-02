<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Cache;
use Utils;
use Http;

class ProductApi extends Controller
{

    public function syncSkuMasters(Request $request) 
    {
        try {
            $token = $this->getAccessToken($request)->access_token;
            $url = "https://lf-gateway-stage.awsvodev.youngliving.com/inventory/skumasters";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($url);

            return json_decode($response);

            // insert to database here

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function getAccessToken($request) 
    {
        $url = "https://auth-stage.youngliving.com/connect/token";
        $data  = [
            'grant_type' => 'client_credentials',
            'client_id' => $request->client_id,
            'client_secret' => $request->client_secret,
            'scope' => 'lf-manila'
        ];
        
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => "POST",
                'content' => http_build_query($data)
            )
        );
        
        $context  = stream_context_create($options);
        $json_response  = json_decode(file_get_contents($url, false, $context));
       
        if ($json_response && $json_response->access_token) {
            return $json_response;
        }
        return json_encode([
            'success' => false,
            'message' => 'Error occured, can\'t get access token.'
        ]);
    }

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
