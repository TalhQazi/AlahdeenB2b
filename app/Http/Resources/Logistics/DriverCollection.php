<?php

namespace App\Http\Resources\Logistics;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DriverCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ]
    }
}
