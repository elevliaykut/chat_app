<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
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
            'email'                 => 'required',
            'name'                  => 'required',
            'surname'               => 'required',
            'sender_bank'           => 'required',
            'buyer_bank'            => 'required',
            'payment_date'          => 'required',
            'price'                 => 'required',
            'description'           => 'nullable',
            'code'                  => 'required'
        ];
    }
}
