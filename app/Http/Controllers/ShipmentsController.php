<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\ShipmentLineItem;
use App\Models\LotCode;
use App\Models\HubInventory;
use App\Models\Hub;
use App\Models\ReturnReason;
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

    public function getLineItems($shipmentId) {
        $lineItem = new ShipmentLineItem;
        return $lineItem->getLineItems($shipmentId);
    }

    public function changeStatus($shipmentId, $status, Shipment $shipment)
    {
        $shipment->changeStatus($shipmentId, $status);

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
                    foreach ($sd->itemDetails as $item) {
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
                    }
                    $shipment->shipmentId =  $sd->shipmentId;
                    $shipment->shipCarrier =  $sd->shipCarrier;
                    $shipment->shipMethod =  $sd->shipMethod;
                    $shipment->totalWeight =  $sd->totalWeight;
                    $shipment->freightCharges =  $sd->freightCharges;
                    $shipment->qtyPackages =  $sd->qtyPackages;
                    $shipment->weightUoM =  $sd->weightUoM;
                    $shipment->currCode =  $sd->currCode;

                    $shipment->save();
                }

            }
                return response()->json([
                    'status' =>  'success',
                    'message' => 'Data was saved.'
                ], 200);
        }
    }
}
