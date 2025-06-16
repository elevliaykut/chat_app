<?php

namespace App\Http\Resources\Payment;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'user'                  => UserResource::make($this->user),
            'email'                 => $this->email,
            'name'                  => $this->name,
            'surname'               => $this->surname,
            'sender_bank'           => $this->sender_bank,
            'buyer_bank'            => $this->buyer_bank,
            'payment_date'          => $this->payment_date,
            'price'                 => $this->price,
            'description'           => $this->description,
            'completed'             => $this->completed,
            'created_at'            => $this->created_at
        ];
    }
}
