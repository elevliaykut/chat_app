<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserStoryResource extends JsonResource
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
            'media'         => $this->media_path,
            'expires_at'    => $this->expires_at,
            'status'        => $this->status,
            'created_at'    => Carbon::parse($this->created_at)->format('d.m.Y H:i'),
            'updated_at'    => Carbon::parse($this->updated_at)->format('d.m.Y H:i'),
        ];
    }
}
