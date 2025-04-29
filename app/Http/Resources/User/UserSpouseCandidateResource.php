<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSpouseCandidateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'age_range'                 => $this->age_range,
            'marital_status'            => $this->marital_status,
            'have_a_child'              => $this->have_a_child,
            'use_cigarette'             => $this->use_cigarette,
            'use_alcohol'               => $this->use_alcohol,
            'education_status'          => $this->education_status,
            'salary'                    => $this->salary,
            'not_compromise'            => $this->not_compromise,
            'community'                 => $this->community,
            'sect'                      => $this->sect,
            'do_you_pray'               => $this->do_you_pray,
            'physical_disability'       => $this->physical_disability
        ];
    }
}
