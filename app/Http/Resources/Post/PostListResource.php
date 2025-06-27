<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
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
            'liked_by_me'       => $this->isLikedBy(auth()->id()),
            'favorited_by_me'   => $this->isFavoritedBy(auth()->id()),
            'smiled_by_me'      => $this->isSmiledBy(auth()->id()),
            'status'            => $this->status,
            'created_at'        => Carbon::parse($this->created_at)->format('d.m.y H:i'),
            'updated_at'        => Carbon::parse($this->updated_at)->format('d.m.y H:i'),
        ];
    }
}
