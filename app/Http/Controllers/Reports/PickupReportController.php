<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pickup;
use App\Exports\PickupExport;
use Maatwebsite\Excel\Facades\Excel;
use Utils;

class PickupReportController extends Controller
{
    public function index($slug, Pickup $pickup) {

        $status = Utils::getPickupStatusBySlug($slug);
        $pickups = $pickup->getAllPaginate(10, $status);
        $status_title = ucfirst(str_replace('-', ' ', $slug));
        return view('reports.pickup.index', compact('pickups','status_title','status','slug'));
    }

    public function filterPickup(Pickup $pickup) {

        $status = request()->status;
   
        $pickups = $pickup->filterPaginate(10, $status);
        $status_title = '';
        return view('reports.pickup.index', compact('pickups','status_title','status'));
    }
}
