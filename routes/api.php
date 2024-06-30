<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\HotelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;

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
Route::middleware('auth:api')->put('/update-user/{user}', [UserController::class, 'update']);
Route::middleware('auth:api')->put('/admin-update/{user}', [UserController::class, 'updateAdmin']);
Route::middleware('auth:api')->put('/admin-update-photo/{user}', [UserController::class, 'updateAdminImage']);
Route::middleware('auth:api')->delete('/admin-delete-photo/{user}', [UserController::class, 'deleteAdminImage']);


Route::post('/add-hotel-commnet/{hotel}', [CommentController::class, 'storeHotelComment']);
Route::post('/add-landmark-commnet/{landmark}', [CommentController::class, 'storeLandmarkComment']);
Route::post('/add-restaurant-commnet/{restaurant}', [CommentController::class, 'storeRestaurantComment']);
Route::get('/get-comment/{comment}', [CommentController::class, 'show']);
Route::get('/get-comments-hotel/{hotel}', [CommentController::class, 'hotelComments']);
Route::get('/get-comments-landmark/{landmark}', [CommentController::class, 'landmarkComments']);
Route::get('/get-comments-restaurant/{restaurant}', [CommentController::class, 'restaurantComments']);
Route::delete('/delete-comment/{comment}', [CommentController::class, 'destroy']);


Route::apiResource('cities',CityController::class);
Route::apiResource('services',CityController::class);
Route::apiResource('hotels',HotelController::class);



