<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

trait RateTrait{
    public function Rate($model,$data,$user_id)
    {
            try {

                DB::beginTransaction();
                $existingRate=$model->Rates()->where('user_id',$user_id)->first();

                if ($existingRate) {
                    $existingRate->update($data);
                    DB::commit();
                    return $existingRate;
                } else {
                    $modelRate = $model->Rates()->Create(
                        $data
                    );
                    DB::commit();
                    return $modelRate;

                }
        }
        catch(\Throwable $th){
            DB::rollback();
            Log::error("Error deleting file: {$th->getMessage()}");
        }

    }

       public function TotalRate($model){

        $total_rate=$model->Rates->select('site_rate','price_rate','service_rate','clean_rate')->first();
        $total=[];
        $total=round(array_sum($total_rate)/count($total_rate));
       return $total;
       }
}

?>
