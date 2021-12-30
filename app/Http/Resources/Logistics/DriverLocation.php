<?php

namespace App\Http\Resources\Logistics;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverLocation extends JsonResource
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
            'driver_id' => $this->driver_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
        ];
    }
}
