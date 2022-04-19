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
    public function index($status, Pickup $pickup) 
    {
        $stat = $this->getStatus($status);
        $sub_title =  $stat->status_text;
        $status_list =  $stat->status_list;
        $pickups = $pickup->getPickup($status_list,10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        return view('pickup.index', compact('pickups', 'hubs', 'sub_title', 'status', 'reasons'));
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

    public function getStatus($status) {
        $sub_title = "";
        $status_list = [];
        if ($status == 0) {
            $status_list = [0,2];
            $sub_title = 'For Pick-up';
        }
        else if ($status == 1) {
            $sub_title = 'Picked-up';
            array_push($status_list, $status);
        }
        else if ($status == 3) {
            $sub_title = 'Returned';
            array_push($status_list, $status);
        }
        return json_decode(json_encode([
            'status_text' => $sub_title,
            'status_list' => $status_list
        ]));
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
                if ($hub_inv->hasStock($sku, $item->quantity))   {
                    $hub_inv->decrementStock($item->partNumber, $item->quantity, $hub_id);
                }
                else {
                    return response()->json([
                        'success' =>  false,
                        'message' => 'not_enough_stock',
                        'sku_list' => $isAllStockEnough->sku
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
                'sku_list' => $isAllStockEnough->sku
            ], 200);
        }

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function tagAsOverDue($shipmentId, Pickup $pickup)
    {
        $status = 2;
        $pickup->changeStatus($shipmentId, $status);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function tagAsReturned($shipmentId, Pickup $pickup)
    { 
        $status = 3;
        $reason = request()->reason;
        $pickup->changeStatus($shipmentId, $status, $reason);

        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
