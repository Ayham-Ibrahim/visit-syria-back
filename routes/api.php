<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HotelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
    ], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    });
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);



Route::group(['prefix' => 'restaurants'], function () {
    Route::get('/page/{page}', [RestaurantController::class, 'index']);
    Route::get('/by_city/{city_id}', [RestaurantController::class, 'showByCity']);
    Route::get('/sort/{sort_by}', [RestaurantController::class, 'showStored']);
    Route::get('/{restaurant}', [RestaurantController::class, 'show']);

    Route::post('/create', [RestaurantController::class, 'store']);
    // ->middleware('admin');

    Route::post('/update/{restaurant}', [RestaurantController::class, 'update']);
    // ->middleware('admin');

    Route::delete('/delete/{restaurant}', [RestaurantController::class, 'destroy']);
    // ->middleware('admin');
});

Route::apiResource('cities',CityController::class);
Route::apiResource('services',ServiceController::class);
Route::apiResource('hotels',HotelController::class);
