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
            'username'              => 'required',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required',
            'type'                  => 'required',
            'gender'                => 'required',
            'birth_date'            => 'required',
        ];
    }
}
