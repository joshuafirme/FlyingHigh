<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pickup;
use App\Models\LineItem;
use App\Models\HubInventory;
use App\Models\Hub;
use App\Models\ReturnReason;
use Utils;

class PickupController extends Controller
{
    public function index(Pickup $pickup) 
    {
        $pickups = $pickup->getPickup(10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        return view('pickup.index', compact('pickups', 'hubs', 'reasons'));
    }

    public function getReturnedList(LineItem $line_item) 
    {
        $returned_list = $line_item->getReturnedList(10);
        return view('pickup.returned', compact('returned_list'));
    }

    public function search($status, Pickup $pickup)
    {
        $stat = $this->getStatus($status);
        $sub_title =  $stat->status_text;
        $status_list =  $stat->status_list;
        $key = isset(request()->key) ? request()->key : "";
        $pickups = $pickup->searchPickup($key, $status_list, 10);
        $hubs = Hub::where('status', 1)->get();
        $product_count = Pickup::count('id');
        return view('pickup.index', compact('pickups', 'hubs', 'sub_title', 'status'));
    }

    public function getLineItems($orderId, LineItem $lineItem) 
    {
        return $lineItem->getLineItems($orderId);
    }

    public function fetchPickupData(Pickup $pickup) 
    {
        $path = public_path() . '/pickup.json';
        $data = json_decode(file_get_contents($path));
        if ($pickup->isOrderExists($data->orderId)) {
            return response()->json([
                'status' =>  'success',
                'message' => 'OrderID is already exists.'
            ], 200);
        }
        else {
            foreach ($data->lineItems as $item) {
                $lineItem = new LineItem;
                $lineItem->lineNumber = $item->lineNumber;
                $lineItem->orderId = $item->orderId;
                $lineItem->partNumber = $item->partNumber;
                $lineItem->quantity = $item->quantity;
                $lineItem->name = $item->name;
                $lineItem->lineType = $item->lineType;
                $lineItem->parentKitItem = $item->parentKitItem;
                $lineItem->remarks = $item->remarks;
                $lineItem->pv = $item->pv;
                $lineItem->itemUnitPrice = $item->itemUnitPrice;
                $lineItem->itemExtendedPrice = $item->itemExtendedPrice;
                $lineItem->salesPrice = $item->salesPrice;
                $lineItem->taxableAmount = $item->taxableAmount;
                $lineItem->lineItemTotal = $item->lineItemTotal;
                $lineItem->save();
            }
            $pickup->shipmentId = $data->shipmentId;
            $pickup->customerEmail = $data->customerEmail;
            $pickup->custId = $data->custId;
            $pickup->custName = $data->custName;
            $pickup->shipPhone = $data->shipPhone;
            $pickup->shipName = $data->shipName;
            $pickup->shipAddr1 = $data->shipAddr1;
            $pickup->shipAddr2 = $data->shipAddr2;
            $pickup->shipAddr3 = $data->shipAddr3;
            $pickup->shipAddr4 = $data->shipAddr4;
            $pickup->shipCity = $data->shipCity;
            $pickup->shipState = $data->shipState;
            $pickup->shipZip = $data->shipZip;
            $pickup->shipCountryIso = $data->shipCountryIso;
            $pickup->shipMethod = $data->shipMethod;
            $pickup->shipCarrier = $data->shipCarrier;
            $pickup->batchId = $data->batchId;
            $pickup->contractDate = $data->contractDate;
            $pickup->orderId = $data->orderId;
            $pickup->govInvoiceNumber = $data->govInvoiceNumber;
            $pickup->dateTimeSubmittedIso = $data->dateTimeSubmittedIso;
            $pickup->shippingChargeAmount = $data->shippingChargeAmount;
            $pickup->customerTIN = $data->customerTIN ;
            $pickup->salesTaxAmount = $data->salesTaxAmount;
            $pickup->shippingTaxTotalAmount = $data->shippingTaxTotalAmount;
            $pickup->packageTotal = $data->packageTotal;
            $pickup->orderSource = $data->orderSource;

            $pickup->save();

            return response()->json([
                'status' =>  'success',
                'message' => 'Data was saved.'
            ], 200);
        }
    }

    public function tagAsPickedUp($shipmentId, Pickup $pickup, LineItem $line_item, HubInventory $hub_inv)
    {
        $hub_id = request()->hub_id;

        $orderId = $pickup->getOrderIdByShipmentId($shipmentId);

        $line_items = $line_item->getLineItems($orderId);

        $isAllStockEnough = json_decode($hub_inv->isAllStockEnough($line_items, $hub_id));

        if ($isAllStockEnough->result) {
            foreach ($line_items as $item) {
                // ignore CASH ON DELIVERY SHIP, etc...
                if ($hub_inv->ignoreOtherSKU($item->partNumber)) { 
                    continue; 
                }
                if ($hub_inv->hasStock($item->partNumber, $item->quantity, $hub_id) && $item->status == 0)   {
                    $hub_inv->decrementStock($item->partNumber, $item->quantity, $hub_id);
                    LineItem::where('orderId', $item->orderId)
                        ->where('partNumber', $item->partNumber)
                        ->update([ 'status' => 1 ]);
                }
                else {
                    return response()->json([
                        'success' =>  false,
                        'message' => 'not_enough_stock',
                        'sku_list' => [
                            'sku' => $item->partNumber,
                            'description' => $item->description
                        ]
                    ], 200);
                }
            }
            $status = 1;
            $pickup->changeStatus($shipmentId, $status, $hub_id);
        }
        else {
              return response()->json([
                'success' =>  false,
                'message' => 'not_enough_stock',
                'sku_list' => $isAllStockEnough->sku_list
            ], 200);
        }

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function tagOneAsPickedUp() {
        $sku = request()->sku;
        $orderId = request()->orderId;
        $qty = request()->qty;
        $hub_id = request()->hub_id;

        $hub_inv = new HubInventory;

        if ($sku && $orderId && $hub_id && $qty) {
            if ($hub_inv->hasStock($sku, $qty, $hub_id))   {
                $hub_inv->decrementStock($sku, $qty, $hub_id);
                LineItem::where('orderId', $orderId)
                    ->where('partNumber', $sku)
                    ->update([ 'status' => 1 ]);

                LineItem::where('orderId', $orderId)
                    ->where('partNumber', $sku)
                    ->update([ 'status' => 1 ]);

                return response()->json(['success' => true], 200);
            }
            else {
                return response()->json([
                        'success' =>  false,
                        'message' => 'not_enough_stock',
                    ], 200); 
                    
            }
        }
        return response()->json(['success' => false], 200);

    }

    public function tagAsReturned() {
        $sku = request()->sku;
        $orderId = request()->orderId;
        $qty = request()->qty;
        $reason = request()->reason;

        if ($sku && $orderId && $qty && $reason) {
            LineItem::where('orderId', $orderId)
            ->where('partNumber', $sku)
            ->update([ 
                'qty_returned' => $qty,
                'return_reason' => $reason,
                'status' => 2 
            ]);

            return response()->json(['success' => true], 200);
        }
        
        return response()->json(['success' => false], 200);
    }


    public function changeStatus($shipmentId, $status, Pickup $pickup)
    {
        $pickup->changeStatus($shipmentId, $status);

        return response()->json([
            'success' => true
        ], 200);
    }
}
