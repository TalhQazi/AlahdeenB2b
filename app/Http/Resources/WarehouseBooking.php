<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class WarehouseBooking extends JsonResource
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
            'warehouse' => Warehouse::make($this->whenLoaded('warehouse')),
            'booked_by' => User::make($this->whenLoaded('bookedBy')),
            'item' => $this->item,
            'description' => $this->description,
            'start_time' => Carbon::parse($this->start_time)->isoFormat('YYYY-MM-DD h:mm a'),
            'end_time' => Carbon::parse($this->end_time)->isoFormat('YYYY-MM-DD h:mm a'),
            'start_date' => Carbon::parse($this->start_time)->isoFormat('YYYY-MM-DD'),
            'time_start' => Carbon::parse($this->start_time)->isoFormat('HH:mm:ss'),
            'end_date' => Carbon::parse($this->end_time)->isoFormat('YYYY-MM-DD'),
            'time_end' => Carbon::parse($this->end_time)->isoFormat('HH:mm:ss'),
            'booking_status' => $this->booking_status,
            'booking_type' => $this->type,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'area' => $this->area,
            'goods_value' => $this->goods_value,
            'deleted_at' => $this->deleted_at,
            'created_at' => Carbon::parse($this->created_at)->isoFormat('YYYY-MM-DD h:mm a'),
            'invoice' => BookingAgreementTerm::make($this->invoice)
        ];
    }
}
