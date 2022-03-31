<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'sku',
        'description',
        'qty',
        'buffer_stock',
        'jde_lot_code',
        'supplier_lot_code',
        'expiration',
        'status',
    ];
}
