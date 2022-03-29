<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Role extends Model
{
    use HasFactory;

    protected $table = 'user_role';

    protected $fillable = [
        'name',
        'permission',
    ];

    
    
    public static function permissions() {
        $permissions = self::where('id', Auth::user()->access_level)->value('permission');
        return $permissions ? explode(', ', $permissions) : [];
    }
}
