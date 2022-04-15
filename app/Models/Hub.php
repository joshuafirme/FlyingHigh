<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hub extends Model
{
    use HasFactory;

    protected $table = 'hubs';

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'status',
    ];

    public function getHubName($hub_id) {
        return self::where('id', $hub_id)->value('name');
    }
}
