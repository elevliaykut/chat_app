<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'description'       => $this->description,
            'creator_user'      => UserResource::make($this->creatorUser),
            'photo'             => PostPhotoResource::collection($this->photos),
            'like_count'        => $this->like_count,
            'favorite_count'    => $this->favorite_count,
            'simile_count'      => $this->simile_count,
            'status'            => $this->status,
            'created_at'        => $this->created_at->getTimestamp(),
            'updated_at'        => $this->updated_at->getTimestamp()
        ];
    }
}
