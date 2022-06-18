<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\PurchaseOrder;
use App\Models\POLineItems;
use Cache;
use Utils;
use Http;

class YLApiController extends Controller
{

    public function syncSkuMasters(Request $request) 
    {
        try {
            $token = $this->getAccessToken($request)->access_token;
            $url = "https://lf-gateway-stage.awsvodev.youngliving.com/inventory/skumasters";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($url);

            $response = json_decode($response);
            $products = $response->skuMasterDetails;
            $newly_inserted = [];
           
            // Insert products to database
            foreach ($products as $item) {
                
                $p = new Product;

                if ( ! $p->isItemExists($item) ) {
                    array_push($newly_inserted, $item);

                    $p->storeProduct($item);
                }
                       
                
            }

            $t = new Transaction;
            $t->saveTransaction($response);

            Cache::put('sku_master_last_sync', date("Y-m-d H:i:s"));

            return response()->json([
                "success" => true,
                "message" => 'SKU Masters was successfully synced!',    
                "transactionType"=> $response->transactionType,
                "transactionReferenceNumber"=> $response->transactionReferenceNumber,
                "itemCount" => count($newly_inserted),
                "newly_inserted" => $newly_inserted
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                    "success" => false,
                    "exceptionMessage" => $e->getMessage(),    
            ], 200);
        }
    }

    public function postPurchaseOrders(Request $request) 
    {
        try {

            $token = $this->getAccessToken($request)->access_token;

            $url = "https://lf-gateway-stage.awsvodev.youngliving.com/inventory/asn";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($url);

            $response = json_decode($response);
            $duplicates = [];
            foreach ($response->purchaseOrderHeaders as $purchaseOrder) { 
                $po = new PurchaseOrder;
                
                if ($po->isPurchaseOrderExists($purchaseOrder->orderNumber)) {
                    array_push($duplicates, $purchaseOrder);
                }
                else {
                    $po->savePurchaseOrders($purchaseOrder);

                    foreach ($purchaseOrder->purchaseOrderDetails as $lineItems) {
                        $poli = new POLineItems;
                        $poli->saveItem($lineItems);
                    }
                }
            }

            $t = new Transaction;
            $t->saveTransaction($response);

            return response()->json([
                "success" => true,    
                "transactionType"=> $response->transactionType,
                "transactionReferenceNumber"=> $response->transactionReferenceNumber,
                "warnings" => [
                    "count" => count($duplicates),
                    "already_exists_orders" => $duplicates
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "exceptionMessage" => $e->getMessage(),    
            ], 200);
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
        
        $response = Utils::httpPost($data, $url);
       
        if ($response && $response->access_token) {
            return $response;
        }
        return json_encode([
            'success' => false,
            'message' => 'Error occured, can\'t get access token.'
        ]);
    }
}
