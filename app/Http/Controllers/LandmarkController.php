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

            $cityName = $request->input('city');
            $sortBy = $request->input('sort_by', 'id');

            $landmarks = Landmark::with('city')
                ->whereHas('city', function ($query) use ($cityName) {
                    $query->where('name', $cityName);
                })->orderBy($sortBy, 'asc');


            $data = $landmarks->paginate(9);
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


            // connect images in Image model with Landmark model
            foreach ($request->file('images') as $image) {
                Image::create([
                    'path' => $this->storeFile($image, 'landmark'),
                    'imageable_type' => Landmark::class,
                    'imageable_id' => $landmark->id,
                ]);
            }
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


            if (isset($request->internal_image)) {
                $this->deleteImage($landmark->internal_image, storage_path('app\public\landmark'));

                //save new image
                $image_path = $this->storeFile($request->internal_image, 'landmark');

                //add new image to list
                $newData['internal_image'] = $image_path;
            }

            if (isset($request->external_image)) {
                $this->deleteImage($landmark->external_image, storage_path('app\public\landmark'));


                //save new image
                $image_path = $this->storeFile($request->external_image, 'landmark');

                //add new image to list
                $newData['external_image'] = $image_path;
            }

            if (isset($request->images)) {

                //delete the old list
                foreach ($landmark->images as $image) {

                    // delete image from storage
                    $this->deleteImage($image->path, storage_path('app\public\landmark'));

                    // delete image from Image 
                    $image->delete();
                }

                // add the new list
                foreach ($request->file('images') as $image) {
                    $image_path = $this->storeFile($image, 'landmark');

                    Image::create([
                        'path' => $image_path,
                        'imageable_type' => Landmark::class,
                        'imageable_id' => $landmark->id,
                    ]);

                    $newData['images'][] = $image_path;
                }
            }

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
            DB::beginTransaction();
            $this->deleteImage($landmark->internal_image, storage_path('app\public\landmark'));
            $this->deleteImage($landmark->external_image, storage_path('app\public\landmark'));

            foreach ($landmark->images as $image) {
                // delete image from Image 
                $this->deleteImage($image->path, storage_path('app\public\landmark'));
            }


            $landmark->delete();
            DB::commit();
            return $this->successResponse(null, 'deleted successfully', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }
}
