<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;
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
    
    public function importProduct(Request $request) 
    {
        Excel::import(new ProductImport, $request->file('file')->store('temp'));
        return back();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transfer(Product $product, HubInventory $hub)
    {
        $sku = request()->sku;
        $qty = request()->stock;
        $hub_id = request()->hub_id;
        $except_values = ['description'];
        
        if ($product->hasStock($sku, $qty)) {
            if ($hub->isSkuExistsInHub($sku, $hub_id)) {
                $hub->incrementStock($sku, $qty, $hub_id);
            }
            else {
                HubInventory::create(request()->except($except_values));
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

    public function search()
    {
        $key = isset(request()->key) ? request()->key : "";
        $products = Product::where('sku', 'LIKE', '%' . $key . '%')
                    ->orWhere('description', 'LIKE', '%' . $key . '%')
                    ->paginate(10);
        $hubs = Hub::where('status', 1)->get();
        $page_title = "products";
        return view('product.index', compact('page_title', 'products', 'hubs'));
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
