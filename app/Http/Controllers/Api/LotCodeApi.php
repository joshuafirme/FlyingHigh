<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;

class LotCodeApi extends Controller
{
    public function getLotCode($sku, Inventory $lot_code) {
        return $lot_code->getLotCode($sku);
    }
}
