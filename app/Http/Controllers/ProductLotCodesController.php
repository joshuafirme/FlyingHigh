<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LotCode;

class ProductLotCodesController extends Controller
{
    public function index(LotCode $lc) {
        $products = $lc->getAllPaginate(10);
        return view('product-lot-codes.index', compact('products'));
    }
}
