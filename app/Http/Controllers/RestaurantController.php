<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RestaurantController extends Controller
{
     use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $restaurant = Restaurant::paginate();
            return $this->successResponse($restaurant, 'Done', 200);
            // return $this->paginated($restaurant, 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $restaurant = Restaurant::create([
                //
            ]);
            return $this->successResponse($restaurant, 'Created Successfuly', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {
        try {
            return $this->successResponse($restaurant, 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        try {
            // $restaurant->nn = $request->input('nn') ?? $restaurant->nn;
            $restaurant->save();
            return $this->successResponse($restaurant, ' Updated Successfuly', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        try {
            $restaurant->delete();
            return $this->successResponse(null,'deleted successfully', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }
}
