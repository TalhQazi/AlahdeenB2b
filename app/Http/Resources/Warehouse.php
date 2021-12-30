<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class Warehouse extends JsonResource
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
            'property_type' => $this->propertyType,
            'user' => User::make($this->whenLoaded('owner')),
            'city' => $this->whenLoaded('city', function() {
                return $this->city->city;
            }),
            'city_info' => $this->whenLoaded('city'),
            'locality' => $this->whenLoaded('locality', function() {
                return $this->locality->name;
            }),
            'locality_info' => $this->whenLoaded('locality'),
            'lat' => $this->coordinates->getLat(),
            'lng' => $this->coordinates->getLng(),
            'area' => $this->area,
            'price' => $this->price,
            'can_be_shared' => $this->can_be_shared,
            'features' => WarehouseFeatureCollection::make($this->whenLoaded('features', function(){
                return $this->features->keyBy->feature_id;
            })),
            'images' => WarehouseImageCollection::make($this->whenLoaded('images')),
            'is_approved' => $this->is_approved,
            'is_active' => $this->is_active,
            'deleted_at' => $this->deleted_at,
            'created_at' => Carbon::parse($this->created_at)->isoFormat('YYYY-MM-DD h:mm a')
        ];
    }
}
