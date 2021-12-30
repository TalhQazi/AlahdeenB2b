<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Category extends JsonResource
{
    /**
     * Transform the resource into an array
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
            'image_path' => Storage::url($this->image_path),
            'parent_category' => Category::make($this->whenLoaded('parentCategory')),//$this->when($this->parent_cat_id !== null, Category::make($this->parentCategory)),
            'level' => $this->level,
            'bread_crumb' => $this->bread_crumb,
            'sub_categories' => CategoryCollection::make($this->whenLoaded('subCategories')),
            'all_parent_categories' => Category::make($this->whenLoaded('allParentCategories')),
            'products' => ProductCollection::make($this->whenLoaded('products')),
            'home_page_category' => $this->whenLoaded('homePageCategory'),
            'created_at' => $createdAt->isoFormat('YYYY-MM-DD h:mm a'),
            'deleted_at' => $this->deleted_at
        ];
    }
}
