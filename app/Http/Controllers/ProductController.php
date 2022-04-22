<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;
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
        $products = Product::orderBy('created_at','desc')->paginate(10);
        $remarks = AdjustmentRemarks::where('status', 1)->get();
        $product_count = Product::count('id');
        $hubs = Hub::where('status', 1)->get();
        return view('product.index', compact('remarks', 'products', 'hubs', 'product_count'));
    }

    public function getHubsStockBySku($sku, Product $product)
    {
        return $product->getHubsStockBySku($sku);
    }


    public function importAPI(Request $request, Transaction $trans, Product $product) 
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
            $trans->transactionReferenceNumber = $data->transactionReferenceNumber;
            $trans->transactionType = $data->transactionType;
            $trans->save();

            foreach ($data->purchaseOrderReceiptHeaders as $po) {

                foreach ($po->purchaseOrderReceiptDetails as $item) {
                    $trans_item = new TransactionLineItems;
                    $trans_item->transactionReferenceNumber = $data->transactionReferenceNumber;
                    $trans_item->orderNumber = $po->orderNumber;
                    $trans_item->orderType = $po->orderType;

                    $product->incrementStock($item->itemNumber, $item->qtyRcdGood);

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

    public function transfer(Product $product, HubInventory $hub, HubTransfer $hub_transfer)
    {
        $sku = request()->sku;
        $qty = request()->stock;
        $hub_id = request()->hub_id;
        $except_values = ['description'];
        
        if ($product->hasStock($sku, $qty)) {
            if ($hub->isSkuExistsInHub($sku, $hub_id)) {
                $hub->incrementStock($sku, $qty, $hub_id);
                $hub_transfer->record($sku, $qty, $hub_id);
            }
            else {
                HubInventory::create(request()->except($except_values));
                $hub_transfer->record($sku, $qty, $hub_id);
            }
            $product->decrementStock($sku, $qty);
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

    public function bulkTransfer(Request $request, Product $product, HubInventory $hub, HubTransfer $hub_transfer)
    {
        $all_sku = $request->sku;
        $qty = $request->qty;
        $hub_id = $request->hub_id;
        $except_values = ['bundles', 'search_terms'];
        $isAllStockEnough = json_decode($product->isAllStockEnough($all_sku, $qty));
      
        if ($isAllStockEnough->result) {

            foreach ($all_sku as $key => $sku) {  
                if ($product->hasStock($sku, $qty[$key])) {
                    if ($hub->isSkuExistsInHub($sku, $hub_id[$key])) {
                        $hub->incrementStock($sku, $qty[$key], $hub_id[$key]);
                        $hub_transfer->record($sku, $qty[$key], $hub_id[$key]);
                    }
                    else {
                        HubInventory::create([
                            'sku' => $sku,
                            'stock' => $qty[$key],
                            'hub_id' => $hub_id[$key]
                        ]);
                        $hub_transfer->record($sku, $qty[$key], $hub_id[$key]);
                    }
                    $product->decrementStock($sku, $qty[$key]);
                }
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
                'sku_list' => $isAllStockEnough->sku
            ], 200);
        }
    }

    public function search()
    {
        $key = isset(request()->key) ? request()->key : "";
        $products = Product::where('sku', 'LIKE', '%' . $key . '%')
                    ->orWhere('description', 'LIKE', '%' . $key . '%')
                    ->paginate(10);
        $remarks = AdjustmentRemarks::where('status', 1)->get();
        $hubs = Hub::where('status', 1)->get();
        $product_count = Product::count('id');
        $page_title = "products";
        return view('product.index', compact('page_title', 'products', 'hubs', 'product_count', 'remarks'));
    }

    public function store(Request $request, Product $product)
    {
        if ($product->isSkuExists($request->sku)) {
            return redirect()->back()->with('danger', 'SKU is already exists.');
        }
        Cache::forget('all_sku_cache');
        $inputs = $request->all();
        $inputs['has_bundle'] = $request->has_bundle == 'on' ? 1 : 0;
       
        $bundles = isset($request->bundles) ? implode(',', $request->bundles) : '';
        $inputs['bundles'] = $bundles;
        Product::create($inputs);

        $product->incrementStock($request->sku, $request->qty);

        return redirect()->back()->with('success', 'Product was successfully added.');
    }

    public function adjustStock(Request $request, Product $product, StockAdjustment $stock_adjustment) {
        $sku = $request->sku;
        $qty = $request->qty;
        $action = $request->action;
 
        if ($action == 'add') {
            $product->incrementStock($sku, $qty);
        }
        else {
            $product->decrementStock($sku, $qty);
        }

        $stock_adjustment->record($sku, $qty, $action, $request->remarks_id);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        Cache::forget('all_sku_cache');
        $except_values = ['_token','search_terms'];
        $request['has_bundle'] = $request->has_bundle == 'on' ? 1 : 0;
        $bundles = isset($request->bundles) ? implode(',', $request->bundles) : [];
        $request['bundles'] = $bundles;
        
        Product::where('id',$id)->update($request->except($except_values));
        return redirect()->back()->with('success', 'Product was updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
}
