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

                $headers = ["Content-Type: application/json", "Authorization: Bearer {$token}"];
                
                $acknowledge_response = Utils::curlPut($acknowledge_url, $headers);

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

    public function confirmPurchaseOrders($transaction_ref, Request $request) {

        try {

            DB::beginTransaction();

            $orders = PurchaseOrder::select('orderNumber', 'orderType', 'receiptDate')
            ->where('transactionReferenceNumber', $transaction_ref)
            ->where('status', 1)->get();

            if (count($orders) < 1) {
                return response()->json([
                    "success" => false, 
                    "transactionReferenceNumber"=> $transaction_ref,
                    "message" => "PurchaseOrderReceiptHeaders is empty",
                ], 200);
            }

            $token = $this->getAccessToken($request)->access_token;

            $data = [];
            $data['TransactionType'] = '3P';
            $data['TransactionReferenceNumber'] = $transaction_ref;
            $data['MessageCount'] = count($orders);
            $data['Sender'] = 4803;
            $data['Receiver'] = 1001;
            $data['PurchaseOrderReceiptHeaders'] = [];

            foreach ($orders as $order) {
                
                $PurchaseOrderReceiptDetails = [];

                $line_items = POLineItems::where('orderNumber', $order->orderNumber)->get();

                foreach ($line_items as $item) {
                    array_push($PurchaseOrderReceiptDetails, [
                        "BillOfLading" => $item->iOfLading ? $item->iOfLading : "N/A",
                        "ItemNumber" => $item->itemNumber,
                        "UnitOfMeasure" => $item->unitOfMeasure,
                        "QtyRcdGood" => $item->quantityOrdered,
                        "QtyRcdBad" => 0,
                        "RcvComments" => "",
                        "PalletId" => $item->palletId ? $item->palletId : "N/A",
                        "LineNumber" => $item->lineNumber,
                        "LotNumber" => $item->lotNumber,
                        "LotExpiration" => $item->lotExp,
                        "ReceiptDate" => "2022-03-21T12:00:00",
                        "Location" => $item->location
                    ]);
                }

                $PurchaseOrderReceiptHeaders = [
                    'OrderNumber' => $order->orderNumber,
                    'OrderType' => $order->orderType,
                    'PurchaseOrderReceiptDetails' => $PurchaseOrderReceiptDetails
                ];
                array_push($data['PurchaseOrderReceiptHeaders'], $PurchaseOrderReceiptHeaders);
            }

            $url = "https://lf-gateway-stage.awsvodev.youngliving.com/inventory/asn";
            
            $headers = ["Content-Type: application/json", "Authorization: Bearer {$token}"];
            
            $confirmation_response = Utils::curlPost($url, $headers, $data);
            
            DB::commit(); 
            
            return response()->json([
                "success" => true, 
                "transactionReferenceNumber"=> $transaction_ref,
                "confirmation_response" => $confirmation_response,
            ], 200);
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

            DB::beginTransaction();

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

            DB::commit();

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
            
            DB::rollback();

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
        
        $response = Utils::httpRequest($header, "POST", http_build_query($data), $url);
       
        if ($response && $response->access_token) {
            return $response;
        }
        return json_encode([
            'success' => false,
            'message' => 'Error occured, can\'t get access token.'
        ]);
    }
}
