<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCaracteristicFeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'question_one'                  => $this->question_one,
            'question_two'                  => $this->question_two,
            'question_three'                => $this->question_three,
            'question_four'                 => $this->question_four,
            'question_five'                 => $this->question_five,
            'question_six'                  => $this->question_six,
            'question_seven'                => $this->question_seven,
            'question_eight'                => $this->question_eight,
            'question_nine'                 => $this->question_nine,
            'question_ten'                  => $this->question_ten,
            'question_eleven'               => $this->question_eleven,
        ];
    }
}
