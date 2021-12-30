<?php

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

class WarehouseBookingInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'warehouse_id' => ["required"],
            'item' => ["required"],
            'description' => ["required"],
            'booked_by' => ["required"],
            'start_time' => ["required", "date", "date_format:Y-m-d H:i:s"],
            'end_time' => ["required", "date", "date_format:Y-m-d H:i:s", "after:".$this->arg('start_time')],
            'booking_status' => ["required"],
            'quantity' => ["nullable", "numeric"],
            'unit' => ["nullable", "string", Rule::in(config('quantity_unit'))],
            'type' => ["nullable", "string", Rule::in('partial','fully')],
            'area' => [Rule::RequiredIf($this->arg('type') == 'partial'), "numeric"],
            'goods_value' => ["nullable", "numeric"],
        ];
    }

    public function messages(): array
    {
        return [
            'warehouse_id.required' => __('Valid warehouse id is required'),
        ];
    }
}
