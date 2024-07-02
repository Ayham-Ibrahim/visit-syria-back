<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Requests\BlogRequest;
use App\Http\Requests\BlogResuest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Resources\BlogResource;
use App\Http\Traits\FileStorageTrait;

class BlogController extends Controller
{
    use ApiResponseTrait,FileStorageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Blog::with('city');
            if ($request->has('sort_by')) {
                $sortBy = $request->sort_by;
                $query->orderBy($sortBy, 'asc');
            }
            //for site
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }
            $blogs = $query->paginate(9);
            return $this->resourcePaginated(BlogResource::collection($blogs), 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        try {
            DB::beginTransaction();
            $blog = Blog::create([
                'title' => $request->title,
                'content' => $request->content,
                'category' => $request->category,
                'city_id' => $request->city_id,
                'main_image' => $this->storeFile($request->main_image, 'blog'),
            ]);
            $this->storeAndAssociateImages($blog, $request->images, 'blog');
            DB::commit();
            return $this->successResponse(new BlogResource($blog), 'Created Successfuly', 200);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        try {
            return $this->successResponse(new BlogResource($blog), 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        try {
            DB::beginTransaction();
            $blog->title = $request->input('title') ?? $blog->title;
            $blog->content = $request->input('content') ?? $blog->content;
            $blog->category = $request->input('category') ?? $blog->category;
            $blog->city_id = $request->input('city_id') ?? $blog->city_id;
            $blog->main_image = $this->fileExists($request->main_image, 'blog') ?? $blog->main_image;
            $this->updateAndAssociateNewImages($blog, $request->images, 'blog');
            $blog->save();
            DB::commit();
            return $this->successResponse(new BlogResource($blog), ' Updated Successfuly', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        try {
            $blog->delete();
            return $this->successResponse(null, 'deleted successfully', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }
}
