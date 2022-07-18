<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\LineItem;
use App\Models\HubInventory;
use App\Models\Hub;
use App\Models\ReturnReason;
use App\Models\LotCode;
use App\Models\Shipment;
use App\Models\ShipmentLineItem;
use App\Models\Attribute;
use Utils;

class OrderController extends Controller
{
    public function index(Order $orders) 
    {
        $orders = $orders->getOrder(10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        $invoice = new Invoice;
        return view('orders.index', compact('orders', 'hubs', 'reasons', 'invoice'));
    }

    public function filterPaginate(Order $orders) 
    {
        $orders = $orders->filterPaginate(10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        $invoice = new Invoice;
        return view('orders.index', compact('orders', 'hubs', 'reasons', 'invoice'));
    }

    public function getReturnedList(LineItem $line_item) 
    {
        $returned_list = $line_item->getReturnedList(10);
        return view('orders.returned', compact('returned_list'));
    }

    public function search(Order $orders)
    {
        $key = isset(request()->key) ? request()->key : "";
        $orders = $orders->searchOrder($key, 10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        $invoice = new Invoice;
        return view('orders.index', compact('orders', 'hubs','reasons', 'invoice'));
    }

    public function getOneOrder($shipmentId, LineItem $lineItem) 
    {
        $order_details = Order::where('shipmentId', $shipmentId)->first();

        if ( ! isset($order_details->orderId)) {        
            return response()->json([
                'success' => false,
                'message' => "Shipment not found."
            ], 200);
        }
        $lineItems =  $lineItem->getLineItems($order_details->orderId);
        
        return response()->json([
            'success' => true,
            'order_details' => $order_details,
            'lineItems' => $lineItems
        ], 200);
    }
    
    public function getLineItems($orderId, LineItem $lineItem) 
    {
        return $lineItem->getLineItems($orderId);
    }

    public function fetchOrdersData() 
    {
        $path = public_path() . '/payloads/182223459.json';
        $data = json_decode(file_get_contents($path));

        if ($data->shipmentsToShip) {
            foreach ($data->shipmentsToShip as $shipment) {
                $orders = new Order;
                if ($orders->isOrderExists($shipment->orderId)) {
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
                    $orders->shipmentId = $shipment->shipmentId;
                    $orders->customerEmail = $shipment->customerEmail;
                    $orders->custId = $shipment->custId;
                    $orders->custName = $shipment->custName;
                    $orders->shipPhone = $shipment->shipPhone;
                    $orders->shipName = $shipment->shipName;
                    $orders->shipAddr1 = $shipment->shipAddr1;
                    $orders->shipAddr2 = $shipment->shipAddr2;
                    $orders->shipAddr3 = $shipment->shipAddr3;
                    $orders->shipAddr4 = $shipment->shipAddr4;
                    $orders->shipCity = $shipment->shipCity;
                    $orders->shipState = $shipment->shipState;
                    $orders->shipZip = $shipment->shipZip;
                    $orders->shipCountryIso = $shipment->shipCountryIso;
                    $orders->shipMethod = $shipment->shipMethod;
                    $orders->shipCarrier = $shipment->shipCarrier;
                    $orders->batchId = $shipment->batchId;
                    $orders->contractDate = $shipment->contractDate;
                    $orders->orderId = $shipment->orderId;
                    $orders->govInvoiceNumber = $shipment->govInvoiceNumber;
                    $orders->dateTimeSubmittedIso = $shipment->dateTimeSubmittedIso;
                    $orders->shippingChargeAmount = $shipment->shippingChargeAmount;
                    $orders->customerTIN = $shipment->customerTIN ;
                    $orders->salesTaxAmount = $shipment->salesTaxAmount;
                    $orders->shippingTaxTotalAmount = $shipment->shippingTaxTotalAmount;
                    $orders->packageTotal = $shipment->packageTotal;
                    $orders->orderSource = $shipment->orderSource;
                    $orders->save();

                    foreach ($shipment->invoices as $invoice_item) {
                        $invoice = new Invoice;
                        $invoice->invoiceType = $invoice_item->invoiceType;
                        $invoice->invoiceDetail = $invoice_item->invoiceDetail;
                        $invoice->shipmentId = $shipment->shipmentId;
                        $invoice->orderId = $shipment->orderId;
                        $invoice->save();
                    }
                }

            }
                return response()->json([
                    'status' =>  'success',
                    'message' => 'Data was saved.'
                ], 200);
        }
    }

    public function tagAsPickedUp($shipmentId, Order $orders, LineItem $line_item, HubInventory $hub_inv)
    {
        $hub_id = request()->hub_id;

        $orderId = $orders->getOrderIdByShipmentId($shipmentId);

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
            $orders->changeStatus($shipmentId, $status, $hub_id);
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
        $orderId = request()->orderId;
        $partNumber = request()->partNumber;

        if ($orderId && $partNumber) {
            ShipmentLineItem::where('orderId', $orderId)
                ->where('partNumber', $partNumber)
                ->update([ 'status' => 1 ]);

            return response()->json(['success' => true], 200);
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


    public function changeStatus($shipmentId, $status, Order $orders)
    {
        $orders->changeStatus($shipmentId, $status);

        return response()->json([
            'success' => true
        ], 200);
    }

    public function doShip(Request $request, Order $orders, LineItem $line_item, LotCode $lc)
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
                    
                    $orders_details = $orders->getOrderDetails($request->shipmentId);
                    $shipment->sender = "4803";
                    $shipment->receiver = $request->receiver;
                    $shipment->shipmentId =  $request->shipmentId;
                    $shipment->shipCarrier =  $orders_details->shipCarrier ? $orders_details->shipCarrier : "N/A";
                    $shipment->shipMethod =  $orders_details->shipMethod;
                    $shipment->totalWeight =  $request->totalWeight;
                    $shipment->freightCharges =  $request->freightCharges;
                    $shipment->qtyPackages =  $request->qtyPackages;
                    $shipment->weightUoM =  $request->weightUoM;
                    $shipment->trackingNo = $request->trackingNo;
                    // shipped
                    $shipment->status =  0;
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
                        $lineItem->qtyShipped = 0;
                        $lineItem->reasonCode = "";
                        $lineItem->shipDateTime = "";
                        $lineItem->lotNumber = "";//$request->lot_code[$ctr];
                        $lineItem->save();

                       // $lc->decrementStock($item->partNumber,$request->lot_code[$ctr],$item->quantity);
                    }
                }
        }

        $orders->changeStatus($request->shipmentId, $status, $request->receiver);

        return response()->json([
            'success' => true
        ], 200);
    }

    public function generateSalesInvoice($shipmentId, $orderId) 
    {
        $order = new Order;
        $line_item = new LineItem;

        $order_details = $order->getOrderDetails($shipmentId);
        $line_items = $line_item->getLineItems($orderId);
        if (request()->type == 2) {
            $output = $this->renderInvoice($order_details, $line_items, $orderId);
        }
        else if (request()->type == 3){
            $output = $this->renderDelivery($order_details, $line_items, $orderId);
        }
        else if (request()->type == 4){
            $output = $this->renderCollection($order_details, $line_items, $orderId);
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
            $output .= $this->getInvoiceHeader("Sales Invoice", $order_details);
            $output .= '
            <table width="100%" style="border-collapse:collapse; margin-top: 47px !important;" class="table-items">
                <thead>
                    <th>Qty</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Unit PV</th>
                    <th>Unit Price</th>
                    <th>VAT /Unit</th>
                    <th>With VAT</th>
                    <th>Total Cost</th>
                </thead>
                <tbody>
                    ';
                $total_amount = 0;
                $order_subtotal = 0;
                foreach ($line_items as $item) {

                    if ($item->lineType == "PN" || $item->lineType == "N" || $item->remarks == "Component") {
                        continue;
                    }

                    $component_txt = "";
                    if ($item->remarks == "Component") {
                        $component_txt = $item->remarks;
                    }

                    // old computation $with_vat = Utils::getWithVAT($item->itemUnitPrice, $item->pv);
                    $with_vat = $item->itemUnitPrice + Utils::getTaxPerItem($item->itemUnitPrice);
                    $total_cost = Utils::getTotalCost($with_vat, $item->quantity);

                    $total_amount += $total_cost;
                    $order_subtotal += $item->itemUnitPrice * $item->quantity;
                    $output .= '
                    <tr>
                        <td class="text-center">' . $item->quantity . '</td>
                        <td>' . $component_txt . " " . $item->partNumber . '</td>
                        <td>' . $item->name . '</td>
                        <td class="text-right">' . number_format(Utils::toFixed($item->pv), 2, '.', ',') . '</td>
                        <td class="text-right"><span class="peso">&#8369;</span> ' . number_format(Utils::toFixed($item->itemUnitPrice), 2, '.', ',') . '</td>
                        <td class="text-right"><span class="peso">&#8369;</span> ' . number_format(Utils::getTaxPerItem($item->itemUnitPrice), 2, '.', ',') . '</td>
                        <td class="text-right"><span class="peso">&#8369;</span>' . number_format($with_vat, 2, '.', ',') . '</td>
                        <td class="text-right"><span class="peso">&#8369;</span> ' . number_format($total_cost, 2, '.', ',') . '</td>
                    </tr>';
                }
                $vatable_sales = $order_subtotal + $order_details->shippingChargeAmount;
                $vat = Utils::toFixed($vatable_sales * 0.12);
                $total_amount_due = $vatable_sales + $vat;
                $total_w_vat = $total_amount;
                $output .= '
                </tbody>
            </table>
            <table width="100%" style="border-collapse:collapse; margin-top: 12px;" class="table-computation">
                <thead>
                    <th></th>
                    <th class="text-left" style="padding: 0px !important; margin: 0px !important;font-size:15px;">Total w/VAT:<span class="float-right"><span class="peso">&#8369;</span> ' . number_format($total_w_vat, 2, '.', ',') . '</span></th>
                </thead>  
                <tbody>
                    <tr>
                        <td class="text-left">Order Subtotal</td>
                        <td><span class="float-right">' . number_format($order_subtotal, 2, '.', ',') . '</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">Package Shipping/Handling</td>
                        <td><span class="float-right"><span class="peso">&#8369;</span> ' . number_format($order_details->shippingChargeAmount, 2, '.', ',') . '</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">Vatable Sales</td>
                        <td><span class="float-right"><span class="peso">&#8369;</span> ' . number_format($vatable_sales, 2, '.', ',') . '</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">VAT Exempt Sales</td>
                        <td><span class="float-right"><span class="peso">&#8369;</span> 0.00</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">Zero-rated sales</td>
                        <td><span class="float-right"><span class="peso">&#8369;</span> 0.00</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">12% VAT</td>
                        <td><span class="float-right"><span class="peso">&#8369;</span> ' . number_format($vat, 2, '.', ',') .'</span></td>
                    </tr>
                    <tr>
                        <td class="text-left">&shy;</td>
                        <th style="padding: 3px !important; margin: 2px !important;font-size: 18px;"><span class="mr-2">TOTAL AMOUNT DUE:</span> <span class="float-right"><span class="peso">&#8369;</span> ' .  number_format($total_amount_due, 2, '.', ',') . '</span></th>
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
            $output .= $this->getInvoiceHeader("Delivery Receipt", $order_details);
            $output .= '
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
                    $component_txt = $item->remarks == "Component" ? '- ' . $item->remarks . " " : "";
                    $output .= '
                    <tr>
                        <td class="text-center">' . $component_txt . $item->quantity . '</td>
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

    public function renderCollection($order_details, $line_items, $orderId) 
    {

        $total_amount_due = $order_details->packageTotal;
        
        $currency  = $total_amount_due > 0 ? "pesos" : "peso"; 
        $invoice = new Invoice;
        $sales_invoice_no = $invoice->getInvoiceNo($order_details->shipmentId, 2);

        $output = $this->invoiceStyle();
        $output .= 
        '<div class="text-left">
            ';
            $output .= $this->getInvoiceHeader("Collection Receipt", $order_details);
            $output .= '
            <div class="text-info " style="margin-top:80px">
            The sum of  <u>' . Utils::convertNumberToWord(number_format((float)$total_amount_due, 2, '.', ''), $currency) . '</u>
            </div>
            <div class="text-info  text-right">(P '.number_format((float)$total_amount_due, 2, '.', ',').')</div>
            <div class="head-2 mb-1"><u>IN PAYMENT OF THE FOLLOWING</u></div>
            <table width="100%" style="border-collapse:collapse;">
                <thead>
                    <th class="head-2">SALES INVOICE NO.</th>
                    <th class="head-2">SALES ORDER NO.</th>
                    <th class="head-2">AMOUNT</th>
                </thead>
                <tbody>
                <tr>
                    <td class="text-info border-l">'.$sales_invoice_no.'</td>
                    <td class="text-info">'.$order_details->orderId.'</td>
                    <td class="text-info border-r text-right">P '.number_format((float)$total_amount_due, 2, '.', ',').'</td>
                </tr>
                <tr><td colspan="3" class="border-lr"></td></tr>
                <tr><td colspan="3" class="border-lr"></td></tr>
                <tr><td colspan="3" class="border-lr"></td></tr>
                <tr><td colspan="3" class="border-lr"></td></tr>
                <tr><td colspan="3" class="border-lr"></td></tr>
                <tr><td colspan="3" class="border-lr"></td></tr>
                <tr><td colspan="3" class="border-lr"></td></tr>
                <tr><td colspan="3" class="border-lr border-b"></td></tr>
                <tr>
                    <td class="text-info border-bl text-bold">Payment Method</td>
                    <td class="text-info border-b"></td>
                    <td class="text-info border-br text-bold">Payment Amount</td>
                </tr>';

                foreach ($line_items as $item) {
                    if ($item->lineType == "PN") {
                        $total_amount_due = $total_amount_due + $item->itemUnitPrice;
                        $output .= '<tr style="background:#F0F0F0;">
                            <td>'.$item->name.'</td>
                            <td></td>
                            <td class="text-right"><span class="peso">&#8369;</span>'.number_format((float)str_replace('-','',$item->itemUnitPrice), 2, '.', ',').'</td>
                        </tr>';
                    }
                }

                $output .= '
                <tr>
                    <td></td>
                    <td class="text-info text-right"><span class="text-bold">BALANCE:</span></td>
                    <td class="text-info border-solid text-right text-bold"><span class="peso">&#8369;</span>'.number_format((float)$total_amount_due, 2, '.', ',').'</td>
                </tr>
                </tbody>
            </table>
        </div>';

        $output .= $this->getInvoiceFooter();
    
        return $output;
    }

    static function getTotalAmountDue($line_items, $order_details) {
        $order_subtotal = 0;
        foreach ($line_items as $item) {
            if ($item->lineType == "PN" || $item->lineType == "N") {
                continue;
            }
            $with_vat = Utils::getWithVAT($item->itemUnitPrice, $item->pv);
            $total_cost = Utils::getTotalCost($with_vat, $item->quantity);

            $order_subtotal += $item->itemUnitPrice * $item->quantity;
        }
        $vatable_sales = $order_subtotal + $order_details->shippingChargeAmount;
        $vat = Utils::toFixed($vatable_sales * 0.12);
        return ($vatable_sales + $vat);
    }

    function getInvoiceHeader($title, $order_details) {
        return '<img class="logo" src="' . public_path() . "/assets/yl_logo_hd.png" . '" width="200" alt="" />
            <div class="head-2">' . $title . '</div>
            <div class="serial-number">No. ' . request()->invoice_no . '</div>
            <div class="head-1">YOUNG LIVING PHILIPPINES LLC</div>
            <div class="head-2"> YOUNG LIVING PHILIPPINES LLC - PHILIPPINE BRANCH</div>
            <div class="text-info">
                Unit G07, G08, G09 & 12th Floor, <br>
                Twenty-Five Seven McKinley Building, <br>
                25th Street corner 7th Avenue, Bonifacio Global City, <br>
                Taguig City, Metro Manila <br>
                VAR REG TIN: 009-915-795-000 <br>
            </div>
            <hr style="margin-top: 25px; margin-bottom: 2px">
            <div class="text-info float-right" style="line-height: 19px;">
                <div><span style="margin-right: 105px;">Order Number:</span> <span class="float-right">' . $order_details->orderId . '</span></div>
                <div><span style="margin-right: 10px;">Order Date:</span> <span class="float-right">' . date('m/d/Y', strtotime($order_details->dateTimeSubmittedIso)) . '</span></div>
                <div>Member ID: <span class="float-right">' . $order_details->custId . '</span></div>
            </div>
            <div class="text-info">
                <div>Sold To: <span style="margin-left:37px;">' . $order_details->custName . '</span></div>
                <div style="margin-top: 25px;">Address: <span style="margin-left:34px;">' . $order_details->shipAddr1 . " " . $order_details->shipAddr2 .'</span></div>
                <div class="mt-2">TIN: <span style="margin-left:60px;">' . Utils::separateString($order_details->customerTIN) . '</span></div>
            </div>
            ';
    }

    function getInvoiceFooter() {
        $output = 
            '<footer>
            <table width="100%" style="border-collapse:collapse;" class="table-footer mt-1">
                <td class="text-left">Prepared by:</td>
                <td class="text-left">Received by:</td> 
                <td class="text-left">Date:</td> 
            </table>

            <div class="text-info mt-1" style="line-height: 20px;">
                Accreditation No.: AC-044-10-2021-00141 <br>
                Accreditation Date: FEBRUARY 15, 2022 <br>
                Acknowledgement Certificate No.: AC-044-10-2021-0014 <br>
                <span class="mr-1">Date issued: FEBRUARY 15, 2022 </span> Valid Until: FEBRUARY 14, 2027 <br>
                Approved Series No.: 000001 to 999999
            </div>';

        $output .= '<div class="phrase mt-4 text-center">THIS INVOICE/RECEIPT SHALL BE VALID FOR FIVE (5) YEARS FROM THE DATE OF THE ACKNOWLEDGEMENT CERTIFICATE</div>';
        $output .= '</footer>';
        return $output;
    }

    function invoiceStyle() {
        return "<style>
            @page { margin: 18px 38px 15px 38px; }
            * { font-family: Calibri, sans-serif; }
            .text-left { text-align:left; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .text-bold { font-style: bold; }
            .phrase { font-size: 12px; }
            .head-3 { font-size: 14px; }
            .head-2 { font-size: 12px; font-style:bold; text-transform: uppercase; }
            .head-1 { font-size: 15px; font-style:bold; margin-top: 5px; }
            .logo { 
                object-fit: cover;
                position: absolute;
                right: 0;
                top: 1px;
            }
            .serial-number { 
                font-style: bold;
                font-size: 24px;
                position: absolute;
                right: 13px;
                top: 58px;
                color: #FF0000; 
            }
            .text-info { 
                margin-top: 3px; 
                font-size: 12px;
            }
            .mb-1 { margin-bottom:10px; }
            .ml-1 { margin-left:10px; }
            .ml-2 { margin-left:20px; }
            .ml-3 { margin-left:30px; }
            .mr-2 { margin-right:20px; }
            .mr-3 { margin-right:30px; }
            .mr-100 { margin-right:100px; }
            .mt-08 { margin-top:8px; }
            .mt-1 { margin-top:10px; }
            .mt-2 { margin-top:20px; }
            .mt-3 { margin-top:30px; }
            .mt-4 { margin-top:40px !important; }
            .mt-5 { margin-top:50px; }
            .mt-100 { margin-top:100px; }
            .float-right { float:right; }
            table { font-size: 12px; }
            td, th { padding: 5px; }
            th { border: 1px solid; }
            .table-computation td, th, .table-items th { padding: 2px !important; margin: 2px !important; }
            .table-footer td, th { padding: 10px !important; border: 1px solid; }
            .border-solid { border: 1px solid; }
            .border-bl { border-bottom: 1px solid black; border-left: 1px solid black; }
            .border-br { border-bottom: 1px solid black; border-right: 1px solid black; }
            .border-b { border-bottom: 1px solid black; }
            .border-lr { border-left: 1px solid black; border-right: 1px solid black; }
            .border-l { border-left: 1px solid black; }
            .border-r { border-right: 1px solid black; }
            span.peso {
                font-family: DejaVu Sans; sans-serif;
            }
            footer {
                position: fixed;
                bottom: 0px;
                left: 0px;
                right: 0px;
                margin-bottom: 0px;
            }
        </style>";
    }
}
