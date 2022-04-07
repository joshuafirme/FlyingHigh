<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HubInventory extends Model
{
    use HasFactory;

    protected $table = 'hub_inventory';

    protected $fillable = [
        'sku',
        'hub_id',
        'stock',
    ];

    public function isSkuExistsInHub($sku, $hub_id) 
    {
        $res = self::where('sku', $sku)
                ->where('hub_id', $hub_id)->get();
        return count($res) > 0 ? true : false;
    }

    public function incrementStock($sku, $qty, $hub_id) {
        self::where('sku', $sku)
        ->where('hub_id', $hub_id)->update([
            'stock' => DB::raw('stock + ' . $qty)
        ]);
    }

    public function decrementStock($sku, $qty, $hub_id) {
        self::where('sku', $sku)
        ->where('hub_id', $hub_id)->update([
            'stock' => DB::raw('stock - ' . $qty)
        ]);
    }
}
