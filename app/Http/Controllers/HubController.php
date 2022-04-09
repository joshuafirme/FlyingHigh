<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hub;
use App\Models\HubInventory;
use Utils;
use Str;

class HubController extends Controller
{
    public function index() {
        $hubs = Hub::paginate(10);

        $page_title = "Hubs";
        $hubs = Hub::paginate(10);
        return view('hub.index', compact('page_title', 'hubs', 'hubs'));
    }

    public function hubInventory($slug, $hub_id, HubInventory $hub) {
        $products = HubInventory::select('P.*', 'hub_inventory.stock')
                    ->leftJoin('products as P', 'P.sku', '=', 'hub_inventory.sku')
                    ->where('hub_inventory.hub_id', $hub_id)
                    ->paginate(10);

        $hub_name = ucwords(str_replace('-', ' ', $slug));
        return view('hubs-inventory.index', compact('products', 'hub_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    public function search()
    {
        $key = isset(request()->key) ? request()->key : "";
        $hubs = Hub::where('name', 'LIKE', '%' . $key . '%')
                    ->orWhere('email', 'LIKE', '%' . $key . '%')
                    ->paginate(10);
        $page_title = "Hubs";
        return view('hub.index', compact('page_title', 'hubs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $all_req = $request->all();
        $all_req['slug'] = Str::slug($all_req['name'], '-');
        Hub::create($all_req);
        return redirect()->back()->with('success', 'Hub was successfully added.');
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
        $all_req = $request->except($except_values);
        $all_req['slug'] = Str::slug($all_req['name'], '-');
        Hub::where('id',$id)->update($all_req);
        return redirect()->back()->with('success', 'Hub was updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hub $hub)
    {
        if ($hub->delete()) {
            return response()->json([
                'status' =>  'success',
                'message' => 'hub was deleted.'
            ], 200);
        }

        return response()->json([
            'status' =>  'error',
            'message' => 'Deleting hub failed.'
        ], 200);
    }
}
