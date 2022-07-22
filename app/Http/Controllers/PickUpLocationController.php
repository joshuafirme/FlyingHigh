<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PickUpLocation;
use App\Models\User;
use Utils;

class PickUpLocationController extends Controller
{
       public function index()
    {
        $locations = PickUpLocation::paginate(10);

        $page_title = "Locations";
        $locations = PickUpLocation::paginate(10);
        return view('pickup-location.index', compact('page_title', 'locations'));
    }

    public function search()
    {
        $key = isset(request()->key) ? request()->key : "";
        $locations = PickUpLocation::where('name', 'LIKE', '%' . $key . '%')
            ->paginate(10);
        $page_title = "Locations";
        return view('pickup-location.index', compact('page_title', 'locations'));
    }

    public function store(Request $request, PickUpLocation $location)
    {
        $all_req = $request->all();
        PickUpLocation::create($all_req);
        return redirect()->back()->with('success', 'Location was successfully added.');
    }

    public function update(Request $request, $id)
    {
        $except_values = ['_token'];
        $all_req = $request->except($except_values);
        PickUpLocation::where('id', $id)->update($all_req);
        return redirect()->back()->with('success', 'Location was updated successfully.');
    }

    public function destroy(PickUpLocation $location)
    {
        if ($location->delete()) {
            return response()->json([
                'status' =>  'success',
                'message' => 'Location was deleted.'
            ], 200);
        }

        return response()->json([
            'status' =>  'error',
            'message' => 'Deleting location failed.'
        ], 200);
    }
}
