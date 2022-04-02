<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hub;
use App\Models\HubInventory;
use Utils;

class PickupController extends Controller
{
    public function index() {
        $pickups = json_decode(url('/pickup.json'));
        return view('pickup.index', compact('pickups'));
    }
}
