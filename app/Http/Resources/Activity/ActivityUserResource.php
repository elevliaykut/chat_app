<?php

namespace App\Http\Resources\Activity;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'user'          => UserResource::make($this->user),
            'activity_type' => $this->activity_type,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at  
        ];
    }
}
