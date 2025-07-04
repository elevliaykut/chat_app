<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPhotoResource extends JsonResource
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
            'user'              => UserResource::make($this->user),
            'photo_path'        => $this->photo_path,
            'status'            => $this->status,
            'created_at'        => Carbon::parse($this->created_at)->format('d.m.Y H:i'),
            'updated_at'        => Carbon::parse($this->created_at)->format('d.m.Y H:i'),
        ];
    }
}
