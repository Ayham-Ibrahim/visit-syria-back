<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResturantRequest;
use App\Http\Requests\UpdateResturantRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\FileStorageTrait;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RestaurantController extends Controller
{
    use ApiResponseTrait, FileStorageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $restaurant = Restaurant::with('city', 'services')->paginate(9);

            return $this->successResponse($restaurant, 'Done', 200);
            // return $this->paginated($restaurant, 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);

            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    public function showByCity($city_id)
    {
        try {
            $restaurant = Restaurant::with('city', 'services')->where('city_id', $city_id)->paginate(9);

            return $this->successResponse($restaurant, 'Done', 200);
            // return $this->paginated($restaurant, 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);

            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    public function showStored($sort_by)
    {
        try {
            $sortBy = $sort_by;

            $restaurant = Restaurant::with('city', 'services')
                ->when(request('city_id'), function ($query) {
                    $query->where('city_id', request('city_id'));
                })
                ->when($sortBy, function ($query) use ($sortBy) {
                    $query->orderBy($sortBy);
                })
                ->paginate(9);

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
    public function store(StoreResturantRequest $request)
    {
        try {
            $restaurant = Restaurant::create([
                'name' => $request->name,
                'location' => $request->location,
                'city_id' => $request->city_id,
                'primary_description' => $request->primary_description,
                'secondary_description' => $request->secondary_description,
                'logo' => $this->storeFile($request->logo, 'resturant'),
                'cover_image' => $this->storeFile($request->cover_image, 'resturant'),
                'table_price' => $request->table_price,
                'menu' => $request->menu,
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
            return $this->successResponse($restaurant::with('city', 'services'), 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);

            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResturantRequest $request, Restaurant $restaurant)
    {
        try {
            $restaurant->name = $request->input('name') ?? $restaurant->name;
            $restaurant->location = $request->input('location') ?? $restaurant->location;
            $restaurant->city_id = $request->input('city_id') ?? $restaurant->city_id;
            $restaurant->primary_description = $request->input('primary_description') ?? $restaurant->primary_description;
            $restaurant->secondary_description = $request->input('secondary_description') ?? $restaurant->secondary_description;
            $restaurant->logo = $this->fileExists($request->logo, 'resturant')  ?? $this->storeFile($request->logo, 'resturant');
            $restaurant->cover_image = $this->fileExists($request->cover_image, 'resturant') ?? $this->storeFile($request->cover_image, 'resturant');
            $restaurant->table_price = $request->input('table_price') ?? $restaurant->table_price;
            $restaurant->menu = $request->input('menu') ?? $restaurant->menu;

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
