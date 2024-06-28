<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResturantRequest;
use App\Http\Requests\UpdateResturantRequest;
use App\Http\Resources\RestaurantResource;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\FileStorageTrait;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RestaurantController extends Controller
{
    use ApiResponseTrait, FileStorageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Restaurant::select('restaurants.id', 'restaurants.name', 'restaurants.location', 'cities.name as city_name', 'restaurants.primary_description', 'restaurants.secondary_description', 'restaurants.table_price', 'restaurants.cover_image', 'restaurants.logo','restaurants.menu')
                                ->join('cities', 'restaurants.city_id', '=', 'cities.id');
            if ($request->has('city')) {
                $query->where('cities.name', $request->city);
            }
            if ($request->has('sort_by')) {
                $sortBy = $request->sort_by;
                $query->orderBy($sortBy, 'asc');
            }
            $restaurants = $query->paginate(9);
            return $this->paginated($restaurants, 'Done', 200);
            // return $this->paginated($restaurant, 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    public function showByCity($city_id)
    {
        try {
            $restaurant = Restaurant::where('city_id', $city_id)->paginate(9);

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

            $restaurant = Restaurant::when(request('city_id'), function ($query) {
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
            DB::beginTransaction();

            $restaurant = Restaurant::create([
                'name' => $request->name,
                'location' => $request->location,
                'city_id' => $request->city_id,
                'primary_description' => $request->primary_description,
                'secondary_description' => $request->secondary_description,
                'logo' => $this->storeFile($request->logo, 'resturant'),
                'cover_image' => $this->storeFile($request->cover_image, 'resturant'),
                'table_price' => $request->table_price,
                'menu' => $this->storeFile($request->menu, 'resturant'),
            ]);

            $this->storeAndAssociateImages($restaurant, $request->images, 'resturant');
            DB::commit();

            return $this->successResponse(new RestaurantResource($restaurant), 'Created Successfuly', 200);
        } catch (\Throwable $th) {
            DB::rollback();
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
            return $this->successResponse(new RestaurantResource($restaurant), 'Done', 200);
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
            DB::beginTransaction();

            $restaurant->name = $request->input('name') ?? $restaurant->name;
            $restaurant->location = $request->input('location') ?? $restaurant->location;
            $restaurant->city_id = $request->input('city_id') ?? $restaurant->city_id;
            $restaurant->primary_description = $request->input('primary_description') ?? $restaurant->primary_description;
            $restaurant->secondary_description = $request->input('secondary_description') ?? $restaurant->secondary_description;
            $restaurant->table_price = $request->input('table_price') ?? $restaurant->table_price;
            $restaurant->logo = $this->fileExists($request->logo, 'resturant')  ?? $restaurant->logo;
            $restaurant->cover_image = $this->fileExists($request->cover_image, 'resturant') ?? $restaurant->cover_image;
            $restaurant->menu = $this->fileExists($request->menu, 'resturant') ?? $restaurant->menu;

            $this->updateAndAssociateNewImages($restaurant, $request->images, 'resturant');

            $restaurant->save();
            DB::commit();

            return $this->successResponse(new RestaurantResource($restaurant), ' Updated Successfuly', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
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
