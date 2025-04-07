<?php

namespace App\Http\Resources\Activity;

use App\Http\Resources\Post\PostListResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'post'                  => PostListResource::make($this->post),
            'activity_type'         => $this->activity_type,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at
        ];
    }
}
