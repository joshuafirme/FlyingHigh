<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\ShipmentLineItem;
use App\Models\LotCode;
use App\Models\HubInventory;
use App\Models\Hub;
use App\Models\ReturnReason;
use App\Models\Pickup;
use Utils;

class ShipmentsController extends Controller
{
    public function index(Shipment $shipment) 
    {
        $shipments = $shipment->getShipment(10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        return view('shipments.index', compact('shipments', 'hubs', 'reasons'));
    }

    public function search(Shipment $shipment)
    {
        $key = isset(request()->key) ? request()->key : "";
        $shipments = $shipment->search($key, 10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        return view('shipments.index', compact('shipments', 'hubs', 'reasons'));
    }

    public function getLineItems($shipmentId) {
        $lineItem = new ShipmentLineItem;
        return $lineItem->getLineItems($shipmentId);
    }

    public function doShip($shipmentId)
    {
        $shipment = new Shipment; 
        $shipment_line_items = new ShipmentLineItem; 
        $lc = new LotCode;
        $pickup = new Pickup;
        $line_items = $this->getLineItems($shipmentId);

        $status = 1;

        // COMMENTED TEMPORARY
        //$validate_stock = json_decode($lc->validateStock($line_items));

       // if ($validate_stock->result) {

            foreach ($line_items as $item) {
                if ($item->lineType == "PN" || $item->lineType == "N") {
                    continue;
                }
                $lc->decrementStock($item->partNumber, $item->lotNumber, $item->qtyShipped);
                $shipment_line_items->changeStatus($shipmentId, $item->partNumber, $status);
            }

            $shipment->changeStatus($shipmentId, $status);
            $pickup->changeStatus($shipmentId, $status);

            return response()->json([
                'success' => true
            ], 200);
      //  }
      /*  else {
            return response()->json([
                'success' => false,
                'sku_list' => $validate_stock->sku_list
            ], 200);
        }*/
    }

    public function doDelivered($shipmentId) {
        $shipment = new Shipment; 
        $shipment_line_items = new ShipmentLineItem; 
        $lc = new LotCode;
        $pickup = new Pickup;
        $line_items = $this->getLineItems($shipmentId);

        $status = 2;

        foreach ($line_items as $item) {
            if ($item->lineType == "PN" || $item->lineType == "N") {
                continue;
            }
            $shipment_line_items->changeStatus($shipmentId, $item->partNumber, $status);
        }

        $shipment->changeStatus($shipmentId, $status);
        $pickup->changeStatus($shipmentId, $status);

        return response()->json([
            'success' => true
        ], 200);      
    }

    public function doPickup() {
        $shipment = new Shipment; 
        $shipment_line_items = new ShipmentLineItem; 
        $lc = new LotCode;
        $pickup = new Pickup;

        $shipmentId = request()->shipmentId;
        $orderId = request()->orderId;
        $qtyShipped = request()->qtyShipped;

        $status = 3;

        $shipment_line_items->doPickUp(request(), $status);

        return response()->json([
            'success' => true
        ], 200);      
    }

    public function changeStatus($shipmentId, $status) {
        $shipment = new Shipment; 
        $pickup = new Pickup;
        $shipment->changeStatus($shipmentId, $status);
        $pickup->changeStatus($shipmentId, $status);

        return response()->json([
            'success' => true
        ], 200);      
    }

    public function fetchShipments() 
    {
        $path = public_path() . '/shipments.json';
        $data = json_decode(file_get_contents($path));

        if ($data->shipmentDetails) {
            foreach ($data->shipmentDetails as $sd) {
                $shipment = new Shipment;
                if ($shipment->isShipmentExists($sd->shipmentId)) {
                    return response()->json([
                        'status' =>  'success',
                        'message' => 'Shipment ID is already exists.'
                    ], 200);
                }
                else {

                    $shipment->sender = $data->sender;
                    $shipment->receiver = $data->receiver;

                    $shipment->shipmentId =  $sd->shipmentId;
                    $shipment->shipCarrier =  $sd->shipCarrier;
                    $shipment->shipMethod =  $sd->shipMethod;
                    $shipment->totalWeight =  $sd->totalWeight;
                    $shipment->freightCharges =  $sd->freightCharges;
                    $shipment->qtyPackages =  $sd->qtyPackages;
                    $shipment->weightUoM =  $sd->weightUoM;
                    $shipment->currCode =  $sd->currCode;

                    $shipment->save();

                    
                    foreach ($sd->itemDetails as $key => $item) {
                        $lineItem = new ShipmentLineItem;
                        $lineItem->orderId = $item->orderId;
                        $lineItem->shipmentId = $sd->shipmentId;
                        $lineItem->orderLineNumber = $item->orderLineNumber;
                        $lineItem->partNumber = $item->partNumber;
                        $lineItem->trackingNo = $item->trackingNo;
                        $lineItem->qtyOrdered = $item->qtyOrdered;
                        $lineItem->qtyShipped = $item->qtyShipped;
                        $lineItem->reasonCode = $item->reasonCode;
                        $lineItem->shipDateTime = $item->shipDateTime;
                        $lineItem->lotNumber = $item->lotNumber;
                        $lineItem->save();

                        if ($key == count($sd->itemDetails) - 1) {
                            $shipment->trackingNo =  $item->trackingNo;
                            $shipment->save();
                        }
                    }
                }

            }
                return response()->json([
                    'status' =>  'success',
                    'message' => 'Data was saved.'
                ], 200);
        }
    }
}
