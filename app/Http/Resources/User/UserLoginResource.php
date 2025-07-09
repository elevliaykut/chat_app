<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                            => $this->id,
            'name'                          => $this->name,
            'surname'                       => $this->surname,
            'username'                      => $this->username,
            'email'                         => $this->email,
            'phone'                         => $this->phone,
            'type'                          => $this->type,
            'status'                        => $this->status,
            'age'                           => $this->age,
            'tckn'                          => $this->tckn,
            'gender'                        => $this->gender,
            'personal_info_complete'        => $this->personal_info_complete,
            'profile_photo_path'            => $this->profile_photo_path,
            'birth_date'                    => $this->birth_date,
            'like_count'                    => $this->like_count,
            'favorite_count'                => $this->favorite_count,
            'smile_count'                   => $this->smile_count,
            'is_online'                     => $this->is_online,
            'is_that_active'                => $this->isThatActive(),
            'created_at'                    => $this->created_at,
            'updaeted_at'                   => $this->updaeted_at,
        ];
    }
}
