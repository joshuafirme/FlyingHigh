<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\PurchaseOrder;
use App\Models\POLineItems;
use DB;
use Cache;
use Utils;
use Http;

class YLApiController extends Controller
{

    public function syncSkuMasters(Request $request) 
    {
        try {
            DB::beginTransaction();
            
            $token = $this->getAccessToken($request)->access_token;
            $url = "https://lf-gateway-stage.awsvodev.youngliving.com/inventory/skumasters";
            
            $header = [
                'Authorization' => 'Bearer ' . $token,
            ];

            //$response = Http::withHeaders([
            //    'Authorization' => 'Bearer ' . $token,
            //])->get($url);

            $response = Utils::curlRequestWithHeaders($url, $header);

            $path = public_path() . '/sku_master.json';
            $response = json_decode(file_get_contents($path));

            //$response = json_decode($response);
            if ($response->transactionReferenceNumber != "NA") {
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

                $acknowledge_url = "https://lf-gateway-stage.awsvodev.youngliving.com/inventory/skumasters/S20220618063455/103434";

                $acknowledge_response = Utils::httpPut($acknowledge_url, $header);

                DB::commit();

                return response()->json([
                    "success" => true,
                    "message" => 'SKU Masters was successfully synced!',    
                    "transactionType"=> $response->transactionType,
                    "transactionReferenceNumber"=> $response->transactionReferenceNumber,
                    "acknowledge_response" => $acknowledge_response,
                    "itemCount" => count($newly_inserted),
                    "newly_inserted" => $newly_inserted
                ], 200);
            }
            else {

            }
 
        } catch (\Exception $e) {

            DB::rollback();

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

            $header = [
                'Authorization: Bearer ' . $token,
            ];
            
            $response = Utils::curlRequestWithHeaders($url, $header);
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

        $header = "Content-type: application/x-www-form-urlencoded\r\n";
        
        $response = Utils::httpRequest($header, "POST", $data, $url);
       
        if ($response && $response->access_token) {
            return $response;
        }
        return json_encode([
            'success' => false,
            'message' => 'Error occured, can\'t get access token.'
        ]);
    }
}
