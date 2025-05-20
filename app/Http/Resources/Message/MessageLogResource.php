<?php

namespace App\Http\Resources\Message;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'sender'        => UserResource::make($this->sender),
            'receiver'      => UserResource::make($this->receiver),
            'created_at'    => $this->created_at
        ];
    }
}
