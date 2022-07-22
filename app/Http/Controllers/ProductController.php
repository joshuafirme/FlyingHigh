<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;
use App\Exports\ProductExport;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Hub;
use App\Models\HubTransfer;
use App\Models\HubInventory;
use App\Models\Transaction;
use App\Models\TransactionLineItems;
use App\Models\AdjustmentRemarks;
use App\Models\StockAdjustment;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Attribute;
use Utils;
use DB;
use Cache;

class ProductController extends Controller
{
    private $page = "SKU Master";

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (User::isPermitted($this->page)) { return $next($request); }
            return abort(401);
        });
    }    
    
    public function index() 
    {
        $products = Product::orderBy('created_at','desc')->where('status', 1)->paginate(20);
        $remarks = AdjustmentRemarks::where('status', 1)->get();
        $product_count = Product::count('id');
        $lot_code = new Inventory;
        $attribute = new Attribute;
        $hubs = Hub::where('status', 1)->get();
        return view('product.index', compact('remarks', 'products', 'hubs', 'product_count', 'lot_code', 'attribute'));
    }

    public function search()
    {
        $key = isset(request()->key) ? request()->key : "";
        $products = Product::where('itemNumber', 'LIKE', '%' . $key . '%')
                    ->orWhere('productDescription', 'LIKE', '%' . $key . '%')
                    ->paginate(50);
        $remarks = AdjustmentRemarks::where('status', 1)->get();
        $hubs = Hub::where('status', 1)->get();
        $product_count = Product::count('id');
        $page_title = "products";
        $lot_code = new Inventory;
        $attribute = new Attribute;
        return view('product.index', compact('page_title', 'products', 'hubs', 'product_count', 'remarks', 'lot_code', 'attribute'));
    }

    public function store(Request $request, Product $product)
    { 
        Cache::forget('all_sku_cache');
        
        if ($product->isSkuExists($request->sku, $request->baseUOM)) {
            return redirect()->back()->with('danger', 'SKU is already exists.');
        }

        $inputs = $request->except('lot_code', 'stock', 'expiration');

        $data = array_merge($inputs, [
            'food' => $request->food ? 'T' : 'F',
            'refrigerated' => $request->refrigerated ? 'T' : 'F',
            'willMelt' => $request->willMelt ? 'Y' : 'N',
            'willFreeze' => $request->willFreeze ? 'Y' : 'N',
            'isBarcoded' => $request->isBarcoded ? 'Y' : 'N',
            'isLotControlled' => $request->isLotControlled ? 'T' : 'F'
        ]);
        
        Product::create($data);

        return redirect()->back()->with('success', 'Product was successfully added.');
    }

    public function update(Request $request, $id)
    {  
        Cache::forget('all_sku_cache');
        
        $except_values = ['_token','search_terms','lot_code','lot_code','stock','expiration'];

        $data = $request->except($except_values);

        $data = array_merge($data, [
            'food' => $request->food ? 'T' : 'F',
            'refrigerated' => $request->refrigerated ? 'T' : 'F',
            'willMelt' => $request->willMelt ? 'Y' : 'N',
            'willFreeze' => $request->willFreeze ? 'Y' : 'N',
            'isBarcoded' => $request->isBarcoded ? 'Y' : 'N',
            'isLotControlled' => $request->isLotControlled ? 'T' : 'F'
        ]);
        
        Product::where('id',$id)->update($data);

        return redirect()->back()->with('success', 'Product was updated successfully.');
    }

    public function addLotCode($request, $action) {
        $lc = new Inventory;
        $lot_codes = [];
        $lot_code_count = 0;
        $ctr = 0;
        $current_lot_code_count = 0;
        if ($action == 'update') {
            $current_lot_code_count = count($lc->getLotCode($request->sku));
        }
        if (isset($request['lot_code']) && count($request['lot_code']) > 0) {
            foreach ($request['lot_code'] as $key => $lot_code) {
                $lot_code = $lot_code ? $lot_code : 0;
                if ($current_lot_code_count > 0 && $current_lot_code_count <= $key) {
                    if ($lc->isLotCodeExists($request->sku, $lot_code)) {
                        array_push($lot_codes, $lot_code);
                    }
                    else {
                        $expiration = $request->expiration[$key];
                        $lc->createLotCode($request->sku, $lot_code, $expiration, null);
                    }
                    $ctr++;
                }
                else {
                    if ($lc->isLotCodeExists($request->sku, $lot_code)) {
                        array_push($lot_codes, $lot_code);
                    }
                    else {
                        $expiration = $request->expiration[$key];
                        $lc->createLotCode($request->sku, $lot_code, $expiration, null);
                    }
                }
        }
        }
        if (count($lot_codes) > 0) {
            $is_are = count($lot_codes) > 1 ? 's are' : ' is';
            $lot_codes = implode(', ', $lot_codes);     
            return json_encode([
                'success' => false,
                'lot_codes' => $lot_codes,
                'is_are' => $is_are
            ]);
        }
        return json_encode([
            'success' => true
        ]);
    }

    public function archiveLotCode($id, Inventory $lc)
    {
        return $lc->archiveLotCode($id);
    }

    public function getHubsStockBySku($sku, Product $product)
    {
        return $product->getHubsStockBySku($sku);
    }

    
    public function importProduct(Request $request) 
    {
        Excel::import(new ProductImport, $request->file('file')->store('temp'));
        return back();
    }

    public function transfer(Product $product, HubInventory $hub, HubTransfer $hub_transfer, Inventory $lc)
    {
        $sku = request()->sku;
        $lot_code = request()->lot_code;
        $qty = request()->stock;
        $hub_id = request()->hub_id;
        $except_values = ['description'];
        
        if ($lc->hasStock($lot_code, $qty)) {
            if ($hub->isLotCodeExistsInHub($sku, $lot_code, $hub_id)) {
                $hub->incrementStock($lot_code, $qty, $hub_id);
                $hub_transfer->record($sku, $lot_code, $qty, $hub_id);
            }
            else {
                $hub->createLotCode($sku, $lot_code, $qty, $hub_id);
                $hub_transfer->record($sku, $lot_code, $qty, $hub_id);
            }
            $lc->decrementStock($sku, $lot_code, $qty);
            return response()->json([
                'success' =>  true,
                'message' => 'transfer_success'
            ], 200);
        }
        return response()->json([
            'success' =>  true,
            'message' => 'not_enough_stock'
        ], 200);
    }

    public function bulkTransfer(
        Request $request, 
        Product $product, 
        HubInventory $hub, 
        HubTransfer $hub_transfer, 
        Inventory $lc
    )
    {
        $all_sku = $request->sku;
        $lot_codes = $request->lot_code;
        $qty = $request->qty;
        $hub_id = $request->hub_id;
        $except_values = ['bundles', 'search_terms'];
        $isAllStockEnough = json_decode($lc->isAllStockEnough($all_sku, $lot_codes, $qty));
       
        if ($isAllStockEnough->result) {
            foreach ($all_sku as $ctr => $sku) {  
                if ($hub->isLotCodeExistsInHub($sku, $lot_codes[$ctr], $hub_id[$ctr])) { 
                    $hub->incrementStock($lot_codes[$ctr], $qty[$ctr], $hub_id[$ctr]);
                    $hub_transfer->record($sku, $lot_codes[$ctr], $qty[$ctr], $hub_id[$ctr]);
                }
                else {  
                    $hub->createLotCode($sku, $lot_codes[$ctr], $qty[$ctr], $hub_id[$ctr]);
                    $hub_transfer->record($sku, $lot_codes[$ctr], $qty[$ctr], $hub_id[$ctr]);
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

    public function adjustStock(Request $request, StockAdjustment $stock_adjustment, Inventory $lot_code) {
        $sku = $request->sku;
        $lot_number = $request->lot_code;
        $qty = $request->qty;
        $action = $request->action;
 
        if ($action == 'add') {
            $lot_code->incrementStock($sku, $lot_number, $qty);
        }
        else { 
            if ($lot_code->hasStock($sku, $lot_number, $qty)) {
                 $lot_code->decrementStock($sku, $lot_number, $qty);
            }
            else {
                 return response()->json([
                    'message' => 'not_enough_stock'
                ], 200);
            }
        }

        $stock_adjustment->record($sku, $lot_number, $qty, $action, $request->remarks_id);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function delete($id)
    {
        Product::where('id', $id)->update(['status' => 0]);
        return response()->json([
            'success' =>  true,
            'message' => 'Product was deleted.'
        ], 200);
    }

    

    public function incrementStock(Product $product)
    {
        $sku = request()->sku;
        $qty = request()->qty;
        $product->where('sku', $sku)->update(['qty' => DB::raw('qty + ' . $qty)]);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function export()
    {
         return Excel::download(new ProductExport, 'product.xlsx');
    }
}
