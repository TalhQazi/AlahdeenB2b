<?php

namespace App\Http\Resources;

use App\Http\Resources\BusinessDetail as ResourcesBusinessDetail;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class Product extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $createdAt = Carbon::parse($this->created_at);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->price,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'category' => Category::make($this->whenLoaded('productCategory')),
            'created_by' => $this->when(!empty(Auth::user()) && Auth::user()->hasRole(['super-admin','admin']), User::make($this->whenLoaded('user'))),
            'user' => User::make($this->whenLoaded('user')),
            'company_details' => ResourcesBusinessDetail::make($this->whenLoaded('company')),
            'product_services' => UserProductServiceCollection::make($this->whenLoaded('productServices')),
            'deleted_at' => $this->deleted_at,
            'created_at' => $createdAt->isoFormat('YYYY-MM-DD h:mm a'),
            'images' => ProductImageCollection::make($this->whenLoaded('images', function() {
               return $this->images->sortByDesc('is_main');
            })),
            'details' => ProductDetailCollection::make($this->whenLoaded('details')),
            'videos' => $this->whenLoaded('videos'),
            'documents' => ProductDocumentCollection::make($this->whenLoaded('documents')),
            'is_featured' => $this->is_featured,
            'promotion_offer' => PromotionalProduct::make($this->whenLoaded('promotionAgainst')),
            'has_promotion' => $this->promotionAgainst()->exists()
        ];
    }
}
