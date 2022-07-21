<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Auth;
use App\Models\Hub;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'access_level',
        'branch_id'
    ];

    public static function isPermitted($page) {
        $hub = new Hub;
        $is_permitted = false;

        if (Auth::check()) {

            $permissions = Role::permissions();

            if ($page == 'Hub Inventory') {
                
                $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                
                $hub_id = substr($current_url, strpos($current_url, "hubs/")+5);
                $hub_name = $hub->getHubName($hub_id);
                
                if (in_array($hub_name, $permissions)) {
                    return true;
                }

            } 
            else {
                if (in_array($page, $permissions)) {
                    return true;
                }
            }
        }
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
