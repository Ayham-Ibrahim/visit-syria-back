<?php

namespace App\Http\Resources;

use App\Http\Traits\GetCommentItem;
use App\Http\Traits\GetCommentItemTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function App\Helpers\formatDate;

class CommentResource extends JsonResource
{
    use GetCommentItem ;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user'        => $this->user->name,
            'user_image' => $this->user->image,
            'comment_content' => $this->comment_content,
            'created_at' => $this->created_at,
            'item'        => $this->getCommentItem($this->commentable_type, $this->commentable_id),
            // 'publish'     => formatDate($this->created_at)
        ];
    }
}
