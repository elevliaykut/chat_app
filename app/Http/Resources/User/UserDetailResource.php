<?php

namespace App\Http\Resources\User;

use App\Helper\Types\UserMaritalStatusHelper;
use App\Http\Resources\Definitions\CityResource;
use App\Http\Resources\Definitions\DistrictResource;
use App\Models\Definitions\District;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                                => $this->id,
            'profile_summary'                   => $this->profile_summary,
            'biography'                         => $this->biography,
            'horoscope'                         => $this->horoscope,
            'city'                              => CityResource::make($this->city),
            'district'                          => DistrictResource::make($this->district),
            'marital_status'                    => $this->marital_status,
            'marital_status_value'              => UserMaritalStatusHelper::getTypeName($this->marital_status),
            'online_status'                     => $this->online_status,
            'headscarf'                         => $this->headscarf,
            'tall'                              => $this->tall,
            'weight'                            => $this->weight,
            'eye_color'                         => $this->eye_color,
            'hair_color'                        => $this->hair_color,
            'skin_color'                        => $this->skin_color,
            'body_type'                         => $this->body_type,
            'have_a_child'                      => $this->have_a_child,
            'want_a_child'                      => $this->want_a_child,
            'use_cigarette'                     => $this->use_cigarette,
            'use_alcohol'                       => $this->use_alcohol,
            'education_status'                  => $this->education_status,
            'foreign_language'                  => $this->foreign_language,
            'job'                               => $this->job,
            'salary'                            => $this->salary,
            'work_status'                       => $this->work_status,
            'live_with'                         => $this->live_with,
            'clothing_style'                    => $this->clothing_style,
            'not_compromise'                    => $this->not_compromise,
            'community'                         => $this->community,
            'sect'                              => $this->sect,
            'do_you_pray'                       => $this->do_you_pray,
            'do_you_know_quran'                 => $this->do_you_know_quran,
            'are_you_fasting'                   => $this->are_you_fasting,
            'consider_important_in_islam'       => $this->consider_important_in_islam,
            'listening_music_types'             => $this->listening_music_types,
            'reading_book_types'                => $this->reading_book_types,
            'looking_qualities'                 => $this->looking_qualities,
            'your_hobbies'                      => $this->your_hobbies,
            'your_personality'                  => $this->your_personality,
            'physical_disability'               => $this->physical_disability,
            'created_at'                        => $this->created_at,
            'updated_at'                        => $this->updated_at
        ];              
    }
}
