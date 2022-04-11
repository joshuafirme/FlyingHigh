<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjustmentRemarks extends Model
{
    use HasFactory;

    protected $table = 'adjustment_remarks';

    protected $fillable = [
        'name',
        'status',
    ];
}
