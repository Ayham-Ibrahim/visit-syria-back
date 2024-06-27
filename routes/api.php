<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\HotelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandmarkController;

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
Route::middleware('auth:api')->put('/update-user/{user}', [AuthController::class, 'update']);



Route::apiResource('cities',CityController::class);
Route::apiResource('services',CityController::class);
Route::apiResource('hotels',HotelController::class);


Route::apiResource('cities', CityController::class);
Route::apiResource('services', CityController::class);



Route::middleware("admin")->group(function () {
    Route::post('landmarks', [LandmarkController::class, 'store']);
    Route::put('landmarks/{landmark}', [LandmarkController::class, 'update']);
    Route::delete('landmarks/{landmark}', [LandmarkController::class, 'destroy']);
});

Route::get('landmarks', [LandmarkController::class, 'index']);
Route::get('landmarks/{landmark}', [LandmarkController::class, 'show']);


