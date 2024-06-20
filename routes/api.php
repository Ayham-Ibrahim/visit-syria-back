<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'restaurants'], function () {
    Route::get('/', [RestaurantController::class, 'index']);
    Route::get('/by_city/{city_id}', [RestaurantController::class, 'showByCity']);
    Route::get('/sort/{sort_by}', [RestaurantController::class, 'showStored']);
    Route::get('/{restaurant}', [RestaurantController::class, 'show']);

    Route::post('/create', [RestaurantController::class, 'store']);
    // ->middleware(['auth', 'admin']);

    Route::put('/update/{restaurant}', [RestaurantController::class, 'update']);
    // ->middleware(['auth', 'admin']);

    Route::delete('/delete/{restaurant}', [RestaurantController::class, 'destroy']);
    // ->middleware(['auth', 'admin']);
});

Route::apiResource('cities',CityController::class);
Route::apiResource('services',ServiceController::class);
