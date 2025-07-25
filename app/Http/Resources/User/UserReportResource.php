<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserReportResource extends JsonResource
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
            'creator_user'      => UserResource::make($this->creatorUser),
            'description'       => $this->description,
            'created_at'        => Carbon::parse($this->created_at)->format('d.m.Y H:i')
        ];
    }
}
