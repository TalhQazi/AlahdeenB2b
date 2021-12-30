<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $requestr
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_name' => $this->company_name,
            'address' => $this->address,
            'locality' => $this->locality,
            'city' => $this->businessCity->city,
            'year_of_establishment' => $this->year_of_establishment,
            'website' => $this->alternate_website,
            'user_id' => $this->user_id,
            'seller' =>  User::make($this->whenLoaded('user')),
            'details' => AdditionalBusinessDetail::make($this->whenLoaded('additionalDetails')),
            'product_services' => UserProductServiceCollection::make($this->whenLoaded('productServices')),
            'business_certificates' => $this->whenLoaded('businessCertificates'),
            'director' => BusinessDirector::make($this->whenLoaded('director'))
        ];
    }
}
