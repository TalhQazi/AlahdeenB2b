<?php

namespace App\Http\Resources\Logistics;

use App\Http\Resources\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Driver extends JsonResource
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
            'user' => User::make($this->whenLoaded('user')),
            'dob' => $this->dob,
            'license_number' => $this->license_number,
            'license_photo' => $this->license_photo,
            'license_expiry_date' => $this->license_expiry_date,
            'cnic_front' => Storage::url($this->cnic_front),
            'cnic_back' => Storage::url($this->cnic_back),
            'referral_code' => $this->referral_code,
            'is_verified' => $this->is_verified,
            'vehicle' => $this->vehicle
        ];
    }
}
