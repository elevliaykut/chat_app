<?php

namespace App\Http\Resources\Message;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'sender'     => UserResource::make($this->sender),
            'receiver'      => UserResource::make($this->receiver),
            'message'       => $this->message,
            'created_at'    => $this->created_at
        ];
    }
}
