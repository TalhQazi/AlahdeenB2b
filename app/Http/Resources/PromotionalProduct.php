<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotionalProduct extends JsonResource
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
            'product_id' => $this->product_id,
            'product' => Product::make($this->whenLoaded('promotionAgainst')),
            'promotional_product' =>  Product::make($this->whenLoaded('productOnPromotion')),
            'discount_percentage' =>  $this->discount_percentage,
            'by_date' =>  $this->by_date,
            'by_no_of_units'=> $this->by_no_of_units,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'no_of_units' => $this->no_of_units,
            'remaining_no_of_units' => $this->remaining_no_of_units,
            'description' => $this->description,
            'is_active' => $this->is_active
        ];
    }
}
