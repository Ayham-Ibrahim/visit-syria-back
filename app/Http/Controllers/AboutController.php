<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\FileStorageTrait;
use App\Http\Resources\About\AboutResource;
use App\Http\Requests\About\StoreAboutRequest;
use App\Http\Requests\About\UpdateAboutRequest;

class AboutController extends Controller
{
    use ApiResponseTrait;
    use FileStorageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 9); // Default to 15 if not provided
            $query = About::select('id', 'title', 'category', 'content', 'main_image');
            if ($request->has('sort_by')) {
                $sortBy = $request->sort_by;
                $query->orderBy($sortBy, 'asc');
            }
            // for site
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }
            $about = $query->paginate($perPage);
            return $this->resourcePaginated(AboutResource::collection($about), 'Done', 200);
        } catch (\Throwable $th) {
            Log::debug($th);
            Log::error($th->getMessage());
                        return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAboutRequest $request)
    {
        try {
            DB::beginTransaction();
            $content=$request->content;
             $content=$content !=null ?$request->content:null;

               $about = About::create([
                 'title' =>$request->title,
                 'content' =>$content,
                 'category' =>$request->category,
                 'main_image' => $this->storeFile($request->main_image,'About')

               ]);
               if($request->images){

               $this->storeAndAssociateImages($about, $request->images, 'About');
               $about->images;
               }
               DB::commit();

               return $this->successResponse( new AboutResource($about),'Created Successfuly', 200);
        }
        catch (\Throwable $th)
         {
            DB::rollBack();

            Log::debug($th);
            Log::error($th->getMessage());

            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(About $about)
    {
        try {
            $about->images;
            return $this->successResponse(new AboutResource($about), 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAboutRequest $request, About $about)
    {
        try {
            DB::beginTransaction();
            $newData=[];
            if(isset($request->title)){
              $newData['title'] = $request->title;
            }
            if(isset($request->content)){
              $newData['content'] = $request->content;
            }
            if(isset($request->main_image)){
              $newData['main_image'] = $this->fileExists($request->main_image,'About')??$request->main_image;
            }
            if(isset($request->category)){
              $newData['category'] = $request->category;
            }
            $about->update($newData);
            if($request->images){

              if(!empty($request->images)){
                  $this->updateAndAssociateNewImages($about, $request->images, 'About');
              }        }
            $about->images;

            DB::commit();
            return $this->successResponse(new AboutResource($about), ' Updated Successfuly', 200);
        }
        catch (\Throwable $th) {
            Log::debug($th);
            Log::error($th->getMessage());
         return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about)
    {
        try {

            $about->delete();
            return $this->successResponse(null,'deleted successfully', 200);
        }
        catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null,"there is something wrong in server",500);
        }
    }
}
