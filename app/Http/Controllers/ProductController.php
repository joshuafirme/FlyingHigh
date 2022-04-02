<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Hub;
use App\Models\HubInventory;
use Utils;
use DB;

class ProductController extends Controller
{
    public function index() {
        $products = Product::paginate(10);
        $hubs = Hub::where('status', 1)->get();
        $page_title = "products";
        $products = Product::paginate();
        return view('product.index', compact('page_title', 'products', 'hubs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transfer()
    {
        $sku = request()->sku;
        $qty = request()->stock;
        $hub_id = request()->hub_id;
        $except_values = ['description'];
        
        if ($this->hasStock($sku, $qty)) {
            if ($this->isSkuExistsInHub($sku, $hub_id)) {
                HubInventory::where('sku', $sku)
                            ->where('hub_id', $hub_id)->update([
                    'stock' => DB::raw('stock + ' . $qty)
                ]);
            }
            else {
                HubInventory::create(request()->except($except_values));
            }
            Product::where('sku', $sku)->update([
                'qty' => DB::raw('qty - ' . $qty)
            ]);
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

    public function hasStock($sku, $qty) 
    {
        $current_qty = Product::where('sku', $sku)->value('qty');
        if ($current_qty > $qty) {
            return true;
        }
        return false;
    }

    public function isSkuExistsInHub($sku, $hub_id) 
    {
        $res = HubInventory::where('sku', $sku)
                ->where('hub_id', $hub_id)->get();
        return count($res) > 0 ? true : false;
    }

    public function search()
    {
        $key = isset(request()->key) ? request()->key : "";
        $products = Product::where('sku', 'LIKE', '%' . $key . '%')
                    ->orWhere('description', 'LIKE', '%' . $key . '%')
                    ->paginate(10);
        $page_title = "products";
        return view('product.index', compact('page_title', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Product::create($request->all());
        return redirect()->back()->with('success', 'Product was successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $except_values = ['_token'];

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
}
