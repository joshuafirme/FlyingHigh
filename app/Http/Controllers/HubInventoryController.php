<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hub;
use App\Models\User;
use App\Models\HubInventory;
use App\Models\Shipment;
use App\Models\ShipmentLineItem;
use App\Models\LotCode;
use App\Models\ReturnReason;
use App\Models\Order;
use App\Models\LineItem;
use App\Models\Invoice;
use App\Models\Attribute;
use Utils;

class HubInventoryController extends Controller
{
    private $page = 'Hub Inventory';
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (User::isPermitted($this->page)) { return $next($request); }
            return abort(401);
        });
    }

    public function hubInventory($receiver, HubInventory $hub_inv, Hub $hub, Shipment $shipment)
    {
        $shipments = $shipment->getDeliveredByReceiver($receiver, 10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        $attribute = new Attribute;

        $hub_name = $hub->getHubName($receiver);
        return view('hubs-inventory.index', compact('shipments', 'hub_name', 'receiver', 'hub_inv', 'attribute'));
    }

    public function searchShipment($receiver, HubInventory $hub_inv, Hub $hub, Shipment $shipment)
    {
        $key = isset(request()->key) ? request()->key : "";
        $shipments = $shipment->searchShipment($receiver, $key, 10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        $attribute = new Attribute;

        $hub_name = $hub->getHubName($receiver);
        return view('hubs-inventory.index', compact('shipments', 'hub_name', 'receiver', 'hub_inv', 'attribute'));
    }


    public function pickup($receiver, $shipmentId, HubInventory $hub_inv, Hub $hub, Shipment $shipment, LineItem $line_item)
    {
        $order_details = $this->getOrderDetails($shipmentId);
        $order_line_items = $line_item->getLineItems($order_details->orderId);
        $line_items = $this->getLineItems($shipmentId);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        $invoice = new Invoice;
        $package_details = $shipment->readOne($shipmentId);
        $attribute = new Attribute;

        $hub_name = $hub->getHubName($receiver);
        return view('hubs-inventory.pickup', 
            compact(
                'order_line_items',
                'line_items',
                'hub_name', 
                'receiver',
                'order_details',
                'package_details', 
                'invoice',
                'attribute'
            )
        );
    }

    public function getOrderDetails($shipmentId)
    {
        $order = new Order;
        return $order->getOrderDetails($shipmentId);
    }
    
    public function getLineItems($shipmentId) {
        $lineItem = new ShipmentLineItem;
        return $lineItem->getLineItems($shipmentId);
    }

    public function getAllStock($sku, HubInventory $hub_inv) {
        return $hub_inv->getAllStock($sku);
    }

    public function searchProduct($receiver, HubInventory $hub_inv, Hub $hub, Shipment $shipment)
    {
        $key = isset(request()->key) ? request()->key : "";

        $deliveries = $shipment->searchDeliveredByReceiver($receiver, $key, 10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();

        $hub_name = $hub->getHubName($receiver);
        return view('hubs-inventory.index', compact('deliveries', 'hub_name', 'receiver', 'hub_inv'));
        return view('hubs-inventory.index', compact('products', 'hub_name', 'hub_id', 'hub_inv'));
    }
}
