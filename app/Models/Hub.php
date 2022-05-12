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
        'receiver'
    ];

    public function getHubName($receiver) {
        return self::where('receiver', $receiver)->value('name');
    }

    public function isReceiverExists($receiver) {
        $res = self::where('receiver', $receiver)->get();
        return $res && count($res) > 0 ? true : false;
    }
}
