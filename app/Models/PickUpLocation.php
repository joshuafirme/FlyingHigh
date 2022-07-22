<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickUpLocation extends Model
{
    use HasFactory;    
    
    protected $table = 'pickup_location';

    protected $fillable = [
        'name',
        'branch_id',
        'status'
    ];

    
    public function getLocationName($branch_id) {
        return self::where('branch_id', $branch_id)->value('name');
    }
}
