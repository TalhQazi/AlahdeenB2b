<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Testimonial extends JsonResource
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
            'user_name' => $this->user_name,
            'designation' => $this->designation,
            'company_name' => $this->company_name,
            'company_website' => $this->company_website,
            'message' => $this->message,
            'image_path' => Storage::url($this->image_path),
            'created_at' => Carbon::parse($this->created_at)->isoFormat('YYYY-MM-DD h:mm a'),
            'deleted_at' => $this->deleted_at
        ];
    }
}
