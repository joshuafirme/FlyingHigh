<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pickup;
use App\Models\LineItem;
use App\Models\HubInventory;
use App\Models\Hub;
use Utils;

class PickupController extends Controller
{
    public function index() 
    {
        $pickups = Pickup::where('status', 0)->paginate(10);
        $hubs = Hub::where('status', 1)->get();
        return view('pickup.index', compact('pickups', 'hubs'));
    }

    public function pickedUpList() 
    {
        $pickups = Pickup::where('pickups.status', 1)
            ->select('shipmentId','shipmentId','customerEmail','custName','pickups.status',
            'batchId','orderId','pickups.updated_at','hubs.name as hub')
            ->leftJoin('hubs', 'hubs.id', '=', 'pickups.hub_id')
            ->paginate(10);
            
        $hubs = Hub::where('status', 1)->get();

        return view('pickup.pickedup-list', compact('pickups', 'hubs'));
    }

    public function getLineItems($orderId, LineItem $lineItem) 
    {
        return $lineItem->getLineItems($orderId);
    }

    public function fetchPickupData(Pickup $pickup) 
    {
        $path = public_path() . '/pickup.json';
        $data = json_decode(file_get_contents($path));
      //  dd($data->lineItems);
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

        $pickup->tagAsPickedUp($shipmentId, $hub_id);

        $orderId = $pickup->getOrderIdByShipmentId($shipmentId);

        $line_items = $line_item->getLineItems($orderId);

        foreach ($line_items as $item) {
            $hub_inv->decrementStock($item->partNumber, $item->quantity, $hub_id);
        }

        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
