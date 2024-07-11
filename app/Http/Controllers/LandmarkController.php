<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Landmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\FileStorageTrait;
use App\Http\Resources\LandmarkResource;
use App\Http\Requests\LandmarkStoreRequest;
use App\Http\Requests\LandmarkUpdateRequest;
use Psy\Readline\Hoa\Console;

class LandmarkController extends Controller
{
    use ApiResponseTrait, FileStorageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 9);
            $cityName = $request->input('city');
            $sortBy = $request->input('sort_by', 'id');

            $landmarks = Landmark::with('city');
            if ($cityName) {
                $landmarks->whereHas('city', function ($query) use ($cityName) {
                    $query->where('name', $cityName);
                });
            }
            if ($sortBy) {
                if ($sortBy == "city") {
                    $landmarks->join('cities', 'landmarks.city_id', '=', 'cities.id')
                        ->orderBy('cities.name', 'asc')
                        ->select('landmarks.*');
                } else {
                    $landmarks->orderBy($sortBy, 'asc');
                }
            }

            $data = $landmarks->paginate($perPage);
            return $this->resourcePaginated(LandmarkResource::collection($data), 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LandmarkStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $internal_image = $this->storeFile($request->internal_image, 'landmark');
            $external_image = $this->storeFile($request->external_image, 'landmark');


            $landmark = Landmark::create([
                'name'                  =>  $request->name,
                'city_id'               =>  $request->city_id,
                'location'              =>  $request->location,
                'primary_description'   =>  $request->primary_description,
                'secondary_description' =>  $request->secondary_description,
                'internal_image'        =>  $internal_image,
                'external_image'        =>  $external_image,
            ]);

            $this->storeAndAssociateImages($landmark, $request->images, 'landmark');

            DB::commit();
            return $this->successResponse(new LandmarkResource($landmark), 'Created Successfuly', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Landmark $landmark)
    {
        try {
            return $this->successResponse(new LandmarkResource($landmark), 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LandmarkUpdateRequest $request, Landmark $landmark)
    {
        try {
            DB::beginTransaction();
            $newData = [];

            if (isset($request->name)) {
                $newData['name'] = $request->name;
            }
            if (isset($request->city_id)) {
                $newData['city_id'] = $request->city_id;
            }
            if (isset($request->location)) {
                $newData['location'] = $request->location;
            }

            if (isset($request->primary_description)) {
                $newData['primary_description'] = $request->primary_description;
            }
            if (isset($request->secondary_description)) {
                $newData['secondary_description'] = $request->secondary_description;
            }

            if (isset($request->internal_image))
                $newData['internal_image'] = $this->fileExists($request->internal_image, 'landmark') ?? $landmark->internal_image;


            if (isset($request->external_image))
                $newData['external_image'] = $this->fileExists($request->external_image, 'landmark') ?? $landmark->external_image;


            if (isset($request->images))
                $this->updateAndAssociateNewImages($landmark, $request->images, 'landmark');

            $landmark->update($newData);
            DB::commit();
            return $this->successResponse(new LandmarkResource($landmark), ' Updated Successfuly', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Landmark $landmark)
    {
        try {
            $landmark->delete();
            return $this->successResponse(null, 'deleted successfully', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }
}
