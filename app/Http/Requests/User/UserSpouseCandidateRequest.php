<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserSpouseCandidateRequest extends FormRequest
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
            'about'                             => 'nullable',
            'tall'                              => 'tall',
            'weight'                            => 'weight',
            'eye_color'                         => 'eye_color',
            'hair_color'                        => 'hair_color',
            'skin_color'                        => 'skin_color',
            'body_type'                         => 'body_type',
            'want_a_child'                      => 'want_a_child',
            'looking_qualities'                 => 'looking_qualities',
            'age_range'                         => 'nullable',
            'marital_status'                    => 'nullable',
            'have_a_child'                      => 'nullable',
            'use_cigarette'                     => 'nullable',
            'use_alcohol'                       => 'nullable',
            'education_status'                  => 'nullable',
            'salary'                            => 'nullable',
            'not_compromise'                    => 'nullable',
            'community'                         => 'nullable',
            'sect'                              => 'nullable',
            'do_you_pray'                       => 'nullable',
            'physical_disability'               => 'nullable',
        ];
    }
}
