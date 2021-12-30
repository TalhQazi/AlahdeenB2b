<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductBuyRequirement extends JsonResource
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
            'required_product' => $this->required_product,
            'requirement_details' => $this->requirement_details,
            'attachment' => $this->image_path ? Storage::url($this->image_path) : null,
            'buyer' => User::make($this->whenLoaded('buyer')),
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'budget' => $this->budget,
            'requirement_urgency' => $this->requirement_urgency,
            'requirement_frequency' => $this->requirement_frequency,
            'supplier_location' => $this->supplier_location,
            'created_at' => Carbon::parse($this->created_at)->isoFormat('YYYY-MM-DD h:mm a'),
            'added_at' => Carbon::createFromTimestamp(strtotime($this->created_at))->diffForHumans()
        ];
    }
}
