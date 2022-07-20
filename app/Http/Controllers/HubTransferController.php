<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Hub;
use App\Models\HubTransfer;
use App\Models\HubInventory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\LotCode;
use App\Models\Attribute;
use App\Models\HubTransferList;
use Utils;
use DB;
use Cache;

class HubTransferController extends Controller
{
    public function index(LotCode $lc) 
    {
        $products = $lc->getAllPaginate(15);
        $lot_code = new LotCode;
        $attribute = new Attribute;
        $hubs = Hub::where('status', 1)->get();
        return view('hub-transfer.index', compact('products', 'hubs', 'lot_code', 'attribute'));
    }

    public function search(LotCode $lc) 
    {
        $products = $lc->searchPaginate(request()->key, 15);
        $lot_code = new LotCode;
        $attribute = new Attribute;
        $hubs = Hub::where('status', 1)->get();
        return view('hub-transfer.index', compact('products', 'hubs', 'lot_code', 'attribute'));
    }

    public function store(LotCode $lc, Request $request) 
    {
        $lot = HubTransferList::where('lot_code_id',$request->lot_code_id)->get();
        if (count($lot) > 0) {
            return response()->json([
                'success' => false,
                'message' => "The item was already on the list."
            ]);
        }
        HubTransferList::create([
            'lot_code_id' => $request->lot_code_id,
            'warehouse_id' => "4803"
        ]);

        return response()->json([
            'success' => true,
            'message' => "Product was added"
        ]);
    }    
    
    public function remove($id) 
    {
        HubTransferList::where('lot_code_id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => "Item removed"
        ]);
    }

    public function getTransferList() 
    {
        return HubTransferList::leftJoin('product_lot_codes as pl', 'pl.id', '=', 'lot_code_id')
            ->get();
    }

    public function transfer(
        Request $request, 
        HubInventory $hub, 
        HubTransfer $hub_transfer, 
        LotCode $lc
    )
    {
        $all_sku = $request->sku;
        $lot_codes = $request->lot_code;
        $qty = $request->qty_to_transfer;
        $hub_id = $request->hub_id;
        $except_values = ['bundles', 'search_terms'];
        $isAllStockEnough = json_decode($lc->isAllStockEnough($all_sku, $lot_codes, $qty));
       
        if ($isAllStockEnough->result) {
            foreach ($all_sku as $ctr => $sku) {  
                if ($hub->isLotCodeExistsInHub($sku, $lot_codes[$ctr], $hub_id)) { 
                    $hub->incrementStock($lot_codes[$ctr], $qty[$ctr], $hub_id);
                    $hub_transfer->record($sku, $request, $ctr);
                }
                else {  
                    $hub->createLotCode($sku, $lot_codes[$ctr], $qty[$ctr], $hub_id);
                    $hub_transfer->record($sku, $request, $ctr);
                }
                $lc->decrementStock($sku, $lot_codes[$ctr], $qty[$ctr]);
            }
                return response()->json([
                    'success' =>  true,
                    'message' => 'transfer_success'
                ], 200);
        }
        else {
            return response()->json([
                'success' =>  false,
                'message' => 'not_enough_stock',
                'lot_codes' => $isAllStockEnough->lot_codes
            ], 200);
        }
    }

}
