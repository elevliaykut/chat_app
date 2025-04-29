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
