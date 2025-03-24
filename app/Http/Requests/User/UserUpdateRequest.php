<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends FormRequest
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
        $user = Auth::user();

        return [
            'name'              => 'nullable',
            'surname'           => 'nullable',
            'username'          => 'nullable|unique:users,username,' . $user->id,
            'email'             => 'nullable|unique:users,email,' . $user->id,
            'phone'             => 'nullable',
            'age'               => 'nullable',
            'tckn'              => 'nullable',
            'birth_date'        => 'nullable',
            'gender'            => 'nullable'
        ];
    }
}
