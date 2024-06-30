<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AboutController;
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

/**
 * Auth Routes
 *
 * These routes handle user authentication, including login, registration, and logout.
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
Route::middleware('auth:api')->put('/admin-update/{user}', [UserController::class, 'updateAdmin']);
Route::middleware('auth:api')->put('/admin-update-photo/{user}', [UserController::class, 'updateAdminImage']);
Route::middleware('auth:api')->delete('/admin-delete-photo/{user}', [UserController::class, 'deleteAdminImage']);

/**
 *  Routes for visistor
 *
 * These Groupe handle the routes that allowed for all visitors
*/
# Hotel routes (show and list all hotels)
Route::get('hotels/{hotel}', [HotelController::class, 'show']);
Route::get('hotels', [HotelController::class, 'index']);

# Blog routes (show and list all hotels)
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blog/{blog}', [BlogController::class, 'show']);

# Landmark routes (show and list all hotels)
Route::get('landmarks', [LandmarkController::class, 'index']);
Route::get('landmarks/{landmark}', [LandmarkController::class, 'show']);

# AboutSyria routes (show and list all hotels)
Route::get('/about',[AboutController::class,'index']);
Route::get('/about/{about}',[AboutController::class,'show']);


/**
 *  Route Groupe for admin Routes
 *
 * These Groupe handle the routes that allowed just for admin and his dashboard
*/
Route::middleware(['auth', 'admin'])->group(function () {

    # Hotels Routes (Store , update ,Delete)
    Route::post('hotels',[HotelController::class,'store']);
    Route::put('hotels/{hotel}', [HotelController::class, 'update']);

    # Blog Routes (Store , update ,Delete)
    Route::post('/add', [BlogController::class, 'store']);
    Route::put('/update/{blog}', [BlogController::class, 'update']);
    Route::delete('/blog/{blog}', [BlogController::class, 'destroy']);

    # AboutSyria Routes (Store , update ,Delete)
    Route::post('/about',[AboutController::class,'store']);
    Route::put('/about/{about}',[AboutController::class,'update']);
    Route::delete('about/{about}',[AboutController::class,'destroy']);

    # landmarks Routes (Store , update ,Delete)
    Route::post('landmarks', [LandmarkController::class, 'store']);
    Route::put('landmarks/{landmark}', [LandmarkController::class, 'update']);
    Route::delete('landmarks/{landmark}', [LandmarkController::class, 'destroy']);

});

Route::delete('hotels/{hotel}', [HotelController::class, 'destroy'])->middleware(['auth', 'admin']);

Route::apiResource('cities',CityController::class);
Route::apiResource('services',ServiceController::class);


Route::group(['prefix' => 'restaurants'], function () {
    Route::get('/page', [RestaurantController::class, 'index']);
    Route::get('/by_city/{city_id}', [RestaurantController::class, 'showByCity']);
    Route::get('/sort/{sort_by}', [RestaurantController::class, 'showStored']);
    Route::get('/{restaurant}', [RestaurantController::class, 'show']);

    Route::post('/create', [RestaurantController::class, 'store'])
    ->middleware(['auth','admin']);

    Route::put('/update/{restaurant}', [RestaurantController::class, 'update'])
    ->middleware(['auth','admin']);

    Route::delete('/delete/{restaurant}', [RestaurantController::class, 'destroy'])
    ->middleware(['auth','admin']);
});
