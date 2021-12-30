<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ProductDocument extends JsonResource
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
            'title' => $this->title,
            // 'path' => url('').'/'.str_replace('public','storage',$this->path),
            'path' => $this->path,
            'created_at' => Carbon::parse($this->created_at)->isoFormat('YYYY-MM-DD h:mm a')

        ];
    }
}
