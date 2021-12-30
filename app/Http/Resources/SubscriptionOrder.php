<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class SubscriptionOrder extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'user' => User::make($this->user),
            'plan' => $this->plan,
            'payment_method' => $this->paymentMethod,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'created_at' => Carbon::parse($this->created_at)->isoFormat('YYYY-MM-DD h:mm a')

        ];
    }
}
