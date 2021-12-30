<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class Advertisment extends JsonResource
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
            'image_path' => Storage::url($this->image_path),
            'url_link' => $this->url_link,
            'display_order' => $this->display_order,
            'display_section' => $this->display_section,
            'is_active' => $this->is_active,
            'created_at' => $createdAt->isoFormat('YYYY-MM-DD h:mm a'),
        ];
    }
}
