<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class Catalog extends JsonResource
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
            'path' => $this->path,
            'owner' => $this->user_id,
            'created_at' => $createdAt->isoFormat('YYYY-MM-DD h:mm a'),
        ];
    }
}
