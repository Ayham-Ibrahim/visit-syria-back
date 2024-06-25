<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CityController;
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
Route::middleware('auth:api')->put('/update-user/{user}', [AuthController::class, 'update']);



Route::get('/blogs', [BlogController::class, 'index']);
Route::post('/add', [BlogController::class, 'store']);
Route::get('/blog/{blog}', [BlogController::class, 'show']);
Route::put('/update/{blog}', [BlogController::class, 'update']);
Route::delete('/blog/{blog}', [BlogController::class, 'destroy']);
Route::apiResource('cities', CityController::class);
Route::apiResource('services', CityController::class);
Route::apiResource('hotels', HotelController::class);
