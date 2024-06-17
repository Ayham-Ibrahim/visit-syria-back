<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\Landmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LandmarkController extends Controller
{
     use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $landmark = Landmark::paginate();
            return $this->successResponse($landmark, 'Done', 200);
            // return $this->paginated($landmark, 'Done', 200);
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
            $landmark = Landmark::create([
                //
            ]);
            return $this->successResponse($landmark, 'Created Successfuly', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Landmark $landmark)
    {
        try {
            return $this->successResponse($landmark, 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Landmark $landmark)
    {
        try {
            // $landmark->nn = $request->input('nn') ?? $landmark->nn;
            $landmark->save();
            return $this->successResponse($landmark, ' Updated Successfuly', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Landmark $landmark)
    {
        try {
            $landmark->delete();
            return $this->successResponse(null,'deleted successfully', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }
}
