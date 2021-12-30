<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWarehouseBookingAgreement extends FormRequest
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
            'start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_date' => ['required', 'date', 'date_format:Y-m-d', "after:".$this->start_date],
            'end_time' => ['required', 'date_format:H:i:s'],
            'quantity' => ['nullable', 'numeric'],
            'unit' => ['nullable', 'string', Rule::in(config('quantity_unit'))],
            'type' => ["nullable", "string", Rule::in('partial','fully')],
            'area' => [Rule::RequiredIf($this->type == 'partial'), "numeric", "nullable"],
            'goods_value' => ["nullable", "numeric"],
            'price' => ["required", "numeric"],
            'user_terms' => ["required", "string"],
            'owner_terms' => ["required", "string"],
        ];
    }
}
