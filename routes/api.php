<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HotelController;

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


//about
Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::post('/about',[AboutController::class,'store']);
    Route::put('/about/{about}',[AboutController::class,'update']);
    Route::delete('about/{about}',[AboutController::class,'destroy']);
    });

Route::get('/about',[AboutController::class,'index']);
Route::get('/about/{about}',[AboutController::class,'show']);

//Rating
Route::Post('/RateHotel/{id}',[RateController::class,'RateHotel'])->middleware(['auth']);
Route::get('/total/{id}',[RateController::class,'Total_Rate']);
