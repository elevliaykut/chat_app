<?php

namespace App\Http\Resources\Payment;

use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
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
            'payment_date'          => Carbon::parse($this->payment_date)->format('d.m.Y'),
            'price'                 => $this->price,
            'description'           => $this->description,
            'code'                  => $this->code,
            'completed'             => $this->completed,
            'package_time'          => $this->package_time,
            'created_at'            => Carbon::parse($this->created_at)->format('d.m.Y'),
        ];
    }
}
