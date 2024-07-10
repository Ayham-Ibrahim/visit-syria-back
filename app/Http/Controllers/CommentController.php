<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Comment;
use App\Models\Landmark;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Requests\StoreComment;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    use ApiResponseTrait;

    
    /**
     * Retrieves the comments associated with a specific landmark.
     *
     * @param Landmark $landmark The landmark to fetch comments for.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the comments for the specified landmark.
     */
    public function landmarkComments(Landmark $landmark)
    {
        $comment_landmark = $landmark->comments;
        $comment_data = CommentResource::collection($comment_landmark);
        return $this->successResponse($comment_data, 'Success', 200);
    }


    /**
     * Retrieves the comments associated with a specific hotel.
     *
     * @param Hotel $hotel The hotel to fetch comments for.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the comments for the specified hotel.
     */
    public function hotelComments(Hotel $hotel)
    {
        $comment_hotel = $hotel->comments;
        $comment_data = CommentResource::collection($comment_hotel);
        return $this->successResponse($comment_data, 'Success', 200);
    }


    /**
     * Retrieves the comments associated with a specific restaurant.
     *
     * @param Restaurant $restaurant The restaurant to fetch comments for.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the comments for the specified restaurant.
     */
    public function restaurantComments(Restaurant $restaurant)
    {
        $comment_restaurant = $restaurant->comments;
        $comment_data = CommentResource::collection($comment_restaurant);
        return $this->successResponse($comment_data, 'Success', 200);
    }


    /**
     * Stores a new hotel comment for the specified hotel.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the comment data.
     * @param \App\Models\Hotel $hotel The hotel to associate the comment with.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the created comment data.
     */
    public function storeHotelComment(StoreComment $request, Hotel $hotel)
    {
        try {
            $comment = $hotel->comments()->create([
                'comment_content' => $request->comment_content
            ]);
            return response()->json([
                'message' => 'Commented Added Successfully',
                'hotel_name' => $hotel->name,
                'comment_id' => $comment->id,
                'comment_content' => $comment->comment_content,
                'user_id' => $comment->user->id,
                'user_name' => $comment->user->name,
                'user_image' => $comment->user->image ? $comment->user->image : null,
                'created_at' =>  $comment->created_at,
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }


    /**
     * Stores a new landmark comment for the specified landmark.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the comment data.
     * @param \App\Models\Landmark $landmark The landmark to associate the comment with.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the created comment data.
     */
    public function storeLandmarkComment(StoreComment $request, Landmark $landmark)
    {
        try {
            $comment = $landmark->comments()->create([
                'comment_content' => $request->comment_content
            ]);
            return response()->json([
                'message' => 'Commented Added Successfully',
                'landmark_name' => $landmark->name,
                'comment_id' => $comment->id,
                'comment_content' => $comment->comment_content,
                'user_id' => $comment->user->id,
                'user_name' => $comment->user->name,
                'user_image' => $comment->user->image ? $comment->user->image : null,
                'created_at' =>  $comment->created_at,
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }


    /**
     * Stores a new restaurant comment for the specified restaurant.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the comment data.
     * @param \App\Models\Restaurant $restaurant The restaurant to associate the comment with.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the created comment data.
     */
    public function storeRestaurantComment(StoreComment $request, Restaurant $restaurant)
    {
        try {
            $comment = $restaurant->comments()->create([
                'comment_content' => $request->comment_content
            ]);
            return response()->json([
                'message' => 'Commented Added Successfully',
                'restaurant_name' => $restaurant->name,
                'comment_id' => $comment->id,
                'comment_content' => $comment->comment_content,
                'user_id' => $comment->user->id,
                'user_name' => $comment->user->name,
                'user_image' => $comment->user->image ? $comment->user->image : null,
                'created_at' =>  $comment->created_at,

            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }
    


    /**
     * Retrieves the specified comment.
     *
     * @param \App\Models\Comment $comment The comment to retrieve.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the retrieved comment data.
     */
    public function show(Comment $comment)
    {
        return $this->successResponse(new CommentResource($comment), 'Success', 200);
    }


    
    
    /**
     * Deletes the specified comment.
     *
     * @param \App\Models\Comment $comment The comment to delete.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the comment was deleted successfully.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return $this->successResponse(null, 'Comment Deleted Successfully', 200);
    }
}
