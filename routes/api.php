<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CityController;
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


Route::apiResource('cities', CityController::class);
Route::apiResource('services', CityController::class);

Route::get('/blogs', [BlogController::class, 'index']);
Route::post('/add', [BlogController::class, 'store']);
Route::get('/blog/{blog}', [BlogController::class, 'show']);
Route::put('/update/{blog}', [BlogController::class, 'update']);
Route::delete('/blog/{blog}', [BlogController::class, 'destroy']);