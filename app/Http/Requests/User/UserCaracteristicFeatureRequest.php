<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserCaracteristicFeatureRequest extends FormRequest
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
            'question_one'                  => 'nullable',
            'question_two'                  => 'nullable',
            'question_three'                => 'nullable',
            'question_four'                 => 'nullable',
            'question_five'                 => 'nullable',
            'question_six'                  => 'nullable',
            'question_seven'                => 'nullable',
            'question_eight'                => 'nullable',
            'question_nine'                 => 'nullable',
            'question_ten'                  => 'nullable',
            'question_eleven'               => 'nullable',
        ];
    }
}
