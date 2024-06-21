<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\HotelRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\FileStorageTrait;
use App\Http\Requests\UpdateHotelRequest;
use App\Http\Resources\HotelResource;

class HotelController extends Controller
{
    use ApiResponseTrait,FileStorageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $hotels = Hotel::select(['id','name','location','city_id','primary_description','secondary_description','price','cover_image','logo'])->paginate(9);
            // return $this->successResponse($hotels, 'Done', 200);
            return $this->paginated($hotels, 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HotelRequest $request)
    {
        try {
            DB::beginTransaction();
            $hotel = Hotel::create([
                'name'                  => $request->name,
                'location'              => $request->location,
                'city_id'               => $request->city_id,
                'primary_description'   => $request->primary_description,
                'secondary_description' => $request->secondary_description,
                'price'                 => $request->price,
                'cover_image'           => $this->storeFile($request->cover_image, 'hotel'),
                'logo'                  => $this->storeFile($request->logo, 'hotel'),
            ]);
             // Assuming request->images is an array of image paths/files
            $this->storeAndAssociateImages($hotel, $request->images, 'hotel');
            DB::commit();
            return $this->successResponse(new HotelResource($hotel), 'Created Successfuly', 200);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        try {
            return $this->successResponse(new HotelResource($hotel), 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        try {

            DB::beginTransaction();
            $hotel->name = $request->input('name') ?? $hotel->name;
            $hotel->location = $request->input('location') ?? $hotel->location;
            $hotel->city_id = $request->input('city_id') ?? $hotel->city_id;
            $hotel->primary_description = $request->input('primary_description') ?? $hotel->primary_description;
            $hotel->secondary_description = $request->input('secondary_description') ?? $hotel->secondary_description;
            $hotel->price = $request->input('price') ?? $hotel->price;
            $hotel->cover_image = $this->fileExists($request->cover_image, 'hotel') ?? $hotel->cover_image;
            $hotel->logo = $this->fileExists($request->logo, 'hotel') ?? $hotel->logo;
            if(!empty($request->images)){
                $this->updateAndAssociateNewImages($hotel, $request->images, 'hotel');
            }
            $hotel->save();
            DB::commit();
            return $this->successResponse(new HotelResource($hotel), ' Updated Successfuly', 200);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        try {
            $hotel->delete();
            return $this->successResponse(null,'deleted successfully', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }
}
