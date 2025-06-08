<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserPersonalInformationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                          => 'nullable',
            'surname'                       => 'nullable',
            'age'                           => 'nullable',
            'phone'                         => 'nullable',
            'profile_summary'               => 'nullable',
            'biography'                     => 'nullable',
            'horoscope'                     => 'nullable',
            'city_id'                       => 'nullable',
            'district_id'                   => 'nullable',
            'marital_status'                => 'nullable',
            'headscarf'                     => 'nullable',
            'tall'                          => 'nullable',
            'weight'                        => 'nullable',
            'eye_color'                     => 'nullable',
            'hair_color'                    => 'nullable',
            'skin_color'                    => 'nullable',
            'body_type'                     => 'nullable',
            'have_a_child'                  => 'nullable',
            'want_a_child'                  => 'nullable',
            'use_cigarette'                 => 'nullable',
            'use_alcohol'                   => 'nullable',
            'education_status'              => 'nullable',
            'foreign_language'              => 'nullable',
            'job'                           => 'nullable',
            'salary'                        => 'nullable',
            'work_status'                   => 'nullable',
            'live_with'                     => 'nullable',
            'clothing_style'                => 'nullable',
            'not_compromise'                => 'nullable',
            'community'                     => 'nullable',
            'sect'                          => 'nullable',
            'do_you_pray'                   => 'nullable',
            'do_you_know_quran'             => 'nullable',
            'are_you_fasting'               => 'nullable',
            'consider_important_in_islam'   => 'nullable',
            'listening_music_types'         => 'nullable',
            'reading_book_types'            => 'nullable',
            'looking_qualities'             => 'nullable',
            'your_hobbies'                  => 'nullable',
            'your_personality'              => 'nullable',
            'physical_disability'           => 'nullable'
        ];
    }
}
