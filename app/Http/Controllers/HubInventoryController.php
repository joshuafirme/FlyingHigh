<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hub;
use App\Models\User;
use App\Models\HubInventory;
use App\Models\Shipment;
use App\Models\ShipmentLineItem;
use App\Models\Inventory;
use App\Models\ReturnReason;
use App\Models\Order;
use App\Models\LineItem;
use App\Models\Invoice;
use App\Models\PickUpLocation;
use App\Models\Attribute;
use Utils;

class HubInventoryController extends Controller
{
    private $page = 'Hub Inventory';
    
    public function __construct()
    {
      //  $this->middleware(function ($request, $next) {
       //     if (User::isPermitted($this->page)) { return $next($request); }
     //       return abort(401);
      //  });
    }

    public function hubInventory($branch_id, HubInventory $hub_inv, PickUpLocation $location, Shipment $shipment)
    {
        if (request()->tab == "shipments") {
            $shipments = $shipment->getShipmentByHub($branch_id, 10);
        }
        else {
            $shipments = $shipment->getCancelledShipmentByHub($branch_id, 10);
        }
        $inventory = $hub_inv->getByHub($branch_id, 15);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        $attribute = new Attribute;

        $hub_name = $location->getLocationName($branch_id);
        return view('hubs-inventory.index', compact('shipments', 'hub_name', 'branch_id', 'inventory', 'attribute', 'hub_inv'));
    }

    public function searchShipment($branch_id, HubInventory $hub_inv, PickUpLocation $location, Shipment $shipment)
    {
        $key = isset(request()->key) ? request()->key : "";
        $shipments = $shipment->searchShipment($branch_id, $key, 10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();
        $attribute = new Attribute;

        $hub_name = $location->getLocationName($branch_id);
        return view('hubs-inventory.index', compact('shipments', 'hub_name', 'branch_id', 'hub_inv', 'attribute'));
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

    public function searchProduct($hub_id, HubInventory $hub_inv, PickUpLocation $location, Shipment $shipment)
    {
        $key = isset(request()->key) ? request()->key : "";

        $deliveries = $shipment->searchDeliveredByhub_id($hub_id, $key, 10);
        $hubs = Hub::where('status', 1)->get();
        $reasons = ReturnReason::where('status', 1)->get();

        $hub_name = $location->getLocationName($hub_id);
        return view('hubs-inventory.index', compact('deliveries', 'hub_name', 'hub_id', 'hub_inv'));
        return view('hubs-inventory.index', compact('products', 'hub_name', 'hub_id', 'hub_inv'));
    }
}
