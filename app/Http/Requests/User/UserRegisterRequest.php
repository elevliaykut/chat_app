<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'name'                  => 'required',
            'surname'               => 'required',
            'username'              => 'required',
            'email'                 => 'required',
            'age'                   => 'required',
            'password'              => 'required',
            'phone'                 => 'required',
            'type'                  => 'required',
            'gender'                => 'required',
            'birth_date'            => 'required',
        ];
    }
}
