<?php

namespace App\Http\Resources\Logistics;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Vehicle extends JsonResource
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
            'name' => $this->name,
            'parent_vehicle' => Vehicle::make($this->whenLoaded('parentVehicle')),
            'parent_id' => $this->parent_id,
            'image_path' => Storage::url($this->image_path)
        ];
    }
}
