<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnReason extends Model
{
    use HasFactory;

    protected $table = 'return_reasons';

    protected $fillable = [
        'reason',
        'status',
    ];
}
