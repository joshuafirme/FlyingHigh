<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hub;
use App\Models\User;
use App\Models\HubInventory;
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

    public function hubInventory($hub_id, HubInventory $hub_inv, Hub $hub)
    {
        $products = $hub_inv->getByHub($hub_id, 10);

        $hub_name = $hub->getHubName($hub_id);
        return view('hubs-inventory.index', compact('products', 'hub_name', 'hub_id', 'hub_inv'));
    }

    public function getAllStock($sku, HubInventory $hub_inv) {
        return $hub_inv->getAllStock($sku);
    }

    public function searchProduct($hub_id, HubInventory $hub_inv, Hub $hub)
    {
        $key = isset(request()->key) ? request()->key : "";
        if ($key) {
            $products = $hub_inv->searchByHub($hub_id, 10);
        }
        else {
             $products = $hub_inv->getByHub($hub_id, 10);
        }

        $hub_name = $hub->getHubName($hub_id);
        return view('hubs-inventory.index', compact('products', 'hub_name', 'hub_id'));
    }
}
