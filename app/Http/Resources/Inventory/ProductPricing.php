<?php

namespace App\Http\Resources\Inventory;

use App\Http\Resources\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPricing extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this);
        return [
            'id' => $this->id,
            'product' => Product::make($this->whenLoaded('product')),
            'total_units' => $this->total_units,
            'price_per_unit' => $this->price_per_unit,
            'avg_cost_per_unit' => $this->avg_cost_per_unit,
            'sales_tax_percentage' => $this->sales_tax_percentage,
            'allow_below_cost_sale' => $this->allow_below_cost_sale,
            'allow_price_change' => $this->allow_price_change,
            'discount_percentage' => $this->discount_percentage,
            // 'promotional_offers' => $this->whenLoaded('product.promotionAgainst')
        ];
    }
}
