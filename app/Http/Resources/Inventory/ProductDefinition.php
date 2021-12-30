<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDefinition extends JsonResource
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
            'product' => $this->whenLoaded('product'),
            'product_code' => $this->product_code,
            'brand_name' => $this->brand_name,
            'purchase_unit' => $this->purchase_unit,
            'product_group' => $this->product_group,
            'purchase_type' => $this->purchase_type,
            'conversion_factor' => $this->conversion_factor,
            'product_gender' => $this->product_gender,
            'value_addition' => $this->value_addition,
            'life_type' => $this->life_type,
            'tax_code' => $this->tax_code,
            'supplier' => $this->supplier,
            'accquire_type' => $this->accquire_type,
            'additional_attributes' => $this->additional_attributes,
            'technical_details' => $this->technical_details,
            'additional_description' => $this->additional_description,
            'purchase_production_interval' => $this->purchase_production_interval,
            'purchase_production_unit' => $this->purchase_production_unit
        ];
    }
}
