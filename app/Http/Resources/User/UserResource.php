<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'          => $this->name,
            'surname'       => $this->surname,
            'username'      => $this->username,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'type'          => $this->type,
            'status'        => $this->status,
            'tckn'          => $this->tckn,
            'gender'        => $this->gender,
            'birth_date'    => $this->birth_date,
            'created_at'    => $this->created_at,
            'updaeted_at'    => $this->updaeted_at,
        ];
    }
}
