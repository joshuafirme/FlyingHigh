<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\HubController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PickupController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('/users', UserController::class);
    Route::post('/user/update/{id}', [UserController::class, 'update']);
    Route::get('/user/search', [UserController::class, 'search'])->name('searchUser');

    Route::resource('/role', RoleController::class);
    Route::post('/role/update/{id}', [RoleController::class, 'update']);

    Route::get('/hubs/{slug}/{hub_id}', [HubController::class, 'hubInventory']);

    Route::post('/hub/update/{id}', [HubController::class, 'update']);
    Route::get('/hub/search', [HubController::class, 'search'])->name('searchHub');
    Route::resource('/hub', HubController::class);

    Route::get('/pickup', [PickupController::class, 'index']);

    Route::post('/client/update/{id}', [ClientController::class, 'update']);
    Route::get('/client/search', [ClientController::class, 'search'])->name('searchClient');
    Route::resource('/client', ClientController::class);
    
    Route::post('/product/transfer', [ProductController::class, 'transfer']);
    Route::post('/product/update/{id}', [ProductController::class, 'update']);
    Route::get('/product/search', [ProductController::class, 'search'])->name('searchProduct');
    Route::resource('/product', ProductController::class);

});




Auth::routes(['register' => false]);
