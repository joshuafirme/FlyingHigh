<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LotCode;

class InventoryReportController extends Controller
{
    public function index(LotCode $lc) {
        $products = $lc->getAllPaginate(10);
        return view('reports.inventory.index', compact('products'));
    }
}
