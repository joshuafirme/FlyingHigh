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
        'location_id',
        'status'
    ];

    
    public function getLocationName($location_id) {
        return self::where('location_id', $location_id)->value('name');
    }
}
