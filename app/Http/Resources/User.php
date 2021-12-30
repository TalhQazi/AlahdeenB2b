<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class User extends JsonResource
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
            'designation' => $this->designation,
            'user_type' => $this->getRoleNames()->first(),
            'business' => $this->whenLoaded('business'),
            'company_banner' => $this->whenLoaded('companyBanner', function() {
                return Storage::url($this->companyBanner->banner_image_path);
            }),
            'badges' => BadgeCollection::make($this->whenLoaded('badges')),
            'company_products' => CompanyPageProductCollection::make($this->whenLoaded('companyDisplayProducts')),
            'deleted_at' => $this->deleted_at,
            'created_at' => Carbon::parse($this->created_at)->isoFormat('YYYY-MM-DD h:mm a')
        ];
    }
}
