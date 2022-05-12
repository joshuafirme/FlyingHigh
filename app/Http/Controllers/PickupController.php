<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pickup;
use App\Models\LineItem;
use App\Models\HubInventory;
use App\Models\Hub;
use App\Models\ReturnReason;
use App\Models\LotCode;
use App\Models\Shipment;
use App\Models\ShipmentLineItem;
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

    public function search(Pickup $pickup)
    {
        $key = isset(request()->key) ? request()->key : "";
        $pickups = $pickup->searchPickup($key, 10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        return view('pickup.index', compact('pickups', 'hubs','reasons'));
    }

    public function getLineItems($orderId, LineItem $lineItem) 
    {
        return $lineItem->getLineItems($orderId);
    }

    public function fetchPickupData() 
    {
        $path = public_path() . '/pickup.json';
        $data = json_decode(file_get_contents($path));

        if ($data->shipmentsToShip) {
            foreach ($data->shipmentsToShip as $shipment) {
                $pickup = new Pickup;
                if ($pickup->isOrderExists($shipment->orderId)) {
                    return response()->json([
                        'status' =>  'success',
                        'message' => 'OrderID is already exists.'
                    ], 200);
                }
                else {
                    foreach ($shipment->lineItems as $item) {
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
                    $pickup->shipmentId = $shipment->shipmentId;
                    $pickup->customerEmail = $shipment->customerEmail;
                    $pickup->custId = $shipment->custId;
                    $pickup->custName = $shipment->custName;
                    $pickup->shipPhone = $shipment->shipPhone;
                    $pickup->shipName = $shipment->shipName;
                    $pickup->shipAddr1 = $shipment->shipAddr1;
                    $pickup->shipAddr2 = $shipment->shipAddr2;
                    $pickup->shipAddr3 = $shipment->shipAddr3;
                    $pickup->shipAddr4 = $shipment->shipAddr4;
                    $pickup->shipCity = $shipment->shipCity;
                    $pickup->shipState = $shipment->shipState;
                    $pickup->shipZip = $shipment->shipZip;
                    $pickup->shipCountryIso = $shipment->shipCountryIso;
                    $pickup->shipMethod = $shipment->shipMethod;
                    $pickup->shipCarrier = $shipment->shipCarrier;
                    $pickup->batchId = $shipment->batchId;
                    $pickup->contractDate = $shipment->contractDate;
                    $pickup->orderId = $shipment->orderId;
                    $pickup->govInvoiceNumber = $shipment->govInvoiceNumber;
                    $pickup->dateTimeSubmittedIso = $shipment->dateTimeSubmittedIso;
                    $pickup->shippingChargeAmount = $shipment->shippingChargeAmount;
                    $pickup->customerTIN = $shipment->customerTIN ;
                    $pickup->salesTaxAmount = $shipment->salesTaxAmount;
                    $pickup->shippingTaxTotalAmount = $shipment->shippingTaxTotalAmount;
                    $pickup->packageTotal = $shipment->packageTotal;
                    $pickup->orderSource = $shipment->orderSource;

                    $pickup->save();
                }

            }
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

    public function tagAsReturned(LotCode $lc) {
        $sku = request()->sku;
        $lot_code = request()->lot_code;
        $orderId = request()->orderId;
        $qty = request()->qty;
        $reason = request()->reason;
        $rma_number = request()->rma_number;

        if ($sku && $orderId && $qty && $reason && $rma_number) {

            $lc->incrementStock($sku, $lot_code, $qty);

            LineItem::where('orderId', $orderId)
            ->where('partNumber', $sku)
            ->update([ 
                'lot_code' => $lot_code,
                'qty_returned' => $qty,
                'return_reason' => $reason,
                'rma_number' => $rma_number,
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

    public function doShip(Request $request, Pickup $pickup, LineItem $line_item, LotCode $lc)
    {
        $status = 1;
        $line_items = $line_item->getLineItems($request->orderId);
      
        if (count($line_items) > 0) {
                $shipment = new Shipment;
                if ($shipment->isShipmentExists($request->shipmentId)) {
                    return response()->json([
                        'success' =>  false,
                        'message' => 'Shipment ID is already exists.'
                    ], 200);
                }
                else {
                    
                    $pickup_details = $pickup->getOrderDetails($request->shipmentId);
                    $shipment->sender = "4803";
                    $shipment->receiver = $request->receiver;
                    $shipment->shipmentId =  $request->shipmentId;
                    $shipment->shipCarrier =  $pickup_details->shipCarrier ? $pickup_details->shipCarrier : "N/A";
                    $shipment->shipMethod =  $pickup_details->shipMethod;
                    $shipment->totalWeight =  $request->totalWeight;
                    $shipment->freightCharges =  $request->freightCharges;
                    $shipment->qtyPackages =  $request->qtyPackages;
                    $shipment->weightUoM =  $request->weightUoM;
                    // shipped
                    $shipment->status =  1;
                    $shipment->currCode =  "PHP";
                    $shipment->save();

                    
                    foreach ($line_items as $ctr => $item) {
                        if ($item->lineType == "PN" || $item->lineType == "N") {
                            continue;
                        }
                        $lineItem = new ShipmentLineItem;
                        $lineItem->orderId = $item->orderId;
                        $lineItem->shipmentId = $request->shipmentId;
                        $lineItem->orderLineNumber = $item->lineNumber;
                        $lineItem->partNumber = $item->partNumber;
                        $lineItem->trackingNo = $request->trackingNo;
                        $lineItem->qtyOrdered = $item->quantity;
                        $lineItem->qtyShipped = $item->quantity;
                        $lineItem->reasonCode = "";
                        $lineItem->shipDateTime = date('Y-m-d') . 'T' . date('h:m:s');
                        $lineItem->lotNumber = $request->lot_code[$ctr];
                        $lineItem->save();

                        $lc->decrementStock($item->partNumber,$request->lot_code[$ctr],$item->quantity);
                    }
                }
        }

        $pickup->changeStatus($request->shipmentId, $status);

        return response()->json([
            'success' => true
        ], 200);
    }

    public function generateSalesInvoice($shipmentId, $orderId) 
    {
        $order = new Pickup;
        $line_item = new LineItem;

        $order_details = $order->getOrderDetails($shipmentId);
        $line_items = $line_item->getLineItems($orderId);
        if (request()->type == "invoice") {
            $output = $this->renderInvoice($order_details, $line_items, $orderId);
        }
        else if (request()->type == "delivery_receipt"){
            $output = $this->renderDelivery($order_details, $line_items, $orderId);
        }
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'portrait');
    
        return $pdf->stream('test.pdf');
    }

    public function renderInvoice($order_details, $line_items, $orderId) 
    {
        $output = $this->invoiceStyle();
        $output .= 
        '<div class="text-left">
            ';
            $output .= $this->getInvoiceHeader("Sales Invoice");
            $output .= '
            <hr class="ml-2 mr-2">
            <div class="text-info float-right mr-100">
                Sold to: <span>' . $order_details->custName . '</span> <br>
                Address: <span>' . $order_details->shipAddr1 . '</span> <br>
                TIN: <span>' . $order_details->customerTIN . '</span> <br>
            </div>
            <div class="text-info ml-1">
                Order Number: <span>' . $orderId . '</span> <br>
                Order Date: <span>' . date('d-M-Y', strtotime($order_details->dateTimeSubmittedIso)) . '</span> <br>
                Member ID: <span>' . $order_details->custId . '</span> <br>
            </div>
            <table width="100%" style="border-collapse:collapse;" class="mt-2">
                <thead>
                    <th>Qty</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Unit PV</th>
                    <th>Unit Price</th>
                    <th>VAT/Item</th>
                    <th>With VAT</th>
                    <th>Total Cost</th>
                </thead>
                <tbody>
                    ';
                $total_amount = 0;
                $order_subtotal = 0;
                foreach ($line_items as $item) {

                    if ($item->lineType == "PN" || $item->lineType == "N") {
                        continue;
                    }

                    $with_vat = Utils::getWithVAT($item->itemUnitPrice, $item->pv);
                    $total_cost = Utils::getTotalCost($with_vat, $item->quantity);

                    $total_amount += $total_cost;
                    $order_subtotal += $item->itemUnitPrice * $item->quantity;
                    $output .= '
                    <tr>
                        <td class="text-center">' . $item->quantity . '</td>
                        <td class="text-center">' . $item->partNumber . '</td>
                        <td class="text-center">' . $item->name . '</td>
                        <td class="text-center">' . Utils::toFixed($item->pv) . '</td>
                        <td class="text-center">Php ' . Utils::toFixed($item->itemUnitPrice) . '</td>
                        <td class="text-center">Php ' . Utils::getTaxPerItem($item->itemUnitPrice) . '</td>
                        <td class="text-center">Php ' . $with_vat . '</td>
                        <td class="text-right">Php ' . $total_cost . '</td>
                    </tr>';
                }
                $vatable_sales = $order_subtotal + $order_details->shippingChargeAmount;
                $vat = Utils::toFixed($vatable_sales * 0.12);
                $total_amount_due = $vatable_sales + $vat;
                $output .= '
                </tbody>
            </table>
            <table width="100%" style="border-collapse:collapse;" class="mt-3">
                <thead>
                    <th></th>
                    <th class="text-left">Total<span class="float-right">Php ' . Utils::toFixed($total_amount) . '</span></th>
                </thead>  
                <tbody>
                    <tr>
                        <td class="text-left">Order Subtotal</td>
                        <td><span class="float-right">' . Utils::toFixed($order_subtotal) . '</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">Package Shipping/Handling</td>
                        <td><span class="float-right">Php ' . $order_details->shippingChargeAmount . '</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">Vatable Sales</td>
                        <td><span class="float-right">Php ' . $vatable_sales . '</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">VAT Exempt Sales</td>
                        <td><span class="float-right"></span></td>
                    </tr>
                    <tr>
                        <td class="text-left">Zero-rated sales</td>
                        <td><span class="float-right"></span></td>
                    </tr>
                    <tr>
                        <td class="text-left">12% VAT</td>
                        <td><span class="float-right">Php ' . $vat .'</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">&shy;</td>
                        <th><span class="float-right"><span class="mr-2">TOTAL AMOUNT DUE:</span> Php ' . $total_amount_due . '</span></th>
                    </tr>
                </tbody>  
            </table>
        </div>';

        $output .= $this->getInvoiceFooter();
    
        return $output;
    }

    public function renderDelivery($order_details, $line_items, $orderId) 
    {
        $output = $this->invoiceStyle();
        $output .= 
        '<div class="text-left">
            ';
            $output .= $this->getInvoiceHeader("Delivery Receipt");
            $output .= '
            <hr class="ml-2 mr-2">
            <div class="text-info ml-1 float-right">
                Order Number: <span>' . $orderId . '</span> <br>
                Order Date: <span>' . date('d-M-Y', strtotime($order_details->dateTimeSubmittedIso)) . '</span> <br>
                Member ID: <span>' . $order_details->custId . '</span> <br>
            </div>
            <div class="text-info mr-100">
                Delivered to: <span>' . $order_details->custName . '</span> <br>
                Address: <span>' . $order_details->shipAddr1 . '</span> <br>
                TIN: <span>' . $order_details->customerTIN . '</span> <br>
            </div>
            <table width="100%" style="border-collapse:collapse;" class="mt-2">
                <thead>
                    <th>Qty</th>
                    <th>Code</th>
                    <th>Description</th>
                </thead>
                <tbody>
                    ';
                foreach ($line_items as $item) {

                    if ($item->lineType == "PN" || $item->lineType == "N") {
                        continue;
                    }
                    $output .= '
                    <tr>
                        <td class="text-center">' . $item->quantity . '</td>
                        <td class="text-center">' . $item->partNumber . '</td>
                        <td class="text-center">' . $item->name . '</td>
                    </tr>';
                }
                $output .= '
                </tbody>
            </table>
        </div>';

        $output .= $this->getInvoiceFooter();
    
        return $output;
    }

    function getInvoiceHeader($title) {
        return '<img class="logo" src="' . public_path() . "/assets/yl_logo.png" . '" width="173" height="55" alt="" />
            <div class="head-2">' . $title . '</div>
            <div class="head-1">YOUNG LIVING PHILIPPINES LLC</div>
            <div class="head-3"> YOUNG LIVING PHILIPPINES LLC - PHILIPPINE BRANCH</div>
            <div class="text-info ml-1">
                Unit G07, G08, G09 & 12th Floor, <br>
                Twenty-Five Seven McKinley Building, <br>
                25th Street corner 7th Avenue, Bonifacio Global City, <br>
                Taguig City, Metro Manila <br>
                VAR REG TIN: 009-915-795-000 <br>
                <small>Business Name/Style: Other Wholesaling</small>
            </div>';
    }

    function getInvoiceFooter() {
        $output = 
            '<table width="100%" style="border-collapse:collapse;" class="mt-3">
                <th class="text-left">Powered by</th>
                <th class="text-left">Received by</th> 
            </table>

            <div class="text-info mt-3">
                BIR Accreditation number: <br>
                Date of Accreditation:  <br>
                Acknowledgement Certificate No.: <br>
                <span class="mr-1">Date issued: mm/dd/yy </span> Valid Until: mm/dd/yy <br>
                Approved Series No.: 
            </div>
        ';

        $output .= '<div class="phrase mt-5 text-center">THIS INVOICE/RECEIPT SHALL BE VALID FOR FIVE (5) YEARS FROM THE DATE OF THE PERMIT TO USE.</div>';
        return $output;
    }

    function invoiceStyle() {
        return "<style>
            * { font-family: Calibri, sans-serif; }
            .text-left { text-align:left; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .phrase { font-size: 21px; }
            .head-3 { font-size: 14px; }
            .head-2 { font-size: 16px; font-style:bold; }
            .head-1 { font-size: 21px; font-style:bold; }
            .logo { float:right; }
            .text-info { 
                line-height: 20px; 
                margin-top: 3px; 
                font-size: 14px;
            }
            .ml-1 { margin-left:10px; }
            .ml-2 { margin-left:20px; }
            .mr-2 { margin-right:20px; }
            .mr-100 { margin-right:100px; }
            .mt-2 { margin-top:20px; }
            .mt-3 { margin-top:30px; }
            .mt-5 { margin-top:50px; }
            .float-right { float:right; }
            table { font-size: 11px; }
            td, th { padding: 5px; }
            th { border: 1px solid; }
            .border-solid { border: 1px solid; }
        </style>";
    }
}
