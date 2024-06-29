<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Hotel;
use Illuminate\Http\Request;
use App\Http\Traits\RateTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Rate\RateRequest;

class RateController extends Controller
{
    //
    use  ApiResponseTrait,RateTrait;
    public function RateHotel(RateRequest $request,$id)
    {
        try{
            DB::beginTransaction();

            $hotel = Hotel::where('id',$id)->first();
//total Rate
            $total= $this->TotalRate($hotel);
                      $data=[
                'user_id'=>Auth::user()->id,
                'site_rate' => $request->site_rate,
                'clean_rate' => $request->clean_rate,
                'service_rate' => $request->service_rate,
                'price_rate' => $request->price_rate,
                'total_rate' =>$total
                  ];

           $Rate= $this->Rate($hotel,$data,Auth::User()->id);
        DB::commit();
        return $this->successResponse($Rate, 'Done', 200);

        }
        catch (Throwable $th) {
            DB::rollback();
            Log::debug($th);
            Log::error($th->getMessage());
          return $this->errorResponse(null,"there is something wrong in server",500);
        }

    }

   }
