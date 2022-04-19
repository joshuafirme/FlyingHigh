<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hub;
use App\Models\User;
use App\Models\HubInventory;
use Utils;
use Str;

class HubController extends Controller
{

    public function index()
    {
        $hubs = Hub::paginate(10);

        $page_title = "Hubs";
        $hubs = Hub::paginate(10);
        return view('hub.index', compact('page_title', 'hubs', 'hubs'));
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

    public function store(Request $request)
    {
        $all_req = $request->all();
        $all_req['slug'] = Str::slug($all_req['name'], '-');
        Hub::create($all_req);
        return redirect()->back()->with('success', 'Hub was successfully added.');
    }

    public function update(Request $request, $id)
    {
        $except_values = ['_token'];
        $all_req = $request->except($except_values);
        $all_req['slug'] = Str::slug($all_req['name'], '-');
        Hub::where('id', $id)->update($all_req);
        return redirect()->back()->with('success', 'Hub was updated successfully.');
    }

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
