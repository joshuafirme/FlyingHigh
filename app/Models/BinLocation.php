<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinLocation extends Model
{
    use HasFactory;

        protected $table = 'hubs';

    protected $fillable = [
        'product_id',
        'bin',
        'qty'
    ];
}
