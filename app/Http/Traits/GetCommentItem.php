<?php

namespace App\Http\Traits;


use App\Models\Hotel;
use App\Models\Landmark;
use App\Models\Restaurant;

trait GetCommentItem
{

    /**
     * Get the comment item based on the commentable type and ID.
     *
     * @param string $commentableType
     * @param int $commentableId
     * @return \App\Models\Category|\App\Models\Document|string
     */
    public function getCommentItem(string $commentableType, int $commentableId)
    {
        switch ($commentableType) {
            case 'App\Models\Hotel':
                $hotel = Hotel::findOrFail($commentableId);
                return $hotel;
                break;
            case 'App\Models\Restaurant':
                $restaurant = Restaurant::findOrFail($commentableId);
                return $restaurant;
                break;
            case 'App\Models\Landmark':
                    $landmark = Landmark::findOrFail($commentableId);
                    return $landmark;
                    break;
            default:
                return 'Not Found!';
                break;
        }
    }
}