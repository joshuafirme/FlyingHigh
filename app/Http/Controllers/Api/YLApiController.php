<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\PurchaseOrder;
use App\Models\POLineItems;
use App\Models\Inventory;
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
            
            $token = Utils::getAccessToken($request)->access_token;
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
           
            foreach ($request->order_numbers as $key => $order_no) {
                $orders = PurchaseOrder::where('transactionReferenceNumber', $transaction_ref)
                ->where('orderNumber', $order_no)
                ->update([
                    'receiptDate' => $request->received_dates[$key],
                    'status' => 1
                ]);

                $this->transferByOrderNo($order_no, $request->received_dates[$key]);
            }

            $orders = PurchaseOrder::select('orderNumber', 'orderType', 'receiptDate')
                ->where('transactionReferenceNumber', $transaction_ref)
                ->whereIn('orderNumber', $request->order_numbers)
                ->get();

            if (count($orders) < 1) {
                return response()->json([
                    "success" => false, 
                    "transactionReferenceNumber"=> $transaction_ref,
                    "message" => "PurchaseOrderReceiptHeaders is empty",
                ], 200);
            }

            $token = Utils::getAccessToken($request)->access_token;

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
                        "ReceiptDate" => $order->receiptDate,
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

            //return $data;
            
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

    public function transferByOrderNo($orderNumber, $receiptDate) 
    {
        $line_items = POLineItems::where('orderNumber', $orderNumber)->get();

        foreach ($line_items as $item) {
            
            $lot = new Inventory; 
            $product = new Product; 
            
            if ( ! $product->isItemExists($item)) {
                $product->itemNumber = $item->itemNumber;
                $product->baseUOM = $item->unitOfMeasure;
             //   $product->lot_code = $item->lotNumber;
                $product->productDescription = $item->description;
                $product->save();
            }

            if ($lot->isLotCodeExists(
                $item->itemNumber, $item->lotNumber, $item->unitOfMeasure)) {
                
                Inventory::where([
                    ['sku', '=', $item->itemNumber],
                    ['lot_code', '=', $item->lotNumber],
                    ['uom', '=', $item->unitOfMeasure],
                ])->increment('stock', $item->quantityOrdered);
            }
            else {
                $lot->sku = $item->itemNumber;
                $lot->lot_code = $item->lotNumber; 
                $lot->stock = $item->quantityOrdered;
                $lot->expiration = $item->lotExp;
                $lot->location = $item->location;
                $lot->uom = $item->unitOfMeasure;
                $lot->palletId = $item->palletId;
                $lot->save();
            }
        }
    }

    public function postPurchaseOrders(Request $request) 
    {
        try {

            DB::beginTransaction();

            $token = Utils::getAccessToken($request)->access_token;

            $url = "https://lf-gateway-stage.awsvodev.youngliving.com/inventory/asn";

            $header = [
                'Authorization: Bearer ' . $token,
            ];
            
            $response = Utils::curlRequestWithHeaders($url, $header);
            $response = json_decode($response);

            $saved_po = 0;
            $duplicates = [];
            foreach ($response->purchaseOrderHeaders as $purchaseOrder) { 
                $po = new PurchaseOrder;
                
                if ($po->isPurchaseOrderExists($purchaseOrder->orderNumber)) {
                    array_push($duplicates, $purchaseOrder->orderNumber);
                }
                else {
                    $po->savePurchaseOrders($purchaseOrder, $response->transactionReferenceNumber);

                    foreach ($purchaseOrder->purchaseOrderDetails as $lineItems) {
                        $poli = new POLineItems;
                        $poli->saveItem($lineItems, $purchaseOrder);
                    }
                    $saved_po++;
                }
            }
            
            if ($saved_po > 0) {
                $t = new Transaction;
                $t->saveTransaction($response);
            }

            DB::commit();
            $warnings = [];
            if (count($duplicates) > 0) {
                $warnings = [
                    "already_exists_order_numbers" => $duplicates
                ];
            }

            return response()->json([
                "success" => true,    
                "transactionType"=> $response->transactionType,
                "transactionReferenceNumber"=> $response->transactionReferenceNumber,
                "warnings" => $warnings
            ], 200);

        } catch (\Exception $e) {
            
            DB::rollback();

            return response()->json([
                "success" => false,
                "exceptionMessage" => $e->getMessage(),    
            ], 200);
        }
    }

    
    public function sendStockStatus(Request $request) {

        try {
            DB::beginTransaction();

            $token = Utils::getAccessToken($request)->access_token;
            $transaction_id = Transaction::where('transactionType', '3P')->latest()->value('id');
            $transaction_prefix = 'P' . date('Ymd');
            
            $transaction_ref = $transaction_prefix . ($transaction_id + 10);
            
            $lot_codes = Inventory::get();
          
            $body = [];
            $body['StockStatusDate'] = date("Y-m-d\TH:i:s", time());
            $body['TransactionReferenceNumber'] = $transaction_ref;
            $body['TransactionType'] = '3P';
            $body['MessageCount'] = count($lot_codes);
            $body['Sender'] = 4803;
            $body['Receiver'] = 1001;
            $body['StockStatusDetails'] = [];
 
            if (count($lot_codes) < 1) {
                return response()->json([
                    "success" => false, 
                    "transactionReferenceNumber"=> $transaction_ref,
                    "message" => "No data found.",
                ], 200);
            }

            foreach ($lot_codes as $item) {
                array_push($body['StockStatusDetails'], [
                    "ItemNumber" => $item->sku,
                    "Location" => $item->location == 'WS' ? 'AV' : $item->location,
                    "LotStatus" => "",
                    "QuantityOnHand" => $item->stock,
                    "UnitOfMeasure" => $item->uom,
                    "LotExpiration" => $item->expiration,
                    "LotNumber" => $item->lot_code,
                ]);
            }

            $url = "https://lf-gateway-stage.awsvodev.youngliving.com/inventory/stockstatus";
            
            $headers = ["Content-Type: application/json", "Authorization: Bearer {$token}"];
             
            $confirmation_response = Utils::curlPost($url, $headers, $body);
  
            if ($confirmation_response->message == "Ok") {
                $trans = [];
                $trans['transactionReferenceNumber'] = $transaction_ref;
                $trans['transactionType'] = "3P";
                $trans['messageCount'] = count($lot_codes);
                $trans['sender'] = 4803;
                $trans['receiver'] = 1001;
                $t = new Transaction;
              
                $t->saveTransaction(json_decode(json_encode($trans)));

                DB::commit(); 
                
                return response()->json([
                    "success" => true, 
                    "transactionReferenceNumber"=> $transaction_ref,
                    "confirmation_response" => $confirmation_response,
                ], 200);
            }
            else {
                return response()->json([
                    "success" => false, 
                    "transactionReferenceNumber"=> $transaction_ref,
                    "confirmation_response" => $confirmation_response,
                ], 200);
            }
            
        } catch (\Exception $e) {
            
            DB::rollback();

            return response()->json([
                "success" => false,
                "exceptionMessage" => $e->getMessage(),    
            ], 200);
        }
    }
    

}
