<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWarehouseBooking extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item' => ['required', 'string'],
            'description' => ['required', 'string'],
            'start' => ['required', 'date', 'date_format:Y-m-d H:i:s'],
            // 'start_time' => ['required', 'date_format:H:i:s'],
            'end' => ['required', 'date', 'date_format:Y-m-d H:i:s', "after:".$this->start],
            // 'end_time' => ['required', 'date_format:H:i:s'],
            'booking_status' => ['string', Rule::in('pending','approved', 'confirmed', 'rejected')],
            'quantity' => ['nullable', 'numeric'],
            'unit' => ['nullable', 'string', Rule::in(config('quantity_unit'))],
            'type' => ["nullable", "string", Rule::in('partial','fully')],
            'area' => [Rule::RequiredIf($this->type == 'partial'), "numeric", "nullable"],
            'goods_value' => ["nullable", "numeric"],
        ];

    }
}
