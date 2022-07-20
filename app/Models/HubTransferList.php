<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HubTransferList extends Model
{
    use HasFactory;

    protected $table = 'hub_transfer_list';

    protected $fillable = [
        'lot_code_id',
        'warehouse_id'
    ];
}
