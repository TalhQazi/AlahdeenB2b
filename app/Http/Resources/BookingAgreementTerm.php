<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingAgreementTerm extends JsonResource
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
            'warehouse_booking' => WarehouseBooking::make($this->whenLoaded('booking')),
            'item' => $this->item,
            'description' => $this->description,
            'start_time' => Carbon::parse($this->start_time)->isoFormat('YYYY-MM-DD h:mm a'),
            'end_time' => Carbon::parse($this->end_time)->isoFormat('YYYY-MM-DD h:mm a'),
            'start_date' => Carbon::parse($this->start_time)->isoFormat('YYYY-MM-DD'),
            'time_start' => Carbon::parse($this->start_time)->isoFormat('HH:mm:ss'),
            'end_date' => Carbon::parse($this->end_time)->isoFormat('YYYY-MM-DD'),
            'time_end' => Carbon::parse($this->end_time)->isoFormat('HH:mm:ss'),
            'booking_type' => $this->type,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'area' => $this->area,
            'goods_value' => $this->goods_value,
            'deleted_at' => $this->deleted_at,
            'created_at' => Carbon::parse($this->created_at)->isoFormat('YYYY-MM-DD h:mm a'),
            'price' => $this->price,
            'user_terms' => $this->user_terms,
            'owner_terms' => $this->owner_terms,
            'requestor_payment_status' => $this->requestor_payment_status,
            'owner_paid_status' => $this->owner_paid_status,
            'commission_percentage' => $this->commission_percentage ?? 0,
            'commission_paid' => $this->commission_paid ?? $this->price * ($this->commission_percentage/100),
            'tax_percentage' => $this->tax_percentage ?? 0,
            'tax_amount' => $this->tax_amount ?? $this->price * ($this->tax_percentage/100),
            'booking_status' => $this->status
        ];
    }
}
