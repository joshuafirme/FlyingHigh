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
use App\Models\LotCode;
use Utils;
use DB;
use Cache;

class ProductController extends Controller
{
    private $page = "Product";

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (User::isPermitted($this->page)) { return $next($request); }
            return abort(401);
        });
    }    
    
    public function index() 
    {
        $products = Product::orderBy('created_at','desc')->paginate(20);
        $remarks = AdjustmentRemarks::where('status', 1)->get();
        $product_count = Product::count('id');
        $lot_code = new LotCode;
        $hubs = Hub::where('status', 1)->get();
        return view('product.index', compact('remarks', 'products', 'hubs', 'product_count', 'lot_code'));
    }

    public function archiveLotCode($id, LotCode $lc)
    {
        return $lc->archiveLotCode($id);
    }

    public function getHubsStockBySku($sku, Product $product)
    {
        return $product->getHubsStockBySku($sku);
    }


    public function importAPI(Request $request, Transaction $trans, Product $product, LotCode $lot_code) 
    {
        $path = public_path() . '/purchase_order.json';
        $data = json_decode(file_get_contents($path));

        if ($trans->isTransactionExists($data->transactionReferenceNumber)) {
            return response()->json([
                'success' =>  true,
                'transactionReferenceNumber' => $data->transactionReferenceNumber,
                'transactionRType' => $data->transactionType,
                'message' => 'transferred',
                'status' => 1
            ], 200);
        }
        else {
            foreach ($data->purchaseOrderReceiptHeaders as $po) {
                $is_all_sku_exists = json_decode($product->isAllSKUExists($po->purchaseOrderReceiptDetails));
                if (!$is_all_sku_exists->result) { 
                    $sku_count = count($is_all_sku_exists->sku_list);
                    $these_this = $sku_count > 1 ? 'These' : 'The';
                    $is_are = $sku_count > 1 ? 'are' : 'is';
                    return response()->json([
                        'success' =>  false,
                        'message' => 'some_sku_not_exists',
                        'description' => $these_this . ' SKU below ' . $is_are . ' not exists',
                        'sku_list' => $is_all_sku_exists->sku_list,
                        'status' => 1
                    ], 200); 
                }

                foreach ($po->purchaseOrderReceiptDetails as $item) {
                    $trans_item = new TransactionLineItems;
                    $trans_item->transactionReferenceNumber = $data->transactionReferenceNumber;
                    $trans_item->orderNumber = $po->orderNumber;
                    $trans_item->orderType = $po->orderType;
                    
                    if ($lot_code->isLotCodeExists($item->lotNumber)) {
                        $lot_code->incrementStock($item->itemNumber, $item->lotNumber, $item->qtyRcdGood);
                    }
                    else {
                        $lot_code->createLotCode($item->itemNumber, $item->itemNumber, $item->lotNumber, $item->qtyRcdGood);
                    }

                    $trans_item->lineNumber = $item->lineNumber;
                    $trans_item->itemNumber = $item->itemNumber;
                    $trans_item->qtyRcdGood = $item->qtyRcdGood;
                    $trans_item->qtyRcdBad = $item->qtyRcdBad;
                    $trans_item->billOfLading = $item->billOfLading;
                    $trans_item->rcvComments = $item->rcvComments;
                    $trans_item->palletId = $item->palletId;
                    $trans_item->location = $item->location ;
                    $trans_item->unitOfMeasure = $item->unitOfMeasure;
                    $trans_item->lotNumber = $item->lotNumber;
                    $trans_item->receiptDate = $item->receiptDate;
                    $trans_item->lotExpiration = $item->lotExpiration;

                    $trans_item->save();
                }
            }

            
            $trans->transactionReferenceNumber = $data->transactionReferenceNumber;
            $trans->transactionType = $data->transactionType;
            $trans->save();

            return response()->json([
                'success' =>  true,
                'transactionReferenceNumber' => $data->transactionReferenceNumber,
                'transactionRType' => $data->transactionType,
                'message' => 'transaction_success',
                'status' => 1
            ], 200);
        }

        
    }
    
    public function importProduct(Request $request) 
    {
        Excel::import(new ProductImport, $request->file('file')->store('temp'));
        return back();
    }

    public function transfer(Product $product, HubInventory $hub, HubTransfer $hub_transfer, LotCode $lc)
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
        LotCode $lc
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
        $lot_code = new LotCode;
        return view('product.index', compact('page_title', 'products', 'hubs', 'product_count', 'remarks', 'lot_code'));
    }

    public function store(Request $request, Product $product)
    { 
        Cache::forget('all_sku_cache');

        $add_lot_code = json_decode($this->addLotCode($request, $action = 'store'));

        if ($add_lot_code->success == false) {
            return redirect()->back()->with('danger', $add_lot_code->lot_codes . ' lot code'.$add_lot_code->is_are.' already exists.');
        }
        
        if ($product->isSkuExists($request->sku)) {
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

        if ($request['lot_code']) {
            foreach ($request['lot_code'] as $key => $lot_code) {
                $expiration = $request->expiration[$key];
                $lc = new LotCode;
                $lc->where('lot_code', $lot_code)->update(['expiration' => $expiration]);
            }
        }
        
        $add_lot_code = json_decode($this->addLotCode($request, $action = 'update'));
        
        if ($add_lot_code->success == false) {
            //return redirect()->back()->with('danger', $add_lot_code->lot_codes . ' lot code'.$add_lot_code->is_are.' already exists.');
        }

        return redirect()->back()->with('success', 'Product was updated successfully.');
    }

    public function addLotCode($request, $action) {
        $lc = new LotCode;
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
                        $lc->createLotCode($request->sku, $lot_code, $expiration, 0);
                    }
                    $ctr++;
                }
                else {
                    if ($lc->isLotCodeExists($request->sku, $lot_code)) {
                        array_push($lot_codes, $lot_code);
                    }
                    else {
                        $expiration = $request->expiration[$key];
                        $lc->createLotCode($request->sku, $lot_code, $expiration, 0);
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

    public function adjustStock(Request $request, StockAdjustment $stock_adjustment, LotCode $lot_code) {
        $sku = $request->sku;
        $lot_number = $request->lot_code;
        $qty = $request->qty;
        $action = $request->action;
 
        if ($action == 'add') {
            $lot_code->incrementStock($sku, $lot_number, $qty);
        }
        else { 
            if ($lot_code->hasStock($sku, $qty)) {
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

    public function destroy(Product $product)
    {
        Cache::forget('all_sku_cache');
        if ($product->delete()) {
            return response()->json([
                'status' =>  'success',
                'message' => 'Product was deleted.'
            ], 200);
        }

        return response()->json([
            'status' =>  'error',
            'message' => 'Deleting Product failed.'
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
