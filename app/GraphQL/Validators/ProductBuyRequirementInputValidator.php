<?php

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

class ProductBuyRequirementInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'user_id' => ["required"],
            'product_id' => ["required"],
            'requirement_details' => ["required", "string"],
            'quantity' => ["required", "numeric"],
            'unit' => ["required", "string", Rule::in(config('quantity_unit'))],
            'budget' => ["nullable", "numeric"],
            'requirement_urgency' => ["nullable", "string", Rule::in(['immediate', 'after one month'])],
            'requirement_frequency' => ["nullable", "string", Rule::in(['one time', 'regular'])],
            'supplier_location' => ["nullable", "string", Rule::in('local','any where in pakistan')],
        ];
    }

    public function messages(): array
    {
        return [
            'requirement_details.text' => __('Valid warehouse id is required'),
        ];
    }
}
