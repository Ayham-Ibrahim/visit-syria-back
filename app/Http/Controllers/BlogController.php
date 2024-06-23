<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog = Blog::all();
        return response()->json($blog);
        try {
            $blog = Blog::paginate();
            return $this->successResponse($blog, 'Done', 200);
            // return $this->paginated($blog, 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'main_image' => 'required|file|image|mimes:png,jpg|max:10000|mimetypes:image/jpeg,image/png,image/jpg',
            // 'category' => 'in:الطبيعة,الاثرية',
            // 'city_id' => 'required|exists:cities,id'
        ]);

        try {
            if ($request->hasFile('main_image')) {
                $filename = time() . '_' . $request->file('main_image')->getClientOriginalName();
                $request->file('main_image')->move(public_path('images'), $filename);
                $data['main_image'] = 'images/' . $filename;
            }
            $blog = Blog::create([
                'title' => $data['title'],
                'content' => $data['content'],
                'main_image' => $data['main_image'],
                // 'category' => $data['category'],
                // 'city_id' => $data['city_id']
            ]);
            return $this->successResponse($blog, 'Created Successfuly', 200);
        } catch (\Throwable $th) {
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
            return $this->successResponse($blog, 'Done', 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
            'main_image' => 'sometimes|file|image|mimes:png,jpg|max:10000|mimetypes:image/jpeg,image/png,image/jpg',
            // 'category' => 'sometimes|required|in:الطبيعة,الاثرية',
            // 'city_id' => 'sometimes|required|exists:cities,id'
        ]);

        try {
            foreach ($data as $key => $value) {
                if ($key == 'main_image' && $request->hasFile('main_image')) {
                    $blog->main_image = $request->file('main_image')->store('main_images', 'public');
                } else {
                    $blog->$key = $value;
                }
            }
            $blog->save();
            return $this->successResponse($blog, ' Updated Successfuly', 200);
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
